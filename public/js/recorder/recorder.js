
class VideoRecorder {
    constructor({media, mimeType="video/webm"}) {
        this._mimeType = mimeType;
        this._chunks = [];
        this._timeLimit = 15 * 60;  // max 15 min
        this._startTime = 0;
        this._timeInterval = null;
        this._timeIntervalHandlers = [];

        this._recorder = new MediaRecorder(media, {mimeType});
        this._recorder.addEventListener('dataavailable', e => {
            this._chunks.push(e.data);
        });
    }

    get recordedBlob() {
        return new Blob(this._chunks, {
            type: this._chunks[0].type
        });
    }

    start() {
        this._recorder.start(50);
        this._startTime = new Date();
        this._timeInterval = setInterval(_ => {
            const seconds = this.#timeDiff();
            this._timeIntervalHandlers.forEach(callback => callback(seconds));
            if (seconds >= this._timeLimit)
                this.stop();
        }, 1000);
    }

    stop() {
        this._recorder.stop();
        clearInterval(this._timeInterval);
    }

    onStart(callback) {
        if (typeof callback === "function")
            this._recorder.addEventListener('start', _ => callback());
    }

    onStop(callback) {
        if (typeof callback === "function")
            this._recorder.addEventListener('stop', _ => callback());
    }

    onTimeInterval(callback) {
        if (typeof callback === "function")
            this._timeIntervalHandlers.push(callback);
    }

    /**
     * Zwraca liczbę sekund, które upłyneły od rozpoczęcia nagrywania
     * @returns {number}
     */
    #timeDiff() {
        const timeDiff = new Date() - this._startTime;
        return Math.round(timeDiff/1000);
    }
}


const defaultMediaOptions = {
    video: {
        cursor: "always"
    },
    audio: true
};


const getDisplayMedia = async (options=null) => {
    try {
        if (!options)
            options = defaultMediaOptions;
        return await navigator.mediaDevices.getDisplayMedia(options);
    }
    catch(error) {
        console.error("Error getDisplayMedia:" + error);
        throw error;
    }
}


class VideoBox {
    constructor({videoTag=null}) {
        this.reset();
        this._videoTag = videoTag;
    }

    reset() {
        this._media = null;
        this._timeIntervalHandlers = [];
        this._onStartHandlers = [];
        this._onStopHandlers = [];
        this._recorder = null;
    }

    async selectDisplayMedia(options=null) {
        if (!this._media)
            this._media = await getDisplayMedia(options);
        return this._media;
    }

    get hasMedia() {
        return this._media !== null;
    }

    startPreview() {
        if (this._videoTag && this._media)
            this._videoTag.srcObject = this._media;
    }

    initVideoRecorder() {
        const recorder = new VideoRecorder({media: this._media});
        recorder.onTimeInterval(seconds => {
            this._timeIntervalHandlers.forEach(callback => callback(seconds))
        });
        recorder.onStart(_ => {
            this._onStartHandlers.forEach(callback => callback());
        });
        recorder.onStop( _ => {
            this._onStopHandlers.forEach(callback => callback());
        });
        this._recorder = recorder;
        return this._recorder;
    }

    onTimeInterval(callback) {
        if (typeof callback === "function")
            this._timeIntervalHandlers.push(callback);
    }

    onStart(callback) {
        if (typeof callback === "function")
            this._onStartHandlers.push(callback)
    }

    onStop(callback) {
        if (typeof callback === "function")
            this._onStopHandlers.push(callback)
    }

    startRecording() {
        if (!this._recorder)
            this.initVideoRecorder();
        this._recorder.start();
    }

    stopRecording() {
        if (this._recorder)
            this._recorder.stop();
    }

    save() {
        if (!this._recorder)
            return;
        const downloadLink = document.createElement('a');
        downloadLink.href = URL.createObjectURL(this._recorder.recordedBlob);
        downloadLink.download = `video.webm`;
        document.body.appendChild(downloadLink);
        downloadLink.click();
        document.body.removeChild(downloadLink);
    }

    upload(url) {
        if (!this._recorder)
            return;
        return new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            const fd = new FormData();
            xhr.open("POST", url, true);
            xhr.onreadystatechange = () => {
                if (xhr.readyState === 4 && xhr.status === 200)
                    resolve();
                if (xhr.readyState === 4 && xhr.status !== 200)
                    reject();
            };
            fd.append('myFile', this._recorder.recordedBlob);
            xhr.send(fd);
        })
    }
}


const createVideoBox = (params) => {
    return new VideoBox(params);
}


export {
    createVideoBox
}
