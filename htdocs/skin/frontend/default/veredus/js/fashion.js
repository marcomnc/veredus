if (typeof(Fashion) == 'undefined') {
    var Fashion = {};
};

//******** configurable Class
Fashion.Configurable = Class.create();
Fashion.Configurable.prototype = {
   initialize: function (colorElement, sizeElement) {
       this.imgSize=null;
       this.imgLargeSize=null;
       this.imgGallerySize=null;
       this.elementColor = colorElement;
       this.elementSize = sizeElement;       
       this.firstColor = '';
       this.arrayGallery = {};
       this.sizeAttribute=null; //deprecated
       this.colorAttribute=null;       
       this.onlyColor=false;
       try {
           Event.observe(colorElement, 'click', this.changeColor.bind(this));
           Event.observe(sizeElement, 'click', this.changeSize.bind(this));
       } catch (err) {
           //Diamo per scontato che l'errore è sulla taglia'
           this.onlyColor=true;
       }
   },
   setUrl: function(url, imageUrl) {
        this.requestUrl = url;
        this.skinUrl = imageUrl;
   },
   setSizeAttribute : function (attribute) {
       //Deprecated
       this.sizeAttribute=attribute;
       this.onlyColor=false;
   },
   setColorAttribute : function (attribute) {
       this.colorAttribute=attribute;
   },   
   setCustomImgSize: function (imageSize, largeImageSize, galleryImmageSize) {
        this.imgSize=imageSize;
        this.imgLargeSize=largeImageSize;
        this.imgGallerySize=galleryImmageSize;
   },
   makeRequestUrl: function (productId) {
       var rqUrl = this.requestUrl + 'getmasterimage/productid/'+productId;
       rqUrl += (this.imgSize!=null)?'/is/'+this.imgSize:'';
       rqUrl += (this.imgLargeSize!=null)?'/ils/'+this.imgLargeSize:'';
       rqUrl += (this.imgGallerySize!=null)?'/igs/'+this.imgGallerySize:'';
       return rqUrl;
   },
   addProductConfig: function(spConfig) {
       this.productConfig = spConfig;
   },
   setColorByElement: function (element) {
       if (element.className=='color_switch'){
           $$('#'+this.elementColor+' img.color_switch').each(function(elem){
               Element.removeClassName(elem, 'color_selected')
           });     
           Element.addClassName(element, 'color_selected');
           this.setDDL(element, 0);
           this.setImmageMaster(element.attributes['attrib_value'].value);               
           if (!this.onlyColor) {this.viewSize()};
           if (element.up('a').readAttribute('pdescr')!=null && element.up('a').readAttribute('pdescr')!="" && typeof($('pdescr'))!='undefined') {
               $('pdescr').innerHTML = element.up('a').readAttribute('pdescr');
           } 
       }
   },           
   setFirstColor: function (){
       if (this.firstColor != '') {
           var color = this.firstColor;
           element = $(this.elementColor).getElementsBySelector('[attrib_value="'+color+'"]')[0];
           if (typeof(element)=='undefined') {
               element = $$('#'+this.elementColor+' img')[0];
           }
           //simulo il click x jqzoom
           if( document.createEvent ) {
               var e = document.createEvent('MouseEvents');
               e.initEvent( 'click', true, true );
               element.dispatchEvent(e);
           } else {
               var e = document.createEventObject('');
               element.fireEvent( 'onclick', e )
           }
                      
           this.setColorByElement(element);
       }
       $$('p.product-image')[0].setStyle({visibility: 'visible'});       
       $$('div.rcol').each( function (elem) {
           if (elem.getStyle('visibility')=='hidden' ) {elem.setStyle({visibility: 'visible'});}
       });
   },
   changeColor: function (event) {
       var element = Event.element(event);
       this.setColorByElement(element);        
   },
   setImmageMaster: function (prod) {      
       if (this.productConfig.settings[0].selectedIndex != 0 || typeof($('more-views-'+prod))!='undefined') {
           var productId = 0
           if (this.productConfig.settings[0].selectedIndex != 0) {
	       var index = this.productConfig.settings[0].selectedIndex-1;      
	       productId=this.productConfig.config.attributes[''+this.colorAttribute].options[index].id;
            } else {
               productId = prod; 
            }
            var loader = $$('div.zoomPreload')[0];
            if (typeof(loader)!='undefined') {$$('div.zoomPreload')[0].setStyle({visibility: 'visible'})};       

            $$('.more-views').each(function (elem) {
               if (elem===$('more-views')) elem.setStyle({visibility: 'hidden'});
               else elem.setStyle({display: 'none'});
            });
            $('more-views-'+productId).setStyle({top: ($('more-views').cumulativeOffset().top - $('product-img-box').cumulativeOffset().top)+'px',
                                                left: ($('more-views').cumulativeOffset().left- $('product-img-box').cumulativeOffset().left)+'px',
                                                position: 'absolute',
                                                display: 'block'});            
            $$('[rel="campaign"]').each(function(img){img.setStyle({display:'none'})});
            for (var ii=1;ii<3;ii++) {
                if (typeof($('view'+ii+'-'+productId))=='undefined'||$('view'+ii+'-'+productId)==null){
                    if (typeof($('imgDetail'+ii).down('img'))!='undefined'&&$('imgDetail'+ii).down('img')!=null){
                        $('imgDetail'+ii).down('img').setStyle({display: 'block'});
                    } 
                } else {
                    $('view'+ii+'-'+productId).setStyle({top: ($('imgDetail'+ii).cumulativeOffset().top-$('imgDetail'+ii).up('.product-essential').cumulativeOffset().top+1)+'px',
                                                         left: ($('imgDetail'+ii).cumulativeOffset().left-$('imgDetail'+ii).up('.product-essential').cumulativeOffset().left+1)+'px',
                                                         position: 'absolute',
                                                         display: 'block'});
                }
            }
            if (typeof(loader)!='undefined') $$('div.zoomPreload')[0].setStyle({visibility: 'hidden'});
       }
   },
   viewSize: function() {
        $$('#'+this.elementSize+' li').each(function(elem){
            Element.removeClassName(elem, 'enabled');
            Element.removeClassName(elem, 'mysize')
        });     
        if (!this.productConfig.settings[1].disabled) {
	        this.productConfig.settings[1].selectedIndex = 0;
	        for (var ii=1; ii< this.productConfig.settings[1].length; ii++){
	            var k = $(this.elementSize).getElementsBySelector('[attrib_value="'+this.productConfig.settings[1].options[ii].value+'"]').length
	            for (var xx=0;xx < k; xx++) {
	                Element.addClassName($(this.elementSize).getElementsBySelector('[attrib_value="'+this.productConfig.settings[1].options[ii].value+'"]')[xx], 'enabled');
	            }
	        }                
        }
   },
   setDDL: function(element, DDLRif) {
      if (typeof(this.productConfig)!='undefined') {
            for (var ii=0; ii<=this.productConfig.settings[DDLRif].length; ii++){
                try {
                if (this.productConfig.settings[DDLRif].options[ii].value == element.attributes['attrib_value'].value) {
                    this.productConfig.settings[DDLRif].selectedIndex = ii;
                    this.productConfig.configureElement(this.productConfig.settings[DDLRif]);                                               
                    break;
                }
                } catch (err) {
                    this.productConfig.settings[DDLRif].selectedIndex = 0;
                    this.productConfig.configureElement(this.productConfig.settings[DDLRif]);                                               
		    break;
                }
            }
       }
   },
   changeSize: function (event) {               
        var element = Event.element(event);
        var lielement = Event.element(event);
        while (lielement.nodeName != 'LI') {
            lielement = lielement.parentNode;
        }
        if (lielement.className == 'enabled') {
            $$('#'+this.elementSize+' li').each(function(elem){
                Element.removeClassName(elem, 'mysize')
            });     
            Element.addClassName(lielement, 'mysize');
            this.setDDL(lielement, 1);
        }
   }
}

