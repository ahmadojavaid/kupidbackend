<link rel="stylesheet" type="text/css" href="<?php echo(base_url("Newassets/admin/css/general.css"))?>">
<section class="inner-intro bg bg-fixed bg-overlay-black-60">
	<div class="container">
		<div class="row intro-title text-center">
			<div class="col-sm-12">
				<div class="section-title">
					<h1 class="pos-r divider"><?php if($this->session->userdata('logged_in')) {echo lang('lbl_sidebar_gallery');} else { echo lang('lbl_menu_name_register');}?></h1>
				</div>
			</div>
		</div>
	</div>
</section>
<div class="ajaxloader">
	<img src="<?php echo base_url("Newassets/images/ajaxloader.gif")?>" id="ajaxloader" style="display: none;">
</div>

<section class="page-section-ptb text-white">	
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-4"></div>
			<div class="col-md-6">  
				<div class="step-form">
					<div class="row justify-content-center setup-content" id="step-1">
						<div class="col-lg-8 col-sm-12">								
							<div class="alert" align="center">	
							<?php  if($this->session->flashdata("error"))
									{?>
										<br/>
										<div class="row">
											<div class="col-sm-12 text-uppercase  text-danger">
												<div class='alert myerrormsg'>
													<?php 
													echo $this->session->flashdata("error");  ?>
												</div>
											</div>
										</div>
							<?php   }
									if($this->session->flashdata("success"))
									{ ?>
										<br/>
										<div class="row">
										   <div class="col-sm-12 text-uppercase  text-success">
											<div class='alert mysuccessmsg'>
											  <?php 
											  echo $this->session->flashdata("success");  ?>
										  </div>
										   </div>
										</div>
							<?php   } ?>			
							</div>
							<div class="row justify-content-center">
								<div class="col-lg-10 col-md-9">
									<div class="form-group">									
										<div class="row">
											<div class="col-sm-8 mb-4">                      
												<label for="img1" class="red-text avatar-view" >
													<?php
													if(!empty($userdetail->profile_image))
													{															
														$str=base_url("uploads/thumbnail/".$userdetail->profile_image);
														if(file_exists(DIRECTORY_PATH."uploads/thumbnail/".$userdetail->profile_image))
														{																			
															?>
															<img class="w-100 imgupload1" id="1" src="<?php echo $str; ?>" alt="" >
															<?php
														}
														else
														{
															?>
															<img class="w-100 imgupload1" id="1" src="<?php echo base_url('images/step/01.png');?>" alt="">
															<?php
														}							
													}
													else
													{															
														?><img class="w-100 imgupload1" id="1" src="<?php echo base_url('images/step/01.png');?>" alt="">
														<?php
													}
													?>
												</label>																			
											</div>												


											<div class="col-sm-4">
												<div class="row">
													<?php												
													$imgurl="";
													$img="";
													$imgid="";
													for($i=0;$i<=4;$i++)
													{	
														$n=count($content);
														$imgurl=base_url("images/step/0".($i+2).".png");
														for($j=0;$j<$n;$j++)
														{
															$ik="img".($i+2);
															if($content[$j]["img_key"]==$ik)
															{
																if($content[$j]["img_url"]!="")
																$imgurl=base_url("uploads/thumbnail/".$content[$j]["img_url"]);
																$img=$content[$j]["img_url"];
																$imgid=$content[$j]["id"];
															}
														}
														if($i<=1)
															echo "<div class='col-sm-12 mb-3' id='imgupload".($i+2)."'>";
														else
															echo "<div class='col-sm-4 col-xs-4' id='imgupload".($i+2)."'>";
														?>																	
															<label for="<?php echo "img".($i+2); ?>" class="red-text avatar-view<?php echo ($i+2); ?>" >
																<?php	
																if($img!="")
																{																							
																	if(file_exists(DIRECTORY_PATH."uploads/thumbnail/$img"))
																	{	
																		?>
																		<img class="w-100 imgupload<?php echo ($i+2); ?>" id="<?php echo ($i+2); ?>" src="<?php echo $imgurl; ?>" alt="" >
																		<?php
																	}
																	else
																	{
																		?>
																		<img class="w-100 imgupload<?php echo ($i+2); ?>"  id="<?php echo ($i+2); ?>" src="<?php echo base_url('images/step/0'.($i+2).'.png');?>" alt="">
																		<?php
																	}							
																}
																else
																{															
																	?><img class="w-100 imgupload<?php echo ($i+2); ?>"  id="<?php echo ($i+2); ?>" src="<?php echo base_url('images/step/0'.($i+2).'.png');?>" alt="">
																	<?php
																}																
																?>
															</label>	
															<?php
															if((strpos($imgurl, 'step') === false)){
																?>
																<a class="remove-bg" href="javascript:void(0)" data-position="<?php echo ($i+2);?>" data-user="<?php echo $userdetail->id; ?>">
																	<i class="fa fa-close "></i>
																</a>
																<?php
															}?>																
															</div>														
														<?php
														if($i==1)
															echo "</div></div></div><div class='row'>";
													}
													?>												
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
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<?php $this->load->view('_crop'); ?>