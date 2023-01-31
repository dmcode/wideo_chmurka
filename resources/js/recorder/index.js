import {createVideoBox} from "./recorder.js";
import {cloneTemplate} from "../tools.js";


console.log("to jest test widomości")

const tagMediaPreview = document.querySelector('.media-preview-wrapper');
const tagVideoWelcome = document.querySelector('.video-welcome');


const handleVideoBoxEvents = ({videoBox, elMediaControls}) => {
  const elapsedTag = elMediaControls.getElementById('elapsed');
  videoBox.onTimeInterval(seconds => {
    const min = Math.floor(seconds / 60);
    const sec = seconds - (min * 60);
    elapsedTag.innerText = `${min < 10 ? '0' + min : min}:${(sec < 10 ? '0' + sec : sec)}`;
  });
  videoBox.onStart(_ => {
    const tmplRecordingInfo = cloneTemplate('tmplRecordingInfo');
    if (tmplRecordingInfo && tagMediaPreview)
      tagMediaPreview.querySelector('.recording-info').replaceChildren(tmplRecordingInfo);
    removeTagNewVideo();
  });
  videoBox.onStop(_ => {
    const tmplRecordingInfoStop = cloneTemplate('tmplRecordingInfoStop');
    if (tmplRecordingInfoStop && tagMediaPreview)
      tagMediaPreview.querySelector('.recording-info').replaceChildren(tmplRecordingInfoStop);
    if (videoBox.isVideoRecorded)
      renderRecorderActions({videoBox});
  });
}


const renderMediaControls = ({videoBox}) => {
  const elMediaControls = cloneTemplate('tmplMediaControls');
  if (!elMediaControls)
    return;
  const btnRec = elMediaControls.getElementById('btnRec');
  handleVideoBoxEvents({videoBox, elMediaControls});
  videoBox.onStop(_ => {
    btnRec.checked = false;
  });
  btnRec.addEventListener('change', e => {
    if (e.target.checked)
      videoBox.startRecording();
    else
      videoBox.stopRecording();
  });
  tagMediaPreview.appendChild(elMediaControls);
}


const renderSelectMedia = ({videoBox}) => {
  const elSelectMedia = cloneTemplate('tmplSelectMedia');
  if (!elSelectMedia)
    return;
  const btn = elSelectMedia.querySelector('.btn-select-media');
  btn.addEventListener('click', async e => {
    e.preventDefault();
    e.stopPropagation();
    await videoBox.selectDisplayMedia()
      .then(_ => videoBox.startPreview())
      .catch(error => alert("Nie można wybrać urządzenia wideo!"));
    if (videoBox.hasMedia) {
      btn.remove();
      tagMediaPreview.querySelector('.select-media').remove();
      renderMediaControls({videoBox});
    }
  });
  tagMediaPreview.appendChild(elSelectMedia);
}


const renderRecorderActions = ({videoBox}) => {
  removeTagNewVideo();
  const elNewVideo = cloneTemplate('tmplNewVideo');
  if (!elNewVideo)
    return;
  const elVideoRecorderActions = elNewVideo.querySelector('.video-recorder-actions');
  const btnSave = document.createElement('button');
  btnSave.className = 'btn-action btn-download';
  btnSave.innerText = "Pobierz";
  btnSave.addEventListener('click', () => {
    videoBox.save();
  });
  const btnUpload = document.createElement('button');
  btnUpload.className = 'btn-action btn-upload';
  btnUpload.innerText = "Zapisz w bibliotece";
  btnUpload.addEventListener('click', () => {
    renderUploadingBar();
    videoBox.upload(tagMediaPreview.dataset.apiupload)
      .then(_ => {
        renderNewVideoAction()
      })
      .catch(_ => {
        removeTagNewVideo();
        alert("Wystąpił błąd! Nie można zapisać wideo w bibliotece.");
      });
  });
  elVideoRecorderActions.append(btnUpload);
  elVideoRecorderActions.append(btnSave);
  tagVideoWelcome.append(elNewVideo);
}


const renderUploadingBar = () => {
  removeTagNewVideo();
  const elUploadingBar = cloneTemplate('tmplUploadingBar');
  if (!elUploadingBar)
    return false;
  tagVideoWelcome.append(elUploadingBar);
}


const renderNewVideoAction = () => {
  removeTagNewVideo();
  const elNewVideo = cloneTemplate('tmplNewVideo');
  if (!elNewVideo)
    return false;
  const elMessage = elNewVideo.querySelector('.message');
  elMessage.innerText = 'Wideo zostało zapisane w Twojej bibliotece!';
  tagVideoWelcome.append(elNewVideo);
}

const removeTagNewVideo = () => {
  const tagNewVideo = tagVideoWelcome.querySelector('.new-video');
  if (tagNewVideo)
    tagVideoWelcome.removeChild(tagNewVideo);
}


const run = () => {
  const videoTag = document.getElementById('mediaPreview');
  const videoBox = createVideoBox({videoTag});
  renderSelectMedia({videoBox});
}

run()
