/*!
 * A JavaScript implementation of the RSA Data Security, Inc. MD5 Message
 * Digest Algorithm, as defined in RFC 1321.
 * See http://pajhome.org.uk/crypt/md5 for more info.
 */
var hexcase=0;var b64pad="";var chrsz=8;function hex_md5(s){return binl2hex(core_md5(str2binl(s),s.length*chrsz));}function b64_md5(s){return binl2b64(core_md5(str2binl(s),s.length*chrsz));}function str_md5(s){return binl2str(core_md5(str2binl(s),s.length*chrsz));}function hex_hmac_md5(key,data){return binl2hex(core_hmac_md5(key,data));}function b64_hmac_md5(key,data){return binl2b64(core_hmac_md5(key,data));}function str_hmac_md5(key,data){return binl2str(core_hmac_md5(key,data));}function md5_vm_test(){return hex_md5("abc")=="900150983cd24fb0d6963f7d28e17f72";}function core_md5(x,len){x[len>>5]|=0x80<<((len)%32);x[(((len+64)>>>9)<<4)+14]=len;var a=1732584193;var b=-271733879;var c=-1732584194;var d=271733878;for(var i=0;i<x.length;i+=16){var olda=a;var oldb=b;var oldc=c;var oldd=d;a=md5_ff(a,b,c,d,x[i+0],7,-680876936);d=md5_ff(d,a,b,c,x[i+1],12,-389564586);c=md5_ff(c,d,a,b,x[i+2],17,606105819);b=md5_ff(b,c,d,a,x[i+3],22,-1044525330);a=md5_ff(a,b,c,d,x[i+4],7,-176418897);d=md5_ff(d,a,b,c,x[i+5],12,1200080426);c=md5_ff(c,d,a,b,x[i+6],17,-1473231341);b=md5_ff(b,c,d,a,x[i+7],22,-45705983);a=md5_ff(a,b,c,d,x[i+8],7,1770035416);d=md5_ff(d,a,b,c,x[i+9],12,-1958414417);c=md5_ff(c,d,a,b,x[i+10],17,-42063);b=md5_ff(b,c,d,a,x[i+11],22,-1990404162);a=md5_ff(a,b,c,d,x[i+12],7,1804603682);d=md5_ff(d,a,b,c,x[i+13],12,-40341101);c=md5_ff(c,d,a,b,x[i+14],17,-1502002290);b=md5_ff(b,c,d,a,x[i+15],22,1236535329);a=md5_gg(a,b,c,d,x[i+1],5,-165796510);d=md5_gg(d,a,b,c,x[i+6],9,-1069501632);c=md5_gg(c,d,a,b,x[i+11],14,643717713);b=md5_gg(b,c,d,a,x[i+0],20,-373897302);a=md5_gg(a,b,c,d,x[i+5],5,-701558691);d=md5_gg(d,a,b,c,x[i+10],9,38016083);c=md5_gg(c,d,a,b,x[i+15],14,-660478335);b=md5_gg(b,c,d,a,x[i+4],20,-405537848);a=md5_gg(a,b,c,d,x[i+9],5,568446438);d=md5_gg(d,a,b,c,x[i+14],9,-1019803690);c=md5_gg(c,d,a,b,x[i+3],14,-187363961);b=md5_gg(b,c,d,a,x[i+8],20,1163531501);a=md5_gg(a,b,c,d,x[i+13],5,-1444681467);d=md5_gg(d,a,b,c,x[i+2],9,-51403784);c=md5_gg(c,d,a,b,x[i+7],14,1735328473);b=md5_gg(b,c,d,a,x[i+12],20,-1926607734);a=md5_hh(a,b,c,d,x[i+5],4,-378558);d=md5_hh(d,a,b,c,x[i+8],11,-2022574463);c=md5_hh(c,d,a,b,x[i+11],16,1839030562);b=md5_hh(b,c,d,a,x[i+14],23,-35309556);a=md5_hh(a,b,c,d,x[i+1],4,-1530992060);d=md5_hh(d,a,b,c,x[i+4],11,1272893353);c=md5_hh(c,d,a,b,x[i+7],16,-155497632);b=md5_hh(b,c,d,a,x[i+10],23,-1094730640);a=md5_hh(a,b,c,d,x[i+13],4,681279174);d=md5_hh(d,a,b,c,x[i+0],11,-358537222);c=md5_hh(c,d,a,b,x[i+3],16,-722521979);b=md5_hh(b,c,d,a,x[i+6],23,76029189);a=md5_hh(a,b,c,d,x[i+9],4,-640364487);d=md5_hh(d,a,b,c,x[i+12],11,-421815835);c=md5_hh(c,d,a,b,x[i+15],16,530742520);b=md5_hh(b,c,d,a,x[i+2],23,-995338651);a=md5_ii(a,b,c,d,x[i+0],6,-198630844);d=md5_ii(d,a,b,c,x[i+7],10,1126891415);c=md5_ii(c,d,a,b,x[i+14],15,-1416354905);b=md5_ii(b,c,d,a,x[i+5],21,-57434055);a=md5_ii(a,b,c,d,x[i+12],6,1700485571);d=md5_ii(d,a,b,c,x[i+3],10,-1894986606);c=md5_ii(c,d,a,b,x[i+10],15,-1051523);b=md5_ii(b,c,d,a,x[i+1],21,-2054922799);a=md5_ii(a,b,c,d,x[i+8],6,1873313359);d=md5_ii(d,a,b,c,x[i+15],10,-30611744);c=md5_ii(c,d,a,b,x[i+6],15,-1560198380);b=md5_ii(b,c,d,a,x[i+13],21,1309151649);a=md5_ii(a,b,c,d,x[i+4],6,-145523070);d=md5_ii(d,a,b,c,x[i+11],10,-1120210379);c=md5_ii(c,d,a,b,x[i+2],15,718787259);b=md5_ii(b,c,d,a,x[i+9],21,-343485551);a=safe_add(a,olda);b=safe_add(b,oldb);c=safe_add(c,oldc);d=safe_add(d,oldd);}return Array(a,b,c,d);}function md5_cmn(q,a,b,x,s,t){return safe_add(bit_rol(safe_add(safe_add(a,q),safe_add(x,t)),s),b);}function md5_ff(a,b,c,d,x,s,t){return md5_cmn((b&c)|((~b)&d),a,b,x,s,t);}function md5_gg(a,b,c,d,x,s,t){return md5_cmn((b&d)|(c&(~d)),a,b,x,s,t);}function md5_hh(a,b,c,d,x,s,t){return md5_cmn(b^c^d,a,b,x,s,t);}function md5_ii(a,b,c,d,x,s,t){return md5_cmn(c^(b|(~d)),a,b,x,s,t);}function core_hmac_md5(key,data){var bkey=str2binl(key);if(bkey.length>16)bkey=core_md5(bkey,key.length*chrsz);var ipad=Array(16),opad=Array(16);for(var i=0;i<16;i++){ipad[i]=bkey[i]^0x36363636;opad[i]=bkey[i]^0x5C5C5C5C;}var hash=core_md5(ipad.concat(str2binl(data)),512+data.length*chrsz);return core_md5(opad.concat(hash),512+128);}function safe_add(x,y){var lsw=(x&0xFFFF)+(y&0xFFFF);var msw=(x>>16)+(y>>16)+(lsw>>16);return(msw<<16)|(lsw&0xFFFF);}function bit_rol(num,cnt){return(num<<cnt)|(num>>>(32-cnt));}function str2binl(str){var bin=Array();var mask=(1<<chrsz)-1;for(var i=0;i<str.length*chrsz;i+=chrsz)bin[i>>5]|=(str.charCodeAt(i/chrsz)&mask)<<(i%32);return bin;}function binl2str(bin){var str="";var mask=(1<<chrsz)-1;for(var i=0;i<bin.length*32;i+=chrsz)str+=String.fromCharCode((bin[i>>5]>>>(i%32))&mask);return str;}function binl2hex(binarray){var hex_tab=hexcase?"0123456789ABCDEF":"0123456789abcdef";var str="";for(var i=0;i<binarray.length*4;i++){str+=hex_tab.charAt((binarray[i>>2]>>((i%4)*8+4))&0xF)+hex_tab.charAt((binarray[i>>2]>>((i%4)*8))&0xF);}return str;}function binl2b64(binarray){var tab="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";var str="";for(var i=0;i<binarray.length*4;i+=3){var triplet=(((binarray[i>>2]>>8*(i%4))&0xFF)<<16)|(((binarray[i+1>>2]>>8*((i+1)%4))&0xFF)<<8)|((binarray[i+2>>2]>>8*((i+2)%4))&0xFF);for(var j=0;j<4;j++){if(i*8+j*6>binarray.length*32)str+=b64pad;else str+=tab.charAt((triplet>>6*(3-j))&0x3F);}}return str;};

/*!
 * fgnass.github.com/spin.js#v1.3.2
 */
