/**
 * Manage Header Switcher
 *
 */
(function ($, window, undefined) {
    jQuery.fn.headerSwitcher = function(formClass) {
        var myelem = this;
        var rowBt = function () {
            $(myelem).addClass("viewed");
        }
        var rowLf = function () {
            $(myelem).removeClass("viewed");
        }

        $(this).bind("click", function() {
            if ($(formClass).css("display") == "none") {
                $(formClass).css('left', $(this).parent("DIV").position().left - parseInt($(this).parent("DIV").css("padding-left")));
                $(formClass).css('top', ($(this).parent("DIV").outerHeight() + 8)+'px');
                $(formClass).css('position', 'absolute');
                $(formClass).slideDown('fast', rowBt);
            } else {
                $(formClass).slideUp("fast", rowLf);
            }
            return false;
        });

        $(formClass).bind("mouseleave", function() {
            $(this).slideUp("fast", rowLf);
        })

        $(formClass).find("a").bind("click", function() {
            var href = $(this).attr("href");
            if (href!="" && href!="#") window.location = href;
            return false;
        });

    }
    
    jQuery.fn.countrySwitcher = function() {
        var myelem = this;        
        var clone = $('.country-switcher').clone();
        var maxItem = parseInt($('.country-switcher .block-country:last-child').attr('rel'));
        
        var regEx = /[A-Za-z]/;
        var searchString= "";
        
        var rowBt = function () {
            $(myelem).addClass("viewed");
        }
        var rowLf = function () {
            $(window).unbind('keydown')
            $('.country-switcher .country-move').unbind('click');
            $(myelem).removeClass("viewed");
        }
        
        var removeHover = function() {
            $('.country-switcher .block-country').each(function() {
                $(this).removeClass('hover');
            });
        }
        
        var getHover = function () {
           if ($('.country-switcher .block-country.hover').length>0){
               return parseInt($('.country-switcher .block-country.hover').attr('rel'));
           } else {
               return -1;
           }
        }
        
        var moveTo = function(molt, evt, propagation) {
            if (typeof(evt)=="object" && !propagation) {
                evt.preventDefault();
                evt.stopPropagation();
            }
            if ((parseInt($('.country-switcher .country-list').css('top')) < 0 && molt==1) || 
                (($('.country-list').outerHeight() +parseInt($('.country-switcher .country-list').css('top')))> $('.country-all').outerHeight()) && molt==-1)  {
                var top = parseInt($('.country-switcher .country-list').css('top')) + $('.block-country').outerHeight() * molt;
                $('.country-switcher .country-list').css('top', top+'px');
            }            
        }
        
        clone.css({'visibility': 'hidden', 'display': 'block'});
        $('body').append(clone);
        var bWidth = clone.outerWidth();
        var bHeight = clone.find('.block-country').outerHeight();
        clone.remove();        
                
        var timer = null;
        $(this).bind("click", function(evt) {
            evt.stopPropagation();
            if ($('.country-switcher').css("display") == "none") {                
                var wHeight = $(window).height();
                var nr = Math.floor((wHeight - 24) / bHeight);
                if (nr <= 0) {nr = 2;}
                $('.country-switcher').css('left', $(this).position().left  + $(this).outerWidth() - bWidth);                
                $('.country-switcher').css('height', bHeight * nr);
                $('.country-switcher .country-all').css('height', bHeight * nr);
                $('.country-switcher .country-all').css('width', bWidth);
                $('.country-switcher .country-list').css({'position': 'absolute', 'top': '0px'});
                if ($('.country-switcher .block-country.hover').length==0) {
                    $('.country-switcher .block-country[rel="0"]').addClass('hover');
                }
                var movetimer=null;
                //Gestione delle frecce up & Down
                $('.country-move').bind('mousedown', function(evt) {
                    var molt = ($(this).attr('class').indexOf('up')<=-1)?-1:1;
                    moveTo(molt, evt);
                    if (movetimer == null) {
                    	movetimer = setInterval(function() {
                    		moveTo(molt, evt);
                    	}, 400);
                    }
                });
                $('.country-move').bind('mouseup', function(evt) {
                	if (movetimer != null){
                		clearInterval(movetimer);
                	}
                })
                
                //Gestione input da tastiera                                
                $(window).on('keydown', function(evt) {
                    switch(evt.keyCode) {
                        case 27:    //ESC
                            $('.country-switcher').fadeOut("fast", rowLf);
                            break;
                        case 38:    //UP
                        case 40:    //DOWN
                            var molt = (evt.keyCode == 38)?-1:1;
                            moveTo(molt, evt);
                            var pos = getHover();
                            if ((pos < maxItem && evt.keyCode == 38) || (pos > 0 && evt.keyCode == 40)) {
                                $('.country-switcher .block-country[rel="'+pos+'"]').removeClass('hover');
                                $('.country-switcher .block-country[rel="'+(pos-molt)+'"]').addClass('hover');
                            }
                            break;
                        case 13:    //ENTER
                            if (getHover()>=0) {
                                $('.block-country.hover').trigger('click');
                            }
                            break;
                        default:
                            var myChar = String.fromCharCode(evt.keyCode);
                            if (regEx.test(myChar)) {
                                if (myChar != searchString) {
                                    searchString+=myChar;
                                }
                                
                                if (timer!=null) {
                                    clearTimeout(timer);
                                }
                                timer= setTimeout(function(){
                                                searchString="";
                                                timer=null;
                                            },1000);
                                                                
                                var myStr = searchString;
                                var cur = getHover()+1;
                                for(var i=0; i<=maxItem; i++) {
                                    if (cur >= maxItem) {
                                        cur=0;
                                    } else {
                                        cur++;
                                    }
                                    if ($('.country-switcher .block-country[rel="'+cur+'"] a').html().replace(/[^A-Za-z]+ /g,"").toUpperCase().indexOf(myStr.toUpperCase())==0) {                                        
                                        $('.country-switcher .country-list').css({'top': '-'+($('.block-country').outerHeight()*cur)+'px'});
                                        removeHover();
                                        $('.country-switcher .block-country[rel="'+cur+'"]').addClass('hover');
                                        break;
                                    }
                                }
                            }
                            break;
                    }
                    evt.stopPropagation();
                });
                
                //Gestione movimento del mouse
                $('.country-switcher .block-country').on('mouseenter', function() {
                    removeHover();
                    $(this).addClass('hover');
                });
                
                $('.country-switcher').on('mouseleave', function() {
                    $('.country-switcher').fadeOut("fast", rowLf);
                })
                                
                $('.country-switcher').on('mousewheel', function(event, delta){
                    moveTo(delta,event);
                })
                
                //Visualizzo lo switcher
                $('.country-switcher').slideDown('fast', rowBt);
            } else {                
                $('.country-switcher').fadeOut("fast", rowLf);
            }
            return false;
        });
        
        $('.block-country').click(function(evt) {
            evt.preventDefault();
            evt.stopPropagation();
            var countryUrl = $(this).find('a').attr('href');
            $.ajax({
                url: countryUrl,
                beforeSend: function() {
                    $.fn.layer(true,{waiting: Translator.translate('waitingImage'), bindEsc:false, handle:"country-selector" });        
                },
                success: function(response) {
                    var data = $.parseJSON(response);
                    if (data.name != "") {
                        myelem.html(data.name);
                    }
                    if (data.store != "") {
                        if (data.rewrite == "") {
                            window.location = window.location.pathname;
                        } else {
                            window.location = data.rewrite;
                        }
                    } else { 
                        $.fn.layer(false,{exOnHide: function() {$('.country-switcher').fadeOut("fast", rowLf);}, handle:"country-selector"});
                    }
                    return;
                },
                error: function() {
                    $.fn.layer(false,{exOnHide: function() {$('.country-switcher').fadeOut("fast", rowLf);}, handle:"country-selector"});
                    return;
                }
            })
        })
    }
    
    /**
     * Visualizza Nasconde il loading o il pannello di elaborazione
     */
    $.fn.layer = function(show, params) {
        var opts = $.extend({}, {exOnHide: function(){}, zInd:20000, waiting: "", bindEsc:true, handle:null}, params);                                
        var myHandle="layer-block";
        if (opts.handle!=null) {
        	myHandle=opts.handle;
        }
        var hideEle= function() {
                        $("#"+myHandle).fadeOut(100);
                        $("#"+myHandle).remove();}
        if (show){
            if ($("#"+myHandle).length<=0) {
                $("body").append($("<DIV>", {id: myHandle}).addClass("popup-layer")
                                        .css({"position":"fixed", "z-index": opts.zInd,
                          "top":"0px", "left":"0px", "background-color":"black",
                          "width":"100%", "height":"110%", "opacity": 0.2})
                    );
            }
            if (opts.waiting!="") {
                $('#'+myHandle).append($("<img>", {src: opts.waiting}).css({position: "absolute", margin: "50% auto"}));
            }
            $('#'+myHandle).show();
            if (opts.bindEsc){
                $('#'+myHandle).on("click", function (){
                        opts.exOnHide();
                        hideEle();
                        return false;
                    })
                $(window).on('keydown', function(evt) {
                    if (evt.keyCode === 27) {
                        $.fn.layer(false, {exOnHide: opts.exOnHide, bindEsc: true, handle: opts.handle});
                    }                
                    evt.stopPropagation();
                });
            }

        } else {
            opts.exOnHide();
            $("#"+myHandle).fadeOut(100);
            $("#"+myHandle).remove();
            if (opts.bindEsc) {
                $(window).off('keydown');
            }
        }
    }
    
}) (jQuery, this);


