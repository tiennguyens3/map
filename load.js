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

    if (polyline) {
      panZoomTiger.reset();
      polyline.removeClass('selected');

      polyline = null;
    }

    var name = $('#txtName').val();
    if (!name) {
      return;
    }

    polyline = $("polyline:contains('"+$('#txtName').val()+"')");
    if (!polyline) {
      return false;
    }

    polyline.addClass('selected');

    var point = polyline.attr('points').split(" ", 1)[0].split(",");
console.log(point);

    point[0] = Math.round(point[0]) + 200;
    point[1] = Math.round(point[1]) + 200;

    var offset = polyline.offset();

    panZoomTiger.zoomAtPoint(3, {x: point[0], y: point[1]});
  });
});