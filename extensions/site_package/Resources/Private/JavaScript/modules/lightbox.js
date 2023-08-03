// ref: https://github.com/biati-digital/glightbox
import GLightbox from "glightbox";
import "glightbox/dist/css/glightbox.min.css";

export const LIGHTBOX_SELECTOR = '.glightbox';
const init = () => {
  GLightbox({selector: LIGHTBOX_SELECTOR});
}

export default init;


