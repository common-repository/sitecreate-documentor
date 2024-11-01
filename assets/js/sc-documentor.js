/*! @source http://purl.eligrey.com/github/FileSaver.js/blob/master/FileSaver.js */
var saveAs=saveAs||function(e){"use strict";if(typeof e==="undefined"||typeof navigator!=="undefined"&&/MSIE [1-9]\./.test(navigator.userAgent)){return}var t=e.document,n=function(){return e.URL||e.webkitURL||e},r=t.createElementNS("http://www.w3.org/1999/xhtml","a"),o="download"in r,a=function(e){var t=new MouseEvent("click");e.dispatchEvent(t)},i=/constructor/i.test(e.HTMLElement)||e.safari,f=/CriOS\/[\d]+/.test(navigator.userAgent),u=function(t){(e.setImmediate||e.setTimeout)(function(){throw t},0)},s="application/octet-stream",d=1e3*40,c=function(e){var t=function(){if(typeof e==="string"){n().revokeObjectURL(e)}else{e.remove()}};setTimeout(t,d)},l=function(e,t,n){t=[].concat(t);var r=t.length;while(r--){var o=e["on"+t[r]];if(typeof o==="function"){try{o.call(e,n||e)}catch(a){u(a)}}}},p=function(e){if(/^\s*(?:text\/\S*|application\/xml|\S*\/\S*\+xml)\s*;.*charset\s*=\s*utf-8/i.test(e.type)){return new Blob([String.fromCharCode(65279),e],{type:e.type})}return e},v=function(t,u,d){if(!d){t=p(t)}var v=this,w=t.type,m=w===s,y,h=function(){l(v,"writestart progress write writeend".split(" "))},S=function(){if((f||m&&i)&&e.FileReader){var r=new FileReader;r.onloadend=function(){var t=f?r.result:r.result.replace(/^data:[^;]*;/,"data:attachment/file;");var n=e.open(t,"_blank");if(!n)e.location.href=t;t=undefined;v.readyState=v.DONE;h()};r.readAsDataURL(t);v.readyState=v.INIT;return}if(!y){y=n().createObjectURL(t)}if(m){e.location.href=y}else{var o=e.open(y,"_blank");if(!o){e.location.href=y}}v.readyState=v.DONE;h();c(y)};v.readyState=v.INIT;if(o){y=n().createObjectURL(t);setTimeout(function(){r.href=y;r.download=u;a(r);h();c(y);v.readyState=v.DONE});return}S()},w=v.prototype,m=function(e,t,n){return new v(e,t||e.name||"download",n)};if(typeof navigator!=="undefined"&&navigator.msSaveOrOpenBlob){return function(e,t,n){t=t||e.name||"download";if(!n){e=p(e)}return navigator.msSaveOrOpenBlob(e,t)}}w.abort=function(){};w.readyState=w.INIT=0;w.WRITING=1;w.DONE=2;w.error=w.onwritestart=w.onprogress=w.onwrite=w.onabort=w.onerror=w.onwriteend=null;return m}(typeof self!=="undefined"&&self||typeof window!=="undefined"&&window||this.content);if(typeof module!=="undefined"&&module.exports){module.exports.saveAs=saveAs}else if(typeof define!=="undefined"&&define!==null&&define.amd!==null){define("FileSaver.js",function(){return saveAs})}


jQuery(window).load(function() {

	var contentArea = jQuery('#sc-documentor').attr('data-content-area-selector');
	var contentHeadings = jQuery('#sc-documentor').attr('data-headings-selector');
	jQuery(contentArea).find(contentHeadings).each(function(i) {
	    var current = jQuery(this);
	    current.attr("id", "title" + i).attr("data-scroll-id", "title" + i);
	    jQuery("#sc-documentor-toc").append("<a id='link" + i + "' href='#title" +
	        i + "' title='" + current.prop("tagName") + "'>" + 
	        current.html() + "</a>");
	});

	jQuery('body').on('click', '#sc-documentor-toc a', function(event) {
        event.preventDefault();
        console.log(this.hash);
		var scrollOffset = jQuery('#sc-documentor').attr('data-scroll-offset'); 
		var target = jQuery(this.hash);
		jQuery('html, body').animate({
		  scrollTop: target.offset().top - scrollOffset
		}, 400);
  	});

	jQuery('body').on('click', '#sc-documentor-build-docs', function() {
		var url = documentor_data.generator_dir;
		var content_data = jQuery('#sc-documentor').attr('data-content-area-selector');
		var content_title_area = jQuery('#sc-documentor').attr('data-download-title');
		var content_title = jQuery(content_title_area).text();
		var content = jQuery(content_data).html();
		var content_before = '<!DOCTYPE html><html><head><title>Documentation</title><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" /><style type="text/css">#content h1 { margin-top: 60px; }</style></head><body>';
		var content_header = '<div id="header" style="background-color: #f5f5f5;"><div class="container text-sm-center text-center" style="padding-top: 80px; padding-bottom: 80px;"><h1>'+content_title+'</h1></div></div><div id="content" class="container" style="padding-top: 80px; padding-bottom: 80px;">';
		var content_after = '</div><div id="footer" class="text-center text-sm-center" style="padding-bottom: 80px;"><h5>Documentation by <a href="https://www.sitecreate.io/?ref=docs">SiteCreate - Quality WordPress Themes</a></h5></div></body></html>';
		var final_content = content_before + content_header + content + content_after;
		var blob = new Blob([final_content], {type: "text/plain;charset=utf-8"});
		saveAs(blob, 'documentation.html');

    });

});