/**
 * Manage Login Form
 *
 */
(function ($, window, undefined) {
    jQuery.fn.ajaxLogin = function() {
        var existsSelector=($(".menu-item-selector").length>0)?true:false;
        var t=$(this);
        var s=(existsSelector)?$(".menu-item-selector"):null;
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
            p.t.x=t.position().left;
            p.t.y=t.position().top;
            p.l.z=parseInt(l.css("z-index"))||20000;
            p.s.x=0;
            p.s.y=0;
            p.s.z=p.l.z+1;
            if (existsSelector) {
                p.s.x=p.t.x+t.outerWidth()/2;
                p.s.y=p.t.y+t.outerHeight()+p.o;
            }
            p.l.x=p.t.x+t.outerWidth()-l.outerWidth()/2;
            p.l.y=p.t.y+t.outerHeight()+p.o;
            if (existsSelector) p.l.y=p.l.y+s.outerHeight()-1;

            if (existsSelector) s.css({"display": "none",
                                       "position": "absolute",
                                       "top": p.s.y+"px",
                                       "left": p.s.x+"px",
                                       "z-index":p.s.z});
            l.css({"display": "none",
                   "position": "absolute",
                   "top": p.l.y+"px",
                   "left": p.l.x+"px"});
            hideLoginForm();
        }

        function showLoginForm() {
            if (existsSelector) s.css("display", "block");
            if ((t.offset().left+t.outerWidth()/2+l.outerWidth()/2)>$(window).width()) {
                p.l.x=p.t.x+t.outerWidth()+p.o-l.outerWidth();
                if (p.l.x<0) p.l.x=0;
            } else {p.l.x=p.t.x+t.outerWidth()-l.outerWidth()/2;}
            l.css({"display": "block", "left": p.l.x+"px", "zIndex":p.l.z});
            $("body").append($("<DIV>", {id: "layer-login"}).addClass("popup-login-layer").css({"position":"fixed", "z-index": p.l.z-1,
                                          "top":"0px", "left":"0px", "background-color":"black",
                                          "width":"100%", "height":"110%"}).bind("click", function (){
                                                                                hideLoginForm();
                                                                                return false;
                                                                            }).fadeTo(100,0.2)
                                                                        );
            t.unbind("click").bind("click", function() {
                hideLoginForm();
                return false;
            });

        }


        function hideLoginForm() {
            if (existsSelector) s.css("display", "none");
            l.css("display", "none");
            t.unbind("click").bind("click", function() {
                showLoginForm();
                return false;
            })
            if ($("#layer-login").length>0) $("#layer-login").remove();
        }

        init();
    }
}) (jQuery, this);

