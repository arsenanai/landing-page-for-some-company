/*!
	Autosize 3.0.20
	license: MIT
	http://www.jacklmoore.com/autosize
*/
!function(e,t){if("function"==typeof define&&define.amd)define(["exports","module"],t);else if("undefined"!=typeof exports&&"undefined"!=typeof module)t(exports,module);else{var n={exports:{}};t(n.exports,n),e.autosize=n.exports}}(this,function(e,t){"use strict";function n(e){function t(){var t=window.getComputedStyle(e,null);"vertical"===t.resize?e.style.resize="none":"both"===t.resize&&(e.style.resize="horizontal"),s="content-box"===t.boxSizing?-(parseFloat(t.paddingTop)+parseFloat(t.paddingBottom)):parseFloat(t.borderTopWidth)+parseFloat(t.borderBottomWidth),isNaN(s)&&(s=0),l()}function n(t){var n=e.style.width;e.style.width="0px",e.offsetWidth,e.style.width=n,e.style.overflowY=t}function o(e){for(var t=[];e&&e.parentNode&&e.parentNode instanceof Element;)e.parentNode.scrollTop&&t.push({node:e.parentNode,scrollTop:e.parentNode.scrollTop}),e=e.parentNode;return t}function r(){var t=e.style.height,n=o(e),r=document.documentElement&&document.documentElement.scrollTop;e.style.height="auto";var i=e.scrollHeight+s;return 0===e.scrollHeight?void(e.style.height=t):(e.style.height=i+"px",u=e.clientWidth,n.forEach(function(e){e.node.scrollTop=e.scrollTop}),void(r&&(document.documentElement.scrollTop=r)))}function l(){r();var t=Math.round(parseFloat(e.style.height)),o=window.getComputedStyle(e,null),i=Math.round(parseFloat(o.height));if(i!==t?"visible"!==o.overflowY&&(n("visible"),r(),i=Math.round(parseFloat(window.getComputedStyle(e,null).height))):"hidden"!==o.overflowY&&(n("hidden"),r(),i=Math.round(parseFloat(window.getComputedStyle(e,null).height))),a!==i){a=i;var l=d("autosize:resized");try{e.dispatchEvent(l)}catch(e){}}}if(e&&e.nodeName&&"TEXTAREA"===e.nodeName&&!i.has(e)){var s=null,u=e.clientWidth,a=null,p=function(){e.clientWidth!==u&&l()},c=function(t){window.removeEventListener("resize",p,!1),e.removeEventListener("input",l,!1),e.removeEventListener("keyup",l,!1),e.removeEventListener("autosize:destroy",c,!1),e.removeEventListener("autosize:update",l,!1),Object.keys(t).forEach(function(n){e.style[n]=t[n]}),i.delete(e)}.bind(e,{height:e.style.height,resize:e.style.resize,overflowY:e.style.overflowY,overflowX:e.style.overflowX,wordWrap:e.style.wordWrap});e.addEventListener("autosize:destroy",c,!1),"onpropertychange"in e&&"oninput"in e&&e.addEventListener("keyup",l,!1),window.addEventListener("resize",p,!1),e.addEventListener("input",l,!1),e.addEventListener("autosize:update",l,!1),e.style.overflowX="hidden",e.style.wordWrap="break-word",i.set(e,{destroy:c,update:l}),t()}}function o(e){var t=i.get(e);t&&t.destroy()}function r(e){var t=i.get(e);t&&t.update()}var i="function"==typeof Map?new Map:function(){var e=[],t=[];return{has:function(t){return e.indexOf(t)>-1},get:function(n){return t[e.indexOf(n)]},set:function(n,o){e.indexOf(n)===-1&&(e.push(n),t.push(o))},delete:function(n){var o=e.indexOf(n);o>-1&&(e.splice(o,1),t.splice(o,1))}}}(),d=function(e){return new Event(e,{bubbles:!0})};try{new Event("test")}catch(e){d=function(e){var t=document.createEvent("Event");return t.initEvent(e,!0,!1),t}}var l=null;"undefined"==typeof window||"function"!=typeof window.getComputedStyle?(l=function(e){return e},l.destroy=function(e){return e},l.update=function(e){return e}):(l=function(e,t){return e&&Array.prototype.forEach.call(e.length?e:[e],function(e){return n(e,t)}),e},l.destroy=function(e){return e&&Array.prototype.forEach.call(e.length?e:[e],o),e},l.update=function(e){return e&&Array.prototype.forEach.call(e.length?e:[e],r),e}),t.exports=l});