(function(t,e){if(typeof exports=="object")module.exports=e();else if(typeof define=="function"&&define.amd)define(e);else t.Spinner=e()})(this,function(){"use strict";var t=["webkit","Moz","ms","O"],e={},i;function o(t,e){var i=document.createElement(t||"div"),o;for(o in e)i[o]=e[o];return i}function n(t){for(var e=1,i=arguments.length;e<i;e++)t.appendChild(arguments[e]);return t}var r=function(){var t=o("style",{type:"text/css"});n(document.getElementsByTagName("head")[0],t);return t.sheet||t.styleSheet}();function s(t,o,n,s){var a=["opacity",o,~~(t*100),n,s].join("-"),f=.01+n/s*100,l=Math.max(1-(1-t)/o*(100-f),t),u=i.substring(0,i.indexOf("Animation")).toLowerCase(),d=u&&"-"+u+"-"||"";if(!e[a]){r.insertRule("@"+d+"keyframes "+a+"{"+"0%{opacity:"+l+"}"+f+"%{opacity:"+t+"}"+(f+.01)+"%{opacity:1}"+(f+o)%100+"%{opacity:"+t+"}"+"100%{opacity:"+l+"}"+"}",r.cssRules.length);e[a]=1}return a}function a(e,i){var o=e.style,n,r;i=i.charAt(0).toUpperCase()+i.slice(1);for(r=0;r<t.length;r++){n=t[r]+i;if(o[n]!==undefined)return n}if(o[i]!==undefined)return i}function f(t,e){for(var i in e)t.style[a(t,i)||i]=e[i];return t}function l(t){for(var e=1;e<arguments.length;e++){var i=arguments[e];for(var o in i)if(t[o]===undefined)t[o]=i[o]}return t}function u(t){var e={x:t.offsetLeft,y:t.offsetTop};while(t=t.offsetParent)e.x+=t.offsetLeft,e.y+=t.offsetTop;return e}function d(t,e){return typeof t=="string"?t:t[e%t.length]}var p={lines:12,length:7,width:5,radius:10,rotate:0,corners:1,color:"#000",direction:1,speed:1,trail:100,opacity:1/4,fps:20,zIndex:2e9,className:"spinner",top:"auto",left:"auto",position:"relative"};function c(t){if(typeof this=="undefined")return new c(t);this.opts=l(t||{},c.defaults,p)}c.defaults={};l(c.prototype,{spin:function(t){this.stop();var e=this,n=e.opts,r=e.el=f(o(0,{className:n.className}),{position:n.position,width:0,zIndex:n.zIndex}),s=n.radius+n.length+n.width,a,l;if(t){t.insertBefore(r,t.firstChild||null);l=u(t);a=u(r);f(r,{left:(n.left=="auto"?l.x-a.x+(t.offsetWidth>>1):parseInt(n.left,10)+s)+"px",top:(n.top=="auto"?l.y-a.y+(t.offsetHeight>>1):parseInt(n.top,10)+s)+"px"})}r.setAttribute("role","progressbar");e.lines(r,e.opts);if(!i){var d=0,p=(n.lines-1)*(1-n.direction)/2,c,h=n.fps,m=h/n.speed,y=(1-n.opacity)/(m*n.trail/100),g=m/n.lines;(function v(){d++;for(var t=0;t<n.lines;t++){c=Math.max(1-(d+(n.lines-t)*g)%m*y,n.opacity);e.opacity(r,t*n.direction+p,c,n)}e.timeout=e.el&&setTimeout(v,~~(1e3/h))})()}return e},stop:function(){var t=this.el;if(t){clearTimeout(this.timeout);if(t.parentNode)t.parentNode.removeChild(t);this.el=undefined}return this},lines:function(t,e){var r=0,a=(e.lines-1)*(1-e.direction)/2,l;function u(t,i){return f(o(),{position:"absolute",width:e.length+e.width+"px",height:e.width+"px",background:t,boxShadow:i,transformOrigin:"left",transform:"rotate("+~~(360/e.lines*r+e.rotate)+"deg) translate("+e.radius+"px"+",0)",borderRadius:(e.corners*e.width>>1)+"px"})}for(;r<e.lines;r++){l=f(o(),{position:"absolute",top:1+~(e.width/2)+"px",transform:e.hwaccel?"translate3d(0,0,0)":"",opacity:e.opacity,animation:i&&s(e.opacity,e.trail,a+r*e.direction,e.lines)+" "+1/e.speed+"s linear infinite"});if(e.shadow)n(l,f(u("#000","0 0 4px "+"#000"),{top:2+"px"}));n(t,n(l,u(d(e.color,r),"0 0 1px rgba(0,0,0,.1)")))}return t},opacity:function(t,e,i){if(e<t.childNodes.length)t.childNodes[e].style.opacity=i}});function h(){function t(t,e){return o("<"+t+' xmlns="urn:schemas-microsoft.com:vml" class="spin-vml">',e)}r.addRule(".spin-vml","behavior:url(#default#VML)");c.prototype.lines=function(e,i){var o=i.length+i.width,r=2*o;function s(){return f(t("group",{coordsize:r+" "+r,coordorigin:-o+" "+-o}),{width:r,height:r})}var a=-(i.width+i.length)*2+"px",l=f(s(),{position:"absolute",top:a,left:a}),u;function p(e,r,a){n(l,n(f(s(),{rotation:360/i.lines*e+"deg",left:~~r}),n(f(t("roundrect",{arcsize:i.corners}),{width:o,height:i.width,left:i.radius,top:-i.width>>1,filter:a}),t("fill",{color:d(i.color,e),opacity:i.opacity}),t("stroke",{opacity:0}))))}if(i.shadow)for(u=1;u<=i.lines;u++)p(u,-2,"progid:DXImageTransform.Microsoft.Blur(pixelradius=2,makeshadow=1,shadowopacity=.3)");for(u=1;u<=i.lines;u++)p(u);return n(e,l)};c.prototype.opacity=function(t,e,i,o){var n=t.firstChild;o=o.shadow&&o.lines||0;if(n&&e+o<n.childNodes.length){n=n.childNodes[e+o];n=n&&n.firstChild;n=n&&n.firstChild;if(n)n.opacity=i}}}var m=f(o("group"),{behavior:"url(#default#VML)"});if(!a(m,"transform")&&m.adj)h();else i=a(m,"animation");return c});

/*!
 * FastClick: https://github.com/ftlabs/fastclick#v1.0.3
 */
(function(){'use strict';function FastClick(f,g){var h;g=g||{};this.trackingClick=false;this.trackingClickStart=0;this.targetElement=null;this.touchStartX=0;this.touchStartY=0;this.lastTouchIdentifier=0;this.touchBoundary=g.touchBoundary||10;this.layer=f;this.tapDelay=g.tapDelay||200;this.tapTimeout=g.tapTimeout||700;if(FastClick.notNeeded(f)){return}function bind(a,b){return function(){return a.apply(b,arguments)}}var j=['onMouse','onClick','onTouchStart','onTouchMove','onTouchEnd','onTouchCancel'];var k=this;for(var i=0,l=j.length;i<l;i++){k[j[i]]=bind(k[j[i]],k)}if(n){f.addEventListener('mouseover',this.onMouse,true);f.addEventListener('mousedown',this.onMouse,true);f.addEventListener('mouseup',this.onMouse,true)}f.addEventListener('click',this.onClick,true);f.addEventListener('touchstart',this.onTouchStart,false);f.addEventListener('touchmove',this.onTouchMove,false);f.addEventListener('touchend',this.onTouchEnd,false);f.addEventListener('touchcancel',this.onTouchCancel,false);if(!Event.prototype.stopImmediatePropagation){f.removeEventListener=function(a,b,c){var d=Node.prototype.removeEventListener;if(a==='click'){d.call(f,a,b.hijacked||b,c)}else{d.call(f,a,b,c)}};f.addEventListener=function(b,c,d){var e=Node.prototype.addEventListener;if(b==='click'){e.call(f,b,c.hijacked||(c.hijacked=function(a){if(!a.propagationStopped){c(a)}}),d)}else{e.call(f,b,c,d)}}}if(typeof f.onclick==='function'){h=f.onclick;f.addEventListener('click',function(a){h(a)},false);f.onclick=null}}var m=navigator.userAgent.indexOf("Windows Phone")>=0;var n=navigator.userAgent.indexOf('Android')>0&&!m;var o=/iP(ad|hone|od)/.test(navigator.userAgent)&&!m;var p=o&&(/OS 4_\d(_\d)?/).test(navigator.userAgent);var q=o&&(/OS ([6-9]|\d{2})_\d/).test(navigator.userAgent);var r=navigator.userAgent.indexOf('BB10')>0;FastClick.prototype.needsClick=function(a){switch(a.nodeName.toLowerCase()){case'button':case'select':case'textarea':if(a.disabled){return true}break;case'input':if((o&&a.type==='file')||a.disabled){return true}break;case'label':case'iframe':case'video':return true}return(/\bneedsclick\b/).test(a.className)};FastClick.prototype.needsFocus=function(a){switch(a.nodeName.toLowerCase()){case'textarea':return true;case'select':return!n;case'input':switch(a.type){case'button':case'checkbox':case'file':case'image':case'radio':case'submit':return false}return!a.disabled&&!a.readOnly;default:return(/\bneedsfocus\b/).test(a.className)}};FastClick.prototype.sendClick=function(a,b){var c,touch;if(document.activeElement&&document.activeElement!==a){document.activeElement.blur()}touch=b.changedTouches[0];c=document.createEvent('MouseEvents');c.initMouseEvent(this.determineEventType(a),true,true,window,1,touch.screenX,touch.screenY,touch.clientX,touch.clientY,false,false,false,false,0,null);c.forwardedTouchEvent=true;a.dispatchEvent(c)};FastClick.prototype.determineEventType=function(a){if(n&&a.tagName.toLowerCase()==='select'){return'mousedown'}return'click'};FastClick.prototype.focus=function(a){var b;if(o&&a.setSelectionRange&&a.type.indexOf('date')!==0&&a.type!=='time'&&a.type!=='month'){b=a.value.length;a.setSelectionRange(b,b)}else{a.focus()}};FastClick.prototype.updateScrollParent=function(a){var b,parentElement;b=a.fastClickScrollParent;if(!b||!b.contains(a)){parentElement=a;do{if(parentElement.scrollHeight>parentElement.offsetHeight){b=parentElement;a.fastClickScrollParent=parentElement;break}parentElement=parentElement.parentElement}while(parentElement)}if(b){b.fastClickLastScrollTop=b.scrollTop}};FastClick.prototype.getTargetElementFromEventTarget=function(a){if(a.nodeType===Node.TEXT_NODE){return a.parentNode}return a};FastClick.prototype.onTouchStart=function(a){var b,touch,selection;if(a.targetTouches.length>1){return true}b=this.getTargetElementFromEventTarget(a.target);touch=a.targetTouches[0];if(o){selection=window.getSelection();if(selection.rangeCount&&!selection.isCollapsed){return true}if(!p){if(touch.identifier&&touch.identifier===this.lastTouchIdentifier){a.preventDefault();return false}this.lastTouchIdentifier=touch.identifier;this.updateScrollParent(b)}}this.trackingClick=true;this.trackingClickStart=a.timeStamp;this.targetElement=b;this.touchStartX=touch.pageX;this.touchStartY=touch.pageY;if((a.timeStamp-this.lastClickTime)<this.tapDelay){a.preventDefault()}return true};FastClick.prototype.touchHasMoved=function(a){var b=a.changedTouches[0],boundary=this.touchBoundary;if(Math.abs(b.pageX-this.touchStartX)>boundary||Math.abs(b.pageY-this.touchStartY)>boundary){return true}return false};FastClick.prototype.onTouchMove=function(a){if(!this.trackingClick){return true}if(this.targetElement!==this.getTargetElementFromEventTarget(a.target)||this.touchHasMoved(a)){this.trackingClick=false;this.targetElement=null}return true};FastClick.prototype.findControl=function(a){if(a.control!==undefined){return a.control}if(a.htmlFor){return document.getElementById(a.htmlFor)}return a.querySelector('button, input:not([type=hidden]), keygen, meter, output, progress, select, textarea')};FastClick.prototype.onTouchEnd=function(a){var b,trackingClickStart,targetTagName,scrollParent,touch,targetElement=this.targetElement;if(!this.trackingClick){return true}if((a.timeStamp-this.lastClickTime)<this.tapDelay){this.cancelNextClick=true;return true}if((a.timeStamp-this.trackingClickStart)>this.tapTimeout){return true}this.cancelNextClick=false;this.lastClickTime=a.timeStamp;trackingClickStart=this.trackingClickStart;this.trackingClick=false;this.trackingClickStart=0;if(q){touch=a.changedTouches[0];targetElement=document.elementFromPoint(touch.pageX-window.pageXOffset,touch.pageY-window.pageYOffset)||targetElement;targetElement.fastClickScrollParent=this.targetElement.fastClickScrollParent}targetTagName=targetElement.tagName.toLowerCase();if(targetTagName==='label'){b=this.findControl(targetElement);if(b){this.focus(targetElement);if(n){return false}targetElement=b}}else if(this.needsFocus(targetElement)){if((a.timeStamp-trackingClickStart)>100||(o&&window.top!==window&&targetTagName==='input')){this.targetElement=null;return false}this.focus(targetElement);this.sendClick(targetElement,a);if(!o||targetTagName!=='select'){this.targetElement=null;a.preventDefault()}return false}if(o&&!p){scrollParent=targetElement.fastClickScrollParent;if(scrollParent&&scrollParent.fastClickLastScrollTop!==scrollParent.scrollTop){return true}}if(!this.needsClick(targetElement)){a.preventDefault();this.sendClick(targetElement,a)}return false};FastClick.prototype.onTouchCancel=function(){this.trackingClick=false;this.targetElement=null};FastClick.prototype.onMouse=function(a){if(!this.targetElement){return true}if(a.forwardedTouchEvent){return true}if(!a.cancelable){return true}if(!this.needsClick(this.targetElement)||this.cancelNextClick){if(a.stopImmediatePropagation){a.stopImmediatePropagation()}else{a.propagationStopped=true}a.stopPropagation();a.preventDefault();return false}return true};FastClick.prototype.onClick=function(a){var b;if(this.trackingClick){this.targetElement=null;this.trackingClick=false;return true}if(a.target.type==='submit'&&a.detail===0){return true}b=this.onMouse(a);if(!b){this.targetElement=null}return b};FastClick.prototype.destroy=function(){var a=this.layer;if(n){a.removeEventListener('mouseover',this.onMouse,true);a.removeEventListener('mousedown',this.onMouse,true);a.removeEventListener('mouseup',this.onMouse,true)}a.removeEventListener('click',this.onClick,true);a.removeEventListener('touchstart',this.onTouchStart,false);a.removeEventListener('touchmove',this.onTouchMove,false);a.removeEventListener('touchend',this.onTouchEnd,false);a.removeEventListener('touchcancel',this.onTouchCancel,false)};FastClick.notNeeded=function(a){var b;var c;var d;if(typeof window.ontouchstart==='undefined'){return true}c=+(/Chrome\/([0-9]+)/.exec(navigator.userAgent)||[,0])[1];if(c){if(n){b=document.querySelector('meta[name=viewport]');if(b){if(b.content.indexOf('user-scalable=no')!==-1){return true}if(c>31&&document.documentElement.scrollWidth<=window.outerWidth){return true}}}else{return true}}if(r){d=navigator.userAgent.match(/Version\/([0-9]*)\.([0-9]*)/);if(d[1]>=10&&d[2]>=3){b=document.querySelector('meta[name=viewport]');if(b){if(b.content.indexOf('user-scalable=no')!==-1){return true}if(document.documentElement.scrollWidth<=window.outerWidth){return true}}}}if(a.style.msTouchAction==='none'){return true}if(a.style.touchAction==='none'){return true}return false};FastClick.attach=function(a,b){return new FastClick(a,b)};if(typeof define=='function'&&typeof define.amd=='object'&&define.amd){define(function(){return FastClick})}else if(typeof module!=='undefined'&&module.exports){module.exports=FastClick.attach;module.exports.FastClick=FastClick}else{window.FastClick=FastClick}}());

