<div class="page_wrapper">
	<section>
		<div class="container-fluid text-center">
			<div class="row">
			   <div class="col-sm-12 text-uppercase page-title">
				   <h3><?php echo lang('lbl_admin_admod'); ?></h3>
				   <div><img class="img-center" src="<?php echo base_url('images/title-bdr.png'); ?>" alt=""></div>
			   </div>
			</div>
	<?php	if(!empty($_SESSION['success']))
			{
				echo "<h3 class='successful'>$_SESSION[success]</h3>";
			}
			else if(!empty($_SESSION['fail']))
			{
				echo "<h4 class='fail'>$_SESSION[fail]</h4>";
			}
			if (validation_errors()) : ?>
				<div class="col-md-12">
					<div class="alert alert-danger" role="alert">
						<?= validation_errors() ?>
					</div>
				</div>
	<?php 	endif; ?>
	<?php 	if (isset($error)) : ?>
				<div class="col-md-12">
					<div class="alert alert-danger" role="alert">
						<?= $error ?>
					</div>
				</div>
		<?php 	endif; ?>
	<?php
	/*
		On this page we can be display  Admob information and enable/disable information of advertisment information for all account ,enable/disable information of advertisement of all new registration
	*/
	?>
			<div class="row">
				<div class="col-lg-12">
					<div class="panel panel-default">
						<div class="panel-heading"><?php echo lang('lbl_admin_admod'); ?></div>
						<div class="panel-body text-center">
							<!-- /.Form start -->
							<form name="add_edit_user" id="add_edit_user" action="#" enctype="multipart/form-data" method="post">
								<div class="form-group">
									<label class="control-label"><?php echo lang('lbl_admin_admod_enable_all'); ?></label>
									<div class="controls">
										<input type="radio" name="chk_all"  <?php echo ($result[0]['status']==1)?"checked":""; ?> value="on"/><?php echo lang('lbl_admin_admod_on'); ?> &nbsp;
										<input type="radio" name="chk_all"   <?php echo ($result[0]['status']==0)?"checked":""; ?> value="off"/><?php echo lang('lbl_admin_admod_off'); ?>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label"><?php echo lang('lbl_admin_admod_enable_new'); ?></label>
									<div class="controls">
										<input type="radio" name="chk_new"  <?php echo ($result[1]['status']==1)?"checked":""; ?> value="on"/><?php echo lang('lbl_admin_admod_on'); ?> &nbsp;
										<input type="radio" name="chk_new"  <?php echo ($result[1]['status']==0)?"checked":""; ?> value="off"/><?php echo lang('lbl_admin_admod_off'); ?>
									</div>
								</div>
								<div class="form-actions">
									<input type="submit" class="btn btn-primary" name="btn_submit" id="btn_submit" value="<?php echo lang('lbl_admin_admod_submit'); ?>"/>
									&nbsp;
									<input type="button" name="back" value="<?php echo lang('lbl_admin_admod_cancel'); ?>" class="btn"/>
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