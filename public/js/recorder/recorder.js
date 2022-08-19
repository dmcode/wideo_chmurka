
let current_media = null;


class VideoRecorder {
    constructor({media, videoTag=null, mimeType="video/webm"}) {
        this._mimeType = mimeType;
        this._chunks = [];
        this._recorder = new MediaRecorder(media, {mimeType});
        this._recorder.addEventListener('dataavailable', e => {
            this._chunks.push(e.data);
        });
        this._recorder.addEventListener('stop', () => {
             const blob = new Blob(this._chunks, {
                 type: this._chunks[0].type
             });
             const url = URL.createObjectURL(blob);
             console.log(url);
        })

        if (videoTag)
            videoTag.srcObject = media;
    }

    start() {
        this._recorder.start(50);
    }

    stop() {
        this._recorder.stop();
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