jQuery(document).ready(function(){
    //Ajax Login
    if (jQuery("#ajax-login").length>0 && jQuery(".popup-login-content").length>0) jQuery("#ajax-login").ajaxLogin();

    // shop by
    if (jQuery(".block-layered-nav dl dt span").length>0 && (!jQuery.browser.msie || parseInt(jQuery.browser.version, 10) > 7)){
        jQuery(".block-layered-nav dl dt").css("cursor", "pointer");
        jQuery(".block-layered-nav dl dt span").bind ("click", function(){
            if (jQuery(this).attr("class")=="") {
                jQuery(this).addClass("closed");
                jQuery(this).parent("dt").next("dd").slideUp("slow");
            } else {
                jQuery(this).removeClass("closed");
                jQuery(this).parent("dt").next("dd").slideDown("slow");
            }
        })
    }
    if (jQuery("#login-btn-continue").length>0) { //sono alla login del checkout
        jQuery("#login-btn-continue").bind("click", function() {
            if (jQuery('#co-billing-form').css('display')=='none') {
                checkout.setMethod();
                jQuery("#co-billing-form").slideDown("fast");
                jQuery(".login-btn-continue").css({"display":"none"});
            }
        })
    }

    if (jQuery(".fancy-agree").length>0) {
        jQuery(".fancy-agree").fancybox({
            autoScale   : true,
            titleShow   : false
        });
    }

	if (jQuery('#tabber').length>0)jQuery('#tabber').multiminitabs();
    // Timer
    if (jQuery('#time').length>0){
      jQuery('#time').jclock(
         {
          // utc: true,   mettere questa in caso di + 1 sull'ora .... (Emanuele Bittante)
		   utc: false,
           utcOffset: 2,
           format: '%B %d, %I:%M %p',
           //format: '%B %d, %Y %H:%M:%S',
           addTextBefore: "Rome -"
         });
    }

})


