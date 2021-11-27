<?php
/*
	At this page link is open from the mail and we can be update the user password from here
*/
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<center>
<section class="login-form login-img dark-bg page-section-ptb100 pb-70" style="">
	<div class="container">
		<div class="row">
			<div class="col-md-3 col-md-offset-3"></div>
			<div class="col-md-6 col-md-offset-3">
				<div class="login-1-form clearfix text-center">  
						<?php
						if(!empty($_SESSION['success']))
						{?>
			            	<div class="row" align="center">
								<div class="col-md-12 ">
								   <div class="alert mysuccessmsg" role="error">
										<?php  echo $_SESSION["success"];  ?>
								   </div>
								</div>
							</div>
							<?php
							//echo "<h6 class='successful'>$_SESSION[success]</h6>";
						}
						else if(!empty($_SESSION['fail']))
						{?>
			            	<div class="row" align="center">
								<div class="col-md-12 ">
								   <div class="alert myerrormsg" role="error">
										<?php  echo $_SESSION["fail"];  ?>
								   </div>
								</div>
							</div>
							<?php
							//echo "<h6 class='alert'>$_SESSION[fail]</h6>";
						}
						unset($_SESSION['success']);
						unset($_SESSION['fail']);
						?>
				<?php 	if (validation_errors()) : ?>
			            	<div class="row" align="center">
								<div class="col-md-12 ">
								   <div class="alert myerrormsg" role="error">
										<?php echo validation_errors();?>
								   </div>
								</div>
							</div>
							<!-- <div class="col-md-12">
								<div class="alert" role="alert">
									<?php echo validation_errors();?>
								</div>
							</div> -->
				<?php 	endif; ?>
				<?php 	if (isset($error)) : ?>
			            	<div class="row" align="center">
								<div class="col-md-12 ">
								   <div class="alert myerrormsg" role="error">
										<?php echo $error;?>
								   </div>
								</div>
							</div>
							<!-- <div class="col-md-12">
								<div class="alert" role="alert">
									<?php echo $error;?>
								</div>
							</div> -->
				<?php 	endif; ?>
					<?= form_open(base_url()."user/change_pass") ?>
					<div class="row" style="padding: 5px">
						<div class="form-group">
							<h4 class="title divider-3 text-white mb-5 xs-mb-3"><?php echo lang('lbl_reset_password_reset_password'); ?></h4><br/>
							<div style="color: white; margin-bottom: 15px;"><h5>Password reset for <?php echo $email;?></h5></div>
							<input type="hidden" name="id" value="<?php echo $id;?>" /> 
							<input type="hidden" name="token" value="<?php echo $token;?>" /> 
							<label for="reset_password"><?php echo lang('lbl_reset_password_new_password'); ?>*</label>
							<input type="password" style="    margin-left: 30px;" class="form-control" id="reset_password" value="" name="reset_password" placeholder="<?php echo lang('lbl_reset_password_new_password'); ?>*">
						</div> 
					</div>
					<div class="row">
						<div class="form-group">
							<label for="confirm_password"><?php echo lang('lbl_reset_password_confirm_password'); ?>*</label>
							<input type="password" class="form-control" id="confirm_password" value="" name="confirm_password" placeholder="<?php echo lang('lbl_reset_password_confirm_password'); ?>*">
						</div>  
					</div>
					<div class="row">
						<div class="section-field text-uppercase text-center mt-20">
							<input type="submit" class="button  btn-lg btn-theme full-rounded animated right-icn" name="reset_pass" value="<?php echo lang('lbl_reset_password_reset_password'); ?>">
						</div>
					</div>
					</form>
				</div>
			</div>
			<div class="col-md-3 col-md-offset-3"></div>
		</div>
	</div>
