function report( fieldid, key ){
    data={};

    $('tbody tr').each( function(){
        thisval = $(this).children('td:eq('+ fieldid +')').text();
        if( typeof data[thisval] == "undefined" )
            data[thisval] = 1;
        else
            data[thisval] += 1;
    });

    table = '<table class="table-striped table-condensed" style="margin:0 auto"><tr><th>'+ key +'</th><th>Number</th></tr><tr>';

    k = Object.keys(data);
    for (var i = k.length - 1; i >= 0; --i) {
        table += "<tr><td>" + k[i] + "</td><td>" + data[k[i]] + "</td></tr>";
    };

    table += "</table>";

    modal = '<div class="modal fade" id="rmodal"><div class="modal-dialog"><div class="modal-content"><div class="modal-body" style="text-align:center">'+ table +'</div></div></div></div>';
    $("body").append(modal);
    $("#rmodal").modal("show");

    $("#rmodal").on("hidden.bs.modal", function () {
        $("#rmodal").remove();
    });
}

function countfrcftc( frcid, ftcid ){
    data = {frc:0,ftc:0};

    $('tbody tr').each( function(){
        thisval = $(this).children('td:eq('+ fieldid +')').text();
        if( typeof data[thisval] == "undefined" )
            data[thisval] = 1;
        else
            data[thisval] += 1;
    });
}

showhide = {};
function toggle( fieldid ){
    if( typeof showhide[fieldid] === "undefined" ){
        showhide[fieldid] = 0;
        hiderows( fieldid );
    }
    else if( showhide[fieldid] == 0 ){
        showhide[fieldid] = 1;
        showallrows( );
    }
    else if( showhide[fieldid] == 1 ){
        showhide[fieldid] = 0;
        hiderows( fieldid )
    }
}

function hiderows( fieldid ){
    $('tbody tr').each( function(){
        if( !$(this).children('td:eq('+ fieldid +')').children('input:eq(0)').prop("checked") )
            $(this).css("display","none");
    });
}

function showallrows( ){
    $('tbody tr').each( function(){
        $(this).css("display","");
    });
}