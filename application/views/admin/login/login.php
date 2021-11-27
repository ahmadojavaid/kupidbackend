<div class="container">
	<div class="login-form">
		<div class="col-sm-12">
<?php 	if (validation_errors()) : ?>
			<div class="col-xs-12">
				<div class="alert alert-danger" role="alert">
				<?= validation_errors() ?>
				</div>
			</div>
<?php 	endif; ?>
<?php 	if (isset($error)) : ?>
			<div class="col-xs-12">
				<div class="alert alert-danger" role="alert">
				<?= $error ?>
				</div>
			</div>
<?php 	endif; ?>
		</div>
		<div class="row">
			<div class="col-sm-12 text-center"><div class="heading"><?php echo lang('lbl_admin_sign_in_admin'); ?></div></div>
		</div>
		<?php
		/*
			Here is the login page of admin if both are correct then redirect to admin dashboard
		*/
		?>
		<div class="row row-eq-height">
			<div class="col-sm-8 col-sm-offset-2 cetered">
				<p><?php echo lang('lbl_admin_sign_in_blank_value'); ?></p>
				<?= form_open() ?>
					<div class="form-group">
						<label for="username"><?php echo lang('lbl_admin_sign_in_username'); ?></label>
						<input class="form-control " placeholder="<?php echo lang('lbl_admin_sign_in_username'); ?>" name="txtusername" type="text" autofocus>    
					</div>
					<div class="form-group">
						<label for="password"><?php echo lang('lbl_admin_sign_in_password'); ?></label>
						<input class="form-control" placeholder="<?php echo lang('lbl_admin_sign_in_password'); ?>" name="txtpassword" type="password" value=""/>
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-default gradiant-btn"><?php echo lang('lbl_admin_sign_in_submit'); ?></button>
					</div>
				</form>
			</div><hr>
		</div>
	</div>
</div>
<script>
$("#default_language").change(function() {
	var language=$("#default_language").val();
	window.location.href='<?php echo site_url('langswitch/switchLanguage'); ?>/'+language;
});
</script> 