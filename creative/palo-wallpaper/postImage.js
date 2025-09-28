const postImages = [];

const storyContent = document.querySelector('.story-content');
const allImages = storyContent.querySelectorAll('.story-card-image');

(async () => {
  for (const elem of allImages) {
    await new Promise(resolve => {
      elem.scrollIntoView();

      setTimeout(() => {
        if (confirm('Pick this?')) {
          const img = elem.querySelector('picture.qt-image img');
          const title = elem.querySelector('.story-element-image-title');
          const author = elem.querySelector('.story-element-image-attribution');

          postImages.push({
            img: img.src,
            title: title.innerText,
            author: author.innerText,
            target: location.href,
          })

          return resolve(true);
        }

        resolve(false);
      }, 500);
    });
  }

  if (postImages.length > 0) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'https://creative.jahidlimon.com/palo-wallpaper/store.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4) {
        alert('Response: ' + xhr.responseText);
      }
    };
    xhr.send(JSON.stringify(postImages));
  }
})();