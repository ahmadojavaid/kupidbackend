$(document).ready(function () {
	$("form").validate({
		rules: {
			username: {
				required: true,
				email: true
			},
				password: {
				required: true
			}
		},
		messages: {
			username: {
				required: "Enter Your Email Address"
			},
			password: {
				required: "Enter Your Password"
			}
		},
		errorPlacement: function(error, element) {
			error.insertAfter( element.parent() );
		},
		errorClass: "myerrormsg",
	});
});