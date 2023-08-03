// https://github.com/ganlanyuan/tiny-slider
import {tns} from "tiny-slider/src/tiny-slider";

function findSliderContainer(element) {
  let container = element.parentElement;
  if (container.length !== 1) {
    container = findSliderContainer(container)
  }
  return container;
}

function initSlider(sliders) {
    sliders.forEach(slider => {
      const nested = slider.dataset('nestedItemSelector');
      const container = findSliderContainer(slider.querySelector('.' + nested));
      const controlsContainer = slider.querySelector('.tiny-slider-controls-container');
      tns({
        container,
        gutter: 30,
        slideBy: "page",
        items: 3,
        controlsPosition: "bottom",
        autoplay: true,
        autoplayHoverPause: true,
        autoplayButtonOutput: false,
        rewind: false,
        mouseDrag: true,
        speed: 1000,
        controlsContainer,
        nav: true,
        navPosition: "bottom",
        responsive: {
          0: {
            items: 1
          },
          768: {
            items: 2
          },
          992: {
            items: 3
          }
        }
      })
    });
}

export default initSlider;
