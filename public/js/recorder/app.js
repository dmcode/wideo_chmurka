import {hasMediaSelected, getDisplayMedia, getVideoRecorder} from "./recorder.js";
import {cloneTemplate} from "../tools.js";

const elVideoRecorder = document.querySelector('.video-recorder');
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


const renderSelectMedia = () => {
  const elSelectMedia = cloneTemplate('tmplSelectMedia');
  if (!elSelectMedia)
    return;
  const btn = elSelectMedia.querySelector('.select-media');
  btn.addEventListener('click', async e => {
    e.preventDefault();
    await selectMedia();
    if (hasMediaSelected())
      btn.remove();
  });
  elVideoRecorder.appendChild(elSelectMedia);
}


const run = () => {
  if (!hasMediaSelected()) {
    renderSelectMedia();
  }
}

run()