!function(e){"use strict";var t={firstClass:"header",fullSlideContainer:"full",singleSlideClass:"slide",nextElement:"div",previousClass:null,lastClass:"footer",slideNumbersContainer:"slide-numbers",bodyContainer:"pageWrapper",scrollMode:"featuredScroll",useSlideNumbers:!1,slideNumbersBorderColor:"#fff",slideNumbersColor:"#000",animationType:"slow",callback:!1};e.fn.alton=function(s){function l(t){if("featuredScroll"===t){for(w=p.length-1;w>=0;w-=1)r()&&e(p[w]).height()>O?e(p[w]).css("height",e(p[w]).height()):(e(p[w]).css("height",O),e(p[w]).outerHeight(O));if(N.useSlideNumbers&&!r()){e("."+N.bodyContainer).append('<div id="'+N.slideNumbersContainer+'"></div>'),e("#"+N.slideNumbersContainer).css({height:"100%",position:"fixed",top:0,right:"0px",bottom:"0px",width:"86px","z-index":999}),r()&&e("#"+N.slideNumbersContainer).css({height:"auto","min-height":"100%"}),e("."+N.bodyContainer+" #"+N.slideNumbersContainer).append("<ul></ul>"),e("."+N.bodyContainer+" #"+N.slideNumbersContainer+" ul").css({transform:"translateY(-50%)","-moz-transform":"translateY(-50%)","-ms-transform":"translateY(-50%)","-o-transform":"translateY(-50%)","-webkit-transform":"translateY(-50%)",top:"50%",position:"fixed"});for(var s=0;E>s;)e("."+N.bodyContainer+" #"+N.slideNumbersContainer+" ul").append('<li class="paginate"></ul>'),o()?e(".paginate").css({cursor:"pointer","border-radius":"50%","list-style":"none",background:N.slideNumbersBorderColor,"border-color":N.slideNumbersBorderColor,"border-width":"2px","border-style":"solid",height:"11px",width:"11px",margin:"5px 0"}):e(".paginate").css({cursor:"pointer","border-radius":"50%","list-style":"none",background:N.slideNumbersBorderColor,"border-color":N.slideNumbersBorderColor,"border-width":"2px","border-style":"solid",height:"10px",width:"10px",margin:"5px 0"}),s+=1;g="getElementsByClassName"in document?document.getElementsByClassName("paginate"):document.querySelectorAll(".paginate")}}else e("."+N.firstClass).css("height",O+10),e("."+N.firstClass).hasClass("active")||(e("."+N.firstClass).toggleClass("active"),o()&&e(".paginate.active").css({"margin-left":"-1px","border-color":"#"+N.slideNumbersBorderColor,"border-style":"solid","border-width":"2px",height:"8px",width:"8px"}))}function o(){var e=window.navigator.userAgent,t=e.indexOf("MSIE ");return t>0||navigator.userAgent.match(/Trident.*rv\:11\./)?!0:!1}function r(){return navigator.userAgent.match(/(iPhone|iPod|iPad|Android|BlackBerry|BB10|Windows Phone|Tizen|Bada)/)}function n(t,s){s&&e(g[e(t).parent().children().index(t)]).hasClass("active")?(e(g[e(t).parent().children().index(t)]).toggleClass("active"),e(g[e(t).parent().children().index(t)]).css("background",N.slideNumbersBorderColor)):e(g[e(t).parent().children().index(t)]).hasClass("active")||(e(g[e(t).parent().children().index(t)]).toggleClass("active"),e(g[e(t).parent().children().index(t)]).css("background",N.slideNumbersColor))}function i(t){N.useSlideNumbers&&(t?e("#"+N.slideNumbersContainer).fadeIn():e("#"+N.slideNumbersContainer).fadeOut())}function a(t){var s=document.getElementsByClassName(y);e(document).scrollTo(e(s[t])),T=s[t],D=e(T).prev().hasClass(y)?e(T).prev():e("."+N.firstClass),k=e(T).next().hasClass(y)?e(T).next():e("."+N.lastClass),n(e("#"+N.slideNumbersContainer+" li.active"),!0),n(s[t],!1),"function"==typeof N.callback&&N.callback()}function d(){(e(k).length>0||e(D).length>0)&&(e(window).scrollTop()>=e("."+y+":first").offset().top&&e(window).scrollTop()+e(window).outerHeight()!==e(document).outerHeight()?(N.useSlideNumbers&&n(T,!0),e("."+y).each(function(){v=e(this).offset().top,v<=e(window).scrollTop()&&(D=e(this).prev().hasClass(y)?e(this).prev():e("."+N.firstClass),T=e(this),k=T.next().hasClass(y)?e(this).next():e("."+N.lastClass),M=!1)}),N.useSlideNumbers&&n(T,!1),e(document).scrollTo(T)):(N.useSlideNumbers&&(B!==e("."+y+":last-child")[0]?i(!1):(i(!0),n(T,!1))),e(document).scrollTo(T)))}function u(e){void 0!==e&&(e=e||window.event,e.preventDefault&&(e.stopPropagation(),e.returnValue=!1))}function c(e){return u(e)}function m(){return window.pageYOffset||P.scrollTop}function f(t){return b=e("body,html").is(":animated")||e("body").is(":animated")||e("html").is(":animated"),"mousewheel"==t.type||"DOMMouseScroll"==t.type?(clearTimeout(e.data(this,"scrollTimer")),e(document).unbind({scroll:f}),e.data(this,"scrollTimer",setTimeout(function(){Y=!1,e(document).bind({scroll:f})},35)),t.originalEvent.detail>1&&!Y||t.originalEvent.wheelDelta<-1&&!Y?(A+=1,e(document).moveDown(),Y=!0,u()):(t.originalEvent.detail<-1&&!Y||t.originalEvent.wheelDelta>1&&!Y)&&(H+=1,e(document).moveUp(),Y=!0,u())):"scroll"==t.type&&(u(),Y=!1,clearTimeout(e.data(this,"scrollTimer")),e.data(this,"scrollTimer",setTimeout(function(){d()},500))),!1}function h(t){if(C=m(),t.originalEvent.detail>0||t.originalEvent.wheelDelta<0){if(!(e(k).offset().top>0&&C<e("."+N.firstClass).outerHeight()))return!0;if(e("."+N.firstClass).hasClass("active"))return e("."+N.firstClass).toggleClass("active"),e(document).scrollTo(k),D=T,T=k,c(t);if(!e("html, body").is(":animated"))return!0}else if(!e("."+N.firstClass).hasClass("active")&&e(window).scrollTop()<=e("."+N.firstClass).outerHeight())e("."+N.firstClass).toggleClass("active"),e(document).scrollTo(D),k=T,T=D;else if(!e("html, body").is(":animated"))return!0;return!1}var p,b,g,C,v,w,N=e.extend(!0,{},t,s),y=N.singleSlideClass,S=!1,x=!1,T=e("."+N.firstClass),k=e("."+y+":first"),D=null,B=e("."+N.lastClass),E=e("."+N.fullSlideContainer).children().length,M=!0,H=0,A=0,O=e(window).outerHeight(),Y=!1,P=window.document.documentElement;p="getElementsByClassName"in document?document.getElementsByClassName(y):document.querySelectorAll("."+y),T.length||(T=k,k=T.next()),B.length||(B=e("."+y+":last")),B=B[0],"headerScroll"===N.scrollMode&&(T=e("."+N.firstClass),k=e("."+N.bodyContainer+":first")),e.fn.moveDown=function(){C=m(),C>=0&&C<=e(T).scrollTop()&&M===!0?(D=T,T=k,k=T.next(),N.useSlideNumbers&&(B===e("."+y+":last-child")[0]?(n(D,!0),n(T,!1)):(n(T,!1),i(!0))),M=!1,e(document).scrollTo(T)):!b&&k&&e(T).offset().top<C+1&&(k.hasClass(y)?(D=T,T=k,k=e(T).next(),N.useSlideNumbers&&(n(D,!0),n(T,!1)),e(document).scrollTo(T)):B!==e("."+y+":last-child")[0]&&(D=e("."+y+":last-child")[0],T=B,k=null,e(window).scrollTop()+O+10>=e(document).outerHeight()-e(B).outerHeight()&&N.useSlideNumbers&&(n(D,!1),i(!1)),e(document).scrollTo(T),e.event.trigger({type:"lastSlide",slide:B,time:new Date}))),"function"==typeof N.callback&&N.callback()},e.fn.moveUp=function(){C=m(),e("."+N.fullSlideContainer).offset().top+1>C&&D&&C>0?(e(T).offset().top>=C?(T=e("."+N.firstClass),D=null,k=e("."+y),N.useSlideNumbers&&(i(!1),n(k,!1)),M=!0):(T=D,D=null,k=e("."+y),N.useSlideNumbers&&(n(T,!0),n(D,!0))),e(document).scrollTo(T)):!b&&e("."+N.fullSlideContainer).offset().top<C&&(T=D,D=e(T).prev(),k=e(T).next(),N.useSlideNumbers&&(n(T,!1),n(k,!0),i(!0)),e(document).scrollTo(T)),x=!0,S=!1,"function"==typeof N.callback&&N.callback()},e.fn.scrollTo=function(t){t!==B?e("body,html").stop(!0,!0).animate({scrollTop:e(t).offset().top},{duration:375}):e("body,html").stop(!0,!0).animate({scrollTop:e(document).outerHeight()-O},{duration:375})},e(document).ready(function(){if(l(N.scrollMode),"featuredScroll"!==N.scrollMode||r()||d(),"featuredScroll"===N.scrollMode&&!r()){e("#"+N.slideNumbersContainer+" li").on("click",function(){a(e(this).parent().children().index(this))});var t=[];onkeydown=onkeyup=function(s){switch(s=s||event,t[s.which]="keyup"==s.type,s.which){case 40:s.preventDefault(),e(document).moveDown(s);break;case 32:s.preventDefault(),t[16]===!0?e(document).moveUp(s):e(document).moveDown(s);break;case 33:e(document).moveDown(s),s.preventDefault();break;case 34:s.preventDefault(),e(document).moveUp(s);break;case 38:s.preventDefault(),e(document).moveUp(s);break;case 36:s.preventDefault(),0!==e("."+N.firstClass).length?(N.useSlideNumbers&&n(T,!0),D=null,T="."+N.firstClass,k=e("."+y+":first"),N.useSlideNumbers&&n(T,!1),e(document).scrollTo("."+N.firstClass)):(N.useSlideNumbers&&n(T,!0),D=null,T=e(".pane:first"),k=T.next(),e(document).scrollTo(e(".pane")[0]),N.useSlideNumbers&&n(T,!1));break;case 35:0!==e("."+N.firstClass).length?(N.useSlideNumbers&&(n(e(T),!0),n(e(".pane:last"),!1)),D=e(".pane:last")):(N.useSlideNumbers&&(n(e(T),!0),n(e(B),!1)),D=e(B).prev()),T=e(B),k=null,s.preventDefault(),e(document).scrollTo(B)}}}r()||("featuredScroll"===N.scrollMode?e(document).bind({"DOMMouseScroll mousewheel scroll":f}):"headerScroll"===N.scrollMode&&e(document).bind({"DOMMouseScroll mousewheel":h}),e(window).resize(function(){e(p).each(function(){e(this).css("height",e(window).outerHeight()),e(this).outerHeight(e(window).outerHeight())})}))})}}(jQuery);

