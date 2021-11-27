<div class="page_wrapper">
	<section>
		<div class="container-fluid text-center">
			<div class="row">
				<div class="col-sm-12 text-uppercase">
					<h3>Configuration</h3>
					<div><img class="img-center" src="<?php echo base_url('images/title-bdr.png'); ?>" alt=""></div>
				</div>
			</div>
		<?php
		if(!empty($_SESSION['success']))
		{
			echo "<h3 style='color:green'>$_SESSION[success]</h3>";
		}
		else if(!empty($_SESSION['fail']))
		{
			echo "<h4 style='color:red'>$_SESSION[fail]</h4>";
		}
		 if (validation_errors()) : ?>
			<div class="col-md-12">
				<div class="alert alert-danger" role="alert">
					<?= validation_errors() ?>
				</div>
			</div>
<?php 	endif; ?>
			<?php if (isset($error)) : ?>
			<div class="col-md-12">
				<div class="alert alert-danger" role="alert">
					<?= $error ?>
				</div>
			</div>
			<?php endif; ?>
			<?php
			/*
				From this page we can change the site logo on thw web app
			*/
			//echo $GOOGLE_PLACE_API_KEY;die;
			?>
			<div class="row">
				<div class="col-lg-12">
					<div class="panel panel-default">
						<div class="panel-heading"><?php echo lang('lbl_configuration'); ?></div>
						<div class="panel-body text-center">
						<!-- /.Form start -->
							<form name="add_edit_user" id="add_edit_user" action="<?php echo base_url('admin/Setting/update_setting');  ?>" enctype="multipart/form-data" method="post">
								
								<div class="form-group">
									<label for="religion"><?php echo lang('lbl_google_place_api_key'); ?></label>
									<div class="controls">    
										<input class="form-control" type="text" name="GOOGLE_PLACE_API_KEY" id="GOOGLE_PLACE_API_KEY" placeholder="<?php echo lang('lbl_google_place_api_key'); ?>" value="<?php echo (!empty($GOOGLE_PLACE_API_KEY)) ? $GOOGLE_PLACE_API_KEY : '';?>"/>
									</div>
								</div>
								
								<div class="form-group">
									<label for="religion"><?php echo lang('lbl_facebook_key'); ?></label>
									<div class="controls">    
										<input class="form-control" type="text" name="FACEBOOK_KEY" id="FACEBOOK_KEY" placeholder="<?php echo lang('lbl_facebook_key'); ?>" value="<?php echo (!empty($FACEBOOK_KEY)) ? $FACEBOOK_KEY : '';?>"/>
									</div>
								</div>
								
								<!-- <div class="form-group">
									<label for="religion">XMPP ENABLE</label>
									<div class="controls"> 
										<input type="radio" name="XMPP_ENABLE" value="true" <?php echo ($XMPP_ENABLE=='true') ? 'checked' : '';?>> ENABLE
										<input type="radio" name="XMPP_ENABLE" value="false" <?php echo ($XMPP_ENABLE=='false') ? 'checked' : '';?>> DISABLE
									</div>
								</div> -->
								<input type="hidden" name="XMPP_ENABLE" value="true" >
								<div class="enablexampp">
									<!-- <div class="form-group">
										<label for="religion"><?php echo lang('lbl_xmpp_host'); ?></label>
										<div class="controls">    
											<input class="form-control" type="text" name="XMPP_HOST" id="XMPP_HOST" placeholder="<?php echo lang('lbl_xmpp_host'); ?>" value="<?php echo (!empty($XMPP_HOST)) ? $XMPP_HOST : '';?>"/>
										</div>
									</div> -->
									<div class="form-group">
										<label for="religion">APP XMPP HOST</label>
										<div class="controls">    
											<input class="form-control" type="text" name="APP_XMPP_HOST" id="APP_XMPP_HOST" placeholder="APP XMPP HOST" value="<?php echo (!empty($APP_XMPP_HOST)) ? $APP_XMPP_HOST : '';?>"/>
										</div>
									</div>
									<div class="form-group">
										<label for="religion"><?php echo lang("lbl_xmpp_default_password")?></label>
										<div class="controls">    
											<input class="form-control" type="text" name="XMPP_DEFAULT_PASSWORD" id="XMPP_DEFAULT_PASSWORD" placeholder="<?php echo lang("lbl_xmpp_default_password")?>" value="<?php echo (!empty($XMPP_DEFAULT_PASSWORD)) ? $XMPP_DEFAULT_PASSWORD : '';?>"/>
										</div>
									</div>
									<!-- 
									<div class="form-group">
										<label for="religion"><?php echo lang("lbl_xmpp_server")?></label>
										<div class="controls">    
											<input class="form-control" type="text" name="XMPP_SERVER" id="XMPP_SERVER" placeholder="<?php echo lang("lbl_xmpp_server")?>" value="<?php echo (!empty($XMPP_SERVER)) ? $XMPP_SERVER : '';?>"/>
										</div>
									</div> -->
									<div class="form-group">
										<label for="religion"><?php echo lang("lbl_app_xmpp_server")?></label>
										<div class="controls">    
											<input class="form-control" type="text" name="APP_XMPP_SERVER" id="APP_XMPP_SERVER" placeholder="<?php echo lang("lbl_app_xmpp_server")?>" value="<?php echo (!empty($APP_XMPP_SERVER)) ? $APP_XMPP_SERVER : '';?>"/>
										</div>
									</div>
								</div>
								<!--div class="form-group">
									<label for="religion">PEM FILE</label>
									<div class="controls">
										<input style="margin-left:400px;" type="file" name="PEM_FILE" id="PEM_FILE" />
									</div>
								</div-->
															
								<!-- <div class="form-group">
									<label for="religion"><?php echo lang("lbl_push_enable_sandbox")?></label>
									<div class="controls">
										<input type="radio" name="PUSH_ENABLE_SANDBOX" value="true" <?php echo ($PUSH_ENABLE_SANDBOX=='true') ? 'checked' : '';?>><?php echo lang("lbl_enable")?>
										<input type="radio" name="PUSH_ENABLE_SANDBOX" value="false" <?php echo ($PUSH_ENABLE_SANDBOX=='false') ? 'checked' : '';?>><?php echo lang("lbl_disable")?>
									</div>
								</div> -->
								
								<!-- <div class="form-group">
									<label for="religion"><?php echo lang("lbl_push_sandbox_gateway_url")?></label>
									<div class="controls">    
										<input class="form-control" type="url" name="PUSH_SANDBOX_GATEWAY_URL" id="PUSH_SANDBOX_GATEWAY_URL" placeholder="<?php echo lang("lbl_push_sandbox_gateway_url")?>" value="<?php echo (!empty($PUSH_SANDBOX_GATEWAY_URL)) ? $PUSH_SANDBOX_GATEWAY_URL : '';?>"/>
									</div>
								</div>
								
								<div class="form-group">
									<label for="religion"><?php echo lang("lbl_push_gateway_url")?></label>
									<div class="controls">    
										<input class="form-control" type="url" name="PUSH_GATEWAY_URL" id="PUSH_GATEWAY_URL" placeholder="<?php echo lang("lbl_push_gateway_url")?>" value="<?php echo (!empty($PUSH_GATEWAY_URL)) ? $PUSH_GATEWAY_URL : '';?>"/>
									</div>
								</div> -->
								
								<!-- <div class="form-group">
									<label for="religion"><?php echo lang("lbl_fcm_key")?></label>
									<div class="controls">    
										<input class="form-control" type="text" name="ANDROID_FCM_KEY" id="ANDROID_FCM_KEY" placeholder="<?php echo lang("lbl_fcm_key")?>" value="<?php echo (!empty($ANDROID_FCM_KEY)) ? $ANDROID_FCM_KEY : '';?>"/>
									</div>
								</div>
								 -->
								<div class="form-group">
									<label for="religion"><?php echo lang("lbl_instagram_callback_base")?></label>
									<div class="controls">    
										<input class="form-control" type="url" name="INSTAGRAM_CALLBACK_BASE" id="INSTAGRAM_CALLBACK_BASE" placeholder="<?php echo lang("lbl_instagram_callback_base")?>" value="<?php echo (!empty($INSTAGRAM_CALLBACK_BASE)) ? $INSTAGRAM_CALLBACK_BASE : '';?>"/>
									</div>
								</div>
								
								<div class="form-group">
									<label for="religion"><?php echo lang("lbl_instagram_client_secret")?></label>
									<div class="controls">    
										<input class="form-control" type="text" name="INSTAGRAM_CLIENT_SECRET" id="INSTAGRAM_CLIENT_SECRET" placeholder="<?php echo lang("lbl_instagram_client_secret")?>" value="<?php echo (!empty($INSTAGRAM_CLIENT_SECRET)) ? $INSTAGRAM_CLIENT_SECRET : '';?>"/>
									</div>
								</div>
								
								<div class="form-group">
									<label for="religion"><?php echo lang("lbl_instagram_client_id")?></label>
									<div class="controls">    
										<input class="form-control" type="text" name="INSTAGRAM_CLIENT_ID" id="INSTAGRAM_CLIENT_ID" placeholder="<?php echo lang("lbl_instagram_client_id")?>" value="<?php echo (!empty($INSTAGRAM_CLIENT_ID)) ? $INSTAGRAM_CLIENT_ID : '';?>"/>
									</div>
								</div>
								
								<div class="form-group">
									<label for="religion"><?php echo lang("lbl_admobkey")?>y</label>
									<div class="controls">    
										<input class="form-control" type="text" name="adMobKey" id="adMobKey" placeholder="<?php echo lang("lbl_admobkey")?>" value="<?php echo (!empty($adMobKey)) ? $adMobKey : '';?>"/>
									</div>
								</div>
								
								<div class="form-group">
									<label for="religion"><?php echo lang("lbl_admobvideokey")?></label>
									<div class="controls">    
										<input class="form-control" type="text" name="adMobVideoKey" id="adMobVideoKey" placeholder="<?php echo lang("lbl_admobvideokey")?>" value="<?php echo (!empty($adMobVideoKey)) ? $adMobVideoKey : '';?>"/>
									</div>
								</div>
								
								<!-- <div class="form-group">
									<label for="religion"><?php echo lang("lbl_removeaddinappbilling")?></label>
									<div class="controls">    
										<input class="form-control" type="text" name="RemoveAddInAppBilling" id="RemoveAddInAppBilling" placeholder="<?php echo lang("lbl_removeaddinappbilling")?>" value="<?php echo (!empty($RemoveAddInAppBilling)) ? $RemoveAddInAppBilling : '';?>"/>
									</div>
								</div>
								
								<div class="form-group">
									<label for="religion"><?php echo lang("lbl_paidchatinappbilling")?></label>
									<div class="controls">    
										<input class="form-control" type="text" name="PaidChatInAppBilling" id="PaidChatInAppBilling" placeholder="<?php echo lang("lbl_paidchatinappbilling")?>" value="<?php echo (!empty($PaidChatInAppBilling)) ? $PaidChatInAppBilling : '';?>"/>
									</div>
								</div>
								
								<div class="form-group">
									<label for="religion"><?php echo lang("lbl_locationinappbilling")?></label>
									<div class="controls">    
										<input class="form-control" type="text" name="LocationInAppBilling" id="LocationInAppBilling" placeholder="<?php echo lang("lbl_locationinappbilling")?>" value="<?php echo (!empty($LocationInAppBilling)) ? $LocationInAppBilling : '';?>"/>
									</div>
								</div>
								
								<div class="form-group">
									<label for="religion"><?php echo lang("lbl_superlikeinappbilling")?></label>
									<div class="controls">    
										<input class="form-control" type="text" name="SuperLikeInAppBilling" id="SuperLikeInAppBilling" placeholder="<?php echo lang("lbl_superlikeinappbilling")?>" value="<?php echo (!empty($SuperLikeInAppBilling)) ? $SuperLikeInAppBilling : '';?>"/>
									</div>
								</div>
								 -->
								<div class="form-group">
									<label for="religion"><?php echo lang("lbl_removeaddinapppurchase")?></label>
									<div class="controls">    
										<input class="form-control" type="text" name="RemoveAddInAppPurchase" id="RemoveAddInAppPurchase" placeholder="<?php echo lang("lbl_removeaddinapppurchase")?>" value="<?php echo (!empty($RemoveAddInAppPurchase)) ? $RemoveAddInAppPurchase : '';?>"/>
									</div>
								</div>
								
								<div class="form-group">
									<label for="religion"><?php echo lang("lbl_paidchatinapppurchase")?></label>
									<div class="controls">    
										<input class="form-control" type="text" name="PaidChatInAppPurchase" id="PaidChatInAppPurchase" placeholder="<?php echo lang("lbl_paidchatinapppurchase")?>" value="<?php echo (!empty($PaidChatInAppPurchase)) ? $PaidChatInAppPurchase : '';?>"/>
									</div>
								</div>
								
								<div class="form-group">
									<label for="religion"><?php echo lang("lbl_locationinapppurchase")?></label>
									<div class="controls">    
										<input class="form-control" type="text" name="LocationInAppPurchase" id="LocationInAppPurchase" placeholder="<?php echo lang("lbl_locationinapppurchase")?>" value="<?php echo (!empty($LocationInAppPurchase)) ? $LocationInAppPurchase : '';?>"/>
									</div>
								</div>
								
								<div class="form-group">
									<label for="religion"><?php echo lang("lbl_superlikeinapppurchase")?></label>
									<div class="controls">    
										<input class="form-control" type="text" name="SuperLikeInAppPurchase" id="SuperLikeInAppPurchase" placeholder="<?php echo lang("lbl_superlikeinapppurchase")?>" value="<?php echo (!empty($SuperLikeInAppPurchase)) ? $SuperLikeInAppPurchase : '';?>"/>
									</div>
								</div>
								
								<div class="form-group">
									<label for="religion"><?php echo lang("lbl_termsandconditionsurl")?></label>
									<div class="controls">    
										<input class="form-control" type="url" name="TermsAndConditionsUrl" id="TermsAndConditionsUrl" placeholder="<?php echo lang("lbl_termsandconditionsurl")?>" value="<?php echo (!empty($TermsAndConditionsUrl)) ? $TermsAndConditionsUrl : '';?>"/>
									</div>
								</div>
								
								<div class="form-actions">
									<input type="submit" class="btn btn-primary" name="btn_submit" id="btn_submit" value="<?php echo lang('lbl_admin_site_setting_submit'); ?>"/>
									&nbsp;
									<input type="button" name="back" value="<?php echo lang('lbl_admin_site_setting_cancel'); ?>" class="btn"/>
								</div>
							</form>
						<!-- /.form over -->
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script type="text/javascript">
$("document").ready(function(){
	$(".color").click(function(){
		$("#site_style").val($(this).attr("data-style"));
	});
});
</script>
<script>
$(document).ready(function(){ 
    $("input[name='XMPP_ENABLE']").click(function() {
        var test = $(this).val();
		if(test == 'false'){
			$("div.enablexampp").hide();
		}else{
			$("div.enablexampp").show();
		}
    }); 
});
</script>