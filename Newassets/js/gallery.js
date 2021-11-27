$(document).on('click','.imgupload1',function(){
    var position=$(this).attr('id');
    $('.avatar-position').val('');
    $('.avatar-position').val(position);
    $(".avatar-view").click();					 
});

$(document).on('click','.imgupload2',function(){
    var position=$(this).attr('id');
    $('.avatar-position').val('');
    $('.avatar-position').val(position);
    $(".avatar-view2").click();						 
});

$(document).on('click','.imgupload3',function(){
    var position=$(this).attr('id');
    $('.avatar-position').val('');
    $('.avatar-position').val(position);
    $(".avatar-view3").click();						 
});

$(document).on('click','.imgupload4',function(){
    var position=$(this).attr('id');
    $('.avatar-position').val('');
    $('.avatar-position').val(position);
    $(".avatar-view4").click();						 
});

$(document).on('click','.imgupload5',function(){
    var position=$(this).attr('id');
    $('.avatar-position').val('');
    $('.avatar-position').val(position);
    $(".avatar-view5").click();						 
});

$(document).on('click','.imgupload6',function(){
    var position=$(this).attr('id');
    $('.avatar-position').val('');
    $('.avatar-position').val(position);
    $(".avatar-view6").click();						 
});

$(document).on('click','.remove-bg',function(event){
     event.stopPropagation();
    var user_id = $(this).attr('data-user');
	var position = $(this).attr('data-position');
    $(this).remove();
    if(position == '2'){
        var imgpath = BASE_URL+"images/step/02.png";
        $('.imgupload'+position).attr('src',+imgpath);
    }
    if(position == '3'){
        var imgpath = BASE_URL+"images/step/03.png";
        $('.imgupload'+position).attr('src',+imgpath);
    }
    if(position == '4'){
        var imgpath = BASE_URL+"images/step/04.png";
        $('.imgupload'+position).attr('src',+imgpath);
    }
    if(position == '5'){
        var imgpath = BASE_URL+"images/step/05.png";
        $('.imgupload'+position).attr('src',+imgpath);
    }
    if(position == '6'){
        var imgpath = BASE_URL+"images/step/06.png";
        $('.imgupload'+position).attr('src',+imgpath);
    }
    $.ajax({
        url:BASE_URL+'admin/Admin/ajax_remove_gallery_image',
        type:'POST',
        data:{ 
            'position' : position,
			'user_id' : user_id,
            },
        success: function(response){
            var data = $.parseJSON(response);
            $('.imgupload'+position).attr('src',data['url']);
        },
    });					 
});