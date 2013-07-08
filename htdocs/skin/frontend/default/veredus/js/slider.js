(function ($, window, undefined) {  
    jQuery.fn.mySlider =  function(items,options) {
        
        var opts=$.extend({}, $.fn.mySlider.defaults, options);        
        var rel={};
        if (typeof($(this).attr(rel))!="undefined") {
            rel=$(this).attr(rel);
        }
        var DOM_s=$(this);
        var DOM_ul=$(this).find("ul");
        var DOM_sc=null;
        var DOM_bl=null;
        var DOM_br=null;
        var DOM_nc=null;
        var w_li = null;
        var h_li = null;
        var c_li = 1;
        var is_playing=false;
        var focus_flag=true; //deprecated
        var is_pressed=0;
        var stop_auto=false;
        var completed=function() {
            is_playing=false;
        }
        var noJSON =($.browser.msie && $.browser.version <= 7);
        
        function createButton() {
            var myDom=null;
            if (opts.arrowPos.toUpperCase()=="INNER") {
                myDom=DOM_sc;
            } else {
                myDom=DOM_s;
            }
            myDom.append("<div id=\"right_but\" class=\"box-right slider-btn\"></div>");
            myDom.append("<div id=\"left_but\" class=\"box-left slider-btn\"></div>");
            DOM_br=DOM_s.find("#right_but");
            DOM_bl=DOM_s.find("#left_but");
            var w=DOM_br.outerWidth();
            if (w<DOM_bl.outerWidth()) w=DOM_bl.outerWidth();
            var h=DOM_br.outerHeight();
            if (h<DOM_bl.outerHeight()) h=DOM_br.outerHeight();
            var p_h=DOM_ul.outerHeight(true)/2-h/2;
            var z=parseInt(DOM_s.css("z-index"))||0;
            myDom.find(".slider-btn").css({"cursor":"pointer", "position":"absolute", "z-index": z+1, "top": p_h+"px"});
            if (opts.arrowPos.toUpperCase()=="INNER") {
                DOM_br.css("right", "20px");
                DOM_bl.css("left", "20px");
            } else {
                DOM_br.css("right", -w+"px");
                DOM_bl.css("left", -w+"px");
                DOM_s.css({"margin-left":w+"px","margin-right":w+"px"})
                
            }
            
        }
        
        function setButtonBinding() {
            if (DOM_br!=null) {
                DOM_br.bind("click", function(){
                    if (!is_playing) {
                        is_playing=true;
                        if (opts.to.toUpperCase()=="LEFT") {
                            toLeft();
                        } else {
                            toRight();
                        } 
                        stop_auto=true;
                    }
                });
            }
            if (DOM_bl!=null){
                DOM_bl.bind("click", function(){
                    if (!is_playing){
                        is_playing=true;
                        if (opts.to.toUpperCase()=="LEFT") {
                            toRight();  
                        } else {
                            toLeft();
                        } 
                        stop_auto=true;
                    }
                })
            }
        }
        
        function toLeft() {
            DOM_ul.find("LI:first-child").animate({"margin-left":(-1*w_li)+"px"},700, function(){
                DOM_ul.find("LI:first-child").css({"display":"none"});
                DOM_ul.find("LI:last-child").after(DOM_ul.find("LI:first-child"));
                DOM_ul.find("LI:last-child").css({"margin-left": "0px"});
                DOM_ul.find("LI:last-child").css({"display":"block"});
                is_playing=false;
                setNavContainer();
            });                   
        }
        
        function toRight() {
            DOM_ul.find("LI:last-child").css({"display":"none", "margin-left":(-1*w_li)+"px"});
            DOM_ul.find("LI:first-child").before(DOM_ul.find("LI:last-child"));
            DOM_ul.find("LI:first-child").css({"display":"block"});
            DOM_ul.find("LI:first-child").animate({"margin-left":"0px"}, opts.timeEffects, completed);            
            setNavContainer();
        }
        
        function autoScroll() {
            if (!stop_auto) {
                if(is_pressed==0 && focus_flag){
                    if (opts.to.toUpperCase()=="LEFT") {
                        toLeft();
                    } else {
                        toRight();
                    }                
                    //setTimeout(autoScroll, opts.timeOut);
                } else {
                    if(is_pressed==2) {
                        is_pressed=0;
                        //setTimeout(autoScroll, opts.timeOut);
                    }
                }                
            }
            setTimeout(autoScroll, opts.timeOut);
        }
        
        function init() {            
            DOM_ul.find("li").css({"float":"left"});            
            DOM_s.css({"position":"relative", "margin":"0", "padding":"0"});            
            if (opts.width>0){
                DOM_s.css({"position":"relative", "margin":"0", "padding":"0", "width": opts.width+"px"});            
            }
            w_li = DOM_ul.find("li").outerWidth(true);
            h_li = DOM_ul.find("li").outerHeight(true);
            c_li = DOM_ul.find("li").length;        
            DOM_ul.css({"width": w_li*(c_li+1)+"px"});
            DOM_ul.parent("div").append("<div class=\"slider-container\"></div>");
            DOM_sc=DOM_s.find(".slider-container");
            var t="";
            if (typeof(rel.title)!="undefined") t=rel.title;
            if (opts.title!="") t=opts.title;
            DOM_sc.append("<p class=\"slider-title\">"+t+"</p>");
            DOM_s.find(".slider-container p:last-child").after(DOM_ul);
            DOM_sc.css({"overflow":"hidden", "position":"relative", "width": (w_li*items)+"px"});            
            if (opts.arrowPos.toUpperCase()!="NONE" && c_li>items) {
                createButton();
                setButtonBinding();
            }
            if (opts.autoScroll && opts.timeOut>0 && c_li>items) {
                setTimeout(autoScroll, opts.timeOut);
                $(window).blur(function(){
                    is_pressed = 1;
                }).focus(function(){
                    is_pressed = 2;
                });
                DOM_ul.find("li").each(function(){
                    $(this).bind('mouseover', function(){
                        is_pressed = 1;
                        focus_flag = true;
                    });
                });
                DOM_sc.bind("mouseleave", function(){
                    is_pressed = 2;
                    focus_flag = true;
                    stop_auto=false;
                })
            }
            if (opts.controlNav && c_li>items && !noJSON) {
                DOM_sc.append($('<div class="slider-controlNav"></div>'));
                DOM_nc=DOM_sc.find(".slider-controlNav");
                DOM_ul.find("li").each(function(idx) {
                    var liRel=$.extend({}, getElemtRel($(this)), {"iIndex":idx});
                    $(this).attr("rel",JSON.stringify(liRel));
                    DOM_nc.append('<a rel="'+idx+'" class="slider-control">'+(idx+1)+'</a>');
                });
                setNavContainer();
            }
        }
        
        function getElemtRel(elem) {
            var myRel={};
            if (typeof($(elem).attr("rel"))!="undefined") {
               myRel=$(elem).attr("rel");
            }
            return myRel;
        }
        
        function setNavContainer() {
            if (DOM_nc!=null && typeof(DOM_ul.find("LI:first-child").attr("rel"))!="undefined"){
                var myRel=$.parseJSON(DOM_ul.find("LI:first-child").attr("rel"));
                if ("iIndex" in myRel){
                    DOM_nc.find("a").removeClass("selected");
                    $('a[rel="'+myRel.iIndex+'"]').addClass("selected");
                }
            }
        }
        
        return init();
        
    }
    
    $.fn.mySlider.defaults = {
        autoScroll: true,
        timeOut: 7000,
        timeEffetcs: 700,
        arrowPos: "outer", // inner outer none
        title:"",
        width: 0,
        to: "left", // left/right
        controlNav: true
    };
})(jQuery, this);