/*!
 * Part of jQuery Migrate - v1.2.1 - 2013-05-08
 * https://github.com/jquery/jquery-migrate
 */
(function( jQuery, window, undefined ) {
	jQuery.handleError = function( s, xhr, status, e ) {
        // If a local callback was specified, fire it
        if ( s.error )
            s.error( xhr, status, e );
        // If we have some XML response text (e.g. from an AJAX call) then log it in the console
        else if(xhr.responseText)
            console.log(xhr.responseText);
    };
	jQuery.uaMatch = function( ua ) {
		ua = ua.toLowerCase();
	
		var match = /(chrome)[ \/]([\w.]+)/.exec( ua ) ||
			/(webkit)[ \/]([\w.]+)/.exec( ua ) ||
			/(opera)(?:.*version|)[ \/]([\w.]+)/.exec( ua ) ||
			/(msie) ([\w.]+)/.exec( ua ) ||
			ua.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec( ua ) ||
			[];
	
		return {
			browser: match[ 1 ] || "",
			version: match[ 2 ] || "0"
		};
	};
	// Don't clobber any existing jQuery.browser in case it's different
	if ( !jQuery.browser ) {
		matched = jQuery.uaMatch( navigator.userAgent );
		var browser = {};
	
		if ( matched.browser ) {
			browser[ matched.browser ] = true;
			browser.version = matched.version;
		}
	
		// Chrome is Webkit, but Webkit is also Safari.
		if ( browser.chrome ) {
			browser.webkit = true;
		} else if ( browser.webkit ) {
			browser.safari = true;
		}
	
		jQuery.browser = browser;
	}
})( jQuery, this );

