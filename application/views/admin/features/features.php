<div class="page_wrapper">
	<section>
		<div class="container-fluid text-center">
			<div class="row">
				<div class="col-sm-12 text-uppercase">
					<h3><?php echo lang('lbl_features'); ?></h3>
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
						<div class="panel-heading"><?php echo lang('lbl_features'); ?></div>
						<div class="panel-body text-center">
						<!-- /.Form start -->
							<form name="add_edit_user" id="add_edit_user" action="<?php echo base_url('admin/Setting/update_features');  ?>" enctype="multipart/form-data" method="post">
								
								<div class="form-group">
									<label for="religion"><?php echo lang('lbl_paidchat'); ?></label>
									<div class="controls">
										<input type="radio" name="PAID_CHAT" value="ON" <?php echo ($PAID_CHAT=="ON") ? 'checked' : '';?>>ON<br>
										<input type="radio" name="PAID_CHAT" value="OFF" <?php echo ($PAID_CHAT=="OFF") ? 'checked' : '';?>>OFF<br>
									</div>
								</div>
								
								<div class="form-group">
									<label for="religion"><?php echo lang('lbl_paidlocation'); ?></label>
									<div class="controls">
										<input type="radio" name="PAID_LOCATION" value="ON" <?php echo ($PAID_LOCATION=="ON") ? 'checked' : '';?>>ON<br>
										<input type="radio" name="PAID_LOCATION" value="OFF" <?php echo ($PAID_LOCATION=="OFF") ? 'checked' : '';?>>OFF<br>
									</div>
								</div>
								
								<div class="form-group">
									<label for="religion">PAID_AD</label>
									<div class="controls">
										<input type="radio" name="PAID_AD" value="ON" <?php echo ($PAID_AD=="ON") ? 'checked' : '';?>>ON<br>
										<input type="radio" name="PAID_AD" value="OFF" <?php echo ($PAID_AD=="OFF") ? 'checked' : '';?>>OFF<br>
									</div>
								</div>
								
								<div class="form-group">
									<label for="religion"><?php echo lang('lbl_paidsuperlike'); ?></label>
									<div class="controls">
										<input type="radio" name="PAID_SUPERLIKE" value="ON" <?php echo ($PAID_SUPERLIKE=="ON") ? 'checked' : '';?>>ON<br>
										<input type="radio" name="PAID_SUPERLIKE" value="OFF" <?php echo ($PAID_SUPERLIKE=="OFF") ? 'checked' : '';?>>OFF<br>
									</div>
								</div>
								
								<div class="form-group per_day_superlike" style="display: <?php echo ($PAID_SUPERLIKE=="ON") ? 'block' : 'none';?>" >
									<label for="religion">Per Day Superlike</label>
									<div class="controls">    
										<input class="form-control" type="text" name="PER_DAY_SUPERLIKE" id="PER_DAY_SUPERLIKE" value="<?php echo (!empty($PER_DAY_SUPERLIKE)) ? $PER_DAY_SUPERLIKE : '';?>"/>
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
<script>
$(document).ready(function(){
    $("input[name='PAID_SUPERLIKE']:radio").change(function(){
        if($(this).val() == 'ON')
        {
			$('.per_day_superlike').show();
          // do something
        }
        else if($(this).val() == 'OFF')
        {
          $('.per_day_superlike').hide();
        }
    });
});
</script>