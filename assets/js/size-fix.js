$( document ).ready(function() {
	$('#wrap').css('padding-top', ($('.navbar').height() - 40) + "px")
});

$(window).resize(function (e){
	$('#wrap').css('padding-top', ($('.navbar').height() - 40) + "px")
});