jQuery(window).load(function(){

    //Language Switcher
    if (jQuery(".form-language .selected").length>0) jQuery(".form-language .selected").headerSwitcher(".language-switcher");

    //currency Switcher
    if (jQuery(".block-currency .selected").length>0) jQuery(".block-currency .selected").headerSwitcher(".currency-switcher");
    
    //Country Switcher
    if (jQuery(".select-country.selected").length>0) jQuery(".select-country.selected").countrySwitcher();
    //Menu adapter
    if (jQuery("#nav").length>0) {

        jQuery("#nav>LI").each(function(){
            var W=0;
            var H=0;
            jQuery(this).children("UL").children("LI").each(function() {
                var innerW = jQuery(this).outerWidth(true)+5;
                var innerH = /*jQuery(this).outerHeight() +*/ jQuery(this).children("A").outerHeight();
                jQuery(this).children("UL").children("LI").each(function() {
                        innerH+=jQuery(this).outerHeight();
			var currentW = jQuery(this).outerWidth(true)+5;
			//alert("currentW: "+currentW+" innerW:"+innerW);
			if(currentW>innerW){
				innerW = currentW;
			}
                })
                if (innerH>H) H=innerH;
		W+=innerW;
            })

            if (W>jQuery(this).children("UL").outerWidth(true)){
              jQuery(this).children("UL").width(W)
            }else{
              jQuery(this).children("UL").width(jQuery(this).children("UL").width());
            }

            //Comment because for now there are only two levels in menu

            if (H>0){
              jQuery(this).children("UL").height(H);
            }

        })

    }


})
