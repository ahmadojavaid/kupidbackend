$(document).ready(function(){
    $(".imgs").change(function(){
        this.form.toString();
        $(location).attr("href",BASE_URL+"user/edit_gallery_image");
    });

    $( ".sortable" ).sortable({
		axis: 'x',
		containment: "parent" 
    });
    $( ".sortable" ).sortable({
        update: function( event, ui ) {
            var strItems = "";
            $("#sortable").children().each(function (i) {
                var li = $(this);
                strItems += li.attr("id")+",";
            });
            var strItems = strItems.substring(0, strItems.length-1);
            $("#datepref").val(strItems);
        }
    });
});
window.fbAsyncInit = function() {
    FB.init({
      appId: FB_KEY,
      cookie: true, // This is important, it's not enabled by default
      version: 'v2.2'
    });
  };
(function(d, s, id){
var js, fjs = d.getElementsByTagName(s)[0];
if (d.getElementById(id)) {return;}
js = d.createElement(s); js.id = id;
js.src = "https://connect.facebook.net/en_US/sdk.js";
fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
function fb_login() {
    console.log("fb_login");
    FB.login(
        function (response) {
            if (response.authResponse) {
            // console.log('Welcome!  Fetching your information.... ');
                FB.api('/me', { fields: 'name, email,gender,picture' } , function (response) {		
                    console.log(response);	//return false;				 
                    $.post(BASE_URL+"user/loginfb",{"name": response.name,
                                "fbid":response.id,
                                "email" : response.email,
                                "gender" : response.gender,
                                },function(data){
                                    //debugger;
                                    console.log("data : "+data);//return false;
                            if(data.indexOf("register") != -1)
                            {
                                window.location.replace(BASE_URL+"user/registerfb"); 
                            }else if(data.indexOf("Login") != -1){
                                window.location.replace(BASE_URL+"user/my_profile"); 
                            }
                            else
                            {
                                $('#error').text(data);
                            }
                    });
                });
            } else{
                console.log('User cancelled login or did not fully authorize.');
            }
        });
}
$.ajax({
    url: BASE_URL+"user/get_all_session_data", 
    success: function(response){
            var obj = jQuery.parseJSON( response );
            $("#total_users").attr("data-to",obj.total_users);
            $("#online_users").attr("data-to",obj.total_user_online);
            $("#male_users").attr("data-to",obj.men_onlie);
            $("#female_users").attr("data-to",obj.female_online);
            $("#total_users").html(obj.total_users);
            $("#online_users").html(obj.total_user_online);
            $("#male_users").html(obj.men_onlie);
            $("#female_users").html(obj.female_online);
        
    }
});
$(document).ready(function(){
        $(".alert").delay(10000).hide(0, function() {
    });
});