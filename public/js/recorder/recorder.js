
let current_media = null;


class VideoRecorder {
    constructor({media, videoTag=null, mimeType="video/webm"}) {
        this._mimeType = mimeType;
        this._chunks = [];
        this._timeLimit = 10;
        this._startTime = 0;
        this._endTime = 0;
        this._timeInterval = null;
        this._timeIntervalHandlers = [];
        this._onStartHandlers = [];
        this._onStopHandlers = [];

        this._recorder = new MediaRecorder(media, {mimeType});
        this._recorder.addEventListener('dataavailable', e => {
            this._chunks.push(e.data);
            const myStream = this._recorder.stream;
        });
        this._recorder.addEventListener('start', () => {
            this._onStartHandlers.forEach(callback => callback());
        });
        this._recorder.addEventListener('stop', () => {
             const blob = new Blob(this._chunks, {
                 type: this._chunks[0].type
             });
             const url = URL.createObjectURL(blob);
             console.log(url);
            this._onStopHandlers.forEach(callback => callback());
        });

        if (videoTag)
            videoTag.srcObject = media;
    }

    reset() {
        this._chunks = [];
        this._startTime = 0;
        this._endTime = 0;
        this._timeInterval = null;
        this._timeIntervalHandlers = [];
        this._onStartHandlers = [];
        this._onStopHandlers = [];
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
        this._endTime = new Date();
        this._recorder.stop();
        clearInterval(this._timeInterval);
    }

    onStart(callback) {
        if (typeof callback === "function")
            this._onStartHandlers.push(callback)
    }

    onStop(callback) {
        if (typeof callback === "function")
            this._onStopHandlers.push(callback)
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

    save() {
        const blob = new Blob(this._chunks, {
            type: this._chunks[0].type
        });
        const downloadLink = document.createElement('a');
        downloadLink.href = URL.createObjectURL(blob);
        downloadLink.download = `video.webm`;
        document.body.appendChild(downloadLink);
        downloadLink.click();
        URL.revokeObjectURL(blob); // clear from memory
        document.body.removeChild(downloadLink);
    }
}


const getVideoRecorder = ({media=null, videoTag=null}) => {
    if (!media)
        media = current_media
    return new VideoRecorder({media, videoTag})
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
        current_media = await navigator.mediaDevices.getDisplayMedia(options);
        return current_media;
    }
    catch(error) {
        console.error("Error getDisplayMedia:" + error);
        throw error;
    }
}


const hasMediaSelected = _ => current_media !== null


export {
    getDisplayMedia,
    getVideoRecorder,
    hasMediaSelected
}
