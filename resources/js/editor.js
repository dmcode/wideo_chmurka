const videoForm = document.getElementById('videoForm');

const result = (key, text) => {
  const p = document.createElement('p');
  p.className = `results ${key}`;
  p.innerText = text;
  videoForm.querySelector('.results').replaceChildren(p);
  setTimeout(() => {
    videoForm.querySelector('.results').innerHTML = '';
  }, 5000);
}

if (videoForm) {
  videoForm.addEventListener('submit', (e) => {
    e.preventDefault();
    result('pending', "");
    const data = new FormData(e.target);
    const value = Object.assign(Object.fromEntries(data.entries()), {
      visibility: document.getElementById('visibility').checked,
      lid: videoForm.dataset.lid,
      _token: videoForm.dataset.token,
    });
    fetch(videoForm.dataset.apivd, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(value),
    }).then(_ => result('success', "Zapisano pomyślnie!"))
      .catch(_ => {
        alert("Nie udało się zapisać danych!");
        result('error', "Nie udało się zapisać danych!");
      });
  });
}