/*
    jQuery Masked Input Plugin
    Copyright (c) 2007 - 2015 Josh Bush (digitalbush.com)
    Licensed under the MIT license (http://digitalbush.com/projects/masked-input-plugin/#license)
    Version: 1.4.1
*/
!function(a){"function"==typeof define&&define.amd?define(["jquery"],a):a("object"==typeof exports?require("jquery"):jQuery)}(function(a){var b,c=navigator.userAgent,d=/iphone/i.test(c),e=/chrome/i.test(c),f=/android/i.test(c);a.mask={definitions:{9:"[0-9]",a:"[A-Za-z]","*":"[A-Za-z0-9]"},autoclear:!0,dataName:"rawMaskFn",placeholder:"_"},a.fn.extend({caret:function(a,b){var c;if(0!==this.length&&!this.is(":hidden"))return"number"==typeof a?(b="number"==typeof b?b:a,this.each(function(){this.setSelectionRange?this.setSelectionRange(a,b):this.createTextRange&&(c=this.createTextRange(),c.collapse(!0),c.moveEnd("character",b),c.moveStart("character",a),c.select())})):(this[0].setSelectionRange?(a=this[0].selectionStart,b=this[0].selectionEnd):document.selection&&document.selection.createRange&&(c=document.selection.createRange(),a=0-c.duplicate().moveStart("character",-1e5),b=a+c.text.length),{begin:a,end:b})},unmask:function(){return this.trigger("unmask")},mask:function(c,g){var h,i,j,k,l,m,n,o;if(!c&&this.length>0){h=a(this[0]);var p=h.data(a.mask.dataName);return p?p():void 0}return g=a.extend({autoclear:a.mask.autoclear,placeholder:a.mask.placeholder,completed:null},g),i=a.mask.definitions,j=[],k=n=c.length,l=null,a.each(c.split(""),function(a,b){"?"==b?(n--,k=a):i[b]?(j.push(new RegExp(i[b])),null===l&&(l=j.length-1),k>a&&(m=j.length-1)):j.push(null)}),this.trigger("unmask").each(function(){function h(){if(g.completed){for(var a=l;m>=a;a++)if(j[a]&&C[a]===p(a))return;g.completed.call(B)}}function p(a){return g.placeholder.charAt(a<g.placeholder.length?a:0)}function q(a){for(;++a<n&&!j[a];);return a}function r(a){for(;--a>=0&&!j[a];);return a}function s(a,b){var c,d;if(!(0>a)){for(c=a,d=q(b);n>c;c++)if(j[c]){if(!(n>d&&j[c].test(C[d])))break;C[c]=C[d],C[d]=p(d),d=q(d)}z(),B.caret(Math.max(l,a))}}function t(a){var b,c,d,e;for(b=a,c=p(a);n>b;b++)if(j[b]){if(d=q(b),e=C[b],C[b]=c,!(n>d&&j[d].test(e)))break;c=e}}function u(){var a=B.val(),b=B.caret();if(o&&o.length&&o.length>a.length){for(A(!0);b.begin>0&&!j[b.begin-1];)b.begin--;if(0===b.begin)for(;b.begin<l&&!j[b.begin];)b.begin++;B.caret(b.begin,b.begin)}else{for(A(!0);b.begin<n&&!j[b.begin];)b.begin++;B.caret(b.begin,b.begin)}h()}function v(){A(),B.val()!=E&&B.change()}function w(a){if(!B.prop("readonly")){var b,c,e,f=a.which||a.keyCode;o=B.val(),8===f||46===f||d&&127===f?(b=B.caret(),c=b.begin,e=b.end,e-c===0&&(c=46!==f?r(c):e=q(c-1),e=46===f?q(e):e),y(c,e),s(c,e-1),a.preventDefault()):13===f?v.call(this,a):27===f&&(B.val(E),B.caret(0,A()),a.preventDefault())}}function x(b){if(!B.prop("readonly")){var c,d,e,g=b.which||b.keyCode,i=B.caret();if(!(b.ctrlKey||b.altKey||b.metaKey||32>g)&&g&&13!==g){if(i.end-i.begin!==0&&(y(i.begin,i.end),s(i.begin,i.end-1)),c=q(i.begin-1),n>c&&(d=String.fromCharCode(g),j[c].test(d))){if(t(c),C[c]=d,z(),e=q(c),f){var k=function(){a.proxy(a.fn.caret,B,e)()};setTimeout(k,0)}else B.caret(e);i.begin<=m&&h()}b.preventDefault()}}}function y(a,b){var c;for(c=a;b>c&&n>c;c++)j[c]&&(C[c]=p(c))}function z(){B.val(C.join(""))}function A(a){var b,c,d,e=B.val(),f=-1;for(b=0,d=0;n>b;b++)if(j[b]){for(C[b]=p(b);d++<e.length;)if(c=e.charAt(d-1),j[b].test(c)){C[b]=c,f=b;break}if(d>e.length){y(b+1,n);break}}else C[b]===e.charAt(d)&&d++,k>b&&(f=b);return a?z():k>f+1?g.autoclear||C.join("")===D?(B.val()&&B.val(""),y(0,n)):z():(z(),B.val(B.val().substring(0,f+1))),k?b:l}var B=a(this),C=a.map(c.split(""),function(a,b){return"?"!=a?i[a]?p(b):a:void 0}),D=C.join(""),E=B.val();B.data(a.mask.dataName,function(){return a.map(C,function(a,b){return j[b]&&a!=p(b)?a:null}).join("")}),B.one("unmask",function(){B.off(".mask").removeData(a.mask.dataName)}).on("focus.mask",function(){if(!B.prop("readonly")){clearTimeout(b);var a;E=B.val(),a=A(),b=setTimeout(function(){B.get(0)===document.activeElement&&(z(),a==c.replace("?","").length?B.caret(0,a):B.caret(a))},10)}}).on("blur.mask",v).on("keydown.mask",w).on("keypress.mask",x).on("input.mask paste.mask",function(){B.prop("readonly")||setTimeout(function(){var a=A(!0);B.caret(a),h()},0)}),e&&f&&B.off("input.mask").on("input.mask",u),A()})}})});

