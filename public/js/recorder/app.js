import {createVideoBox} from "./recorder.js";
import {cloneTemplate} from "../tools.js";


const tagMediaPreview = document.querySelector('.media-preview-wrapper');


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
  });
  videoBox.onStop(_ => {
    const tmplRecordingInfoStop = cloneTemplate('tmplRecordingInfoStop');
    if (tmplRecordingInfoStop && tagMediaPreview)
      tagMediaPreview.querySelector('.recording-info').replaceChildren(tmplRecordingInfoStop);
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
    videoBox.upload('/api/upload_blob');
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


const run = () => {
  const videoTag = document.getElementById('mediaPreview');
  const videoBox = createVideoBox({videoTag});
  renderSelectMedia({videoBox});
}

run()
