import 'vite/modulepreload-polyfill';

import "bootstrap";
//https://github.com/fontsource/fontsource
import "@fontsource/roboto";

import "../Scss/styles.scss";
import "../Scss/fontawesome.scss";

if (import.meta.hot) {
  import.meta.hot.accept();
}

document.addEventListener('DOMContentLoaded', () => {
  const items = document.querySelectorAll('.glightbox');
  if (items.length > 0) {
    import("./modules/lightbox" /* webpackChunkName: "glightbox" */).then(({default: init}) => {
      init();
    })
  }

  // https://github.com/ganlanyuan/tiny-slider
  const sliders = document.querySelectorAll('.tiny-slider');
  if (sliders.length) {
    import("./modules/tiny-slider" /* webpackChunkName: "tiny-slider" */).then(({default: initSlider}) => {
      initSlider(sliders);
    })
  }
});

// wait for the vitejs backend to reload correctly after vite.config.js or package.json changes
if (process.env.NODE_ENV !== "production") {
  let retries = 5;
  const { fetch: originalFetch } = window;

  window.fetch = async (...args) => {
    let [resource] = args;
    if (resource.includes(`:${import.meta.env.VITE_DEV_PORT ?? 5173}`)) {
      try {
        const response =  await originalFetch(...args);
        if (!response.ok) {
          if (retries > 0) {
            retries--;
            await new Promise(resolve => setTimeout(resolve, 1000));
            return await originalFetch(...args);
          }
        }
      } catch (e) {
        console.log(e);
      }
    }
    return await originalFetch(...args);
  };
}