/*! iScroll v5.1.3(probe) ~ (c) 2008-2014 Matteo Spinelli ~ http://cubiq.org/license */
(function(o,p,q){var s=o.requestAnimationFrame||o.webkitRequestAnimationFrame||o.mozRequestAnimationFrame||o.oRequestAnimationFrame||o.msRequestAnimationFrame||function(a){o.setTimeout(a,1000/60)};var t=(function(){var h={};var j=p.createElement('div').style;var m=(function(){var a=['t','webkitT','MozT','msT','OT'],transform,i=0,l=a.length;for(;i<l;i++){transform=a[i]+'ransform';if(transform in j)return a[i].substr(0,a[i].length-1)}return false})();function _prefixStyle(a){if(m===false)return false;if(m==='')return a;return m+a.charAt(0).toUpperCase()+a.substr(1)}h.getTime=Date.now||function getTime(){return new Date().getTime()};h.extend=function(a,b){for(var i in b){a[i]=b[i]}};h.addEvent=function(a,b,c,d){a.addEventListener(b,c,!!d)};h.removeEvent=function(a,b,c,d){a.removeEventListener(b,c,!!d)};h.prefixPointerEvent=function(a){return o.MSPointerEvent?'MSPointer'+a.charAt(9).toUpperCase()+a.substr(10):a};h.momentum=function(a,b,c,d,e,f){var g=a-b,speed=q.abs(g)/c,destination,duration;f=f===undefined?0.0006:f;destination=a+(speed*speed)/(2*f)*(g<0?-1:1);duration=speed/f;if(destination<d){destination=e?d-(e/2.5*(speed/8)):d;g=q.abs(destination-a);duration=g/speed}else if(destination>0){destination=e?e/2.5*(speed/8):0;g=q.abs(a)+destination;duration=g/speed}return{destination:q.round(destination),duration:duration}};var n=_prefixStyle('transform');h.extend(h,{hasTransform:n!==false,hasPerspective:_prefixStyle('perspective')in j,hasTouch:'ontouchstart'in o,hasPointer:o.PointerEvent||o.MSPointerEvent,hasTransition:_prefixStyle('transition')in j});h.isBadAndroid=/Android /.test(o.navigator.appVersion)&&!(/Chrome\/\d/.test(o.navigator.appVersion));h.extend(h.style={},{transform:n,transitionTimingFunction:_prefixStyle('transitionTimingFunction'),transitionDuration:_prefixStyle('transitionDuration'),transitionDelay:_prefixStyle('transitionDelay'),transformOrigin:_prefixStyle('transformOrigin')});h.hasClass=function(e,c){var a=new RegExp("(^|\\s)"+c+"(\\s|$)");return a.test(e.className)};h.addClass=function(e,c){if(h.hasClass(e,c)){return}var a=e.className.split(' ');a.push(c);e.className=a.join(' ')};h.removeClass=function(e,c){if(!h.hasClass(e,c)){return}var a=new RegExp("(^|\\s)"+c+"(\\s|$)",'g');e.className=e.className.replace(a,' ')};h.offset=function(a){var b=-a.offsetLeft,top=-a.offsetTop;while(a=a.offsetParent){b-=a.offsetLeft;top-=a.offsetTop}return{left:b,top:top}};h.preventDefaultException=function(a,b){for(var i in b){if(b[i].test(a[i])){return true}}return false};h.extend(h.eventType={},{touchstart:1,touchmove:1,touchend:1,mousedown:2,mousemove:2,mouseup:2,pointerdown:3,pointermove:3,pointerup:3,MSPointerDown:3,MSPointerMove:3,MSPointerUp:3});h.extend(h.ease={},{quadratic:{style:'cubic-bezier(0.25, 0.46, 0.45, 0.94)',fn:function(k){return k*(2-k)}},circular:{style:'cubic-bezier(0.1, 0.57, 0.1, 1)',fn:function(k){return q.sqrt(1-(--k*k))}},back:{style:'cubic-bezier(0.175, 0.885, 0.32, 1.275)',fn:function(k){var b=4;return(k=k-1)*k*((b+1)*k+b)+1}},bounce:{style:'',fn:function(k){if((k/=1)<(1/2.75)){return 7.5625*k*k}else if(k<(2/2.75)){return 7.5625*(k-=(1.5/2.75))*k+0.75}else if(k<(2.5/2.75)){return 7.5625*(k-=(2.25/2.75))*k+0.9375}else{return 7.5625*(k-=(2.625/2.75))*k+0.984375}}},elastic:{style:'',fn:function(k){var f=0.22,e=0.4;if(k===0){return 0}if(k==1){return 1}return(e*q.pow(2,-10*k)*q.sin((k-f/4)*(2*q.PI)/f)+1)}}});h.tap=function(e,a){var b=p.createEvent('Event');b.initEvent(a,true,true);b.pageX=e.pageX;b.pageY=e.pageY;e.target.dispatchEvent(b)};h.click=function(e){var a=e.target,ev;if(!(/(SELECT|INPUT|TEXTAREA)/i).test(a.tagName)){ev=p.createEvent('MouseEvents');ev.initMouseEvent('click',true,true,e.view,1,a.screenX,a.screenY,a.clientX,a.clientY,e.ctrlKey,e.altKey,e.shiftKey,e.metaKey,0,null);ev._constructed=true;a.dispatchEvent(ev)}};return h})();function IScroll(a,b){this.wrapper=typeof a=='string'?p.querySelector(a):a;this.scroller=this.wrapper.children[0];this.scrollerStyle=this.scroller.style;this.options={resizeScrollbars:true,mouseWheelSpeed:20,snapThreshold:0.334,startX:0,startY:0,scrollY:true,directionLockThreshold:5,momentum:true,bounce:true,bounceTime:600,bounceEasing:'',preventDefault:true,preventDefaultException:{tagName:/^(INPUT|TEXTAREA|BUTTON|SELECT)$/},HWCompositing:true,useTransition:true,useTransform:true};for(var i in b){this.options[i]=b[i]}this.translateZ=this.options.HWCompositing&&t.hasPerspective?' translateZ(0)':'';this.options.useTransition=t.hasTransition&&this.options.useTransition;this.options.useTransform=t.hasTransform&&this.options.useTransform;this.options.eventPassthrough=this.options.eventPassthrough===true?'vertical':this.options.eventPassthrough;this.options.preventDefault=!this.options.eventPassthrough&&this.options.preventDefault;this.options.scrollY=this.options.eventPassthrough=='vertical'?false:this.options.scrollY;this.options.scrollX=this.options.eventPassthrough=='horizontal'?false:this.options.scrollX;this.options.freeScroll=this.options.freeScroll&&!this.options.eventPassthrough;this.options.directionLockThreshold=this.options.eventPassthrough?0:this.options.directionLockThreshold;this.options.bounceEasing=typeof this.options.bounceEasing=='string'?t.ease[this.options.bounceEasing]||t.ease.circular:this.options.bounceEasing;this.options.resizePolling=this.options.resizePolling===undefined?60:this.options.resizePolling;if(this.options.tap===true){this.options.tap='tap'}if(this.options.shrinkScrollbars=='scale'){this.options.useTransition=false}this.options.invertWheelDirection=this.options.invertWheelDirection?-1:1;if(this.options.probeType==3){this.options.useTransition=false}this.x=0;this.y=0;this.directionX=0;this.directionY=0;this._events={};this._init();this.refresh();this.scrollTo(this.options.startX,this.options.startY);this.enable()}IScroll.prototype={version:'5.1.3',_init:function(){this._initEvents();if(this.options.scrollbars||this.options.indicators){this._initIndicators()}if(this.options.mouseWheel){this._initWheel()}if(this.options.snap){this._initSnap()}if(this.options.keyBindings){this._initKeys()}},destroy:function(){this._initEvents(true);this._execEvent('destroy')},_transitionEnd:function(e){if(e.target!=this.scroller||!this.isInTransition){return}this._transitionTime();if(!this.resetPosition(this.options.bounceTime)){this.isInTransition=false;this._execEvent('scrollEnd')}},_start:function(e){if(t.eventType[e.type]!=1){if(e.button!==0){return}}if(!this.enabled||(this.initiated&&t.eventType[e.type]!==this.initiated)){return}if(this.options.preventDefault&&!t.isBadAndroid&&!t.preventDefaultException(e.target,this.options.preventDefaultException)){e.preventDefault()}var a=e.touches?e.touches[0]:e,pos;this.initiated=t.eventType[e.type];this.moved=false;this.distX=0;this.distY=0;this.directionX=0;this.directionY=0;this.directionLocked=0;this._transitionTime();this.startTime=t.getTime();if(this.options.useTransition&&this.isInTransition){this.isInTransition=false;pos=this.getComputedPosition();this._translate(q.round(pos.x),q.round(pos.y));this._execEvent('scrollEnd')}else if(!this.options.useTransition&&this.isAnimating){this.isAnimating=false;this._execEvent('scrollEnd')}this.startX=this.x;this.startY=this.y;this.absStartX=this.x;this.absStartY=this.y;this.pointX=a.pageX;this.pointY=a.pageY;this._execEvent('beforeScrollStart')},_move:function(e){if(!this.enabled||t.eventType[e.type]!==this.initiated){return}if(this.options.preventDefault){e.preventDefault()}var a=e.touches?e.touches[0]:e,deltaX=a.pageX-this.pointX,deltaY=a.pageY-this.pointY,timestamp=t.getTime(),newX,newY,absDistX,absDistY;this.pointX=a.pageX;this.pointY=a.pageY;this.distX+=deltaX;this.distY+=deltaY;absDistX=q.abs(this.distX);absDistY=q.abs(this.distY);if(timestamp-this.endTime>300&&(absDistX<10&&absDistY<10)){return}if(!this.directionLocked&&!this.options.freeScroll){if(absDistX>absDistY+this.options.directionLockThreshold){this.directionLocked='h'}else if(absDistY>=absDistX+this.options.directionLockThreshold){this.directionLocked='v'}else{this.directionLocked='n'}}if(this.directionLocked=='h'){if(this.options.eventPassthrough=='vertical'){e.preventDefault()}else if(this.options.eventPassthrough=='horizontal'){this.initiated=false;return}deltaY=0}else if(this.directionLocked=='v'){if(this.options.eventPassthrough=='horizontal'){e.preventDefault()}else if(this.options.eventPassthrough=='vertical'){this.initiated=false;return}deltaX=0}deltaX=this.hasHorizontalScroll?deltaX:0;deltaY=this.hasVerticalScroll?deltaY:0;newX=this.x+deltaX;newY=this.y+deltaY;if(newX>0||newX<this.maxScrollX){newX=this.options.bounce?this.x+deltaX/3:newX>0?0:this.maxScrollX}if(newY>0||newY<this.maxScrollY){newY=this.options.bounce?this.y+deltaY/3:newY>0?0:this.maxScrollY}this.directionX=deltaX>0?-1:deltaX<0?1:0;this.directionY=deltaY>0?-1:deltaY<0?1:0;if(!this.moved){this._execEvent('scrollStart')}this.moved=true;this._translate(newX,newY);if(timestamp-this.startTime>300){this.startTime=timestamp;this.startX=this.x;this.startY=this.y;if(this.options.probeType==1){this._execEvent('scroll')}}if(this.options.probeType>1){this._execEvent('scroll')}},_end:function(e){if(!this.enabled||t.eventType[e.type]!==this.initiated){return}if(this.options.preventDefault&&!t.preventDefaultException(e.target,this.options.preventDefaultException)){e.preventDefault()}var a=e.changedTouches?e.changedTouches[0]:e,momentumX,momentumY,duration=t.getTime()-this.startTime,newX=q.round(this.x),newY=q.round(this.y),distanceX=q.abs(newX-this.startX),distanceY=q.abs(newY-this.startY),time=0,easing='';this.isInTransition=0;this.initiated=0;this.endTime=t.getTime();if(this.resetPosition(this.options.bounceTime)){return}this.scrollTo(newX,newY);if(!this.moved){if(this.options.tap){t.tap(e,this.options.tap)}if(this.options.click){t.click(e)}this._execEvent('scrollCancel');return}if(this._events.flick&&duration<200&&distanceX<100&&distanceY<100){this._execEvent('flick');return}if(this.options.momentum&&duration<300){momentumX=this.hasHorizontalScroll?t.momentum(this.x,this.startX,duration,this.maxScrollX,this.options.bounce?this.wrapperWidth:0,this.options.deceleration):{destination:newX,duration:0};momentumY=this.hasVerticalScroll?t.momentum(this.y,this.startY,duration,this.maxScrollY,this.options.bounce?this.wrapperHeight:0,this.options.deceleration):{destination:newY,duration:0};newX=momentumX.destination;newY=momentumY.destination;time=q.max(momentumX.duration,momentumY.duration);this.isInTransition=1}if(this.options.snap){var b=this._nearestSnap(newX,newY);this.currentPage=b;time=this.options.snapSpeed||q.max(q.max(q.min(q.abs(newX-b.x),1000),q.min(q.abs(newY-b.y),1000)),300);newX=b.x;newY=b.y;this.directionX=0;this.directionY=0;easing=this.options.bounceEasing}if(newX!=this.x||newY!=this.y){if(newX>0||newX<this.maxScrollX||newY>0||newY<this.maxScrollY){easing=t.ease.quadratic}this.scrollTo(newX,newY,time,easing);return}this._execEvent('scrollEnd')},_resize:function(){var a=this;clearTimeout(this.resizeTimeout);this.resizeTimeout=setTimeout(function(){a.refresh()},this.options.resizePolling)},resetPosition:function(a){var x=this.x,y=this.y;a=a||0;if(!this.hasHorizontalScroll||this.x>0){x=0}else if(this.x<this.maxScrollX){x=this.maxScrollX}if(!this.hasVerticalScroll||this.y>0){y=0}else if(this.y<this.maxScrollY){y=this.maxScrollY}if(x==this.x&&y==this.y){return false}this.scrollTo(x,y,a,this.options.bounceEasing);return true},disable:function(){this.enabled=false},enable:function(){this.enabled=true},refresh:function(){var a=this.wrapper.offsetHeight;this.wrapperWidth=this.wrapper.clientWidth;this.wrapperHeight=this.wrapper.clientHeight;this.scrollerWidth=this.scroller.offsetWidth;this.scrollerHeight=this.scroller.offsetHeight;this.maxScrollX=this.wrapperWidth-this.scrollerWidth;this.maxScrollY=this.wrapperHeight-this.scrollerHeight;this.hasHorizontalScroll=this.options.scrollX&&this.maxScrollX<0;this.hasVerticalScroll=this.options.scrollY&&this.maxScrollY<0;if(!this.hasHorizontalScroll){this.maxScrollX=0;this.scrollerWidth=this.wrapperWidth}if(!this.hasVerticalScroll){this.maxScrollY=0;this.scrollerHeight=this.wrapperHeight}this.endTime=0;this.directionX=0;this.directionY=0;this.wrapperOffset=t.offset(this.wrapper);this._execEvent('refresh');this.resetPosition()},on:function(a,b){if(!this._events[a]){this._events[a]=[]}this._events[a].push(b)},off:function(a,b){if(!this._events[a]){return}var c=this._events[a].indexOf(b);if(c>-1){this._events[a].splice(c,1)}},_execEvent:function(a){if(!this._events[a]){return}var i=0,l=this._events[a].length;if(!l){return}for(;i<l;i++){this._events[a][i].apply(this,[].slice.call(arguments,1))}},scrollBy:function(x,y,a,b){x=this.x+x;y=this.y+y;a=a||0;this.scrollTo(x,y,a,b)},scrollTo:function(x,y,a,b){b=b||t.ease.circular;this.isInTransition=this.options.useTransition&&a>0;if(!a||(this.options.useTransition&&b.style)){this._transitionTimingFunction(b.style);this._transitionTime(a);this._translate(x,y)}else{this._animate(x,y,a,b.fn)}},scrollToElement:function(a,b,c,d,e){a=a.nodeType?a:this.scroller.querySelector(a);if(!a){return}var f=t.offset(a);f.left-=this.wrapperOffset.left;f.top-=this.wrapperOffset.top;if(c===true){c=q.round(a.offsetWidth/2-this.wrapper.offsetWidth/2)}if(d===true){d=q.round(a.offsetHeight/2-this.wrapper.offsetHeight/2)}f.left-=c||0;f.top-=d||0;f.left=f.left>0?0:f.left<this.maxScrollX?this.maxScrollX:f.left;f.top=f.top>0?0:f.top<this.maxScrollY?this.maxScrollY:f.top;b=b===undefined||b===null||b==='auto'?q.max(q.abs(this.x-f.left),q.abs(this.y-f.top)):b;this.scrollTo(f.left,f.top,b,e)},_transitionTime:function(a){a=a||0;this.scrollerStyle[t.style.transitionDuration]=a+'ms';if(!a&&t.isBadAndroid){this.scrollerStyle[t.style.transitionDuration]='0.001s'}if(this.indicators){for(var i=this.indicators.length;i--;){this.indicators[i].transitionTime(a)}}},_transitionTimingFunction:function(a){this.scrollerStyle[t.style.transitionTimingFunction]=a;if(this.indicators){for(var i=this.indicators.length;i--;){this.indicators[i].transitionTimingFunction(a)}}},_translate:function(x,y){if(this.options.useTransform){this.scrollerStyle[t.style.transform]='translate('+x+'px,'+y+'px)'+this.translateZ}else{x=q.round(x);y=q.round(y);this.scrollerStyle.left=x+'px';this.scrollerStyle.top=y+'px'}this.x=x;this.y=y;if(this.indicators){for(var i=this.indicators.length;i--;){this.indicators[i].updatePosition()}}},_initEvents:function(a){var b=a?t.removeEvent:t.addEvent,target=this.options.bindToWrapper?this.wrapper:o;b(o,'orientationchange',this);b(o,'resize',this);if(this.options.click){b(this.wrapper,'click',this,true)}if(!this.options.disableMouse){b(this.wrapper,'mousedown',this);b(target,'mousemove',this);b(target,'mousecancel',this);b(target,'mouseup',this)}if(t.hasPointer&&!this.options.disablePointer){b(this.wrapper,t.prefixPointerEvent('pointerdown'),this);b(target,t.prefixPointerEvent('pointermove'),this);b(target,t.prefixPointerEvent('pointercancel'),this);b(target,t.prefixPointerEvent('pointerup'),this)}if(t.hasTouch&&!this.options.disableTouch){b(this.wrapper,'touchstart',this);b(target,'touchmove',this);b(target,'touchcancel',this);b(target,'touchend',this)}b(this.scroller,'transitionend',this);b(this.scroller,'webkitTransitionEnd',this);b(this.scroller,'oTransitionEnd',this);b(this.scroller,'MSTransitionEnd',this)},getComputedPosition:function(){var a=o.getComputedStyle(this.scroller,null),x,y;if(this.options.useTransform){a=a[t.style.transform].split(')')[0].split(', ');x=+(a[12]||a[4]);y=+(a[13]||a[5])}else{x=+a.left.replace(/[^-\d.]/g,'');y=+a.top.replace(/[^-\d.]/g,'')}return{x:x,y:y}},_initIndicators:function(){var b=this.options.interactiveScrollbars,customStyle=typeof this.options.scrollbars!='string',indicators=[],indicator;var c=this;this.indicators=[];if(this.options.scrollbars){if(this.options.scrollY){indicator={el:createDefaultScrollbar('v',b,this.options.scrollbars),interactive:b,defaultScrollbars:true,customStyle:customStyle,resize:this.options.resizeScrollbars,shrink:this.options.shrinkScrollbars,fade:this.options.fadeScrollbars,listenX:false};this.wrapper.appendChild(indicator.el);indicators.push(indicator)}if(this.options.scrollX){indicator={el:createDefaultScrollbar('h',b,this.options.scrollbars),interactive:b,defaultScrollbars:true,customStyle:customStyle,resize:this.options.resizeScrollbars,shrink:this.options.shrinkScrollbars,fade:this.options.fadeScrollbars,listenY:false};this.wrapper.appendChild(indicator.el);indicators.push(indicator)}}if(this.options.indicators){indicators=indicators.concat(this.options.indicators)}for(var i=indicators.length;i--;){this.indicators.push(new Indicator(this,indicators[i]))}function _indicatorsMap(a){for(var i=c.indicators.length;i--;){a.call(c.indicators[i])}}if(this.options.fadeScrollbars){this.on('scrollEnd',function(){_indicatorsMap(function(){this.fade()})});this.on('scrollCancel',function(){_indicatorsMap(function(){this.fade()})});this.on('scrollStart',function(){_indicatorsMap(function(){this.fade(1)})});this.on('beforeScrollStart',function(){_indicatorsMap(function(){this.fade(1,true)})})}this.on('refresh',function(){_indicatorsMap(function(){this.refresh()})});this.on('destroy',function(){_indicatorsMap(function(){this.destroy()});delete this.indicators})},_initWheel:function(){t.addEvent(this.wrapper,'wheel',this);t.addEvent(this.wrapper,'mousewheel',this);t.addEvent(this.wrapper,'DOMMouseScroll',this);this.on('destroy',function(){t.removeEvent(this.wrapper,'wheel',this);t.removeEvent(this.wrapper,'mousewheel',this);t.removeEvent(this.wrapper,'DOMMouseScroll',this)})},_wheel:function(e){if(!this.enabled){return}e.preventDefault();e.stopPropagation();var a,wheelDeltaY,newX,newY,that=this;if(this.wheelTimeout===undefined){that._execEvent('scrollStart')}clearTimeout(this.wheelTimeout);this.wheelTimeout=setTimeout(function(){that._execEvent('scrollEnd');that.wheelTimeout=undefined},400);if('deltaX'in e){if(e.deltaMode===1){a=-e.deltaX*this.options.mouseWheelSpeed;wheelDeltaY=-e.deltaY*this.options.mouseWheelSpeed}else{a=-e.deltaX;wheelDeltaY=-e.deltaY}}else if('wheelDeltaX'in e){a=e.wheelDeltaX/120*this.options.mouseWheelSpeed;wheelDeltaY=e.wheelDeltaY/120*this.options.mouseWheelSpeed}else if('wheelDelta'in e){a=wheelDeltaY=e.wheelDelta/120*this.options.mouseWheelSpeed}else if('detail'in e){a=wheelDeltaY=-e.detail/3*this.options.mouseWheelSpeed}else{return}a*=this.options.invertWheelDirection;wheelDeltaY*=this.options.invertWheelDirection;if(!this.hasVerticalScroll){a=wheelDeltaY;wheelDeltaY=0}if(this.options.snap){newX=this.currentPage.pageX;newY=this.currentPage.pageY;if(a>0){newX--}else if(a<0){newX++}if(wheelDeltaY>0){newY--}else if(wheelDeltaY<0){newY++}this.goToPage(newX,newY);return}newX=this.x+q.round(this.hasHorizontalScroll?a:0);newY=this.y+q.round(this.hasVerticalScroll?wheelDeltaY:0);if(newX>0){newX=0}else if(newX<this.maxScrollX){newX=this.maxScrollX}if(newY>0){newY=0}else if(newY<this.maxScrollY){newY=this.maxScrollY}this.scrollTo(newX,newY,0);if(this.options.probeType>1){this._execEvent('scroll')}},_initSnap:function(){this.currentPage={};if(typeof this.options.snap=='string'){this.options.snap=this.scroller.querySelectorAll(this.options.snap)}this.on('refresh',function(){var i=0,l,m=0,n,cx,cy,x=0,y,stepX=this.options.snapStepX||this.wrapperWidth,stepY=this.options.snapStepY||this.wrapperHeight,el;this.pages=[];if(!this.wrapperWidth||!this.wrapperHeight||!this.scrollerWidth||!this.scrollerHeight){return}if(this.options.snap===true){cx=q.round(stepX/2);cy=q.round(stepY/2);while(x>-this.scrollerWidth){this.pages[i]=[];l=0;y=0;while(y>-this.scrollerHeight){this.pages[i][l]={x:q.max(x,this.maxScrollX),y:q.max(y,this.maxScrollY),width:stepX,height:stepY,cx:x-cx,cy:y-cy};y-=stepY;l++}x-=stepX;i++}}else{el=this.options.snap;l=el.length;n=-1;for(;i<l;i++){if(i===0||el[i].offsetLeft<=el[i-1].offsetLeft){m=0;n++}if(!this.pages[m]){this.pages[m]=[]}x=q.max(-el[i].offsetLeft,this.maxScrollX);y=q.max(-el[i].offsetTop,this.maxScrollY);cx=x-q.round(el[i].offsetWidth/2);cy=y-q.round(el[i].offsetHeight/2);this.pages[m][n]={x:x,y:y,width:el[i].offsetWidth,height:el[i].offsetHeight,cx:cx,cy:cy};if(x>this.maxScrollX){m++}}}this.goToPage(this.currentPage.pageX||0,this.currentPage.pageY||0,0);if(this.options.snapThreshold%1===0){this.snapThresholdX=this.options.snapThreshold;this.snapThresholdY=this.options.snapThreshold}else{this.snapThresholdX=q.round(this.pages[this.currentPage.pageX][this.currentPage.pageY].width*this.options.snapThreshold);this.snapThresholdY=q.round(this.pages[this.currentPage.pageX][this.currentPage.pageY].height*this.options.snapThreshold)}});this.on('flick',function(){var a=this.options.snapSpeed||q.max(q.max(q.min(q.abs(this.x-this.startX),1000),q.min(q.abs(this.y-this.startY),1000)),300);this.goToPage(this.currentPage.pageX+this.directionX,this.currentPage.pageY+this.directionY,a)})},_nearestSnap:function(x,y){if(!this.pages.length){return{x:0,y:0,pageX:0,pageY:0}}var i=0,l=this.pages.length,m=0;if(q.abs(x-this.absStartX)<this.snapThresholdX&&q.abs(y-this.absStartY)<this.snapThresholdY){return this.currentPage}if(x>0){x=0}else if(x<this.maxScrollX){x=this.maxScrollX}if(y>0){y=0}else if(y<this.maxScrollY){y=this.maxScrollY}for(;i<l;i++){if(x>=this.pages[i][0].cx){x=this.pages[i][0].x;break}}l=this.pages[i].length;for(;m<l;m++){if(y>=this.pages[0][m].cy){y=this.pages[0][m].y;break}}if(i==this.currentPage.pageX){i+=this.directionX;if(i<0){i=0}else if(i>=this.pages.length){i=this.pages.length-1}x=this.pages[i][0].x}if(m==this.currentPage.pageY){m+=this.directionY;if(m<0){m=0}else if(m>=this.pages[0].length){m=this.pages[0].length-1}y=this.pages[0][m].y}return{x:x,y:y,pageX:i,pageY:m}},goToPage:function(x,y,a,b){b=b||this.options.bounceEasing;if(x>=this.pages.length){x=this.pages.length-1}else if(x<0){x=0}if(y>=this.pages[x].length){y=this.pages[x].length-1}else if(y<0){y=0}var c=this.pages[x][y].x,posY=this.pages[x][y].y;a=a===undefined?this.options.snapSpeed||q.max(q.max(q.min(q.abs(c-this.x),1000),q.min(q.abs(posY-this.y),1000)),300):a;this.currentPage={x:c,y:posY,pageX:x,pageY:y};this.scrollTo(c,posY,a,b)},next:function(a,b){var x=this.currentPage.pageX,y=this.currentPage.pageY;x++;if(x>=this.pages.length&&this.hasVerticalScroll){x=0;y++}this.goToPage(x,y,a,b)},prev:function(a,b){var x=this.currentPage.pageX,y=this.currentPage.pageY;x--;if(x<0&&this.hasVerticalScroll){x=0;y--}this.goToPage(x,y,a,b)},_initKeys:function(e){var a={pageUp:33,pageDown:34,end:35,home:36,left:37,up:38,right:39,down:40};var i;if(typeof this.options.keyBindings=='object'){for(i in this.options.keyBindings){if(typeof this.options.keyBindings[i]=='string'){this.options.keyBindings[i]=this.options.keyBindings[i].toUpperCase().charCodeAt(0)}}}else{this.options.keyBindings={}}for(i in a){this.options.keyBindings[i]=this.options.keyBindings[i]||a[i]}t.addEvent(o,'keydown',this);this.on('destroy',function(){t.removeEvent(o,'keydown',this)})},_key:function(e){if(!this.enabled){return}var a=this.options.snap,newX=a?this.currentPage.pageX:this.x,newY=a?this.currentPage.pageY:this.y,now=t.getTime(),prevTime=this.keyTime||0,acceleration=0.250,pos;if(this.options.useTransition&&this.isInTransition){pos=this.getComputedPosition();this._translate(q.round(pos.x),q.round(pos.y));this.isInTransition=false}this.keyAcceleration=now-prevTime<200?q.min(this.keyAcceleration+acceleration,50):0;switch(e.keyCode){case this.options.keyBindings.pageUp:if(this.hasHorizontalScroll&&!this.hasVerticalScroll){newX+=a?1:this.wrapperWidth}else{newY+=a?1:this.wrapperHeight}break;case this.options.keyBindings.pageDown:if(this.hasHorizontalScroll&&!this.hasVerticalScroll){newX-=a?1:this.wrapperWidth}else{newY-=a?1:this.wrapperHeight}break;case this.options.keyBindings.end:newX=a?this.pages.length-1:this.maxScrollX;newY=a?this.pages[0].length-1:this.maxScrollY;break;case this.options.keyBindings.home:newX=0;newY=0;break;case this.options.keyBindings.left:newX+=a?-1:5+this.keyAcceleration>>0;break;case this.options.keyBindings.up:newY+=a?1:5+this.keyAcceleration>>0;break;case this.options.keyBindings.right:newX-=a?-1:5+this.keyAcceleration>>0;break;case this.options.keyBindings.down:newY-=a?1:5+this.keyAcceleration>>0;break;default:return}if(a){this.goToPage(newX,newY);return}if(newX>0){newX=0;this.keyAcceleration=0}else if(newX<this.maxScrollX){newX=this.maxScrollX;this.keyAcceleration=0}if(newY>0){newY=0;this.keyAcceleration=0}else if(newY<this.maxScrollY){newY=this.maxScrollY;this.keyAcceleration=0}this.scrollTo(newX,newY,0);this.keyTime=now},_animate:function(b,c,d,e){var f=this,startX=this.x,startY=this.y,startTime=t.getTime(),destTime=startTime+d;function step(){var a=t.getTime(),newX,newY,easing;if(a>=destTime){f.isAnimating=false;f._translate(b,c);if(!f.resetPosition(f.options.bounceTime)){f._execEvent('scrollEnd')}return}a=(a-startTime)/d;easing=e(a);newX=(b-startX)*easing+startX;newY=(c-startY)*easing+startY;f._translate(newX,newY);if(f.isAnimating){s(step)}if(f.options.probeType==3){f._execEvent('scroll')}}this.isAnimating=true;step()},handleEvent:function(e){switch(e.type){case'touchstart':case'pointerdown':case'MSPointerDown':case'mousedown':this._start(e);break;case'touchmove':case'pointermove':case'MSPointerMove':case'mousemove':this._move(e);break;case'touchend':case'pointerup':case'MSPointerUp':case'mouseup':case'touchcancel':case'pointercancel':case'MSPointerCancel':case'mousecancel':this._end(e);break;case'orientationchange':case'resize':this._resize();break;case'transitionend':case'webkitTransitionEnd':case'oTransitionEnd':case'MSTransitionEnd':this._transitionEnd(e);break;case'wheel':case'DOMMouseScroll':case'mousewheel':this._wheel(e);break;case'keydown':this._key(e);break;case'click':if(!e._constructed){e.preventDefault();e.stopPropagation()}break}}};function createDefaultScrollbar(a,b,c){var d=p.createElement('div'),indicator=p.createElement('div');if(c===true){d.style.cssText='position:absolute;z-index:9999';indicator.style.cssText='-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;position:absolute;background:rgba(0,0,0,0.5);border:1px solid rgba(255,255,255,0.9);border-radius:3px'}indicator.className='iScrollIndicator';if(a=='h'){if(c===true){d.style.cssText+=';height:5px;left:2px;right:2px;bottom:0';indicator.style.height='100%'}d.className='iScrollHorizontalScrollbar'}else{if(c===true){d.style.cssText+=';width:5px;bottom:2px;top:2px;right:1px';indicator.style.width='100%'}d.className='iScrollVerticalScrollbar'}d.style.cssText+=';overflow:hidden';if(!b){d.style.pointerEvents='none'}d.appendChild(indicator);return d}function Indicator(a,b){this.wrapper=typeof b.el=='string'?p.querySelector(b.el):b.el;this.wrapperStyle=this.wrapper.style;this.indicator=this.wrapper.children[0];this.indicatorStyle=this.indicator.style;this.scroller=a;this.options={listenX:true,listenY:true,interactive:false,resize:true,defaultScrollbars:false,shrink:false,fade:false,speedRatioX:0,speedRatioY:0};for(var i in b){this.options[i]=b[i]}this.sizeRatioX=1;this.sizeRatioY=1;this.maxPosX=0;this.maxPosY=0;if(this.options.interactive){if(!this.options.disableTouch){t.addEvent(this.indicator,'touchstart',this);t.addEvent(o,'touchend',this)}if(!this.options.disablePointer){t.addEvent(this.indicator,t.prefixPointerEvent('pointerdown'),this);t.addEvent(o,t.prefixPointerEvent('pointerup'),this)}if(!this.options.disableMouse){t.addEvent(this.indicator,'mousedown',this);t.addEvent(o,'mouseup',this)}}if(this.options.fade){this.wrapperStyle[t.style.transform]=this.scroller.translateZ;this.wrapperStyle[t.style.transitionDuration]=t.isBadAndroid?'0.001s':'0ms';this.wrapperStyle.opacity='0'}}Indicator.prototype={handleEvent:function(e){switch(e.type){case'touchstart':case'pointerdown':case'MSPointerDown':case'mousedown':this._start(e);break;case'touchmove':case'pointermove':case'MSPointerMove':case'mousemove':this._move(e);break;case'touchend':case'pointerup':case'MSPointerUp':case'mouseup':case'touchcancel':case'pointercancel':case'MSPointerCancel':case'mousecancel':this._end(e);break}},destroy:function(){if(this.options.interactive){t.removeEvent(this.indicator,'touchstart',this);t.removeEvent(this.indicator,t.prefixPointerEvent('pointerdown'),this);t.removeEvent(this.indicator,'mousedown',this);t.removeEvent(o,'touchmove',this);t.removeEvent(o,t.prefixPointerEvent('pointermove'),this);t.removeEvent(o,'mousemove',this);t.removeEvent(o,'touchend',this);t.removeEvent(o,t.prefixPointerEvent('pointerup'),this);t.removeEvent(o,'mouseup',this)}if(this.options.defaultScrollbars){this.wrapper.parentNode.removeChild(this.wrapper)}},_start:function(e){var a=e.touches?e.touches[0]:e;e.preventDefault();e.stopPropagation();this.transitionTime();this.initiated=true;this.moved=false;this.lastPointX=a.pageX;this.lastPointY=a.pageY;this.startTime=t.getTime();if(!this.options.disableTouch){t.addEvent(o,'touchmove',this)}if(!this.options.disablePointer){t.addEvent(o,t.prefixPointerEvent('pointermove'),this)}if(!this.options.disableMouse){t.addEvent(o,'mousemove',this)}this.scroller._execEvent('beforeScrollStart')},_move:function(e){var a=e.touches?e.touches[0]:e,deltaX,deltaY,newX,newY,timestamp=t.getTime();if(!this.moved){this.scroller._execEvent('scrollStart')}this.moved=true;deltaX=a.pageX-this.lastPointX;this.lastPointX=a.pageX;deltaY=a.pageY-this.lastPointY;this.lastPointY=a.pageY;newX=this.x+deltaX;newY=this.y+deltaY;this._pos(newX,newY);if(this.scroller.options.probeType==1&&timestamp-this.startTime>300){this.startTime=timestamp;this.scroller._execEvent('scroll')}else if(this.scroller.options.probeType>1){this.scroller._execEvent('scroll')}e.preventDefault();e.stopPropagation()},_end:function(e){if(!this.initiated){return}this.initiated=false;e.preventDefault();e.stopPropagation();t.removeEvent(o,'touchmove',this);t.removeEvent(o,t.prefixPointerEvent('pointermove'),this);t.removeEvent(o,'mousemove',this);if(this.scroller.options.snap){var a=this.scroller._nearestSnap(this.scroller.x,this.scroller.y);var b=this.options.snapSpeed||q.max(q.max(q.min(q.abs(this.scroller.x-a.x),1000),q.min(q.abs(this.scroller.y-a.y),1000)),300);if(this.scroller.x!=a.x||this.scroller.y!=a.y){this.scroller.directionX=0;this.scroller.directionY=0;this.scroller.currentPage=a;this.scroller.scrollTo(a.x,a.y,b,this.scroller.options.bounceEasing)}}if(this.moved){this.scroller._execEvent('scrollEnd')}},transitionTime:function(a){a=a||0;this.indicatorStyle[t.style.transitionDuration]=a+'ms';if(!a&&t.isBadAndroid){this.indicatorStyle[t.style.transitionDuration]='0.001s'}},transitionTimingFunction:function(a){this.indicatorStyle[t.style.transitionTimingFunction]=a},refresh:function(){this.transitionTime();if(this.options.listenX&&!this.options.listenY){this.indicatorStyle.display=this.scroller.hasHorizontalScroll?'block':'none'}else if(this.options.listenY&&!this.options.listenX){this.indicatorStyle.display=this.scroller.hasVerticalScroll?'block':'none'}else{this.indicatorStyle.display=this.scroller.hasHorizontalScroll||this.scroller.hasVerticalScroll?'block':'none'}if(this.scroller.hasHorizontalScroll&&this.scroller.hasVerticalScroll){t.addClass(this.wrapper,'iScrollBothScrollbars');t.removeClass(this.wrapper,'iScrollLoneScrollbar');if(this.options.defaultScrollbars&&this.options.customStyle){if(this.options.listenX){this.wrapper.style.right='8px'}else{this.wrapper.style.bottom='8px'}}}else{t.removeClass(this.wrapper,'iScrollBothScrollbars');t.addClass(this.wrapper,'iScrollLoneScrollbar');if(this.options.defaultScrollbars&&this.options.customStyle){if(this.options.listenX){this.wrapper.style.right='2px'}else{this.wrapper.style.bottom='2px'}}}var r=this.wrapper.offsetHeight;if(this.options.listenX){this.wrapperWidth=this.wrapper.clientWidth;if(this.options.resize){this.indicatorWidth=q.max(q.round(this.wrapperWidth*this.wrapperWidth/(this.scroller.scrollerWidth||this.wrapperWidth||1)),8);this.indicatorStyle.width=this.indicatorWidth+'px'}else{this.indicatorWidth=this.indicator.clientWidth}this.maxPosX=this.wrapperWidth-this.indicatorWidth;if(this.options.shrink=='clip'){this.minBoundaryX=-this.indicatorWidth+8;this.maxBoundaryX=this.wrapperWidth-8}else{this.minBoundaryX=0;this.maxBoundaryX=this.maxPosX}this.sizeRatioX=this.options.speedRatioX||(this.scroller.maxScrollX&&(this.maxPosX/this.scroller.maxScrollX))}if(this.options.listenY){this.wrapperHeight=this.wrapper.clientHeight;if(this.options.resize){this.indicatorHeight=q.max(q.round(this.wrapperHeight*this.wrapperHeight/(this.scroller.scrollerHeight||this.wrapperHeight||1)),8);this.indicatorStyle.height=this.indicatorHeight+'px'}else{this.indicatorHeight=this.indicator.clientHeight}this.maxPosY=this.wrapperHeight-this.indicatorHeight;if(this.options.shrink=='clip'){this.minBoundaryY=-this.indicatorHeight+8;this.maxBoundaryY=this.wrapperHeight-8}else{this.minBoundaryY=0;this.maxBoundaryY=this.maxPosY}this.maxPosY=this.wrapperHeight-this.indicatorHeight;this.sizeRatioY=this.options.speedRatioY||(this.scroller.maxScrollY&&(this.maxPosY/this.scroller.maxScrollY))}this.updatePosition()},updatePosition:function(){var x=this.options.listenX&&q.round(this.sizeRatioX*this.scroller.x)||0,y=this.options.listenY&&q.round(this.sizeRatioY*this.scroller.y)||0;if(!this.options.ignoreBoundaries){if(x<this.minBoundaryX){if(this.options.shrink=='scale'){this.width=q.max(this.indicatorWidth+x,8);this.indicatorStyle.width=this.width+'px'}x=this.minBoundaryX}else if(x>this.maxBoundaryX){if(this.options.shrink=='scale'){this.width=q.max(this.indicatorWidth-(x-this.maxPosX),8);this.indicatorStyle.width=this.width+'px';x=this.maxPosX+this.indicatorWidth-this.width}else{x=this.maxBoundaryX}}else if(this.options.shrink=='scale'&&this.width!=this.indicatorWidth){this.width=this.indicatorWidth;this.indicatorStyle.width=this.width+'px'}if(y<this.minBoundaryY){if(this.options.shrink=='scale'){this.height=q.max(this.indicatorHeight+y*3,8);this.indicatorStyle.height=this.height+'px'}y=this.minBoundaryY}else if(y>this.maxBoundaryY){if(this.options.shrink=='scale'){this.height=q.max(this.indicatorHeight-(y-this.maxPosY)*3,8);this.indicatorStyle.height=this.height+'px';y=this.maxPosY+this.indicatorHeight-this.height}else{y=this.maxBoundaryY}}else if(this.options.shrink=='scale'&&this.height!=this.indicatorHeight){this.height=this.indicatorHeight;this.indicatorStyle.height=this.height+'px'}}this.x=x;this.y=y;if(this.scroller.options.useTransform){this.indicatorStyle[t.style.transform]='translate('+x+'px,'+y+'px)'+this.scroller.translateZ}else{this.indicatorStyle.left=x+'px';this.indicatorStyle.top=y+'px'}},_pos:function(x,y){if(x<0){x=0}else if(x>this.maxPosX){x=this.maxPosX}if(y<0){y=0}else if(y>this.maxPosY){y=this.maxPosY}x=this.options.listenX?q.round(x/this.sizeRatioX):this.scroller.x;y=this.options.listenY?q.round(y/this.sizeRatioY):this.scroller.y;this.scroller.scrollTo(x,y)},fade:function(b,c){if(c&&!this.visible){return}clearTimeout(this.fadeTimeout);this.fadeTimeout=null;var d=b?250:500,delay=b?0:300;b=b?'1':'0';this.wrapperStyle[t.style.transitionDuration]=d+'ms';this.fadeTimeout=setTimeout((function(a){this.wrapperStyle.opacity=a;this.visible=+a}).bind(this,b),delay)}};IScroll.utils=t;if(typeof module!='undefined'&&module.exports){module.exports=IScroll}else{o.IScroll=IScroll}})(window,document,Math);

