var l;
handleresponse = function (msg) {
    console.log(msg);
    l.stop();
    var level='danger';
    var title='OH SHIT';
    var description='YOU DUN GOOFED, SON';
    $('#alertbox').html('<div id="alert" class="alert alert-'+level+'" style="margin-top: -7px;"><strong>'+title+'</strong> '+description+'</div>');
}
$('#checkin').click(function () {
    l = Ladda.create(this);
    l.start();
    $.ajax({
        type: "GET",
        url: "/",
        data: {
            p: "json",
            r: "checkin",
            d: "11111111"
        }
    }).done(handleresponse);
});