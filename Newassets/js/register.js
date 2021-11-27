$(document).ready(function () {
	var current_email="";
  $("#user_reg_form").validate({
    rules: {
      fname: {
        required: true
      },
      lname: {
        required: true
      },
      username: {
        required: true
      },
      email: {
        required: true,
        email: true
      },
      password: {
        required: true
      },
      password_confirm: {
        required: true,
        equalTo : "#password"
      },
      education: {
        required: true
      },
      profession: {
        required: true
      },
      terms:{
      	required:true
      }
    },
    messages: {
      fname: {
        required: "F"
      },
      lanme: {
        required: "l"
      },
      email: {
        required: "E"
      },
      password: {
        required: "P"
      },
      password_confirm: {
        required: "PC"
      },
      education: {
        required: "Ed"
      },
      profession: {
        required: "Pr"
      },
      terms:{
      	required:"term"
      }
    },
		errorPlacement: function(error, element) {
			error.insertAfter( element.parent() );
		},
		errorClass: "validation-error",
  });

	$("a#user-reg-check").click(function(){
		if($("#user_reg_form").valid()){
			$.ajax({
				url:BASE_URL+'user/send_mail',
				type:"POST",
				data:{
					email:$("#email").val()	
				},
				success: function(data){
					var obj = jQuery.parseJSON( data );
					if(obj.response){			
			            current_email=obj.email;	
					}
					else{
						$("#verify_code").html("<p>"+obj.message+"</p>");
					}
		        }
			});
			$("#user_verification_code_modal").modal('show');
		}
	});

	$("#verify_code").validate({
			rules:{
				verification_code:{required:true}
			},
			messages:{
				verification_code:{
					required:"Please enter verification code",
				}
			},
			submitHandler:function(form) {
				$.ajax({
					url:BASE_URL+'user/email_verification',
					type:"POST",
					data:{
						email:current_email,
						verification_code:$("#verification_code").val(),
					},
					success: function(data){
						var obj = jQuery.parseJSON( data );
						if(obj.response){						
				            $("#user-reg-btn").click();
						}
						else{
							$("#msgajax").html("Invalid verification number.");						
						}
			        }
			});
        }
	});
	
});