function alton() {
  $(document).alton({
      firstClass : 'hero-slider',
      bodyContainer: 'page',
      scrollMode: 'headerScroll'
  })
}

$(function(){
  alton()
  $(window).resize(function() {
    alton()
  })
  $("#phone_input").mask("+7 (799) 9999999")
  $('#name_input').on('input propertychange', function (e) {
    $('#name_field').removeClass('error')
  })
  $('#email_input').on('input propertychange', function (e) {
    $('#email_field').removeClass('error')
  })
  $('#msg_input').on('input propertychange', function (e) {
    $('#msg_field').removeClass('error')
  })
})

$(function(){

  function $(x)   { return document.getElementById(x) || {addEventListener:function(){}} }
  function $$(x, el)  { return Array.prototype.slice.call((el||document).querySelectorAll(x)) }
  function on(x)  { return document.body.classList.add(x) }
  function off(x) { return document.body.classList.remove(x) }
  function ifel(x) { return document.getElementById(x) }
  var HEADER_STICKY_POINT = 95

  setTimeout(function(){
    on('loaded')
  }, 500)

  var PAGE_SCROLLED = false

  $('open_mobile_menu').addEventListener('click', function () {
    on('mobile-menu-active')
  })

  $('close_mobile_menu').addEventListener('click', function () {
    off('mobile-menu-active')
  })

  $('open_contact_form').addEventListener('click', function () {
    on('contact-form-active')
  })

  $('open_contact_form_link').addEventListener('click', function (e) {
    e.preventDefault()
    on('contact-form-active')
  })

  $('close_contact_form').addEventListener('click', function () {
    off('contact-form-active')
  })

  $('main-slider-scroll').addEventListener('click', function () {
    jQuery(document).scrollTo('.page')
  })

  var prevOffset = 0
  var offset = window.pageYOffset || document.documentElement.scrollTop
  if (offset > HEADER_STICKY_POINT) {
    on('site-header-invisible')
  }
  document.addEventListener('scroll', function (e) {
    if (PAGE_SCROLLED) {
      e.preventDefault()
      return
    }
    var offset = window.pageYOffset || document.documentElement.scrollTop
    if ((offset < prevOffset || offset < HEADER_STICKY_POINT) && !PAGE_SCROLLED) {
      off('site-header-invisible')
    } else {
      on('site-header-invisible')
    }
    prevOffset = offset
  })

  function refillSlider($slider, urls, currentPos) {
    [urls[urls.length-1]].concat(urls, urls, urls, urls).slice(0,5).forEach(function (url, id) {
      var img = document.createElement("img")
      var slide = $slider.querySelector('.slide' + id)
      img.setAttribute('src', url)
      img.setAttribute('alt', 'Slide ' + id)
      slide.innerHTML = ''
      slide.appendChild(img)
    })
  }

  function updateSliderInfo($slider, urls, currentPos) {
    $slider.querySelector('.count-curr').innerText = currentPos
    $slider.querySelector('.count-all').innerText = urls.length
  }

  $$('.slider').forEach(function($slider) {
    var urlsList = $slider.querySelector('.images-list').innerText.trim()
      .split(/ +|\n/)
      .filter(function(x) {return x.length})
    var imagesCount = urlsList.length
    var currentImgPos = 1
    refillSlider($slider, urlsList, currentImgPos)
    updateSliderInfo($slider, urlsList, currentImgPos)

    var SLIDER_LOCKED = false;
    ['next-slide', 'prev-slide'].forEach(function (className, actionId) {
      $slider.querySelector('.'+className).addEventListener('click', function () {
        if (SLIDER_LOCKED) return
        SLIDER_LOCKED = true
        $slider.classList.add(className)
        switch (actionId) {
          case 0:
            currentImgPos++
            if (currentImgPos > urlsList.length) {
              currentImgPos = 1
            }
            urlsList.push(urlsList.shift())
            break
          case 1:
            currentImgPos--
            if (currentImgPos < 1) {
              currentImgPos = urlsList.length
            }
            urlsList.unshift(urlsList.pop())
            break
        }
        updateSliderInfo($slider, urlsList, currentImgPos)
        var TRANSITIONS_COUNT = 3
        $slider.querySelector('.slide4').addEventListener('transitionend', function () {
          if (--TRANSITIONS_COUNT == 0) {
            refillSlider($slider, urlsList, currentImgPos)
            $slider.classList.remove(className)
            SLIDER_LOCKED = false
          }
        })
      })
    })
  })

  $('press-main-tab-link').addEventListener('click', function (e) {
    e.preventDefault()
    $('press-smi-tab-link').classList.remove('active')
    $('press-main-tab-link').classList.add('active')
    off('press-smi-active')
    on('press-main-active')
  })
  $('press-smi-tab-link').addEventListener('click', function (e) {
    e.preventDefault()
    $('press-smi-tab-link').classList.add('active')
    $('press-main-tab-link').classList.remove('active')
    off('press-main-active')
    on('press-smi-active')
  })

  var coords = [51.1267, 71.43466]
  if (ifel('map-box')) {
    var map = L.map('map-box', {
      zoomControl: false,
      scrollWheelZoom: false
    }).setView(coords, 15)

    L.tileLayer('http://a.basemaps.cartocdn.com/light_all/{z}/{x}/{y}.png', {
      minZoom: 13,
      maxZoom: 17,
    }).addTo(map)

    var pinIco = L.icon({
      iconUrl: '/static/pin.png',
      iconSize:     [51, 61],
      iconAnchor:   [25, 61]
    })

    L.marker(coords, {icon: pinIco}).addTo(map)
  }

  /*function validate(name, phone, email, msg) {
    var errors = {}
    var values = {}
    var invalid = false
    values.name = name.trim()
    values.phone = phone.trim()
    values.email = email.trim()
    values.msg = msg.trim()
    if (!values.name) {
      errors.name = 'EMPTY_NAME'
      invalid = true
    } else if (values.name.split(' ').length == 1) {
      errors.name = 'INCOMPLITE_NAME'
      invalid = true
    }
    if (!values.email) {
      errors.email = 'EMPTY_EMAIL'
      invalid = true
    } else if (!/^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i.test(values.email)) {
      errors.email = 'INVALID_EMAIL'
      invalid = true
    }
    if (!values.msg) {
      errors.msg = 'EMPTY_MSG'
      invalid = true
    } else if (msg.length < 8) {
      errors.msg = 'INVALID_MSG'
      invalid = true
    }
    return {
      errors: errors,
      values: values,
      invalid: invalid
    }
  }

  function showFormErrors(errors) {
    if (errors.name) {
      $('name_field').classList.add('error')
      jQuery('#name_field .error_text').text(errors.name)
    }
    if (errors.email) {
      $('email_field').classList.add('error')
      jQuery('#email_field .error_text').text(errors.email)
    }
    if (errors.msg) {
      $('msg_field').classList.add('error')
      jQuery('#msg_field .error_text').text(errors.msg)
    }
  }

  $('contact_form_base').addEventListener('submit', function (e) {
    e.preventDefault()
    var name = $('name_input').value
    var phone = $('phone_input').value
    var email = $('email_input').value
    var msg = $('msg_input').value

    var validator = validate(name, phone, email, msg)
    if (validator.invalid) {
      showFormErrors(validator.errors)
    } else {
      $('form-container').classList.add('hidden')
      $('done-container').classList.add('visible')
    }
  })*/

  if (ifel('msg_input')) {
    autosize($('msg_input'))
  }

  halkaBox.run("certificate")

  var CURRENT_MAIN_SLIDE = 1
  var MAIN_NUM_SLIDES = 3

  var CURRENT_CITE_SLIDE = 1
  var CITE_NUM_SLIDES = 3
  function hideSlides () {
    $('main-slider').classList.remove('slide1-active')
    $('main-slider').classList.remove('slide2-active')
    $('main-slider').classList.remove('slide3-active')
  }
  function hideCiteSlides () {
    $('blockquotes').classList.remove('slide1-active')
    $('blockquotes').classList.remove('slide2-active')
    $('blockquotes').classList.remove('slide3-active')
  }
  if (ifel('main-slider')) {
    var $nav = $('main-slider').querySelector('.nav')
    var $citeNav = $('blockquotes').querySelector('.nav')
    $nav.querySelector('.arrow-left').addEventListener('click', function (e) {
      e.preventDefault()
      CURRENT_MAIN_SLIDE--
      if (CURRENT_MAIN_SLIDE < 1) CURRENT_MAIN_SLIDE = MAIN_NUM_SLIDES
      hideSlides()
      $('main-slider').classList.add('slide'+CURRENT_MAIN_SLIDE+'-active')
    })
    $nav.querySelector('.arrow-right').addEventListener('click', function (e) {
      e.preventDefault()
      CURRENT_MAIN_SLIDE++
      if (CURRENT_MAIN_SLIDE > MAIN_NUM_SLIDES) CURRENT_MAIN_SLIDE = 1
      hideSlides()
      $('main-slider').classList.add('slide'+CURRENT_MAIN_SLIDE+'-active')
    })
    $$('li', $nav).forEach(function ($dot, i) {
      $dot.addEventListener('click', function (e) {
        e.preventDefault()
        hideSlides()
        CURRENT_MAIN_SLIDE = i + 1
        $('main-slider').classList.add('slide'+CURRENT_MAIN_SLIDE+'-active')
      })
    })
    $citeNav.querySelector('.arrow-left').addEventListener('click', function (e) {
      e.preventDefault()
      CURRENT_CITE_SLIDE--
      if (CURRENT_CITE_SLIDE < 1) CURRENT_CITE_SLIDE = CITE_NUM_SLIDES
      hideCiteSlides()
      $('blockquotes').classList.add('slide'+CURRENT_CITE_SLIDE+'-active')
    })
    $citeNav.querySelector('.arrow-right').addEventListener('click', function (e) {
      e.preventDefault()
      CURRENT_CITE_SLIDE++
      if (CURRENT_CITE_SLIDE > CITE_NUM_SLIDES) CURRENT_CITE_SLIDE = 1
      hideCiteSlides()
      $('blockquotes').classList.add('slide'+CURRENT_CITE_SLIDE+'-active')
    })
    $$('li', $citeNav).forEach(function ($dot, i) {
      $dot.addEventListener('click', function (e) {
        e.preventDefault()
        hideCiteSlides()
        CURRENT_CITE_SLIDE = i + 1
        $('blockquotes').classList.add('slide'+CURRENT_CITE_SLIDE+'-active')
      })
    })

    var controller = new ScrollMagic.Controller()
    new ScrollMagic.Scene({triggerElement: "#blockquotes"})
      .setClassToggle("#blockquotes", "appear")
      .addTo(controller)
    new ScrollMagic.Scene({triggerElement: "#section2"})
      .setClassToggle("#section2", "appear")
      .addTo(controller)
    new ScrollMagic.Scene({triggerElement: "#section3"})
      .setClassToggle("#section3", "appear")
      .addTo(controller)
    new ScrollMagic.Scene({triggerElement: "#section4"})
      .setClassToggle("#section4", "appear")
      .addTo(controller)
    new ScrollMagic.Scene({triggerElement: "#section5"})
      .setClassToggle("#section5", "appear")
      .addTo(controller)
    new ScrollMagic.Scene({triggerElement: "#section6"})
      .setClassToggle("#section6", "appear")
      .addTo(controller)
  }
})
