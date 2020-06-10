jQuery.expr[':'].contains = function(a, i, m) {
  return jQuery(a).text().toUpperCase()
    .indexOf(m[3].toUpperCase()) >= 0;
};

var polyline = null;
var panZoomTiger = null;
$(function () {
  panZoomTiger = svgPanZoom('#svg-container svg', {
    zoomEnabled: true,
    controlIconsEnabled: true,
    fit: true,
    center: true,
    minZoom: 0.5,
    contain: false
  });

  $('#btnSearch').click(function(event) {
    event.preventDefault();

    // Reset
    panZoomTiger.reset();

    if (polyline) {
      polyline.removeClass('selected');
      polyline = null;
    }

    var name = $('#txtName').val();
    if (!name) {
      return false;
    }

    polyline = $("polyline:contains('"+$('#txtName').val()+"')");
    if (!polyline) {
      return false;
    }

    polyline.addClass('selected');

    var point = polyline.attr('points').split(" ", 1)[0].split(",");
    var a = Math.round(point[0]) + 50;
    var b = Math.round(point[1]) + 150;

    var x = 512 - a;
    var y = 400 - b - 16;

    panZoomTiger.pan({x: x, y: y});
    panZoomTiger.zoomAtPoint(3, {x: 512, y: 400});

    // Show information
    $.ajax({
      url: 'info.php',
      type: 'post',
      data: {'id': polyline.attr('id')},
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