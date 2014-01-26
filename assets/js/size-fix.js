$( document ).ready(function() {
	$('#wrap').css('padding-top', ($('.navbar').height() - 40) + "px")
});

$(window).resize(function (e){
	$('#wrap').css('padding-top', ($('.navbar').height() - 40) + "px")
});

/* =========================== *\
|  Nothing to do with size fix  |
|  loads background after page  |
\* =========================== */ 
$(function () {
	$('body').css('background', 'url(assets/img/wallpaper2.jpg)');
});