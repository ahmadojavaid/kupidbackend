<div class="row justify-content-center">			
	<div class="col-md-8">  
		<div class="step-form">
			<form action="<?php echo base_url('user/reg_contact'); ?>" method="post" class="text-center mt-3">
				<div class="row setup-content" id="step-1">
					<div class="col-md-12">
						<h4 class="title divider-3 mb-5"><?php echo lang('lbl_gallery_photo_name'); ?></h4>

						<div class="alert text-center" align="center">		
							<?php 
							if(!empty($_SESSION['register_success']))
							{?>

								<div class="row" align="center">
									<div class="col-md-12 ">
										<div class="alert mysuccessmsg" role="success">
											<?php echo $_SESSION['register_success']; ?>
										</div>
									</div>
								</div>
								<!--h3 style="color: green;margin-bottom:10px;"><?php echo $_SESSION['register_success'];?></h3-->
								<?php             
							}
							?>								
							<?php 	
							if(!empty($_SESSION['success']))
							{
								?>
								<div class="row" align="center">
									<div class="col-md-12 ">
										<div class="alert mysuccessmsg" role="success">
											<?php echo $_SESSION['success']; ?>
										</div>
									</div>
								</div>
								<?php
							}
							else if(!empty($_SESSION['fail']))
							{
								?>
								<div class="row" align="center">
									<div class="col-md-12 ">
										<div class="alert myerrormsg" role="alert">
											<?php echo $_SESSION['fail']; ?>
										</div>
									</div>
								</div>
								<?php
							}
							?>
						</div>
						<div class="row justify-content-center">
							<div class="col-lg-6 col-md-10">
								<div class="form-group">									
									<div class="row">
										<div class="col-sm-8 mb-4">
										<!--input style="display:none" type="file" id="imgupload1" name="imgupload1" /-->                            
											<label for="img1" class="red-text avatar-view" >
											<?php
											if(!empty($userdetail->profile_image))
											{															
												$str=base_url("uploads/".$userdetail->profile_image);
												if(file_exists(DIRECTORY_PATH."uploads/".$userdetail->profile_image))
												{																			
													?>
													<img class="img-center w-100 imgupload1" id="1" src="<?php echo $str; ?>" alt="" >
													<?php
												}
												else
												{
													?>
													<img class="img-center w-100 imgupload1" id="1" src="<?php echo base_url('images/step/01.png');?>" alt="">
													<?php
												}							
											}
											else
											{															
												?><img class="img-center w-100 imgupload1" id="1" src="<?php echo base_url('images/step/01.png');?>" alt="">
												<?php
											}
											?>
											</label>																			
										</div>											
										<!--input class="edit-images img1" type="file" id="img1" class="" name="1"  /-->			
										<div class="col-sm-4">
											<div class="row">
											<?php												
											$imgurl="";
											$img="";
											$imgid="";
											for($i=0;$i<=4;$i++)
											{	
												$n=count($contentt);
												$imgurl=base_url("images/step/0".($i+2).".png");
												for($j=0;$j<$n;$j++)
												{
													$ik="img".($i+2);
													if($contentt[$j]["img_key"]==$ik)
													{
														if($contentt[$j]["img_url"]!="")
															$imgurl=base_url("uploads/".$contentt[$j]["img_url"]);
														$img=$contentt[$j]["img_url"];
														$imgid=$contentt[$j]["id"];
													}
												}
												if($i<=1)
													echo "<div class='col-sm-12 mb-4' id='imgupload".($i+2)."'>";
												else
													echo "<div class='col-sm-4' id='imgupload".($i+2)."'>";
												?>																									
												<label for="<?php echo "img".($i+2); ?>" class="red-text  avatar-view<?php echo ($i+2); ?>" >
												<?php	
												if($img!="")
												{																							
													if(file_exists(DIRECTORY_PATH."uploads/$img"))
													{	
														?>
														<img class="img-center w-100 imgupload<?php echo ($i+2); ?>" id="<?php echo ($i+2); ?>" src="<?php echo $imgurl; ?>" alt="" >
														<?php
													}
													else
													{
														?>
														<img class="img-center w-100 imgupload<?php echo ($i+2); ?>" id="<?php echo ($i+2); ?>" src="<?php echo base_url('images/step/0'.($i+2).'.png');?>" alt="">
														<?php
													}							
												}
												else
												{															
													?><img class="img-center w-100 imgupload<?php echo ($i+2); ?>" id="<?php echo ($i+2); ?>" src="<?php echo base_url('images/step/0'.($i+2).'.png');?>" alt="">
													<?php
												}																
												?>
												</label>													
												<?php
												if((strpos($imgurl, 'step') === false)){
													?>
													<a class="remove-bg" data-position="<?php echo ($i+2);?>">
														<i class="fa fa-close "></i>
													</a>
													<?php
												}?>			
												</div>														
												<!--input class="edit-images " type="file" id="<?php echo "img".($i+2); ?>" name="<?php echo ($i+2); ?>"  /-->
												<?php
												if($i==1)
													echo "</div></div></div><div class='row'>";
											}?>												
										</div>
									</div>																			
								</div>								
							</div>
							<div class="clearfix"></div>
							<p>
								Recommanded Resolution For Images<br/>
								Image #1 	:	280px	*	250px<br/>
								Image #2-#6	:	1498px	*	844px<br/>
							</p>	
							<div class="form-group mb-0">
								<button type="submit" class="button btn-theme full-rounded btn nextxBtn btn-lg mt-2 animated right-icn" ><?php echo lang('lbl_menu_name_next'); ?></button>	
							</div>
						</div>
					</div>

					</div>
				</div>
			</form>
