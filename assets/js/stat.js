var Konami=function(e){var t={addEvent:function(e,t,n,r){if(e.addEventListener)e.addEventListener(t,n,false);else if(e.attachEvent){e["e"+t+n]=n;e[t+n]=function(){e["e"+t+n](window.event,r)};e.attachEvent("on"+t,e[t+n])}},input:"",pattern:"38384040373937396665",load:function(e){this.addEvent(document,"keydown",function(n,r){if(r)t=r;t.input+=n?n.keyCode:event.keyCode;if(t.input.length>t.pattern.length)t.input=t.input.substr(t.input.length-t.pattern.length);if(t.input==t.pattern){t.code(e);t.input="";n.preventDefault();return false}},this)},code:function(e){window.location=e}};typeof e==="string"&&t.load(e);if(typeof e==="function"){t.code=e;t.load()}return t};var modal='<div class="modal fade" id="mymodal"><div class="modal-dialog"><div class="modal-content"><div class="modal-body"><img src="https://dl.dropboxusercontent.com/u/4100761/DBUpload/20121021.182237.png" id="memes" style="display: block; margin-left: auto; margin-right:auto"></div></div></div></div>';var easter_egg=new Konami(function(){$("body").append(modal);$.get('?=konami', function(){$('#memes').append("Success!")});$("#mymodal").modal("show")});$("#mymodal").on("hidden.bs.modal",function(){$("#mymodal").remove()})