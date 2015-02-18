$(document).ready(function(){
    $('#studentid').focus();
    var timeout;
    var l;
    var working = false;
    handleresponse = function (msg) {
        console.log(msg);
        l.stop();
        working = false;
        $('#studentid').val('').focus();
        var level='danger';
        var title='Uh-Oh...';
        var description='There may be a problem with your account. Please contact an administrator if this keeps happening.';
        if(msg.code=="checkin"){
            level='success';
            title=msg.name+',';
            description='you are now checked in. Don\'t forget to check out when the meeting is over!';
        }
        if(msg.code=="checkout"){
            level='success';
            title=msg.name+',';
            description='you have checked out. You now have '+msg.hours+' hours.';
        }
        if(msg.code=="exceed"){
            level='warning';
            description='You\'ve been checked in too long! Hours from the last session will not be counted. Input again if you need to be checked in.';
        }
        if(msg.code=="false"){
            description='The entered ID is either too short or too nonexistant.';
        }
        $('#alertbox').html('<div class="alert alert-'+level+'" style="margin-top: -7px;"><strong>'+title+'</strong> '+description+'</div>');
        $('#alertbox').fadeIn(200);
        timeout=setTimeout(function(){$('#alertbox').fadeOut(100);},3000);
    }
    $('#checkin').click(function () {
        if(!working){
            l = Ladda.create(this);
            l.start();
            working = true;
            clearTimeout(timeout);
            $('#alertbox').fadeOut(100);
            var studentid=$('#studentid').val();
            $.ajax({
                type: "GET",
                url: '?p=json&r=checkin&d='+studentid
            }).done(handleresponse).fail(function(){console.log('Something has gone very wrong')});
        }
    });
    $("#studentid").keyup(function(event){
        if(event.keyCode == 13){
            $("#checkin").click();
        }
    });
    var timer = countdown(new Date(2015, 1, 17, 20, 0, 0, 0), function(ts) {
        document.getElementById('clock').innerHTML = ts.toString();
    }, countdown.DAYS | countdown.HOURS | countdown.MINUTES | countdown.SECONDS);
});
