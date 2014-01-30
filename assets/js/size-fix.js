$(function () {
	var $wrap = $('#wrap').css('padding-top', ($('.navbar').height() - 40) + "px");
	$(window).on('resize', function () {
		$wrap.css('padding-top', ($('.navbar').height() - 40) + "px");
	});
});