<div class="content">
	<section>
        <div class="container-fluid text-center">
			<div class="row">
				<div class="col-sm-12 text-uppercase">
					<h3><?php echo lang('lbl_admin_dashboard'); ?></h3>
					<div><img class="img-center" src="<?php echo base_url('images/title-bdr.png'); ?>" alt=""></div>
				</div>
			</div>
			<div class="row mt-50">
				<div class="col-sm-4">
					<div class="register-box">
						<div class="frnd-box"> <img class="img-center" src="<?php echo base_url('images/daily.png'); ?>" alt="">
							<div class="frnd-cntn">
								<?php
								/*	Here display today registration information*/
								?>
								<h3 class=""><?php echo lang('lbl_admin_dashboard_today'); ?></h3>
								<div class="form-group">
									<span class="btn btn-default gradiant-btn"><?php echo $today; ?></span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="register-box">
						<div class="frnd-box"> <img class="img-center" src="<?php echo base_url('images/weekly.png'); ?>" alt="">
							<div class="frnd-cntn">
							<?php
									/*	Here display Weekly registration information*/
									?>
							  <h3><?php echo lang('lbl_admin_dashboard_weekly'); ?></h3>
							  <div class="form-group">
							   <span class="btn btn-default gradiant-btn"><?php echo $week; ?></span>
							  </div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="register-box">
						<div class="frnd-box"> <img class="img-center" src="<?php echo base_url('images/monthly.png'); ?>" alt="">
							<div class="frnd-cntn">
							<?php
									/*	Here display Monthly registration information*/
									?>
							  <h3><?php echo lang('lbl_admin_dashboard_monthly'); ?></h3>
							  <div class="form-group">
								<span class="btn btn-default gradiant-btn"><?php echo $week; ?></span>
							  </div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<section>
				<div class="row">
					<div class="col-sm-12 text-uppercase">
					  <h3><?php echo lang('lbl_admin_dashboard'); ?></h3>
					  <div><img class="img-center" src="<?php echo base_url('images/title-bdr.png'); ?>" alt=""></div>
					</div>
				</div>
				<div class="row mt-50">
					<div class="col-lg-3">
						<?php
						/*	Here display Total number of user available in our app*/
						?>
						<div class="counter">
							<img src="<?php echo base_url('images/01.png'); ?>" alt="#">
							<span class="timer" data-to="25000" data-speed="10000"><?php echo $total_users; ?></span><label><?php echo lang('lbl_admin_dashboard_total'); ?></label>
						</div>
					</div>
					<div class="col-lg-3">
						<div class="counter">
							<?php
							/*	Here display Total number of online user available in our only iso app*/
							?>
							<img src="<?php echo base_url('images/02.png'); ?>" alt="#">
							<span class="timer" data-to="25000" data-speed="10000"><?php echo $online_users; ?></span><label><?php echo lang('lbl_admin_dashboard_online'); ?></label>
						</div>
					</div>
					<div class="col-lg-3">
						<div class="counter">
							<?php
							/*	Here display Total number of man online user available in our only iso app*/
							?>
							<img src="<?php echo base_url('images/03.png'); ?>" alt="#">
							<span class="timer" data-to="25000" data-speed="10000"><?php echo $male_users; ?></span><label><?php echo lang('lbl_admin_dashboard_men'); ?></label>
						</div>
					</div>
					<div class="col-lg-3">
						<div class="counter">
							<?php
							/*	Here display Total number of Woman online user available in our only iso app*/
							?>
							<img src="<?php echo base_url('images/04.png'); ?>" alt="#">
							<span class="timer" data-to="25000" data-speed="10000"><?php echo $female_users; ?></span><label><?php echo lang('lbl_admin_dashboard_woman'); ?></label>
						</div>
					</div>
				</div>
			</section>
        </div>
    </section>
</div>