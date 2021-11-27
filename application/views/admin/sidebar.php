<div class="wrapper">
	<div class="sidebar" data-color="purple">
		<div class="sidebar-wrapper">
			<div class="logo">
				<a href="<?php echo base_url();?>" class="simple-text">
				<?php
				/*$logo=$this->Common_model->get_logo();
				if($logo){
					?>
					<img src="<?php echo base_url('Newassets/images/logo.png')?>"  alt="">
					<?php
				}
				else{
					?>
					<img src="<?php echo base_url('Newassets/images/logo.png')?>"  alt="">
					<?php
				}*/
				?>
					<img src="<?php echo base_url('Newassets/images/logo.png')?>"  alt="">
				</a>
			</div>
			<ul class="nav">
				<li class="<?php echo ($this->uri->segment(3)=="dashboard")?"active":""; ?>">
						<a href="<?php echo base_url().'admin/Admin/dashboard'  ?>"><i class="pe-7s-graph"></i> <p><?php echo lang('lbl_admin_sidebar_dashboard'); ?></p></a></li>
				<li class="<?php echo ($this->uri->segment(3)=="admod")?"active":""; ?>">
						<a href="<?php echo base_url().'admin/Admin/admod'  ?>"><i class="fa fa-dashboard fa-fw"></i><p><?php echo lang('lbl_admin_sidebar_admod'); ?></p></a></li>
				<li class="<?php echo ($this->uri->segment(3)=="site_setting")?"active":""; ?>">
						<a href="<?php echo base_url().'admin/Admin/site_setting'  ?>"><i class="fa fa-cogs"></i><p><?php echo lang('lbl_admin_sidebar_site_setting'); ?></p></a></li>
				<li class="<?php echo ($this->uri->segment(3)=="notifications")?"active":""; ?>">
						<a href="<?php echo base_url().'admin/Admin/notifications'  ?>"><i class="fa fa-bell" ></i><p><?php echo lang('lbl_admin_sidebar_push_notification'); ?></p></a></li>
				<li class="<?php echo ($this->uri->segment(3)=="search")?"active":""; ?>">
						<a href="<?php echo base_url().'admin/Admin/search'  ?>"><i class="pe-7s-note2"></i> <p><?php echo lang('lbl_admin_sidebar_search'); ?></p> </a></li>
                <li class="<?php echo ($this->uri->segment(3)=="sampledata")?"active":""; ?>">
						<a href="<?php echo base_url().'admin/Admin/sampledata'  ?>"><i class="fa fa-users"></i><p><?php echo lang('lbl_sampledata'); ?></p> </a></li>
				<li class="<?php echo ($this->uri->segment(3)=="language")?"active":""; ?>">
						<a href="<?php echo base_url().'admin/language/'  ?>"><i class="fa fa-language"></i> <p><?php echo lang('lbl_admin_sidebar_language'); ?></p> </a></li>
				<li class="<?php echo ($this->uri->segment(3)=="block_detail")?"active":""; ?>">
						<a href="<?php echo base_url().'admin/Admin/block_detail'  ?>"><i class="fa fa-ban" ></i> <p><?php echo lang('lbl_admin_sidebar_block_request'); ?></p> </a></li>
				<li class="<?php echo ($this->uri->segment(3)=="report_detail")?"active":""; ?>">
						<a href="<?php echo base_url().'admin/Admin/report_detail'  ?>"><i class="fa fa-ban" ></i> <p><?php echo lang('lbl_admin_sidebar_report_request'); ?></p> </a></li>					
				<li class="<?php echo ($this->uri->segment(3)=="block_users")?"active":""; ?>">
						<a href="<?php echo base_url().'admin/Admin/block_users'  ?>"><i class="fa fa-lock"></i><p><?php echo lang('lbl_admin_sidebar_block_users'); ?></p> </a></li>
				<li class="<?php echo ($this->uri->segment(3)=="siteError")?"active":""; ?>">
						<a href="<?php echo base_url().'admin/Admin/siteError'  ?>"><i class="fa fa-exclamation-triangle"></i><p><?php echo lang('lbl_admin_sidebar_site_error'); ?></p> </a></li>				
                <li class="<?php echo ($this->uri->segment(3)=="religion")?"active":""; ?>">
						<a href="<?php echo base_url().'admin/Admin/religion'  ?>"><i class="fa fa-users"></i><p><?php echo lang('lbl_admin_sidebar_site_religion'); ?></p> </a></li>
				<li class="<?php echo ($this->uri->segment(3)=="ethnicity")?"active":""; ?>">
						<a href="<?php echo base_url().'admin/Admin/ethnicity'  ?>"><i class="fa fa-users"></i><p><?php echo lang('lbl_admin_sidebar_site_ethnicity'); ?></p> </a></li>
				<li class="<?php echo ($this->uri->segment(3)=="question")?"active":""; ?>">
						<a href="<?php echo base_url().'admin/Admin/question'  ?>"><i class="fa fa-question"></i><p><?php echo lang('lbl_admin_sidebar_site_question'); ?></p> </a></li>
				<li class="<?php echo ($this->uri->segment(3)=="setting")?"active":""; ?>">
						<a href="<?php echo base_url().'admin/Setting/index'  ?>"><i class="fa fa-cogs"></i><p><?php echo lang('lbl_configuration'); ?></p></a></li>
				<li class="<?php echo ($this->uri->segment(3)=="setting")?"active":""; ?>">
						<a href="<?php echo base_url().'admin/Setting/features'  ?>"><i class="fa fa-cogs"></i><p><?php echo lang('lbl_features'); ?></p></a></li>
				<li class="<?php echo ($this->uri->segment(3)=="logout")?"active":""; ?>">
					<a href="<?php echo base_url().'admin/Admin/logout'; ?>"> <i class="fa fa-sign-out" aria-hidden="true"></i>
					<p><?php echo lang('lbl_admin_sidebar_log_out'); ?></p></a> </li>	
			</ul>
		</div>
	</div>
    <div class="main-panel">
		<nav class="navbar navbar-default navbar-fixed">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2">
						<span class="sr-only"><?php echo lang('lbl_admin_sidebar_toogle'); ?></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>
				<div class="collapse navbar-collapse">
					<ul class="nav navbar-nav navbar-right">
						<li>
							<a class="profile-pic" href="#"> 
								<img src="<?php  echo base_url("images/user-img.png"); ?>" alt="user-img" class="img-circle">
								<b><?php echo $_SESSION["fname"]; ?></b>
							</a>
						</li>
					</ul>
				</div>
			</div>
		</nav>