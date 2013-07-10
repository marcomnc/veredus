
jQuery(window).load(function(){

    jQuery(".header-selector .selected").headerSwitchers();
});

(function ($, window, undefined) {
    jQuery.fn.headerSwitchers = function() {        
        return $(this).each(function(idx,el) {
            $.fn.headerSwitcher(el);
        })
    }
    jQuery.fn.headerSwitcher = function(elem) {        
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