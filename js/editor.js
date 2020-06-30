$(function () {
  const interaction = new ol.interaction.DragRotateAndZoom();
  // Init map
  const map = new ol.Map({
      interactions: ol.interaction.defaults().extend([
            interaction
      ]),
      controls: ol.control.defaults().extend([
        new ol.control.FullScreen()
    ]),
      target: 'svg-container',
      view: new ol.View({
          center: [0, 0],
          extent: [-3600, -1800, 3600, 1800],
          projection: 'EPSG:4326',
          rotation: Math.PI / 90,
          zoom: 4
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
    $(".plotbound, .smallplot").click(function() {
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

    const id = $('.selectpicker :selected').data('id');
    const name = $('.selectpicker :selected').data('name');
    const type = $('input[name="typeInput"]:checked').val();
    const area = $('.selectpicker :selected').data('area');
    const row = $('.selectpicker :selected').data('row');

    polyline.attr('id', id);
    polyline.attr('name', name);
    polyline.attr('type', type);
    polyline.append("<title>" + name + "</title>");

    // Update parent's data, group
    const parent = polyline.parent();
    parent.attr('id', id);
    parent.attr('area', area);
    parent.attr('row', row);

    // Reset input
    $('#idInput').val("");
    $('#nameInput').val("");

    $('.selectpicker :selected').attr('disabled', true);
    $('.selectpicker').selectpicker('refresh');

    $('#editorModal').modal('hide');

    $.ajax({
      url: 'update.php',
      type: 'post',
      data: {id: id, male: type, svg: parent[0].outerHTML},
      success: function(data) {
        parent.find('.plotinfo').html(data);
        onUpdatePlot();
      }
    });
  });

  // On update plot
  const onUpdatePlot = function() {
    const parent = polyline.parent();
    const id = parent.attr('id');

    const plot = $('<input type="hidden" name="plots['+id+']"></input>').val(parent[0].outerHTML);
    $('#submitForm').append(plot);
  }

  // On save new svg
  $('#saveBtn').click(function(event) {
    event.preventDefault();

    let container = $('.' + svgContainer.className);
    $('#content').val(container.html());

    $('form').submit();
  });

  $('#areaSelect').change(function() {
    let html = '';
    const option = $(this).val();

    $.each(data[option], function(index, value) {
      html += "<option data-area=" + value[2] + " data-row=" + value[3] + " data-id=" + value[0] + " data-name='" + value[1] + "'>"
      html += "Khu vực: " + value[2] + "- Hàng: " + value[3] + "- STT: " + value[4] + ", " + value[1];
      html += "</option>";
    });

    $('#selectpicker').html(html);
    disableSelecteOptions();
    $('#selectpicker').selectpicker('refresh');
  });

  // Init selectpicer
  $('.selectpicker').selectpicker();
  if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
    $('.selectpicker').selectpicker('mobile');
  }
});