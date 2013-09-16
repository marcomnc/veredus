
jQuery(document).ready(function(){
    //on load function
    jQuery(".header-selector .selected").headerSwitchers();
    jQuery.fn.MpsUi();
    if ( jQuery("#ajax-login").length > 0 && jQuery('.popup-login-content').length > 0) {
        jQuery("#ajax-login").ajaxLogin();
    }
    
    if (jQuery(".block-layered-nav dl dt").length>0){
        jQuery(".block-layered-nav dl dt").css("cursor", "pointer");
        jQuery(".block-layered-nav dl dt").bind ("click", function(){
            if (!jQuery(this).hasClass("closed")) {
                jQuery(this).addClass("closed");
                jQuery(this).next("dd").slideUp("slow");
            } else {
                jQuery(this).removeClass("closed");
                jQuery(this).next("dd").slideDown("slow");
            }
        })
    }
});

//Header Switcher
(function ($, window, undefined) {
    $.fn.headerSwitchers = function() {        
        return $(this).each(function(idx,el) {
            $.fn.headerSwitcher(el);
        })
    }
    
    $.fn.headerSwitcher = function(elem) {        
        var rel ='[rel="'+$(elem).attr('id')+'"]';
        var select = rel+'.header-selector';
        var view = rel+'.header-selector-pop-up';
        var closer = null;
        var rowBt = function () {
            $(select).addClass("viewed");
        }
        var rowLf = function () {
            $(select).removeClass("viewed");            
        }                

        $(elem).bind("click", function() {
            if ($(view).css("display") == "none") {
                $(view).css('left', $(select).position().left);
                $(view).css('top', $('.header-right').outerHeight()+'px');
                $(view).css('position', 'absolute');
                $(view).slideDown('fast', rowBt);
            } else {
                $(view).slideUp("fast", rowLf);
                closer=setTimeout(function(){$(view).slideUp("fast", rowLf); closer=null},100);
            }
            return false;
        });

        $(select).parent('li').bind("mouseleave", function() {
            closer=setTimeout(function(){$(view).slideUp("fast", rowLf); closer=null},100);
        })
        
        $(view+", "+select).mouseenter(function() {
            clearTimeout(closer);
        });

        $(view).find("a").bind("click", function() {
            var href = $(this).attr("href");
            if (href!="" && href!="#") window.location = href;
            return false;
        });

    }
    
}) (jQuery, this);

/**
 * Manage Login Form
 *
 */
(function ($, window, undefined) {
    $.fn.ajaxLogin = function() {
        var t=$(this);
        var l=$(".popup-login-content");
        var p=new Object();
        p.o=5;
        p.t=new Object();
        p.t.x=0;p.t.y=0;
        p.s=new Object();
        p.s.x=0;p.s.y=0;p.s.z=0;
        p.l=new Object();
        p.l.x=0;p.l.y=0;p.l.z=0;

        function init () {
            p.t.x=t.offset().left;
            p.t.y=t.offset().top;
            p.l.z=parseInt(l.css("z-index"))||20000;
            p.s.x=0;
            p.s.y=0;
            p.s.z=p.l.z+1;
            p.l.x=p.t.x+t.outerWidth()-l.outerWidth()/2;
            p.l.y=p.t.y+t.outerHeight()+p.o;
            
            l.css({"display": "none",
                   "top": p.l.y+"px",
                   "left": p.l.x+"px"});
            hideLoginForm();
        }

        function showLoginForm() {
            if ((t.offset().left+t.outerWidth()/2+l.outerWidth()/2)>$(window).width()) {
                p.l.x=p.t.x+t.outerWidth()+p.o-l.outerWidth();
                if (p.l.x<0) p.l.x=0;
            } else {p.l.x=p.t.x+t.outerWidth()-l.outerWidth()/2;}
            l.css({"left": p.l.x+"px"}).fadeIn(500);
            $.fn.layerShow(true,'strong',false, null,function() { hideLoginForm(); });
            t.unbind("click").bind("click", function() {
                hideLoginForm();
                return false;
            });

        }


        function hideLoginForm() {
            l.fadeOut(500);
            t.unbind("click").bind("click", function() {
                showLoginForm();
                return false;
            })
            $.fn.layerShow(false);
        }

        init();
    }
}) (jQuery, this);


