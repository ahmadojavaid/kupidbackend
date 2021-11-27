<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
/*
Fetch user Gallery and from that fetch first profile image of friend
*/
	$url = array_column($gallery, 'img_url');
	$flg=0;
	foreach($gallery as $gallery_data)
	{
		if(!empty($gallery_data["img_url"]))
		{
			$flg=1;
		}
	}
	/*
	Another Gallery images are display in slider here of Friend
	*/
	?>
	<section class="inner-intro details-page bg bg-fixed bg-overlay-black-20 pos-r" style="background-image:url()">
		<div class="banner <?php echo ($flg==1)?"":"no-images"; ?>">
			<?php
			if($flg==1)
			{ ?>
				<div class="owl-carousel" data-nav-arrow="true" data-items="1" data-md-items="1" data-sm-items="1"> 
					<?php
					for($i=0;$i<count($gallery);$i++ ) 
					{
						if(!empty($gallery[$i]['img_url'])){
							?>
							<div class="item">
								<div class="Slider-img"><img src="<?php echo base_url("uploads/".$gallery[$i]['img_url']); ?>"></div>
							</div>
							<?php
						}
					}
					?>
				</div>	 
				<?php 
			}
			?>
		</div>
	</section>
	<section>
		<div class="container">
			<div class="row">
				<div class="col-sm-12">
					<div class="profile-cntn text-white">
						<ul>
							<li>
								<?php
								if(!empty($content->profile_image))
								{
									$str=base_url("uploads/thumbnail/".$content->profile_image);
									if(file_exists(DIRECTORY_PATH."uploads/thumbnail/".$content->profile_image))
									{      
										?>
										<img src="<?php echo $str; ?>" alt="" >
										<?php
									}
									else
									{
										?>
										<img src="<?php echo base_url('images/profile/profile-img.png');?>" alt="">
										<?php
									}       
								}
								else
								{
									?>
									<img src="<?php echo base_url('images/profile/profile-img.png');?>" alt="">
									<?php
								}
								?> 
							</li>
							<li>
								<div class="profile-text"> 
									<h2><?php echo $content->fname." ".$content->lname; ?></h2> 
									<h5>
										<?php $today=date('Y-m-d');
										$dob=$content->dob;				
										$diff = date_diff(date_create($dob),date_create($today));
										echo $age = $diff->format('%Y');
										echo lang('lbl_friend_year_old'); ?>
									</h5>
								</div>
							</li>
						</ul>
					</div>
			   </div>
			</div>
		</div>
	</section> 
	<section class="page-section-ptb pt-100 text-left">
		<div class="container">
			<div class="row">
				<div class="col-sm-12 ">
					<h4 class="title divider-3 mb-5"><?php echo lang('lbl_friend_basic_detail'); ?></h4>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div class="table-responsive">
						<table class="table table-bordered text-center">
							<tbody>
								<tr>
									<td><?php echo lang('lbl_user_gender'); ?></td>
									<td><?php echo ucfirst($content->gender); ?></td>
									<td><?php echo lang('lbl_user_education'); ?></td>
									<td><?php echo ucfirst($content->education); ?></td>
								</tr>
								<tr class="tr-bg">
									<td><?php echo lang('lbl_user_age'); ?></td> 
									<td><?php $today=date('Y-m-d');
										$dob=$content->dob;				
										$diff = date_diff(date_create($dob),date_create($today));
										echo $age = $diff->format('%Y');	?> <?php echo lang('lbl_user_year_old'); ?></td>
									<td><?php echo lang('lbl_user_height'); ?></td>
									<td><?php echo $content->height; ?></td>
								</tr>
								<tr class="tr-bg">
									<td><?php echo lang('lbl_user_birthdate'); ?></td>
									<td><?php echo date_format(new DateTime($content->dob),"F d , Y");; ?></td>
									<td><?php echo lang('lbl_user_kids'); ?></td>
									<td><?php echo $content->kids; ?></td>
								</tr>
								<tr class="tr-bg">
									<td><?php echo lang('lbl_user_looking'); ?></td>
									<td><?php echo ucfirst($content->gender_pref); ?></td>
									<td><?php echo lang('lbl_user_work_as'); ?></td>
									<td><?php echo ucfirst($content->profession); ?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="row mt-5">
				<div class="col-sm-12 text-left">
					<h4 class="title divider-3 mb-5"><?php echo lang('lbl_friend_about'); ?></h4>
					<p><?php echo $content->about; ?></p>
				</div>
			</div>
		</div>
	</section>