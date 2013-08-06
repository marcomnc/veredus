// Garden Gnome Software - Skin
// Object2VR 2.0.1/1917
// Filename: controller_object_320singolo.ggsk
// Generated mar mar 13 20:41:21 2012

function object2vrSkin(player,base) {
	var me=this;
	var flag=false;
	this.player=player;
	this.player.skinObj=this;
	this.divSkin=player.divSkin;
	var basePath="";
	// auto detect base path
	if (base=='?') {
		var scripts = document.getElementsByTagName('script');
		for(var i=0;i<scripts.length;i++) {
			var src=scripts[i].src;
			if (src.indexOf('skin.js')>=0) {
				var p=src.lastIndexOf('/');
				if (p>=0) {
					basePath=src.substr(0,p+1);
				}
			}
		}
	} else
	if (base) {
		basePath=base;
	}
	this.elementMouseDown=new Array();
	this.elementMouseOver=new Array();
	var cssPrefix='';
	var domTransition='transition';
	var domTransform='transform';
	var prefixes='Webkit,Moz,O,ms,Ms'.split(',');
	var i;
	for(i=0;i<prefixes.length;i++) {
		if (typeof document.body.style[prefixes[i] + 'Transform'] !== 'undefined') {
			cssPrefix='-' + prefixes[i].toLowerCase() + '-';
			domTransition=prefixes[i] + 'Transition';
			domTransform=prefixes[i] + 'Transform';
		}
	}
	
	this.player.setMargins(0,0,0,0);
	
	this.updateSize=function(startElement) {
		var stack=new Array();
		stack.push(startElement);
		while(stack.length>0) {
			e=stack.pop();
			if (e.ggUpdatePosition) {
				e.ggUpdatePosition();
			}
			if (e.hasChildNodes()) {
				for(i=0;i<e.childNodes.length;i++) {
					stack.push(e.childNodes[i]);
				}
			}
		}
	}
	
	parameterToTransform=function(p) {
		return 'translate(' + p.rx + 'px,' + p.ry + 'px) rotate(' + p.a + 'deg) scale(' + p.sx + ',' + p.sy + ')';
	}
	
	this.findElements=function(id,regex) {
		var r=new Array();
		var stack=new Array();
		var pat=new RegExp(id,'');
		stack.push(me.divSkin);
		while(stack.length>0) {
			e=stack.pop();
			if (regex) {
				if (pat.test(e.ggId)) r.push(e);
			} else {
				if (e.ggId==id) r.push(e);
			}
			if (e.hasChildNodes()) {
				for(i=0;i<e.childNodes.length;i++) {
					stack.push(e.childNodes[i]);
				}
			}
		}
		return r;
	}
	
	this.preloadImages=function() {
		var preLoadImg=new Image();
		preLoadImg.src=basePath + 'images/left__o.png';
		preLoadImg.src=basePath + 'images/right__o.png';
		preLoadImg.src=basePath + 'images/zoom_in__o.png';
		preLoadImg.src=basePath + 'images/zoom_out__o.png';
		preLoadImg.src=basePath + 'images/auto_rotate__o.png';
	}
	
	this.addSkin=function() {
		this._loading_image=document.createElement('div');
		this._loading_image.ggId='loading image'
		this._loading_image.ggParameter={ rx:0,ry:0,a:0,sx:1,sy:1 };
		this._loading_image.ggVisible=true;
		this._loading_image.ggUpdatePosition=function() {
			this.style[domTransition]='none';
			if (this.parentNode) {
				w=this.parentNode.offsetWidth;
				this.style.left=(-112 + w/2) + 'px';
				h=this.parentNode.offsetHeight;
				this.style.top=(-32 + h/2) + 'px';
			}
		}
		hs ='position:absolute;';
		hs+='left: -112px;';
		hs+='top:  -32px;';
		hs+='width: 224px;';
		hs+='height: 64px;';
		hs+=cssPrefix + 'transform-origin: 50% 50%;';
		hs+='visibility: inherit;';
		this._loading_image.setAttribute('style',hs);
		this._loading_image__img=document.createElement('img');
		this._loading_image__img.setAttribute('src',basePath + 'images/loading_image.png');
		this._loading_image__img.setAttribute('style','position: absolute;top: 0px;left: 0px;');
		me.player.checkLoaded.push(this._loading_image__img);
		this._loading_image.appendChild(this._loading_image__img);
		this._loading_text=document.createElement('div');
		this._loading_text.ggId='loading text'
		this._loading_text.ggParameter={ rx:0,ry:0,a:0,sx:1,sy:1 };
		this._loading_text.ggVisible=true;
		hs ='position:absolute;';
		hs+='left: 12px;';
		hs+='top:  14px;';
		hs+='width: 198px;';
		hs+='height: 20px;';
		hs+=cssPrefix + 'transform-origin: 50% 50%;';
		hs+='visibility: inherit;';
		hs+='border: 0px solid #000000;';
		hs+='color: #000000;';
		hs+='text-align: left;';
		hs+='white-space: nowrap;';
		hs+='padding: 0px 1px 0px 1px;'
		hs+='overflow: hidden;';
		this._loading_text.setAttribute('style',hs);
		this._loading_text.ggUpdateText=function() {
			this.innerHTML="<b>Loading... "+(me.player.getPercentLoaded()*100.0).toFixed(0)+"%<\/b>";
		}
		this._loading_text.ggUpdateText();
		this._loading_image.appendChild(this._loading_text);
		this._loading_bar=document.createElement('div');
		this._loading_bar.ggId='loading bar'
		this._loading_bar.ggParameter={ rx:0,ry:0,a:0,sx:1,sy:1 };
		this._loading_bar.ggVisible=true;
		hs ='position:absolute;';
		hs+='left: 11px;';
		hs+='top:  39px;';
		hs+='width: 198px;';
		hs+='height: 10px;';
		hs+=cssPrefix + 'transform-origin: 0% 50%;';
		hs+='visibility: inherit;';
		hs+='border: 2px solid #000000;';
		hs+='background-color: #4f4f4f;';
		this._loading_bar.setAttribute('style',hs);
		this._loading_image.appendChild(this._loading_bar);
		this._loading_close=document.createElement('div');
		this._loading_close.ggId='loading close'
		this._loading_close.ggParameter={ rx:0,ry:0,a:0,sx:1,sy:1 };
		this._loading_close.ggVisible=true;
		hs ='position:absolute;';
		hs+='left: 200px;';
		hs+='top:  1px;';
		hs+='width: 24px;';
		hs+='height: 24px;';
		hs+=cssPrefix + 'transform-origin: 50% 50%;';
		hs+='visibility: inherit;';
		this._loading_close.setAttribute('style',hs);
		this._loading_close__img=document.createElement('img');
		this._loading_close__img.setAttribute('src',basePath + 'images/loading_close.png');
		this._loading_close__img.setAttribute('style','position: absolute;top: 0px;left: 0px;');
		me.player.checkLoaded.push(this._loading_close__img);
		this._loading_close.appendChild(this._loading_close__img);
		this._loading_close.onclick=function () {
			me._loading_image.style[domTransition]='none';
			me._loading_image.style.visibility='hidden';
			me._loading_image.ggVisible=false;
		}
		this._loading_image.appendChild(this._loading_close);
		this.divSkin.appendChild(this._loading_image);
		this._toolbar=document.createElement('div');
		this._toolbar.ggId='toolbar'
		this._toolbar.ggParameter={ rx:0,ry:0,a:0,sx:0.5,sy:0.5 };
		this._toolbar.ggVisible=true;
		this._toolbar.ggUpdatePosition=function() {
			this.style[domTransition]='none';
			if (this.parentNode) {
				w=this.parentNode.offsetWidth;
				this.style.left=(-150 + w/2) + 'px';
				h=this.parentNode.offsetHeight;
				this.style.top=(-53 + h) + 'px';
			}
		}
		hs ='position:absolute;';
		hs+='left: -150px;';
		hs+='top:  -53px;';
		hs+='width: 300px;';
		hs+='height: 50px;';
		hs+=cssPrefix + 'transform-origin: 50% 100%;';
		hs+=cssPrefix + 'transform: ' + parameterToTransform(this._toolbar.ggParameter) + ';';
		hs+='opacity: 0.5;';
		hs+='visibility: inherit;';
		hs+='border: 0px solid #000000;';
		hs+='background-image:url(' + basePath + 'images/alpha_background_000000_1.png);';
		this._toolbar.setAttribute('style',hs);
		this._toolbar.onmouseover=function () {
			if (me.player.transitionsDisabled) {
				me._toolbar.style[domTransition]='none';
			} else {
				me._toolbar.style[domTransition]='all 500ms ease-out 0ms';
			}
			me._toolbar.ggParameter.sx=1;me._toolbar.ggParameter.sy=1;
			me._toolbar.style[domTransform]=parameterToTransform(me._toolbar.ggParameter);
			if (me.player.transitionsDisabled) {
				me._toolbar.style[domTransition]='none';
			} else {
				me._toolbar.style[domTransition]='all 500ms ease-out 0ms';
			}
			me._toolbar.style.opacity='1';
			me._toolbar.style.visibility=me._toolbar.ggVisible?'inherit':'hidden';
		}
		this._toolbar.onmouseout=function () {
			if (me.player.transitionsDisabled) {
				me._toolbar.style[domTransition]='none';
			} else {
				me._toolbar.style[domTransition]='all 500ms ease-out 0ms';
			}
			me._toolbar.ggParameter.sx=0.5;me._toolbar.ggParameter.sy=0.5;
			me._toolbar.style[domTransform]=parameterToTransform(me._toolbar.ggParameter);
			if (me.player.transitionsDisabled) {
				me._toolbar.style[domTransition]='none';
			} else {
				me._toolbar.style[domTransition]='all 500ms ease-out 0ms';
			}
			me._toolbar.style.opacity='0.5';
			me._toolbar.style.visibility=me._toolbar.ggVisible?'inherit':'hidden';
		}
		this._left=document.createElement('div');
		this._left.ggId='left'
		this._left.ggParameter={ rx:0,ry:0,a:0,sx:1.3,sy:1.3 };
		this._left.ggVisible=true;
		hs ='position:absolute;';
		hs+='left: 37px;';
		hs+='top:  0px;';
		hs+='width: 32px;';
		hs+='height: 32px;';
		hs+=cssPrefix + 'transform-origin: 50% 50%;';
		hs+=cssPrefix + 'transform: ' + parameterToTransform(this._left.ggParameter) + ';';
		hs+='visibility: inherit;';
		hs+='cursor: pointer;';
		this._left.setAttribute('style',hs);
		this._left__img=document.createElement('img');
		this._left__img.setAttribute('src',basePath + 'images/left.png');
		this._left__img.setAttribute('style','position: absolute;top: 0px;left: 0px;');
		me.player.checkLoaded.push(this._left__img);
		this._left.appendChild(this._left__img);
		this._left.onmouseover=function () {
			me._left__img.src=basePath + 'images/left__o.png';
		}
		this._left.onmouseout=function () {
			me._left__img.src=basePath + 'images/left.png';
			me.elementMouseDown['left']=false;
		}
		this._left.onmousedown=function () {
			me.elementMouseDown['left']=true;
		}
		this._left.onmouseup=function () {
			me.elementMouseDown['left']=false;
		}
		this._left.ontouchend=function () {
			me.elementMouseDown['left']=false;
		}
		this._toolbar.appendChild(this._left);
		this._right=document.createElement('div');
		this._right.ggId='right'
		this._right.ggParameter={ rx:0,ry:0,a:0,sx:1.3,sy:1.3 };
		this._right.ggVisible=true;
		hs ='position:absolute;';
		hs+='left: 78px;';
		hs+='top:  0px;';
		hs+='width: 32px;';
		hs+='height: 32px;';
		hs+=cssPrefix + 'transform-origin: 50% 50%;';
		hs+=cssPrefix + 'transform: ' + parameterToTransform(this._right.ggParameter) + ';';
		hs+='visibility: inherit;';
		hs+='cursor: pointer;';
		this._right.setAttribute('style',hs);
		this._right__img=document.createElement('img');
		this._right__img.setAttribute('src',basePath + 'images/right.png');
		this._right__img.setAttribute('style','position: absolute;top: 0px;left: 0px;');
		me.player.checkLoaded.push(this._right__img);
		this._right.appendChild(this._right__img);
		this._right.onmouseover=function () {
			me._right__img.src=basePath + 'images/right__o.png';
		}
		this._right.onmouseout=function () {
			me._right__img.src=basePath + 'images/right.png';
			me.elementMouseDown['right']=false;
		}
		this._right.onmousedown=function () {
			me.elementMouseDown['right']=true;
		}
		this._right.onmouseup=function () {
			me.elementMouseDown['right']=false;
		}
		this._right.ontouchend=function () {
			me.elementMouseDown['right']=false;
		}
		this._toolbar.appendChild(this._right);
		this._zoom_in=document.createElement('div');
		this._zoom_in.ggId='zoom in'
		this._zoom_in.ggParameter={ rx:0,ry:0,a:0,sx:1.3,sy:1.3 };
		this._zoom_in.ggVisible=true;
		hs ='position:absolute;';
		hs+='left: 132px;';
		hs+='top:  0px;';
		hs+='width: 32px;';
		hs+='height: 32px;';
		hs+=cssPrefix + 'transform-origin: 50% 50%;';
		hs+=cssPrefix + 'transform: ' + parameterToTransform(this._zoom_in.ggParameter) + ';';
		hs+='visibility: inherit;';
		hs+='cursor: pointer;';
		this._zoom_in.setAttribute('style',hs);
		this._zoom_in__img=document.createElement('img');
		this._zoom_in__img.setAttribute('src',basePath + 'images/zoom_in.png');
		this._zoom_in__img.setAttribute('style','position: absolute;top: 0px;left: 0px;');
		me.player.checkLoaded.push(this._zoom_in__img);
		this._zoom_in.appendChild(this._zoom_in__img);
		this._zoom_in.onmouseover=function () {
			me._zoom_in__img.src=basePath + 'images/zoom_in__o.png';
		}
		this._zoom_in.onmouseout=function () {
			me._zoom_in__img.src=basePath + 'images/zoom_in.png';
			me.elementMouseDown['zoom_in']=false;
		}
		this._zoom_in.onmousedown=function () {
			me.elementMouseDown['zoom_in']=true;
		}
		this._zoom_in.onmouseup=function () {
			me.elementMouseDown['zoom_in']=false;
		}
		this._zoom_in.ontouchend=function () {
			me.elementMouseDown['zoom_in']=false;
		}
		this._toolbar.appendChild(this._zoom_in);
		this._zoom_out=document.createElement('div');
		this._zoom_out.ggId='zoom out'
		this._zoom_out.ggParameter={ rx:0,ry:0,a:0,sx:1.3,sy:1.3 };
		this._zoom_out.ggVisible=true;
		hs ='position:absolute;';
		hs+='left: 174px;';
		hs+='top:  0px;';
		hs+='width: 32px;';
		hs+='height: 32px;';
		hs+=cssPrefix + 'transform-origin: 50% 50%;';
		hs+=cssPrefix + 'transform: ' + parameterToTransform(this._zoom_out.ggParameter) + ';';
		hs+='visibility: inherit;';
		hs+='cursor: pointer;';
		this._zoom_out.setAttribute('style',hs);
		this._zoom_out__img=document.createElement('img');
		this._zoom_out__img.setAttribute('src',basePath + 'images/zoom_out.png');
		this._zoom_out__img.setAttribute('style','position: absolute;top: 0px;left: 0px;');
		me.player.checkLoaded.push(this._zoom_out__img);
		this._zoom_out.appendChild(this._zoom_out__img);
		this._zoom_out.onmouseover=function () {
			me._zoom_out__img.src=basePath + 'images/zoom_out__o.png';
		}
		this._zoom_out.onmouseout=function () {
			me._zoom_out__img.src=basePath + 'images/zoom_out.png';
			me.elementMouseDown['zoom_out']=false;
		}
		this._zoom_out.onmousedown=function () {
			me.elementMouseDown['zoom_out']=true;
		}
		this._zoom_out.onmouseup=function () {
			me.elementMouseDown['zoom_out']=false;
		}
		this._zoom_out.ontouchend=function () {
			me.elementMouseDown['zoom_out']=false;
		}
		this._toolbar.appendChild(this._zoom_out);
		this._auto_rotate=document.createElement('div');
		this._auto_rotate.ggId='auto rotate'
		this._auto_rotate.ggParameter={ rx:0,ry:0,a:0,sx:1.3,sy:1.3 };
		this._auto_rotate.ggVisible=true;
		hs ='position:absolute;';
		hs+='left: 230px;';
		hs+='top:  0px;';
		hs+='width: 32px;';
		hs+='height: 32px;';
		hs+=cssPrefix + 'transform-origin: 50% 50%;';
		hs+=cssPrefix + 'transform: ' + parameterToTransform(this._auto_rotate.ggParameter) + ';';
		hs+='visibility: inherit;';
		hs+='cursor: pointer;';
		this._auto_rotate.setAttribute('style',hs);
		this._auto_rotate__img=document.createElement('img');
		this._auto_rotate__img.setAttribute('src',basePath + 'images/auto_rotate.png');
		this._auto_rotate__img.setAttribute('style','position: absolute;top: 0px;left: 0px;');
		me.player.checkLoaded.push(this._auto_rotate__img);
		this._auto_rotate.appendChild(this._auto_rotate__img);
		this._auto_rotate.onclick=function () {
			me.player.startAutorotate("0.10");
		}
		this._auto_rotate.onmouseover=function () {
			me._auto_rotate__img.src=basePath + 'images/auto_rotate__o.png';
		}
		this._auto_rotate.onmouseout=function () {
			me._auto_rotate__img.src=basePath + 'images/auto_rotate.png';
		}
		this._toolbar.appendChild(this._auto_rotate);
		this.divSkin.appendChild(this._toolbar);
		this.preloadImages();
		this.divSkin.ggUpdateSize=function(w,h) {
			me.updateSize(me.divSkin);
		}
		this.divSkin.ggViewerInit=function() {
		}
		this.divSkin.ggLoaded=function() {
			me._loading_image.style[domTransition]='none';
			me._loading_image.style.visibility='hidden';
			me._loading_image.ggVisible=false;
		}
		this.divSkin.ggReLoaded=function() {
		}
		this.divSkin.ggEnterFullscreen=function() {
		}
		this.divSkin.ggExitFullscreen=function() {
		}
		this.skinTimerEvent();
	};
	this.hotspotProxyClick=function(id) {
	}
	this.hotspotProxyOver=function(id) {
	}
	this.hotspotProxyOut=function(id) {
	}
	this.skinTimerEvent=function() {
		setTimeout(function() { me.skinTimerEvent(); }, 10);
		this._loading_text.ggUpdateText();
		var hs='';
		if (me._loading_bar.ggParameter) {
			hs+=parameterToTransform(me._loading_bar.ggParameter) + ' ';
		}
		hs+='scale(' + (1 * me.player.getPercentLoaded() + 0) + ',1.0) ';
		me._loading_bar.style[domTransform]=hs;
		if (me.elementMouseDown['left']) {
			me.player.changePan(0.1,true);
		}
		if (me.elementMouseDown['right']) {
			me.player.changePan(-0.1,true);
		}
		if (me.elementMouseDown['zoom_in']) {
			me.player.changeFovLog(-0.5,true);
		}
		if (me.elementMouseDown['zoom_out']) {
			me.player.changeFovLog(0.5,true);
		}
	};
	this.addSkin();
};