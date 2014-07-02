// listen for sendToClient event and recieve data

socket.on('sendToClient', function(data){
	// print data (jquery thing)
    var nSound = document.getElementById("notify-sound");
    if(getCookie('mobile')=="false"){
        if(notifysound=="true") nSound.Play();
    }else{
        if(notifysound=="true"){
            jQuery("#notify-div").append("<audio id=\"notify-sound\" src=\"./addons/new_document_notify2/sound/notify.mp3\" autoplay='autoplay'></audio>");
            jQuery('#notify-sound').remove();
        }
    }
	if(data.type=='comment'){
		jQuery("#notify-div").append("<div class=\"notify-div-alert notify-div-alert-info new-document-notify\"><span class=\"notify-text\"><a href=\""+default_url+"/"+data.document_srl+"\">"+data.name+"님이 "+data.title+" 글에 댓글을 남기셨습니다</a></span><a href=\"#\" class=\"close close-button\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</a></div>");
	}else{
		jQuery("#notify-div").append("<div class=\"notify-div-alert notify-div-alert-info new-document-notify\"><span class=\"notify-text\">새글 알림 : <a href=\""+default_url+"/"+data.document_srl+"\">"+data.title+"</a></span><a href=\"#\" class=\"close close-button\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</a></div>");
	}
	jQuery('.new-document-notify:last-child').fadeIn(1000).delay(delay).fadeOut(3000);

});
function getCookie(cName) {
    cName = cName + '=';
    var cookieData = document.cookie;
    var start = cookieData.indexOf(cName);
    var cValue = '';
    if(start != -1){
        start += cName.length;
        var end = cookieData.indexOf(';', start);
        if(end == -1)end = cookieData.length;
        cValue = cookieData.substring(start, end);
    }
    return decodeURI(cValue);
}
+function ($) { "use strict";

  // ALERT CLASS DEFINITION
  // ======================

  var dismiss = '[data-dismiss="alert"]'
  var Alert   = function (el) {
    $(el).on('click', dismiss, this.close)
  }

  Alert.prototype.close = function (e) {
    var $this    = $(this)
    var selector = $this.attr('data-target')

    if (!selector) {
      selector = $this.attr('href')
      selector = selector && selector.replace(/.*(?=#[^\s]*$)/, '') // strip for ie7
    }

    var $parent = $(selector)

    if (e) e.preventDefault()

    if (!$parent.length) {
      $parent = $this.hasClass('alert') ? $this : $this.parent()
    }

    $parent.trigger(e = $.Event('close.bs.alert'))

    if (e.isDefaultPrevented()) return

    $parent.removeClass('in')

    function removeElement() {
      $parent.trigger('closed.bs.alert').remove()
    }

    $.support.transition && $parent.hasClass('fade') ?
      $parent
        .one($.support.transition.end, removeElement)
        .emulateTransitionEnd(150) :
      removeElement()
  }


  // ALERT PLUGIN DEFINITION
  // =======================

  var old = $.fn.alert

  $.fn.alert = function (option) {
    return this.each(function () {
      var $this = $(this)
      var data  = $this.data('bs.alert')

      if (!data) $this.data('bs.alert', (data = new Alert(this)))
      if (typeof option == 'string') data[option].call($this)
    })
  }

  $.fn.alert.Constructor = Alert


  // ALERT NO CONFLICT
  // =================

  $.fn.alert.noConflict = function () {
    $.fn.alert = old
    return this
  }


  // ALERT DATA-API
  // ==============

  $(document).on('click.bs.alert.data-api', dismiss, Alert.prototype.close)

}(jQuery);