<form action="<?php echo site_url()."admin/admin/update_user"?>" method="post">
<div class="page_wrapper">	
    <div class="row padd-bottom">
		<div class="col-sm-12">
			<h1 class="page-header"><?php echo $content->fname."  ".$content->lname; ?></h1>
		</div>
	</div>
    <div class="row">
	<?php if (validation_errors()) : ?>
		<div class="col-md-12">
			<div class="alert alert-danger" role="alert">
				<?= validation_errors() ?>
			</div>
		</div>
	<?php endif; 
	if (isset($error)) : ?>
		<div class="col-md-12">
			<div class="alert alert-danger" role="alert">
				<?= $error ?>
			</div>
		</div>
	<?php endif; ?>
	<?php echo form_open_multipart('', array('role'=>'form')); ?>
	<?php 
		if($content->profile_image!='')
		{
			$imgurl=$content->profile_image;
			if(!file_exists($_SERVER['DOCUMENT_ROOT']."/uploads/thumbnail/$imgurl"))
				$profile_img = base_url("uploads/thumbnail/$imgurl");
			else
				$profile_img = base_url().'assets/images/default.png';
		} 
		else 
		{
			$profile_img = base_url().'assets/images/default.png';
		}?>
		<div class="col-lg-4">
    		<!--div class="form-group">
    			<div class="controls">
    				<img id="profile_image_display" src="<?php echo $profile_img; ?>" height="250" width="250" alt="<?php echo $content->fname; ?>" />
    			</div>
    		</div-->
    		<div class="row">

					<div id="myCarousel" class="carousel slide" data-ride="carousel">
						<ol class="carousel-indicators">
							<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
							<?php $i=1; foreach($gallery as $row) { ?>
							<li data-target="#myCarousel" data-slide-to="<?php echo $i; ?>"></li>
							<?php $i++; } ?>
						</ol>

						<div class="carousel-inner">
							<div class="item active">
								<img id="profile_image_display" src="<?php echo $profile_img; ?>" style="height:350px;" alt="<?php echo $content->fname; ?>" />
							</div>
							
							<?php 
							foreach($gallery as $row)
							{
								if(!empty($row['img_url'])){
									?>
										<div class="item">
											<img src="<?php echo base_url("uploads/thumbnail/".$row['img_url']); ?>" style="height:350px;">
										</div>
									<?php
								}
							}
							?>
						</div>
					</div>
					<div style="margin-top:10px;">				
						<input type="file" name="file" class="btn btn-primary">			
					</div>
				</div>
    	</div>
    	<input type="hidden" name="user_id" value="<?php echo $content->id?>">
        <div class="col-lg-8">
			<div class="form">
		    	<ul class="nav nav-tabs">
					<li class="active"><a data-toggle="tab" href="#home"><?php echo(lang("lbl_profile_information")) ?></a></li>
				</ul>

				<div class="tab-content">
					<div id="home" class="tab-pane fade in active">
						<div class="row">
							<div class="col-sm-12">
								 <div class="form-group">
									<label for="exampleInputEmail1"><?php echo lang('lbl_admin_user_detail_first_name'); ?>:</label>
									<input type="text" class="form-control" id="exampleInputEmail1"  id="fname" name="fname" value="<?php echo $content->fname; ?>" placeholder="Enter a First Name">
								</div>		
								<div class="form-group">
									<label for="exampleInputEmail1"><?php echo lang('lbl_admin_user_detail_last_name'); ?>:</label>
									<input type="text" class="form-control" id="lname" name="lname"  value="<?php echo $content->lname; ?>" placeholder="Enter a Last Name">
								</div>
								<div class="form-group">
									<label for="exampleInputEmail1"><?php echo lang('lbl_admin_user_detail_birthdate'); ?>:</label>
									<div class="row">
									   <div class="col-sm-4">
											<input type="number" min="1" max="12" value="<?php echo date_format(date_create($content->dob),"m"); ?>" class="form-control" name="month" id="exampleInputMonth1" placeholder="Month" >
									   </div>
									   <div class="col-sm-4">
											<input type="number" min="1" max="31"  value="<?php echo date_format(date_create($content->dob),"d"); ?>" class="form-control" id="exampleInputDay" placeholder="Day" name="day" >
										</div>
									   <div class="col-sm-4">
											<input type="number" min="<?php echo (date("Y")-65)  ?>" max="<?php echo date("Y") ?>"  value="<?php echo date_format(date_create($content->dob),"Y"); ?>"  class="form-control" id="exampleInputyear" placeholder="year" name="year">
									   </div>
									</div>
									<div class="form-group">
									   <label for="exampleInputMassage"><?php echo lang('lbl_admin_user_detail_about'); ?>:</label>
									   <textarea class="form-control" name="about" placeholder="Massage" rows="3" ><?PHP echo $content->about; ?></textarea>
									</div>
									<div class="form-group">
										<label for="exampleInputMassage"><?php echo lang('lbl_admin_user_detail_gender'); ?>:</label>
													<div class="row">
														<div class="col-sm-4 xs-mb-2">
															<div class="radio  <?php if($content->gender=="female") echo "checked"?>">
																<span class="icons">
																	<span class="first-icon fa fa-circle-o"></span>
																	<span class="second-icon fa fa-dot-circle-o"></span>
															  	</span>
																<input type="radio" <?php if($content->gender=="female") echo "checked"?> name="gender" id="radio3" value="female">
																<label for="radio3"><?php echo lang('lbl_register_female'); ?></label>
															</div>
														</div>
														<div class="col-sm-4">
															<div class="radio  <?php if($content->gender=="male") echo "checked"?> ">
																<span class="icons">
																	<span class="first-icon fa fa-circle-o"></span>
																	<span class="second-icon fa fa-dot-circle-o"></span>
															  	</span>

																<input type="radio" <?php if($content->gender=="male") echo "checked"?> name="gender" id="radio4" value="male">
																<label for="radio4"><?php echo lang('lbl_register_male'); ?></label>
															</div>
														</div>
													</div>
										
									</div>
									<div class="form-group">
										<label for="exampleInputEmail1"><?php echo lang('lbl_admin_user_detail_education'); ?>:</label>
										<input type="text" class="form-control"  value="<?php echo $content->education; ?>" id="education" name="education">
									</div>
									<div class="form-group">
										<label for="exampleInputEmail1"><?php echo lang('lbl_admin_user_detail_profession'); ?>:</label>
										<input type="text" class="form-control"  value="<?php echo $content->profession; ?>" id="profession" name="profession">
									</div>
								<?php
								$hight="3'0 (92 cm), 3'1 (94 cm),3'2 (97 cm),3'3 (99 cm),3'4 (102 cm),3'5 (104 cm),3'6 (107 cm),3'7 (109 cm),3'8 (112 cm),3'9 (114 cm),3'10 (117 cm),3'11 (119 cm),4'0 (122 cm),4'1 (125 cm),4'2 (127 cm),4'3 (130 cm),4'4 (132 cm),4'5 (135 cm),4'6 (137 cm),4'7 (140 cm),4'8 (142 cm),4'9 (145 cm),4'10 (147 cm),4'11 (150 cm),5'0 (152 cm), 5'1 (155 cm),5'2 (158 cm),5'3 (160 cm),5'4 (163 cm),5'5 (165 cm),5'6 (168 cm),5'7 (170 cm),5'8 (173 cm),5'9 (175 cm),5'10 (178 cm),5'11 (180 cm),6'0 (183 cm),6'1 (185 cm),6'2 (188 cm),6'3 (191 cm),6'4 (193 cm),6'5 (196 cm),6'6 (198 cm),6'7 (201 cm),6'8 (203 cm),6'9 (206 cm),6'10 (208 cm),6'11 (211 cm),7'0 (213 cm)";
								$height=explode(",",$hight);					
								?>
									<div class="form-group">
										<label for="disabledSelect"><?php echo lang('lbl_admin_user_detail_height'); ?>:</label>
										<select  class="form-control" id="height" name="height">
											<option selected value=""><?php echo lang('lbl_admin_user_detail_select_height'); ?></option>
								<?php 	for($i=0;$i<count($height);$i++)
										{  ?>
											<option <?php echo ($height[$i]==$content->height)?'selected':''?> value="<?php echo $height[$i];  ?>"><?php echo $height[$i];  ?></option>
								<?php  	} ?>
										</select>
									</div>
									<div class="form-group">
										<label class="divider-3 mb-3"><?php echo lang('lbl_register_address'); ?>:</label>
										<input type="text" class="form-control" id="address" name="address"  placeholder="<?php echo lang('lbl_register_address'); ?>" value="<?php if(isset($content->address)) echo $content->address;?>">
										<?php 
											if(empty($googleapiskey['value'])){
												?>
													<br>Enter Google place API key in configuration.
												<?php
											}
										?>
									</div>		    
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group mb-0 text-center">
				<button type="submit" class="button btn-theme full-rounded btn nextxBtn btn-lg mt-20 animated right-icn" id="btnstep3"><?php echo lang('lbl_register_submit'); ?><i class="glyph-icon flaticon-hearts" aria-hidden="true"></i></button>
			</div>
		</div>
    </div>
</div>
</form>
		</div>
	</div> 
</div>