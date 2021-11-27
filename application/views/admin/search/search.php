<div class="page_wrapper">
	<section>
		<div class="container-fluid text-center">
			<div class="row">
				<div class="col-sm-12 text-uppercase page-title">
					<h3><?php echo lang('lbl_admin_search'); ?></h3>
					<div><img class="img-center" src="<?php echo base_url('images/title-bdr.png'); ?>" alt=""></div>
				</div>
			</div> 
			<?php
				if(!empty($_SESSION['success']))
				{
					echo "<h3 style='color:green'>$_SESSION[success]</h3>";
				}
				else if(!empty($_SESSION['error']))
				{
					echo "<h4 style='color:red'>$_SESSION[error]</h4>";
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
			<div class="row">
				<div class="row">
					<div class="col-xs-12" style="align:center;">
						<div class="search-form">
							<input class="form-control search-input"  id="txtsearch" name="txtsearch" type="search" placeholder="<?php echo lang('lbl_admin_search'); ?>">
							<button class="search-button" id="pgs_cars_search_btn" value="Search" type="submit"><i class="fa fa-search"></i></button>
						</div>
					</div> <br/><br/>
					<?php
					/*
						At below div tag event charector type fire ajax event and fetch that cherector matched data list is display user data in below div tag and on the click on user name we can viw that user profile informartion information
					*/
					?>
					<div id="userdata" >
					</div>  <!-- /.col-lg-3 col-md-6 -->
				</div>	<!-- /.col-lg-3 col-md-6 -->
			</div>
		</div>
	</section>
</div>
<script>
	$(document).ready(function() {
		$("#txtsearch").keyup(function(){			
		var users=$("#txtsearch").val()
			$.ajax({
				type:'post', 
				url:'fetchusers',
				data:{users:users},
				success:function(data){
					$("#userdata").html(data);
				}
			});
		});
	});
</script>