//*********** Selectable Product
Fashion.ColorSelect = Class.create();
Fashion.ColorSelect.prototype = {
   initialize: function (config, productId, productUrl) {
       this.configure=config.evalJSON();                          
       this.id=productId;
       this.url=productUrl;
       this.closeViewer();
       var i=0, h=0;
       var strHtml='';
       //this.closeViewer();
       for (var obj in this.configure) {                                                   
           if (i++==0||i%4==0) {
               strHtml+='<div>';h+=25;
           }
           strHtml+='<img title=\"'+this.configure[obj].value+'\" class=\"color\"';
           if (this.configure[obj].color_hex != 'null' && this.configure[obj].color_hex != '') {
                strHtml+='style=\"background-color:'+this.configure[obj].color_hex+';\"'; 
           }
           strHtml+='src=\"'+this.configure[obj].thumbnail_name+'\" height=\"17\" width=\"20\"';
           strHtml+='id=\"id-color-product-viewer-'+this.id+'\"';
           strHtml+='value=\"'+this.configure[obj].option_id+'\"/>';

           if (i%4==0) strHtml+='</div>';                               

       }
       if (i%4!=0) strHtml+='</div>';                               
       h+=20;       
       $('color-product-'+this.id).style.height= h+'Px';
       $('color-viewer-container-'+this.id).innerHTML=strHtml;
       $('color-viewer-container-'+this.id).style.height=h-20+'Px';
       $('close-color-viewer-'+this.id).observe('click', this.closeViewer.bind(this));
       $('color-product-'+this.id).observe('mouseout', this.mouseOut.bind(this));
       $('color-product-'+this.id).observe('mouseover', this.mouseIn.bind(this));
       if ($('color-product-'+this.id+'-viewer')!=null){
           $('color-product-'+this.id+'-viewer').observe('mousemove', this.showViewer.bind(this));
           $('color-product-'+this.id+'-viewer').observe('mouseout', this.mouseOut.bind(this));
           $('color-product-'+this.id+'-viewer').observe('mouseover', this.mouseIn.bind(this));
       }
       $('id-color-product-viewer-'+this.id).observe('click', this.submitProduct.bind(this));
   },
   submitProduct: function (event) {
       setLocation(this.url+'?select_color'+event.target.attributes['value'].value);
   },
   mouseIn: function(event) {
       if (this.timer=='null') return
       clearTimeout(this.timer);
       this.timer = null;
   },                       
   mouseOut: function(event) {
       var idname=$('color-product-'+this.id);
       this.timer=setTimeout(function() {$(idname).hide();}, 1000);
   },
   closeViewer: function(event) {
       $('color-product-'+this.id).hide();
   },
   showViewer: function(event) {
       $('color-product-'+this.id).show();
   }
}