//UI
(function ($, window, undefined){
    
    $.fn.CreateSelect = function(elem, reload) {
        var $this = $(elem);
        var opts = {'type': 'normal', 'col': "1", 'default': '', 'class': '', 'imgBaseUrl': '', 'reloadElem': '', 'callback': ''};

        if (typeof($this.attr('mps-rel')) !=  'undefined') {
            eval('var a = {' + $this.attr('mps-rel') + '}');
            var relOpts = a;                
            opts = $.extend(true,{}, opts, relOpts);
        }
        var width = $this.outerWidth(true);
        $this.css({'width' : (width + 25)+'px'});        
        var id = $this.attr('id');
        
        if (!reload) {
            if (typeof(id)=='undefined' && id == '') {
                id = (new Date()).getTick();
            }
            id = "select_container_"+id;
        } else {
            for(var i=0; i < $('.mps-ui-select-container').length; i++) {
                if ($($('.mps-ui-select-container')[i]).find('select').attr('id') == $this.attr('id')) {
                    id = $($('.mps-ui-select-container')[i]).attr('id');
                    break;
                }
            }
        }

        if (!reload)
            var $wrap = $this.wrapAll('<div class="mps-ui-select-container '+ opts['class'] +'" id="' + id + '"><div class="mps-ui-select" style="width: ' + width + 'px"/></div>');
        
        if (opts.type != 'normal') {
            $this.css({'display': 'none'});
            var initText = opts['default'];
            if ($this.find('option:selected').attr('value') != "") {
                 initText = $this.find('option:selected').text();
            }    
            if (!reload) {
                $('#'+id+" .mps-ui-select").append($('<div>', {'class': 'select-value'}));                
            }
            $('#'+id+" .mps-ui-select div.select-value").text(initText);
            
            var open = false;
            if (reload) {
                $('#'+id+" .options-container").remove();
            }
            var html = ""; 
            var liCounter = 0;
            for (var i = 0; i < $this.find('option').length; i++) {    
            	html +="\n<!--"+ i + " - " + liCounter%(opts.col) + "-->";
                if (i == 0 || (liCounter%(opts.col)) == 0) {
                    html += '<ul class="options" style="display:none">';
                }

                if ($this.find('option')[i].value != "" && i != 0) {                        
                    var selectClass = ($this.find('option')[i].value === $this.find('option:selected').attr('value')) ? 'selected': '';
                    html += '<li class="selectable '+selectClass+'">';

                    if ((typeof($this.find('option')[i].attributes['src']) != 'undefined' && $this.find('option')[i].attributes['src'] != "") || 
                        (opts.imgBaseUrl!='')) {
                        var url = (typeof($this.find('option')[i].attributes['src']) != 'undefined' && $this.find('option')[i].attributes['src'] != "") ? $this.find('option')[i].attributes['src'] : (opts.imgBaseUrl + $this.find('option')[i].value+".jpg") ;

                        html += '<div class="select-li-value" rel="' + $this.find('option')[i].value + '"';
                        html += 'style="background-image:url('+url+')"></div>';
                    } else { 
                        html += '<div class="select-li-value" rel="' + $this.find('option')[i].value + '">'+$this.find('option')[i].text+'</div>';
                    }                        
                    html += '</li>';
                    liCounter++;
                }

                if (i == ($this.find('option').length - 1) || ( i != 0  &&  ((liCounter%opts.col) == 0))) {
                    html += "</ul>";
                }
            }

            $("#"+id).append($('<div>', {'class': 'options-container', 'style': 'display:none; width:' + ($('#'+id).find('.mps-ui-select').outerWidth()-2) + 'px'}).append(html));
            
            $('#'+id+" .options").each(function(idx, elem) {
                if (idx == 0) {
                    $(elem).addClass('first');                        
                } 
                if (idx == ($('#'+id+" .options").length-1)) {
                    $(elem).addClass('last');              
                    if ($('#'+id+" .options li").length > 0) {
                        while ($(elem).find('li').length < opts.col) {                    
                            $(elem).append($('<li>', {class: 'not-selectable'}));
                        }
                    }
                }
                
                var border = 0;
                
                $(elem).find('li').each(function(idxLi, elemLi) {
                    if (idxLi == 0) {
                        $(elemLi).addClass('first');                        
                    }
                    if (idxLi == ($(elem).find('li').length-1)) {
                        $(elemLi).addClass('last');                      
                    }
                    border += $(elemLi).outerWidth() - $(elemLi).width();
                    
                    if (idxLi = $(elem).find('li').length-1) {
                        var widthLi = ($('#'+id+' .options-container').width()-border)/opts.col;
                        $(elem).find('li').width(widthLi);
                    }
                });
            });

            $('#'+id+" .mps-ui-select").click(function(event) {
               event.preventDefault();
               event.stopPropagation();
               if ($('#'+id+" .options-container li").length == 0)
                    return false;
               if (!open) {              
                   for (var i=0; i < $(".mps-ui-select-container.open").length; i++) {
                       var hideId = $($(".mps-ui-select-container.open")[i]).attr('id')
                       if (hideId != id) {
                           $('#'+hideId+" .options").slideUp('fast', function() { $('#'+hideId+" .options-container").css({'display': 'none'}); $('#'+hideId).removeClass('open'); });
                       }
                   }
                   $('#'+id).addClass('open');                       
                   $('#'+id+" .options-container").css({'display': 'block'});
                   if ($('#'+id+" .options-container").width() <= ($('#'+id+'.mps-ui-select-container').width()-2)){
                       $('#'+id+" .options-container").addClass('no-angle');
                   } else {
                       $('#'+id+" .options-container").removeClass('no-angle');                           
                   }
                   $('#'+id+" .options").slideDown('fast');                       
               }  else {
                   $('#'+id+" .options").slideUp('fast', function() { $('#'+id+" .options-container").css({'display': 'none'}); $('#'+id).removeClass('open'); });
                   $('#'+id+' .select-value').text( ($this.val() != "") ? $this.find('option:selected').text() : initText);
               }
               open=!open;
            });

            $('#'+id+" .options-container li.selectable").mouseover(function() {
                $(this).addClass('over');
            }).mouseout(function() {
                $(this).removeClass('over');
            }).click(function() {                    
                $('#'+id+" .options-container li").removeClass('selected');
                $(this).addClass('selected');
                $this.val($(this).find('div').attr('rel'));
                $('#'+id+' .select-value').text( ($this.val() != "") ? $this.find('option:selected').text() : initText);
                try {
                    spConfig.configureElement(document.getElementById($this.attr('id')));
                    var elemIds = opts.reloadElem.split(',');
                    for (var i=0; i < elemIds.length; i++) {
                        if (elemIds[i] != '')
                            $.fn.CreateSelect(document.getElementById(elemIds[i]), true);
                    }
                } catch (ex) {
                    //console.log(ex);
                }
                try {
                    if (opts.callback != '') {
                        opts.callback($this.attr('id'));
                    }
                } catch (ex) {
                
                }
                $('#'+id+" .mps-ui-select").trigger('click');
            });

        }
    };
    // Inizializzazione grafica
    $.fn.MpsUi =  function() {
        
        //Check Box
        $('[type="checkbox"]').each(function(idx, elem) {
            var $this = $(elem);
            $this.wrapAll('<span class="mps-ui-checkbox"/>');
        });
        
        $('select').each(function(idx, elem) {
            $.fn.CreateSelect(elem);
        });
        
        $('.mps-ui-checkbox').on('mouseover mouseout click', function(evt) {
            switch(evt.type) {
                case 'mouseover':
                    $(this).addClass('over');
                    break;
                case 'mouseout':
                    $(this).removeClass('over');
                    break;
                default:
                    if ($(this).hasClass('checked')) {
                        $(this).find('input').prop("checked", false).val(0);
                        $(this).removeClass('checked');
                    } else {
                        $(this).find('input').prop("checked", true).val(1);
                        $(this).addClass('checked');
                    }
                    break;
            }
        });
        
        //Link asyncroni
        $('a[mps-type="async-link"]').on('click', function(evt) {
            evt.preventDefault();
            evt.stopPropagation();
            if ($(this).attr('href') != '') {
                $.fn.AsyncRedirect($(this).attr('href'));
            }
        });
        
    };
    $.fn.layerShow = function(show, type,loading, zIndex, callback) {
        if (!type) type="medium";
        if (!loading) loading=false;
        
        var hide=function(){
            $('.popup-loading').fadeOut(100,function(){$('.popup-loading-layer').fadeOut(100)});
            if (callback) {
                callback();
            }
        };
        
        if (show) {
            if (zIndex){
                $('.popup-loading-layer').css({'z-index': zIndex});
            }
            $('.popup-loading-layer').addClass(type).fadeIn(100,function(){if (loading) {$('.popup-loading').fadeIn(100);}});
            $(window).on('keydown', function(evt) {
                if (evt.keyCode===27) {
                    hide();
                }
                evt.stopPropagation();
            });
            $('.popup-loading-layer').click(function(evt) {
               hide(); 
               evt.stopPropagation(); 
            });
        } else {
            hide();
            $(window).off('keydown');
            $('.popup-loading-layer').off('click');
        }
    };
    
    $.fn.AsyncRedirect = function(url) {
        if (typeof(url) != 'undefined' && url != '') {
            $.fn.layerShow(true,'medium',true, null, null);
            window.location = url;
        }
    };    
})(jQuery, this);


