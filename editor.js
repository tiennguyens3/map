$(function () {
  svgPanZoom('#svg-container svg', {
    zoomEnabled: true,
    controlIconsEnabled: true,
    fit: true,
    center: true,
    minZoom: 0.1
  });

  var polyline = null;
  $('polyline').click(function() {
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

  $('#saveChanges').click(function() {
    var id = $('.selectpicker :selected').data('id');
    var name = $('.selectpicker :selected').data('name');

    polyline.attr('id', id);
    polyline.attr('name', name);
    polyline.attr('type', $('input[name="typeInput"]:checked').val());

    polyline.append("<title>" + name + "</title>");

    $('#idInput').val("");
    $('#nameInput').val("");

    $('.selectpicker :selected').attr('disabled', true);
    $('.selectpicker').selectpicker('refresh');

    $('#editorModal').modal('hide');
  });

  $('#saveBtn').click(function(event) {
    event.preventDefault();

    var svg = $('#svg-container').clone();
    svg.find('#svg-pan-zoom-controls').remove();
    $('#content').val(svg.html());

    $('form').submit();
  });

  $('g[fill="white"] polyline[id]').each(function() {
    $('option[data-id="'+ $(this).attr('id') +'"]').attr('disabled', true);
  });

  $('.selectpicker').selectpicker();
});