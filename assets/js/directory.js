$(".editable").click(
    function(){
        $(this).html('<input type="text" class="form-control edit input-sm" value="'+
            ( $(this).children().length == 0 ? $(this).text() : $(this).children()[0].value )+
            '" autocomplete="off">');
        $("input.form-control.edit").focus();
        $("input.edit").focusout(
            function(){
                $(this).replaceWith( $(this).val() )
            }
        )
    }
)