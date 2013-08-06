
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
    // Inizializzazione grafica
    $.fn.MpsUi =  function() {
        
        //Check Box
        $('[type="checkbox"]').each(function(idx, elem) {
            var $this = $(elem);
            $this.wrapAll('<span class="mps-ui-checkbox"/>');
        });
        
        $('select').each(function(idx, elem) {
            var $this = $(elem);
            var width = $this.outerWidth(true);
            $this.css({'width' : (width + 25)+'px'})
            $this.wrapAll('<div class="mps-ui-select" style="width: '+width + 'px"/>');
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
                        $(this).find('input').prop("checked", true);
                        $(this).removeClass('checked');
                    } else {
                        $(this).find('input').prop("checked", false);
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
