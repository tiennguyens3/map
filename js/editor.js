$(function () {

  // Init map
  const map = new ol.Map({
      target: 'svg-container',
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

      // SVG events
      disableSelecteOptions();
      onClickPlot();
  });
  xhr.send();

  const width = 1024;
  const height = 800;
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
        },
    })
  );

  // Disable selected options.
  const disableSelecteOptions = function() {
    $('#svg-container rect[id]').each(function() {
      $('option[data-id="'+ $(this).attr('id') +'"]').attr('disabled', true);
    });
  };

  // On click plot
  let polyline = null;
  const onClickPlot = function() {
    $('rect.st27').click(function() {
      polyline = $(this);

      if (polyline.attr('id')) {
        $('#idInput').val(polyline.attr('id'));
        $('option[data-id="'+ polyline.attr('id') +'"]').attr('selected', true);
      }

      if (polyline.attr('type')) {
        $('input[name="typeInput"][value="'+polyline.attr('type')+'"]').attr('checked', true);
      }

      $('.selectpicker').selectpicker('refresh');
      $('#editorModal').modal('show');
    });
  }

  // On save changes
  $('#saveChanges').click(function() {
    if (!$('.selectpicker').val()) {
      return false;
    }

    var id = $('.selectpicker :selected').data('id');
    var name = $('.selectpicker :selected').data('name');
    var type = $('input[name="typeInput"]:checked').val();

    polyline.attr('id', id);
    polyline.attr('name', name);
    polyline.attr('type', type);

    polyline.append("<title>" + name + "</title>");

    $('#idInput').val("");
    $('#nameInput').val("");

    $('.selectpicker :selected').attr('disabled', true);
    $('.selectpicker').selectpicker('refresh');

    $('#editorModal').modal('hide');

    $.ajax({
      url: 'update.php',
      type: 'post',
      data: {id: id, male: type, svg: polyline.parent()[0].outerHTML},
      success: function(data) {
        var parent = polyline.parent();
        parent.find('text').html(data);
      }
    });
  });

  // On save new svg
  $('#saveBtn').click(function(event) {
    event.preventDefault();

    let container = $('.' + svgContainer.className);
    $('#content').val(container.html());

    $('form').submit();
  });

  // Init selectpicer
  $('.selectpicker').selectpicker();
  if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
    $('.selectpicker').selectpicker('mobile');
  }
});