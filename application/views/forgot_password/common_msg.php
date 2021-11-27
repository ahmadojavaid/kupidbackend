<?php
/*
	When user click on forgot password link then that redirect to this on this page user enter its mail id if it if correct then send reset password link to its email account
*/
?>
<section class="login-form login-img dark-bg page-section-ptb100 pb-70" style="">
	<div class="container">
		<div class="row">
			<div class="col-md-3 col-md-offset-3"></div>
			<div class="col-md-6 col-md-offset-3">
				<div class="login-1-form clearfix text-center">  
					<div class="row">
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
							<!--h3 style="color: green;"><?php echo $_SESSION['success'];?></h3-->
						 <?php             
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
							<!--h3 style="color: red;"><?php echo $_SESSION['fail'];?></h3-->
						 <?php
						}
							unset($_SESSION['success']);
							unset($_SESSION['fail']);
						?>
					</div>
				</div>
			</div>
			<div class="col-md-3 col-md-offset-3"></div>
		</div>
	</div>
</section>
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