$("#ex2").slider({});
$("#ex3").slider({});
$(document).ready(function(){
    $(".tooltip-min,.tooltip-max").css("display","none");
    $(".tooltip-max").find('.bottom').removeClass("bottom");
    $(".tooltip-max").addClass("top");
    $("#ex2").change(function(){
        var age=$(this).val();
        var slit=age.split(",");
        $( "#slider1" ).val( slit[0]);
        $( "#slider2" ).val(slit[1]);
    });
    $("#ex3").change(function(){
        var age=$(this).val();
        var slit=age.split(",");
        $( "#slider3" ).val( slit[0]);
        $( "#slider4" ).val(slit[1]);
    });
    $(".dropdown-menu.inner").css("overflow-y","scroll");
});