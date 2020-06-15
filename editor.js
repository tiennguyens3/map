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

  svgPanZoom('#svg-container svg', {
    zoomEnabled: true,
    controlIconsEnabled: true,
    fit: true,
    center: true,
    minZoom: 0.5,
    customEventsHandler: eventsHandler
  });

  var polyline = null;
  $('#Layer_1').find('rect:not(".svg-pan-zoom-control-background")').click(function() {
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
      data: {id: id, male: type},
      success: function(data) {
        var parent = polyline.parent();
        parent.find('text').css('font-size', '5px').html(data);
      }
    });
  });

  $('#saveBtn').click(function(event) {
    event.preventDefault();

    var container = $('<div><svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 1027.09 918.04"></svg></div>');
    container.find('svg').append($('#Layer_1').find('defs').first());
    container.find('svg').append($('.svg-pan-zoom_viewport'));

    $('#content').val(container.html());

    $('form').submit();
  });

  $('#Layer_1 rect[id]').each(function() {
    $('option[data-id="'+ $(this).attr('id') +'"]').attr('disabled', true);
  });

  $('.selectpicker').selectpicker();

  if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
    $('.selectpicker').selectpicker('mobile');
  }
});