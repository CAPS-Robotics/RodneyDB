var oldVal = "";
var newVal = "";
var thisObj = null;
var timeout;
var l;
var working = false;

function btnConfirm(btnThis){
    if(!working){
        l = Ladda.create(btnThis);
        l.start();
        working = true;
        clearTimeout(timeout);
        $('#alertbox').fadeOut(100);

        $.ajax({
            type: "GET",
            url: '?p=json&r=edit2&d='+(thisObj.parent().parent().attr('id'))+'&f='+(thisObj.parent().index())+'&n='+newVal
        }).done(handleresponse).fail(function(){console.log('Something has gone very wrong');btnDeny();});
    }
}

function btnDeny(){
    if(thisObj != null){
        thisObj.popover('destroy');
        thisObj.html(oldVal);
        thisObj = null;
    }
}

function chkBtnConfirm(btnThis){
    if(!working){
        l = Ladda.create(btnThis);
        l.start();
        working = true;
        clearTimeout(timeout);
        $('#alertbox').fadeOut(100);

        $.ajax({
            type: "GET",
            url: '?p=json&r=edit2&d='+(thisObj.parent().parent().attr('id'))+'&f='+(thisObj.parent().index())+'&n='+(thisObj.prop("checked")?'1':'0')
        }).done(handleresponsebtn).fail(function(){console.log('Something has gone very wrong');chkBtnDeny();});
    }
}

function chkBtnDeny(){
    if(thisObj != null){
        thisObj.popover('destroy');
        thisObj.prop("checked", !thisObj.prop("checked"));
        thisObj = null;
    }
}

$(".editable").click( function(){
    if( $(this).children().length == 0 && thisObj == null ){
        oldVal = $(this).text();
        $(this).html('<input type="text" class="form-control edit input-sm" value="'+ $(this).text() +'" autocomplete="off">');
        $("input.form-control.edit").focus();
        $("input.edit").focusout(
            function(){
                if (oldVal !== $(this).val()) {
                    thisObj = $(this).parent();
                    thisObj.popover({
                        placement: 'bottom',
                        html: 'true',
                        trigger: 'manual',
                        title: 'Confirm edit',
                        content: '<div class="btn-group"><button type="button" class="btn btn-default ladda-button" data-style="slide-up" onclick="btnConfirm(this)"><span class="ladda-label">Yes</span></button><button type="button" class="btn btn-default ladda-button" data-style="slide-up" onclick="btnDeny()"><span class="ladda-label">No</span></button></div>'
                    });
                    thisObj.popover('show');
                    newVal = $(this).val();
                }
                $(this).replaceWith( $(this).val() );
            }
        )
    }
});

$("input").click( function(){
    if( thisObj == null ){
        thisObj = $(this);
        thisObj.popover({
            placement: 'bottom',
            html: 'true',
            trigger: 'manual',
            title: 'Confirm edit',
            content: '<div class="btn-group"><button type="button" class="btn btn-default ladda-button" data-style="slide-up" onclick="chkBtnConfirm(this)"><span class="ladda-label">Yes</span></button><button type="button" class="btn btn-default ladda-button" data-style="slide-up" onclick="chkBtnDeny()"><span class="ladda-label">No</span></button></div>'
        });
        thisObj.popover('show');
    }
});

handleresponse = function (msg) {
    l.stop();

    working = false;
    var level='danger';
    var title='';
    if(msg.code=="success"){
        thisObj.popover('destroy');
        thisObj = null;
        level='success';
        title='Edit saved';
    } else if(msg.code=="failure"){
        btnDeny();
        level='warning';
        title='Edit failed';
    } else if(msg.code=="nothing"){
        btnDeny();
        level='info';
        title='Nothing to update';
    } else {
        btnDeny();
        level='danger';
        title='Something broke...';
    }
    $('#alertbox').html('<div class="alert alert-'+level+'" style="margin-top: -7px;"><strong>'+title+'</strong></div>');
    $('#alertbox').fadeIn(200);
    timeout=setTimeout(function(){$('#alertbox').fadeOut(100);},3000);
}

handleresponsebtn = function (msg) {
    l.stop();

    working = false;
    var level='danger';
    var title='';
    if(msg.code=="success"){
        thisObj.popover('destroy');
        thisObj = null;
        level='success';
        title='Edit saved';
    } else if(msg.code=="failure"){
        chkBtnDeny();
        level='warning';
        title='Edit failed';
    } else if(msg.code=="nothing"){
        chkBtnDeny();
        level='info';
        title='Nothing to update';
    } else {
        chkBtnDeny();
        level='danger';
        title='Something broke...';
    }
    $('#alertbox').html('<div class="alert alert-'+level+'" style="margin-top: -7px;"><strong>'+title+'</strong></div>');
    $('#alertbox').fadeIn(200);
    timeout=setTimeout(function(){$('#alertbox').fadeOut(100);},3000);
}