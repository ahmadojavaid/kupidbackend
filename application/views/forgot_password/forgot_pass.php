<?php
/*
	When user click on forgot password link then that redirect to this on this page user enter its mail id if it if correct then send reset password link to its email account
*/
?>
<section class="login-form login-img dark-bg page-section-ptb100 pb-70" style="">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-6">
				<div class="login-1-form clearfix text-center">  
						<?php
						if(!empty($_SESSION['success']))
						{
							?>
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
						{
							?>
			            	<div class="row" align="center">
								<div class="col-md-12 ">
								   <div class="alert myerrormsg" role="error">
										<?php  echo $_SESSION["fail"];  ?>
								   </div>
								</div>
							</div>
							<?php
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
				<?php 	endif; ?>
				<?php 	if (isset($error)) : ?>
			            	<div class="row" align="center">
								<div class="col-md-12 ">
								   	<div class="alert myerrormsg" role="error">
										<?php echo $error;?>
									</div>
								</div>
							</div>
				<?php 	endif; ?>
					<?= form_open(base_url()."user/check_email") ?>
						<div class="form-group">
							<h4 class="title divider-3 text-white mb-5 xs-mb-3"><?php echo lang('lbl_forgot_password'); ?></h4>
							<input type="email" class="form-control" id="username" name="username" placeholder="<?php echo lang('lbl_forgot_email'); ?>">
						</div>
						<div class="section-field text-uppercase text-center mt-2">
							<input type="submit" class="button  btn-lg btn-theme full-rounded animated right-icn" name="reset_pass" value="<?php echo lang('lbl_reset_password_reset_password'); ?>">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>