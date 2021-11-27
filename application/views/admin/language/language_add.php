<div class="page_wrapper">
	<div class="row padd-bottom"><div class="col-sm-12"><h1 class="page-header"><?php echo lang('lbl_admin_language_add_tag'); ?></h1></div></div>
	<?php if (validation_errors()) : ?>
		<div class="col-md-12">
			<div class="alert alert-danger" role="alert">
				<?= validation_errors() ?>
			</div>
		</div>
	<?php endif; ?>
	<?php if (isset($error)) : ?>
		<div class="col-md-12">
			<div class="alert alert-danger" role="alert">
				<?= $error ?>
			</div>
		</div>
	<?php endif; ?>
	<?php
	 /*
	here we can be send the notification from the admin to selected online users
	*/
	?>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading"><?php echo lang('lbl_admin_language_add_tag'); ?></div>
				<div class="panel-body">
					<!-- /.Form start -->
					<form name="add_edit_user" id="add_edit_user" action="#" enctype="multipart/form-data" method="post">
						<div class="form-group">
							<label class="control-label"><?php echo lang('lbl_admin_language_add_language'); ?></label>
							<div class="controls">    
								<input class="form-control" type="text" name="name" id="name" value=""/>								
							</div>
						</div>	
						<div class="form-group">
							<label class="control-label"><?php echo lang('lbl_is_rtl'); ?></label>
							<div class="controls">    
								<input type="checkbox" name="rtl" class="form-control" value="1" >							
							</div>
						</div>	
						<div class="form-actions">
							<input type="submit" class="btn btn-primary" name="btn_submit" id="btn_submit" value="<?php echo lang('lbl_admin_language_add_submit'); ?>"/>
							<input type="button" name="back" value="<?php echo lang('lbl_admin_language_add_cancel'); ?>" class="btn"/>
						</div>
					</form>
					<!-- /.form over -->
				</div>
			</div>
		</div>
			<!-- /.col-lg-3 col-md-6 -->
	</div>
</div>
<script>
$("#select_all").change(function(){  //"select all" change
    $(".chkbox").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status
});
//".checkbox" change
$('.chkbox').change(function(){
    //uncheck "select all", if one of the listed checkbox item is unchecked
    if(false == $(this).prop("checked")){ //if this item is unchecked
        $("#select_all").prop('checked', false); //change "select all" checked status to false
    }
    //check "select all" if all checkbox items are checked
    if ($('.chkbox:checked').length == $('.chkbox').length ){
        $("#select_all").prop('checked', true);
    }
});
</script>