/*!
 * jQuery hashchange event - v1.3
 * http://benalman.com/projects/jquery-hashchange-plugin/
 */
(function($,e,b){var c="hashchange",h=document,f,g=$.event.special,i=h.documentMode,d="on"+c in e&&(i===b||i>7);function a(j){j=j||location.href;return"#"+j.replace(/^[^#]*#?(.*)$/,"$1")}$.fn[c]=function(j){return j?this.bind(c,j):this.trigger(c)};$.fn[c].delay=50;g[c]=$.extend(g[c],{setup:function(){if(d){return false}$(f.start)},teardown:function(){if(d){return false}$(f.stop)}});f=(function(){var j={},p,m=a(),k=function(q){return q},l=k,o=k;j.start=function(){p||n()};j.stop=function(){p&&clearTimeout(p);p=b};function n(){var r=a(),q=o(m);if(r!==m){l(m=r,q);$(e).trigger(c)}else{if(q!==m){location.href=location.href.replace(/#.*/,"")+q}}p=setTimeout(n,$.fn[c].delay)}$.browser.msie&&!d&&(function(){var q,r;j.start=function(){if(!q){r=$.fn[c].src;r=r&&r+a();q=$('<iframe tabindex="-1" title="empty"/>').hide().one("load",function(){r||l(a());n()}).attr("src",r||"javascript:0").insertAfter("body")[0].contentWindow;h.onpropertychange=function(){try{if(event.propertyName==="title"){q.document.title=h.title}}catch(s){}}}};j.stop=k;o=function(){return a(q.location.href)};l=function(v,s){var u=q.document,t=$.fn[c].domain;if(v!==s){u.title=h.title;u.open();t&&u.write('<script>document.domain="'+t+'"<\/script>');u.close();q.location.hash=v}}})();return j})()})(jQuery,this);

/*!
 * jQuery flexText: Auto-height textareas
 * --------------------------------------
 * Requires: jQuery 1.7+
 * Usage example: $('textarea').flexText()
 * Info: https://github.com/alexdunphy/flexible-textareas
 */
(function(b){function a(c){this.$textarea=b(c);this._init()}a.prototype={_init:function(){var c=this;this.$textarea.wrap('<div class="flex-text-wrap" />').before("<pre><span /><br /><br /></pre>");this.$span=this.$textarea.prev().find("span");this.$textarea.on("input propertychange keyup change",function(){c._mirror()});b.valHooks.textarea={get:function(d){return d.value.replace(/\r?\n/g,"\r\n")}};this._mirror()},_mirror:function(){this.$span.text(this.$textarea.val())}};b.fn.flexText=function(){return this.each(function(){if(!b.data(this,"flexText")){b.data(this,"flexText",new a(this))}})}})(jQuery);
 
/*!
 * flib mobile version, matching jquery mobile library
 *
 * @author Gavin<laigw.vip@gmail.com>
 */
(function( $, w, UNDEF ) {
	
	// Trim whitespace characters
	w.String.prototype.trim = function() {
		return this.replace( /(^\s+)|(\s+$)/g, '' );
	};
	w.String.prototype.ltrim = function() {
		return this.replace( /(^\s+)/g, '' );
	};
	w.String.prototype.rtrim = function() {
		return this.replace( /(\s+$)/g, '' );
	};
	w.String.prototype.isUrl = function() {
		return /(http(s?):\/\/)([\w-]+\.)+[\w:]{2,}(\/[\x00-\xff^ ]*)?/i.test(this);
	};
	w.String.prototype.isMobile = function() {
		return /^0?1[3458]\d{9}$/.test(this);
	};
	w.String.prototype.isEmail = function() {
		return /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/.test(this);
	};
	
	// q like
	w.String.prototype.qlike = function(what) {
		var match = false;
		if (this==what) {
			match = true;
		}else if (this.match(F.genQRegex(what))) {
			match = true;
		}
		return match;
	};
	
	// Define FUI class
	var FUI = function(options) {
		this.user  = {};
		this.data  = {};
		this.box   = {};
		this.queue = {};
		this.location = {};
		this.cache = new Array();
		this.cacheCtrl = new Array();
		this.hashData  = new Array();
		this.qsSep     = ',';
		this.hashTag   = '/';
		this.hashReqId = '_hr';
		this.hashUrlGenCallback = null;
		this.hashHist = 'hashhist';
		this.hashHistSize = 10;	// history queue size default to 10
		this.defaultHash = '#'+this.hashTag;
		this.cleanUrl = true;
		this.timer = null;
		this.maxage= 60; // default maxage
		this.container = '#ifr-body';
		this.lang = {
			loading : '数据加载中...',
			posting : '数据保存中...',
			saved   : '数据已保存'
		};
		
		this.toploadingid  = 'toploading';	// top loading box id
		this.toploadingobj = null;			// top loading box dom jQuery object 
		this.topmainboxid  = 'mainMsgBox';	// top main msg box id
		this.topmainboxobj = null;			// top main msg box dom jQuery object
		
		//~ initialize queue
		this.createQueue(this.hashHist, this.hashHistSize);
	};
	FUI.prototype = {
			
		// Cache dealing
		getCache: function(key) {
			var d = this.cache[key];
			var t = this.time();
			if (d==UNDEF || t >= d.expires) {
				d = '';
			}
			return d;
		},
		setCache: function(key, value) {
			this.cache[key] = value;
		},
		clearCache: function(key) {
			this.cacheDirty(key);
		},
		clearCacheAll: function() {
			this.cache = new Array();
			this.cacheCtrl = new Array();
		},
		cacheDirty: function(url) {
			var key = this.genCacheKey(url);
			var cd  = this.getCache(key);
			if (cd) {
				cd.expires = this.time()-3600;
				this.setCache(key, cd);
			}
		},
		genCacheKey: function($str) {
			return hex_md5($str);
		},
		
		// Util functions
		datetime: function() {
			var now = new Date();
			var Y = now.getFullYear();
			var m = now.getMonth()+1;
			var d = now.getDate();
			var H = now.getHours();
			var i = now.getMinutes();
			var s = now.getSeconds();
			if (m<10) m = '0'+m;
			if (d<10) d = '0'+d;
			if (H<10) H = '0'+H;
			if (i<10) i = '0'+i;
			if (s<10) s = '0'+s;
			return ''+Y+'-'+m+'-'+d+' '+H+':'+i+':'+s;
		},
		time: function() {
			var now = new Date();
			return parseInt(now.getTime()/1000);
		},
		setTimer: function(timer) {
			this.timer = timer;
		},
		getTimer: function (timer) {
			return this.timer || null;
		},
		
		// Queue dealing
		createQueue: function(name, size) {
			if (size==UNDEF || size<0 || name==UNDEF || name=='') {
				return false;
			}
			this.queue[name] = {container:[],size:size};
			return this.queue[name];
		},
		inQueue: function(name,value) {
			if (name==UNDEF || name=='') {
				return false;
			}
			if (this.queue[name].container.length>=this.queue[name].size) {
				this.queue[name].container.shift();
			}
			return this.queue[name].container.push(value);
		},
		outQueue: function(name) {
			if (name==UNDEF || name=='') {
				return false;
			}
			var ret = null;
			if (this.queue[name].container.length>0) {
				ret = this.queue[name].container.shift();
			}
			return ret;
		},
		indexQueue: function(name,index) {
			if (name==UNDEF || name=='') {
				return false;
			}
			if (index==UNDEF) {
				index = 0;
			}
			var ret = null;
			if (index>=0) {
				if (this.queue[name].container.length>index) {
					ret = this.queue[name].container[index];
				}
			}
			else {
				var len = this.queue[name].container.length;
				if (Math.abs(index)<=len) {
					index = len+index;
					ret = this.indexQueue(name, index);
				}
			}
			return ret;
		},
		clearQueue: function(name) {
			if (name==UNDEF || name=='') {
				return false;
			}
			this.queue[name].container.length = 0;
			return true;
		},
		getQueueLen: function(name) {
			if (name==UNDEF || name=='') {
				return false;
			}
			return this.queue[name].container.length;
		},
		getQueueHead: function(name) {
			if (name==UNDEF || name=='') {
				return false;
			}
			var ret = null;
			if (this.queue[name].container.length>0) {
				ret = this.queue[name].container[0];
			}
			return ret;
		},
		getQueueEnd: function(name) {
			if (name==UNDEF || name=='') {
				return false;
			}
			var ret = null;
			var len = this.queue[name].container.length;
			if (len>0) {
				ret = this.queue[name].container[len-1];
			}
			return ret;
		},
		
		// The following two method are used to show or hide top loading tips
		ajaxStart: function(type, callback) {
			if (type==UNDEF || (type!='load'&&type!='post')) {
				type = 'load';
			}
			var showtips = this.lang.loading;
			if (type=='post') {
				showtips = this.lang.posting;
				if (!this.topmainboxobj) this.topmainboxobj = $('#'+this.topmainboxid);
				if (typeof callback == 'function') {
					callback(this.topmainboxobj);
				}
				else {
					if (typeof callback == 'string') { //indicating transfer msg, not function
						showtips = callback;
					}
					this.topmainboxobj.find('span').text(showtips).attr('class','msg msg-loading');
					this.topmainboxobj.show();
				}
			}
			else {
				if (!this.toploadingobj) this.toploadingobj = $('#'+this.toploadingid);
				if (typeof callback == 'function') {
					callback(this.toploadingobj);
				}
				else {
					if (typeof callback == 'string') { //indicating transfer msg, not function
						showtips = callback;
					}
					this.toploadingobj.find('span').text(showtips);
					this.toploadingobj.show();
				}
			}
		},
		ajaxComplete: function(type, callback) {
			if (type==UNDEF || (type!='load'&&type!='post')) {
				type = 'load';
			}
			if (type=='post') {
				if (!this.topmainboxobj) this.topmainboxobj = $('#'+this.topmainboxid);
				if (typeof callback == 'function') {
					callback(this.topmainboxobj);
				}
				else {
					var _msg = this.lang.saved;
					if (typeof callback == 'string') { //indicating transfer msg, not function
						_msg = callback;
					}
					this.topmainboxobj.find('span').attr('class','msg').text(_msg);
					var _oThis = this;
					setTimeout(function(){_oThis.topmainboxobj.fadeOut();},3000);
				}
			}
			else {
				if (!this.toploadingobj) this.toploadingobj = $('#'+this.toploadingid);
				if (typeof callback == 'function') {
					callback(this.toploadingobj);
				}
				else {
					this.toploadingobj.hide();
				}
			}
		},
		
		// Wrapper of jquery ajax method
		ajax: function(options) {
			$.ajax(options);
		},
		load: function(url, data, callback) {
			$.load(url, data, callback);
		},
		get: function(url, data, callback) {
			$.get(url, data, callback);
		},
		getScript: function(url, callback) {
			$.getScript(url, callback);
		},
		getJSON: function(url, data, callback, ajax_start_cb, ajax_complete_cb) {
			if (typeof data == 'function') {
				ajax_complete_cb = ajax_start_cb;
				ajax_start_cb = callback;
				callback = data;
				data = {};
			} else {
				data.maxage = data.maxage != UNDEF ? parseInt(data.maxage) : this.maxage;
			}
			var key = this.genCacheKey(url);
			var cd  = this.getCache(key);
			if (cd && data.maxage>0) {
				callback(cd);
			} else {
				var oThis = this;
				if (this.cacheCtrl[key] == UNDEF) {
					this.cacheCtrl[key] = {ajaxing: 0};
				}
				if (this.cacheCtrl[key].ajaxing) {
					return;
				}
				this.cacheCtrl[key].ajaxing = 1;
				this.ajaxStart('load',ajax_start_cb);
				$.getJSON(url, data, function(d){
					d.expires = F.time() + parseInt(d.maxage);
					oThis.setCache(key, d);
					oThis.cacheCtrl[key].ajaxing = 0;
					oThis.ajaxComplete('load',ajax_complete_cb);
					callback(d);
				});
			}
		},
		post: function(url, data, callback, ajax_start_cb, ajax_complete_cb) {
			if (typeof data == 'function') {
				ajax_complete_cb = ajax_start_cb;
				ajax_start_cb = callback;
				callback = data;
				data = {};
			}
			var oThis = this;
			this.ajaxStart('post',ajax_start_cb);
			$.ajax({
				url: url,
				type:'post',
				data: data,
				dataType: 'json',
				success: function(data){
					oThis.ajaxComplete('post',ajax_complete_cb);
					callback(data);
				},
				error: function(xhr, status, error) {
					oThis.ajaxComplete('post',ajax_complete_cb);
				}
			});
		},
		
		// Hash request core
		hashReq: function(hash, data, callback, options) {
			if (typeof data == 'function') {
				options = callback;
				callback = data;
				data = {};
			}
			data.maxage = data.maxage != UNDEF ? parseInt(data.maxage) : this.maxage;
			data[this.hashReqId] = 1;
			
			if (typeof options == 'undefined') {
				options = {};
			}
			options = $.extend({
				changeHash: true,
				forceRefresh: false,
				renderPrepend: '',
				renderAppend: ''
			},options);
			
			var gourl = '';
			if (hash) {
				var showhash = hash;
				var pos = hash.indexOf('#');
				if (pos>=0) {
					showhash = hash.substring(pos);
				}
				else {
					showhash = '#'+this.hashTag+hash;
				}
				this.inQueue(this.hashHist, {hash:showhash,data:data}); //new hash enter history queue
				gourl = this.parseHashUrl(showhash);
				if (options.changeHash) this.setHash(showhash);
			}
			else {
				gourl = w.location.href.replace(/#.*$/g, ''); //right trim the '#xxx' part
			}
			if (options.forceRefresh) {
				this.clearCache(gourl);
			}
			var _realurl = this.genRealUrl(gourl, data);
			this.getJSON(gourl,data,function(d){
				d.body = options.renderPrepend + '<script type="text/javascript">F.location.hashreq=1;F.location.href=\''+_realurl+'\';</script>' + d.body + options.renderAppend; //prepend F.location.href
				callback(d);
			});
			return false;
		},
		// Hash load page
		hashLoad: function(hash, data, default_cb, options) {
			if (typeof data == 'function') {
				options = default_cb;
				default_cb = data;
				data = {};
			}
			if (!hash) hash = w.location.hash;
			if (!hash) hash = this.defaultHash;
			
			if (this.isReqHash(hash)) {
				this.hashReq(hash, data, function(ret){
					var $c = typeof(options.container)=='undefined' ? $(F.container) : (typeof options.container == 'object' ? options.container : $(options.container));
					$c.html(ret.body);
					if (typeof default_cb == 'function') {
						default_cb(ret);
					}
				}, options);
			}
			return false;
		},
		// Hash Reload Page
		hashReload: function(){
			this.clearCacheAll();
			$(window).hashchange();
			return false;
		},
		// Go to hash reference uri
		hashRefer: function(default_hash, force_refresh) {
			if (!default_hash) {
				var _hashHist = this.indexQueue(this.hashHist,-2); //the last second one of the queue
				if (_hashHist) default_hash = _hashHist.hash;
			}
			if (force_refresh && default_hash) {
				this.clearCache(this.parseHashUrl(default_hash));
			}
			this.setHash(default_hash);
			$(window).hashchange();
			return false;
		},
		// Go to hash reference uri
		hashRedirect: function(gohash, force_refresh) {
			if (!gohash) gohash = this.defaultHash;
			var pos = gohash.indexOf('#');
			if (pos < 0) gohash = '#'+gohash;
			if (force_refresh && gohash) {
				this.clearCache(this.parseHashUrl(gohash));
			}
			this.setHash(gohash);
			$(window).hashchange();
			return false;
		},
		// alias of hashRedirect
		hashGo: function(gohash, force_refresh) {
			return this.hashRedirect(gohash, force_refresh);
		},
		// Generate q RegExp
		genQRegex: function(q) {
			var regex = q.replace( /%d/g, '(\\d+)' );
			regex = regex.replace( /%s/g, '([0-9a-zA-Z_-]+)' );
			return new RegExp('^'+regex+'$');		
		},
		// Generate real request url
		genRealUrl: function(url, data) {
			var real_url = url;
			if (data) {
				var _ppart = '';
				for(var _dk in data) {
					_ppart += '&' + _dk + '=' + data[_dk];
				}
				if (''!=_ppart) {
					_ppart = _ppart.substring(1);
					real_url += (url.lastIndexOf('?')<0 ? '?' : '&') + _ppart;
				}
			}
			return real_url;
		},
		// get hash referrer uri
		getHashReferUrl: function(default_hash, force_refresh) {
			var _hashHist = this.indexQueue(this.hashHist,-2); //the last second one of the queue
			if (_hashHist) {
				return this.genRealUrl(this.parseHashUrl(_hashHist.hash), _hashHist.data);
			}
			return '';
		},
		// Check whether is valid request hash
		isReqHash: function(hash) {
			return hash.match(new RegExp('^#'+this.hashTag+'.*'));
		},
		// Parse hash url
		parseHashUrl: function(hash) {
			var hashurl = '';
			var pos = hash.indexOf('#');
			if (pos>=0) {
				hash = hash.substring(pos+2); //begin with like '#~xxx'
			}
			if (this.qsSep != '?') {
				hash = hash.replace(this.qsSep,'?'); //support separated by ','
			}
			var harr    = hash.split('?');
			var hashurl = '/'+harr[0];
			if (typeof this.hashUrlGenCallback == 'function') {
				hashurl = this.hashUrlGenCallback(harr[0]);
			}
			if (harr.length>1 && harr[1].trim()!='') {
				var tarr;
				harr  = harr[1].split('&');
				for(var i=0,j=0; i<harr.length; ++i) {
					tarr = harr[i].split('=');
					if (tarr[0]=='maxage') continue;
					hashurl += (j==0&&this.cleanUrl?'?':'&') + harr[i];
					++j;
				}
			}
			return hashurl;
		},
		// Get location hash
		getHash: function() {
			var hash = w.location.hash;
			return hash ? hash : '';
		},
		// Set location hash
		setHash: function(h) {
			w.location.hash = h;
		},
		
		// Util functions
		locatePoint: function(id, position){
			var aCtrl = null;
			if (typeof id != 'string') {
				aCtrl = id;
			}else{
				aCtrl = document.getElementById(id);
			}
			if (aCtrl.setSelectionRange) {
				setTimeout(function(){aCtrl.setSelectionRange(position, position);aCtrl.focus();}, 0);
			}else if (aCtrl.createTextRange) {
				var textArea=aCtrl;
				var tempText=textArea.createTextRange();
				tempText.collapse(true); 
				//tempText.moveStart('character',-1+position);
				tempText.moveStart('character',position);
				tempText.select();
				setTimeout(function(){textArea.focus();}, 0);
			}
		},
		placeCaretAtEnd: function(el) {
			el.focus();
			if (typeof window.getSelection != "undefined" && typeof document.createRange != "undefined") {
				var range = document.createRange();
				range.selectNodeContents(el);
				range.collapse(false);
				var sel = window.getSelection();
				sel.removeAllRanges();
				sel.addRange(range);
			} else if (typeof document.body.createTextRange != "undefined") {
				var textRange = document.body.createTextRange();
				textRange.moveToElementText(el);
				textRange.collapse(false);
				textRange.select();
			}
		},
		getBrowserWidth: function() {
			return window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
		},
		getBrowserHeight: function() {
			return window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
		},
		
		// Console Log
		log: function($msg) {
			w.console.log($msg);
		}
	};
	
	w.F = w.FUI = new FUI();
	w._gPop	     = null; // global pop reference
	w._gPopHtml	 = null; // global html pop reference
	w._gWaitSucc = 1;	 // 1 second while success
	w._gWaitWarn = 2;	 // 2 second while warning
	w._gWaitFail = 3;	 // 3 seconds while fail
	
})(jQuery, window);