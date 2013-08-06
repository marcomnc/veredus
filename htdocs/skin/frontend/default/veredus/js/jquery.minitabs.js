//
// Minitabs 0.1
// Requires jquery
//
//
// Usage :
//
// $j("#container").minitabs([speed],[slide|fade]);
//

jQuery.fn.minitabs = function(speed,effect) {
  id = "#" + this.attr('id')
  $j(id + ">DIV:gt(0)").hide();
  $j(id + ">UL>LI>A").css("cursor", "pointer");
  $j(id + ">UL>LI>A:first").addClass("current");
  $j(id + ">UL>LI>A").click(
    function(){
      $j(id + ">UL>LI").removeClass("current");
      $j(this).parent('LI').addClass("current");
      $j(this).blur();
      var re = /([_\-\w]+$)/i;
      //var target = $j('#' + re.exec(this.href)[1]);
      var target = $j('#' + re.exec(this.rel)[1]);
      var old = $j(id + ">DIV");
      var sw = new switcher (speed,effect, target, old);
      if (this.href!=""){
          $j.ajax({
                url: this.href,
                success : function (data, stato) {
                    $j("#"+target[0].id+"").html(data);
                    sw.switchTab();
                }
          });
      } else {sw.switchTab();}
      return false;
    }    
    
 );
};
(function ($,window,undefined) {    
    jQuery.fn.multiminitabs = function(speed,effect) {
        
        function tabs(comp) {
            
            comp.children("DIV:gt(0)").hide();
            comp.find("UL>LI>A").css("cursor", "pointer");
            comp.find("UL>LI:first").addClass("current");
            comp.find("UL>LI>A").click(
                function(){
                	if (!$(this).parent('LI').hasClass('current')) {
	                  comp.find("UL>LI").removeClass("current");
	                  $(this).parent('LI').addClass("current");
	                  $(this).blur();
	                  var re = /([_\-\w]+$)/i;
	                  var target = comp.children('#' + re.exec(this.rel)[1]);
	                  var old = comp.children("DIV");
	                  var sw = new switcher (speed,effect, target, old);
	                  if (this.href!=""){
	                      $j.ajax({
	                            url: this.href,
	                            success : function (data, stato) {
	                                comp.children("#"+target[0].id+"").html(data);
	                                sw.switchTab();
	                            }
	                      });
	                  } else {sw.switchTab();}
	                }
                  return false;
                }    
             );
        }
        return this.each(function (idx)  {
            if ($(this).find("LI").length>0) {
                tabs($(this));
            }            
        })
    };
})(jQuery,this);


function switcher (speed,effect, target, old) {
    this.switchTab = function () {
    switch (effect) {
        case 'fade':
          old.fadeOut(speed).fadeOut(speed);
          target.fadeIn(speed);
          break;
        case 'slide':
          old.slideUp(speed);  
          target.fadeOut(speed).fadeIn(speed);
          break;
        case 'css':
          old.css({'display':'none'});  
          target.css({'display':'block'});  
       case 'class':
          old.removeClass('current');
          target.removeClass('current');
        default :
          old.hide(speed);
          target.show(speed)
    }
  }
}
