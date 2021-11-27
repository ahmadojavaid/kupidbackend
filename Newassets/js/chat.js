(function (root, factory) {
    if (typeof define === 'function' && define.amd) {
        // AMD. Register as a module called "myplugin"
        //define(["converse"], factory);
        define("myplugin", ['converse'], factory);
    } else {
        // Browser globals. If you're not using a module loader such as require.js,
        // then this line below executes. Make sure that your plugin's <script> tag
        // appears after the one from converse.js.
        factory(converse);
    }
}(this, function (converse) {

    
    ///console.log(converse);
    converse.plugins.add('myplugin', {
        
        initialize: function () {
            var _converse = this._converse;
            
            _converse.on('afterMessagesFetched', function (data) {
                var receiverid=data.model.attributes.id;
                $(".chat-message").each(function(){
                    var id=$(this).attr('data-msgid');
                    var message=$(this).find('.chat-msg-content').html();
                    message=message.trim();
                    var lastchar=message[message.length -1];
                    message = message.replace(/(\r\n\t|\n|\r\t)/gm,"");
                    //console.log(message);
                    var datedata=message.match("{(.*)}");
                    if(datedata!=null && lastchar=='#'){
                        var dateobj=JSON.parse(datedata[0]);
                        if(dateobj!=null){
                            //process it
                            var title=dateobj.Title;
                            var dateandtime=dateobj.TimeOfDate;
                            var string_message="Would you like to Come on Date ?<br/>"+" On <b>"+dateandtime+" <br/> @ </b><b>"+ title +"</b>";
                            string_message+="<br/>"+"<span><a href='#' class='btndaterequest accept' data-receiver='"+receiverid+"' data-msg='"+message+"'>Accept</a>&nbsp;<a class='btndaterequest decline' data-receiver='"+receiverid+"' data-msg='"+message+"' href='#'>Decline</a></span>";
                            $(".chat-message[data-msgid='"+id+"'] .chat-msg-content").html(string_message);
                        }
                        
                    }
                    else if(lastchar=='1'){
                        $(".chat-message[data-msgid='"+id+"'] .chat-msg-content").html('Daterequest Accepted');
                    }
                    else if(lastchar=='0'){
                        $(".chat-message[data-msgid='"+id+"'] .chat-msg-content").html('Daterequest Rejected');
                    }
                    $(".chat-message[data-msgid='"+id+"'] .chat-msg-them").parent().addClass('message-from-them');
                    $(".chat-message[data-msgid='"+id+"'] .chat-msg-me").parent().addClass('message-from-me');
                });
            });
            _converse.on('messageAdded', function (data) {
                //check for the date request 
                var receiverid=data.chatbox.id;
                var id=data.message.attributes.msgid;
                var message=String(data.message.attributes.message);
                message=message.trim();
                var lastchar=message[message.length -1];
                message = message.replace(/(\r\n\t|\n|\r\t)/gm,"");
                var datedata=message.match("{(.*)}");
                if(datedata!=null && lastchar=='#'){
                    var dateobj=JSON.parse(datedata[0]);
                    if(dateobj!=null){
                        //process it
                        var title=dateobj.Title;
                        var dateandtime=dateobj.TimeOfDate;
                        var string_message="Would you like to Come on Date ?<br/>"+" On <b>"+dateandtime+" <br/> @ </b><b>"+ title +"</b>";
                        string_message+="<br/>"+"<span><a href='#' class='btndaterequest accept' data-receiver='"+receiverid+"' data-msg='"+message+"'>Accept</a>&nbsp;<a class='btndaterequest decline' data-receiver='"+receiverid+"' data-msg='"+message+"' href='#'>Decline</a></span>";
                        $(".chat-message[data-msgid='"+id+"'] .chat-msg-content").html(string_message);
                    }
                    
                }
                else if(lastchar=='1'){
                    $(".chat-message[data-msgid='"+id+"'] .chat-msg-content").html('Daterequest Accepted');
                }
                else if(lastchar=='0'){
                    $(".chat-message[data-msgid='"+id+"'] .chat-msg-content").html('Daterequest Rejected');
                }
                $(".chat-message[data-msgid='"+id+"'] .chat-msg-them").parent().addClass('message-from-them');
                $(".chat-message[data-msgid='"+id+"'] .chat-msg-me").parent().addClass('message-from-me');
            });

            _converse.on('chatBoxOpened', function (chatbox) { 
                //console.log(chatbox);
                var receiverid=chatbox.model.id;
                $(".chatbox[id='"+chatbox.el.id+"'] .chat-toolbar.no-text-select").append('<li><a class="sedndaterequest" title="Send Date Request" data-receiverid="'+receiverid+'" data-chatboxid="'+chatbox.el.id+'"><i class="icon-heart"></i></a></li>');
                ///append fun icons
            });

        }
    });	
    converse.initialize({
        bosh_service_url: XMPP_HTTP_BIND, // Please use this connection manager only for testing purposes
        show_controlbox_by_default: true,
        auto_login:true,
        jid:EJUSER+'@'+XMPP_SERVER,
        password:XMPP_DEFAULT_PASSWORD,
        allow_registration:false,
        allow_contact_requests:false,
        allow_contact_removal:false,
        allow_logout: false,
        allow_muc:false,
        auto_subscribe: true,
        allow_chat_pending_contacts:false,
        hide_muc_server:false,
        hide_offline_users:false,
        use_vcards:false,
        locked_domain:XMPP_SERVER,
        whitelisted_plugins: ['myplugin'],
        allow_otr:false,
        view_mode:'fullscreen',
    });	
})
);

