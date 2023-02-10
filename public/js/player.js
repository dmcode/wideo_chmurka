const videoPlayer = document.getElementById('htmlplayer');
const vids = {};

if (videoPlayer) {
  const vid = videoPlayer.dataset.vid;
  const _token = videoPlayer.dataset.token;
  videoPlayer.addEventListener('play', () => {
    if (vids.hasOwnProperty(vid))
      return false;
    fetch(videoPlayer.dataset.apirv, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({vid, _token}),
    }).then(_ => {
      vids[vid] = true;
    })
  });
}