/**
 * Utility per simulare il click in prototype
 */
function fireEvent(element,event){
    if (document.createEventObject){
        // IE
        var evt = document.createEventObject();
        return element.fireEvent('on'+event,evt)
    }
    else{
        // Other
        var evt = document.createEvent("HTMLEvents");
        evt.initEvent(event, true, true ); 
        return !element.dispatchEvent(evt);
    }
}
var ChangeColor = function (element) {
                    var curVal = $(element).getValue(); 
                    var $elemToFire = null;
                    var $elemTiFireAlways = null;
                    for(var i=0; i<$$('.more-views li').length; i++) {                        
                        var $elem = $$('.more-views li')[i];
                        if ($elem.hasClassName('zoom-360')) {
                            continue;
                        }
                        eval('var data = {' + $elem.getAttribute('rel') + '}');
                        if (!$elem.hasClassName('always')) {                           
                           if ( data.value == curVal) {
                               $elem.removeClassName('hide');                                                  
                               if (data["default"]) {
                                    $elemToFire = $elem.down('a');                                
                                }
                           } else {
                               $elem.addClassName('hide');                               
                           } 
                       } else {
                            if (data["default"]) {
                                 $elemTiFireAlways = $elem.down('a');                                
                            }
                        }                      
                    }
                    
                    if ($elemToFire == null) 
                        $elemToFire = $elemTiFireAlways;
                    if ($elemToFire != null) 
                        fireEvent($elemToFire, 'click');
                };