$(window).load(function(){
    var l;
    handleresponse = function (msg) {
        console.log(msg);
        l.stop();
        var level='danger';
        var title='OH NO!';
        var description='YOU DUN GOOFED, SON';
        $('#alertbox').html('<div id="alert" class="alert alert-'+level+'" style="margin-top: -7px;"><strong>'+title+'</strong> '+description+'</div>');
    }
    $('#checkin').click(function () {
        l = Ladda.create(this);
        l.start();
        $.ajax({
            type: "GET",
            url: "/repositories/RodneyDB/json",
            data: {
                p: "json",
                r: "checkin",
                d: "11111111"
            }
        }).done(handleresponse).fail(function(){console.log('Something has gone very wrong')});
    });
});