$(document).ready(function(){
    var placeid="";
    var rating="";
    var imgPlace="";
    var TimeOfDate="";
    var Title="";
    var Distance="";

    $("body").on('click','a.btndaterequest',function(e){
        var mode="accept";
        var to=$(this).attr('data-receiver');
        var msg=$(this).attr('data-msg');
        if($(this).hasClass('accept'))
            mode="accept";
        else
            mode="decline";
        $.ajax({
            url:BASE_URL+'friends/sendDateRequestResponse',
            type:'post',
            data:{mode:mode,to:to,msg:msg},
            success:function(response){
                console.log(response);
            }
        });
        if(mode=="accept")
            $(this).parent().html('You have <b>accepted</b> the date request!');
        else
            $(this).parent().html('You have <b>decliced</b> the date request!');
        return false;
    });
    $("body").on('click','a.sedndaterequest',function(e){
        //show the four icons
        dtrequestreceiverid=$(this).attr('data-receiverid');
        chatboxid=$(this).attr('data-chatboxid');
        
        $(".places").hide();
        $(".daterequestdatetime").hide();
        $(".daterequesticons").show();
        $("#daterequestmodal").modal('show');
        
        
    });
    
    $("body").on('click',".Preference-icon img",function(e){
        //click on icons
        var type=$(this).attr('data-type');
        var gdata="";
        if(type=="cafe")
            gdata=cafe;
        else if(type=="bar")
            gdata=bar;
        else if(type=="restaurant")
            gdata=restaurant;
        else if(type=="beach")
            gdata=beach;
        $(".places").html('');
        $.each(gdata,function(i,item){
            
            var imgref=item.photo_reference;
            var star=Math.round(item.rating);
            var distance=parseFloat(item.distance);
            distance=distance.toFixed(2);
            if (imgref == "NA") {
                
                itemhtml='<div class="place row" data-imgPlace="'+imgref+'" data-placeid="'+item.id+'" data-rating="'+item.rating+'" data-title="'+item.name+'"><div class="col-sm-4 place-img"><img src="'+default_place_image+'"/></div><div class="col-sm-8 place-title">'+item.name+'<span class="rating">';
                for(i=1;i<=star;i++){
                    itemhtml+='<i class="fa fa-star"></i>';
                }
                itemhtml+='</span><span class="distance"><i class="fa fa-map-marker">&nbsp;<b>'+distance+'</b> Miles</span></div></div>';
            }
            else{
                itemhtml='<div class="place row" data-imgPlace="'+imgref+'" data-placeid="'+item.id+'" data-rating="'+item.rating+'" data-title="'+item.name+'"><div class="col-sm-4 place-img"><img src="https://maps.googleapis.com/maps/api/place/photo?maxwidth=100&photoreference='+imgref+'&key=AIzaSyB-0xiIlNO91Dl3mpBqOuW31BsphxnTYis"/></div><div class="col-sm-8 place-title">'+item.name+'<span class="rating">';

                for(i=1;i<=star;i++){
                    itemhtml+='<i class="fa fa-star"></i>';
                }
                itemhtml+='</span><span class="distance"><i class="fa fa-map-marker">&nbsp;<b>'+distance+'</b> Miles</span></div></div>';
            }
                
                
            $(".places").append(itemhtml);
        });
        $(".places").show();
        $(".daterequestdatetime").hide();
        $(".daterequesticons").hide();
    });

    //place click
    $("body").on("click",'.place',function(e){
        $(".daterequestdatetime").show();
        $(".places").hide();
        $(".daterequesticons").hide();
        placeid=$(this).attr('data-placeid');
        rating=$(this).attr('data-rating');
        Title=$(this).attr('data-title');
        imgPlace=$(this).attr('data-imgPlace');
        Distance=0;
        $('#datetimepicker_format').datetimepicker(
            {
                ampm	: true, // FOR AM/PM FORMAT
                format 	: 'd/m/Y, g:i A',
                inline	: true,
                value	:'01/05/2018, 01:00 PM',
                minDate	:new Date(),
            }
        );
    });

    $("body").on("click",".btnsendrequest",function(e){
        var receiver=dtrequestreceiverid;
        TimeOfDate=$(this).parent().find('input.TimeOfDate').val();
        $.ajax({
            url:BASE_URL+"friends/sendDateRequest",
            type:'post',
            data:{placeid:placeid,ratings:rating,TimeOfDate:TimeOfDate,Title:Title,Distance:Distance,receiver:receiver,imgPlace:imgPlace},
            success:function(response){
                console.log(response);
                //alert('Date request has been sent successfully!');
                $("#daterequestmodal").modal('hide');
                //add message 
                var dt = new Date();
                var string_message="Would you like to Come on Date ?<br/>"+" On <b>"+TimeOfDate+" <br/> @ </b><b>"+ Title +"</b>";
                $(".chatbox[id='"+chatboxid+"'] .chat-content").append('<div class="message chat-message message-from-me" data-isodate="'+dt.toISOString()+'"><span class="chat-msg-author chat-msg-me">'+dt.getHours() + ':' + dt.getMinutes()+' me:&nbsp;</span><span class="chat-msg-content">'+string_message+'</span></div>');
                //

            }
        });
    });
});