//****** Tool Tip sul Prodotto ******* /

Fashion.ToolTip = Class.create();
Fashion.ToolTip.prototype = {
    initialize: function (config) { 
        this.tOut = null;        
        this.actRef = null
        this.sOn = this.setOn.bind(this);
        this.ToolTipId = new Array();
        for (var id=0; id <config.length; id++){
            $('element-'+config[id]).observe('mouseout', this.toolTipOff.bind(this));
            $('element-'+config[id]).observe('mousemove', this.toolTipMove.bind(this));
            $('element-'+config[id]).observe('mouseover', this.toolTipOn.bind(this));
            this.ToolTipId['element-'+config[id]] = 'tooltip-'+config[id];
        }        
    },
    toolTipOn: function (event) {    
        this.actRef = this.ToolTipId[event.target.id];
        this.tOut = setTimeout(this.sOn, 1000);        
    },
    toolTipMove: function(event) {        
        var x=event.clientX;
        var y=event.clientY ;                 
        $(this.ToolTipId[event.target.id]).setStyle({top:y+10+'px'});
        $(this.ToolTipId[event.target.id]).setStyle({left:x+10+'px'});
    },
    toolTipOff: function(event) {
        $(this.ToolTipId[event.target.id]).setStyle({display:'none'});
        clearTimeout (this.tOut);
    },
    setOn: function () {
        $(this.actRef).setStyle({display:'block'});
        clearTimeout (this.tOut);
    }
}

/** per il nuovo layput di fermani cè un comporatamento diverso della taglia **/
if (typeof(aeffe2012) == 'undefined') {
    var aeffe2012 = {};
};
aeffe2012.Configurable = Class.create();
//aeffe2012.Configurable.prototype = new Fashion.Configurable();
//aeffe2012.Configurable.prototype.changeColor = function (event) {
//    var element = Event.element(event);
//    var lielement = Event.element(event);
//    while (lielement.nodeName != 'LI') {
//        lielement = lielement.parentNode;
//    }
//    if (lielement.className == 'enabled') {
//        $$('#'+this.elementSize+' li').each(function(elem){
//            Element.removeClassName(elem, 'mysize')
//        });     
//        Element.addClassName(lielement, 'mysize');
//        this.setDDL(lielement, 1);
//        $('selectedsize').value = lielement.innerText;
//    }
//}
aeffe2012.Configurable.prototype = Object.extend(new Fashion.Configurable(), {
    changeSize : function (event) {
        var element = Event.element(event);
        var lielement = Event.element(event);
        while (lielement.nodeName != "LI") {
            lielement = lielement.parentNode;
        }        
        if (lielement.className == 'enabled') {            
            $$('#'+this.elementSize+' li').each(function(elem){
                Element.removeClassName(elem, 'mysize')
            });     
            Element.addClassName(lielement, 'mysize');
            this.setDDL(lielement, 1);            
            $('selectedsize').value = lielement.getInnerText();
        }   
    }
})
