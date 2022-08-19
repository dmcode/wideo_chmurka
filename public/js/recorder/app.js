import {hasMediaSelected, getDisplayMedia, getVideoRecorder} from "./recorder.js";
import {cloneTemplate} from "../tools.js";

const elVideoRecorder = document.querySelector('.video-recorder');
const elMediaPreview = document.querySelector('.media-preview-wrapper');
const videoTag = document.getElementById('mediaPreview');

let recorder = null;


const selectMedia = async () => {
  try {
    await getDisplayMedia();
    recorder = getVideoRecorder({videoTag});
  }
  catch (error) {
    alert("Nie można wybrać urządzenia wideo!");
  }
}


const startRecording = ({elMediaControls=null, elapsedTag=null, btnRec=null}) => {
  recorder.reset();
  recorder.onTimeInterval(seconds => {
    const min = Math.floor(seconds / 60);
    const sec = seconds - (min * 60);
    elapsedTag.innerText = `${min < 10 ? '0' + min : min}:${(sec < 10 ? '0' + sec : sec)}`;
  });
  recorder.onStart(_ => {
    const tmplRecordingInfo = cloneTemplate('tmplRecordingInfo');
    if (tmplRecordingInfo && elMediaPreview) {
      elMediaPreview.querySelector('.recording-info').appendChild(tmplRecordingInfo);
    }
  });
  recorder.onStop(_ => {
    btnRec.checked = false;
    if (elMediaPreview)
      elMediaPreview.querySelector('.recording-info').replaceChildren();
  });
  recorder.start();
}


const stopRecording = () => {
  if (recorder)
    recorder.stop();
}


const renderMediaControls = () => {
  const elMediaControls = cloneTemplate('tmplMediaControls');
  if (!elMediaControls)
    return;
  const btnRec = elMediaControls.getElementById('btnRec');
  const elapsedTag = elMediaControls.getElementById('elapsed');
  btnRec.addEventListener('change', e => {
    if (e.target.checked)
      startRecording({elMediaControls, elapsedTag, btnRec});
    else
      stopRecording();
  });
  elMediaPreview.appendChild(elMediaControls);
}


const renderSelectMedia = () => {
  const elSelectMedia = cloneTemplate('tmplSelectMedia');
  if (!elSelectMedia)
    return;
  const btn = elSelectMedia.querySelector('.btn-select-media');
  btn.addEventListener('click', async e => {
    e.preventDefault();
    e.stopPropagation();
    await selectMedia();
    if (hasMediaSelected()) {
      btn.remove();
      elMediaPreview.querySelector('.select-media').remove();
      renderMediaControls();
    }
  });
  elMediaPreview.appendChild(elSelectMedia);
}


const run = () => {
  if (!hasMediaSelected()) {
    renderSelectMedia();
  }
}

run()
