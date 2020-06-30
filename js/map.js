jQuery.expr[':'].contains = function(a, i, m) {
  return jQuery(a).text().toUpperCase()
    .indexOf(m[3].toUpperCase()) >= 0;
};

let polyline = null;

$(function(){

  var interaction = new ol.interaction.DragRotateAndZoom();
  const map = new ol.Map({
    interactions: ol.interaction.defaults().extend([interaction]),
controls: ol.control.defaults().extend([
      new ol.control.FullScreen()
  ]),
      target: 'map',
      view: new ol.View({
          center: [0, 0],
          extent: [-3600, -1800, 3600, 1800],
          projection: 'EPSG:4326',
          rotation: Math.PI / 90,
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

      // SVG events
      plotDetail();
  });
  xhr.send();

  const width = 1280;
  const height = 2560;
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

  const plotDetail = function() {
    $(".plotbound, .smallplot").click(function() {
      let rect = $(this);
      if (!rect.attr('id')) {
        return false;
      }
  
      // Show information
      $.ajax({
        url: 'detail.php',
        type: 'post',
        data: {'id': rect.attr('id')},
        success: function(data) {
          if ('NO' == data) {
            return false;
          }
          $('#detailBody').html(data);
          $('#biaModal').modal('show');
        }
      });
    });
  }

  $('#btnSearch').click(function(event) {
    event.preventDefault();

    if (polyline) {
      polyline.removeClass('plot-selected');
      polyline = null;
    }

    const name = $('#txtName').val();
    if (!name) {
      return false;
    }

    polyline = $("rect:contains('"+$('#txtName').val()+"')");
    if (!polyline.length) {
      return false;
    }

    polyline.addClass('plot-selected');

    // Zoom
    const x = polyline.attr('x');
    const y = polyline.attr('y');

    const view = map.getView();
    //view.fit([x,y]);

    // Show information
    $.ajax({
      url: 'info.php',
      type: 'post',
      data: {'id': polyline.parent().attr('id')},
      success: function(data) {
        if ('NO' == data) {
          return false;
        }
        $('#groupData').html(data);
        $('#infoModal').modal('show');
      }
    });
  });

});
