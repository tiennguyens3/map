jQuery.expr[':'].contains = function(a, i, m) {
  return jQuery(a).text().toUpperCase()
    .indexOf(m[3].toUpperCase()) >= 0;
};

var polyline = null;
var panZoomTiger = null;
$(function () {
  var eventsHandler = {
    haltEventListeners: ['touchstart', 'touchend', 'touchmove', 'touchleave', 'touchcancel']
  , init: function(options) {
      var instance = options.instance
        , initialScale = 1
        , pannedX = 0
        , pannedY = 0

      // Init Hammer
      // Listen only for pointer and touch events
      this.hammer = Hammer(options.svgElement, {
        inputClass: Hammer.SUPPORT_POINTER_EVENTS ? Hammer.PointerEventInput : Hammer.TouchInput
      })

      // Enable pinch
      this.hammer.get('pinch').set({enable: true})

      // Handle double tap
      this.hammer.on('doubletap', function(ev){
        instance.zoomIn()
      })

      // Handle pan
      this.hammer.on('panstart panmove', function(ev){
        // On pan start reset panned variables
        if (ev.type === 'panstart') {
          pannedX = 0
          pannedY = 0
        }

        // Pan only the difference
        instance.panBy({x: ev.deltaX - pannedX, y: ev.deltaY - pannedY})
        pannedX = ev.deltaX
        pannedY = ev.deltaY
      })

      // Handle pinch
      this.hammer.on('pinchstart pinchmove', function(ev){
        // On pinch start remember initial zoom
        if (ev.type === 'pinchstart') {
          initialScale = instance.getZoom()
          instance.zoomAtPoint(initialScale * ev.scale, {x: ev.center.x, y: ev.center.y})
        }

        instance.zoomAtPoint(initialScale * ev.scale, {x: ev.center.x, y: ev.center.y})
      })

      // Prevent moving the page on some devices when panning over SVG
      options.svgElement.addEventListener('touchmove', function(e){ e.preventDefault(); });
    }

  , destroy: function(){
      this.hammer.destroy()
    }
  }

  panZoomTiger = svgPanZoom('#svg-container svg', {
    zoomEnabled: true,
    controlIconsEnabled: true,
    fit: true,
    center: true,
    minZoom: 0.5,
    contain: false,
    customEventsHandler: eventsHandler
  });

  $('#btnSearch').click(function(event) {
    event.preventDefault();

    // Reset
    panZoomTiger.reset();

    if (polyline) {
      polyline.removeClass('plot-selected');
      polyline = null;
    }

    var name = $('#txtName').val();
    if (!name) {
      return false;
    }

    polyline = $("rect:contains('"+$('#txtName').val()+"')");
    if (!polyline.length) {
      return false;
    }

    polyline.addClass('plot-selected');

    var x = polyline.attr('x');
    var y = polyline.attr('y');

    //panZoomTiger.pan({x: x, y: y});
    panZoomTiger.zoomAtPoint(3, {x: x, y: y});

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

  var rect = null;
  $('#Layer_1').find('rect').click(function() {
    rect = $(this);
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
});