</section>
</center>
	<link href="<?php  echo base_url("Newassets/css/bootstrap-slider.min.css"); ?>" rel="stylesheet" />
	<link href="<?php  echo base_url("Newassets/css/bootstrap-select.min.css"); ?>" rel="stylesheet" />
	<link href="<?php  echo base_url("Newassets/css/mega-menu/mega_menu.css"); ?>" rel="stylesheet" />
	<link href="<?php  echo base_url("Newassets/css/magnific-popup/magnific-popup.css"); ?>" rel="stylesheet" />
	<link href="<?php  echo base_url("Newassets/css/font-awesome.min.css"); ?>" rel="stylesheet" />
	<link href="<?php  echo base_url("Newassets/css/flaticon.css"); ?>" rel="stylesheet" />
	<link href="<?php  echo base_url("Newassets/css/animate.min.css"); ?>" rel="stylesheet"/>
	<link href="<?php  echo base_url("Newassets/css/general.css"); ?>" rel="stylesheet" />
	<link href="<?php  echo base_url("Newassets/css/style.css"); ?>" rel="stylesheet" />
	<link href="<?php  echo base_url("Newassets/css/owl.carousel.css"); ?>" rel="stylesheet" />
	<link href="<?php  echo base_url("Newassets/css/jquery-ui.min.css"); ?>" rel="stylesheet" />
	<link href="<?php  echo base_url("Newassets/crop/dist/cropper.min.css"); ?>" rel="stylesheet" />
	<link href="<?php  echo base_url("Newassets/crop/css/main.css"); ?>" rel="stylesheet" />
	
	<!--  Light Bootstrap Table core CSS    -->
	<!--     Fonts and icons     -->
	<link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet"> 
	<link href="<?php  echo base_url("Newassets/css/pe-icon-7-stroke.css"); ?>" rel="stylesheet" />
	<!--Skin Load as per admin settings -->
	<?php $site_style=$header[19]['status']; ?>
	<?php if(file_exists(DIRECTORY_PATH."Newassets/css/skins/".$site_style.".css")) { ?>
		<link href="<?php  echo base_url("Newassets/css/skins/".$site_style.".css"); ?>" rel="stylesheet" />
	<?php } ?>
	<?php 
	if($this->Common_model->is_rtl()){
		?>			
		<link href="<?php  echo base_url("Newassets/css/rtl.css"); ?>" rel="stylesheet" /> 
		<?php 
	}
	?>
	<!--js -->
	<script>
		var BASE_URL='<?php echo base_url(); ?>';
		var FB_KEY='<?php echo $this->FACEBOOK_KEY;  ?>';
	</script>
	<script src="<?php echo base_url('Newassets/js/jquery.min.js');?>" type="text/javascript"></script>
	<script src="<?php echo base_url('Newassets/js/popper.min.js');?>" type="text/javascript"></script>
	
	<script src="<?php  echo base_url("Newassets/js/bootstrap.min.js"); ?>" type="text/javascript"></script>
	<script src="<?php  echo base_url("Newassets/js/bootstrap-slider.js"); ?>"  type="text/javascript"></script>
	<!--script src="<?php  echo base_url("Newassets/js/bootstrap-slider.min.js"); ?>" type="text/javascript"></script-->	
	<script src="<?php  echo base_url("Newassets/crop/dist/cropper.min.js"); ?>" type="text/javascript"></script>
	<script src="<?php  echo base_url("Newassets/crop/js/main.js"); ?>" type="text/javascript"></script>
	<script src="<?php  echo base_url("Newassets/js/gallery.min.js"); ?>" type="text/javascript"></script>
	<?php if(!empty($JS_Middle)) { ?>	
		<?php foreach($JS_Middle as $js) { ?>
			<script src="<?php  echo $js; ?>" type="text/javascript"></script>
		<?php } ?>
	<?php } ?>
	<!--   Core JS Files   -->
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
	<script src="<?php  echo base_url("Newassets/js/jquery.downCount.js"); ?>" type="text/javascript"></script>
	<script src="<?php  echo base_url("Newassets/js/bootstrap-notify.js"); ?>"  type="text/javascript"></script>
	<script src="<?php  echo base_url("Newassets/js/demo.js"); ?>"  type="text/javascript"></script>
	<script src="<?php  echo base_url("Newassets/js/bootstrap-select.js"); ?>"  type="text/javascript"></script>
	<script src="<?php  echo base_url("Newassets/js/bootstrap-select.min.js"); ?>"  type="text/javascript"></script>
	<script src="<?php  echo base_url("Newassets/js/magnific-popup/jquery.magnific-popup.min.js"); ?>"  type="text/javascript"></script>
	<script src="<?php  echo base_url("Newassets/js/chartist.min.js"); ?>"></script>
	<script src="<?php  echo base_url("Newassets/js/counter/jquery.countTo.js"); ?>"  type="text/javascript"></script>
	<script src="<?php  echo base_url("Newassets/js/light-bootstrap-dashboard.js"); ?>"  type="text/javascript"></script>
    <script src="<?php  echo base_url("Newassets/js/owl.carousel.min.js"); ?>"  type="text/javascript"></script>
	<?php 
	if($this->Common_model->is_rtl()){
		?>
		<script src="<?php  echo base_url("Newassets/js/custom-rtl.js"); ?>"></script>	
		<?php 
	}
	else{
		?>
		<script src="<?php  echo base_url("Newassets/js/custom.js"); ?>"></script>
		<?php
	}
	?>
	<script src="<?php  echo base_url("Newassets/js/jquery.appear.js"); ?>" type="text/javascript"></script>
	<script src="<?php  echo base_url("Newassets/js/mega-menu/mega_menu.js"); ?>" type="text/javascript"></script>
	<script src="<?php  echo base_url("Newassets/js/footer.min.js"); ?>" type="text/javascript"></script>
</body>
</html>