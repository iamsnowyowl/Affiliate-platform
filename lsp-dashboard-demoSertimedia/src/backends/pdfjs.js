export default class PDFJs {
  init = (source, element) => {
    const iframe = document.createElement('iframe');

    iframe.src = `${source}`;
    iframe.width = '600px';
    iframe.height = '700px';

    element.appendChild(iframe);
  };
}
