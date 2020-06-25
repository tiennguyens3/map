$(function () {
  const interaction = new ol.interaction.DragRotateAndZoom();
  const map = new ol.Map({
      interactions: ol.interaction.defaults().extend([
        interaction
      ]),
      controls: ol.control.defaults().extend([
        new ol.control.FullScreen()
      ]),
      target: 'map',
      view: new ol.View({
          center: [0, 0],
          extent: [-180, -90, 180, 90],
          projection: 'EPSG:4326',
          zoom: 1
      }),
  });

  const svgContainer = document.createElement('div');
  const xhr = new XMLHttpRequest();
  xhr.open('GET', svgPath);
  xhr.addEventListener('load', function() {
      const svg = xhr.responseXML.documentElement;
      svgContainer.ownerDocument.importNode(svg);
      svgContainer.appendChild(svg);
  });
  xhr.send();

  const width = 1920;
  const height = 1200;
  const svgResolution = 360 / width;
  svgContainer.style.width = width + 'px';
  svgContainer.style.height = height + 'px';
  svgContainer.style.transformOrigin = 'top left';
  svgContainer.className = 'svg-layer';

  map.addLayer(
    new ol.layer.Layer({
      render: function(frameState) {
        const scale = svgResolution / frameState.viewState.resolution;
        const center = frameState.viewState.center;
        const size = frameState.size;
        const cssTransform = ol.transform.composeCssTransform(
            size[0] / 2,
            size[1] / 2,
            scale,
            scale,
            frameState.viewState.rotation, -center[0] / svgResolution - width / 2,
            center[1] / svgResolution - height / 2
        );
        svgContainer.style.transform = cssTransform;
        svgContainer.style.opacity = this.getOpacity();
        return svgContainer;
      }
    })
  );

});