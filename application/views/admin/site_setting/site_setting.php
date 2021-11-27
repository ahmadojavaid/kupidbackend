<div class="page_wrapper">
	<section>
		<div class="container-fluid text-center">
			<div class="row">
				<div class="col-sm-12 text-uppercase">
					<h3><?php echo lang('lbl_admin_site_setting'); ?></h3>
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
			?>
				<?php
				$site_logo;$default_language;
				foreach ($setting as $set) {
					if($set['mode']=="site_logo"){
						$site_logo=$set['status'];
					}
					elseif ($set['mode']=="default_language"){
						$default_language=$set['status'];
					}
				}
				?>
			<div class="row">
				<div class="col-lg-12">
					<div class="panel panel-default">
						<div class="panel-heading"><?php echo lang('lbl_admin_site_setting'); ?></div>
						<div class="panel-body text-center">
						<!-- /.Form start -->
							<form name="add_edit_user" id="add_edit_user" action="<?php echo base_url('admin/admin/update_site_setting');  ?>" enctype="multipart/form-data" method="post">
								<div class="form-group center">
									<label class="control-label"><?php echo lang('lbl_admin_site_setting_logo'); ?></label>
									<div class="controls ">
										<input style="margin:0 auto;" type="file" name="site_logo" />							
									</div>
								</div>
								<?php
								//echo "<pre>";print_r($setting);die;
								//$default_language =$setting[3]['status'];
								if(!empty($languages))
								{
								?>
								<div class="form-group ">
									<label for="religion"><?php echo lang('lbl_admin_site_setting_default_lang'); ?></label>
									<select class="form-control col-sm-3" id="default_language" name="default_language" >
									<?php
										foreach($languages as $language)
										{
											?>
											<option value="<?php echo $language['name']; ?>" <?php echo ($language['name']==$default_language)?"selected":""; ?>><?php echo $language['name']; ?></option>
											<?php
										}
									?>
									</select>
								</div>
								<?php } ?>						
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