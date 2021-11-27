<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<style>
	.ui-slider .ui-slider-handle {
		width:3em;
		left:-.6em;
		hight:14em; 
		background:#FABD0D;
		text-decoration:none;
		text-align:center;
	}
	.ui-slider-range{
		background:#FABD0D;
	}
	.slider-tip {
		opacity:1;
		bottom:120%;
		margin-left: -1.36em;
	}
	.tooltip-inner{
		background:#FABD0D;
		width:3em;
		hight:10em; 
		font-size:1em;
	}
	.tooltip-arrow{
		color:#FABD0D;
	}
	.ui-state-default{
		background:none;
	}
	body {
        font-family: arial;
        background: rgb(242, 244, 246);
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }
      h3 {
        color: rgb(199, 204, 209);
        font-size: 28px;
        text-align: center;
      }
      #elements-container {
        text-align: center;
      }
      .draggable-element {
        display: inline-block;
        height: 40px;
		padding:1%;
        background: white;
        border: 1px solid rgb(196, 196, 196);
        line-height: 10%;
        text-align: center;
        margin: 10px;
        color: rgb(51, 51, 51);
        font-size: 30px;
        cursor: move;
      }
      .drag-list {
        width: 400px;
        margin: 0 auto;
      }
      .drag-list > li {
        list-style: none;
        background: rgb(255, 255, 255);
        border: 1px solid rgb(196, 196, 196);
        margin: 5px 0;
        font-size: 24px;
      }
      .drag-list .title {
        display: inline-block;
        width: 130px;
        padding: 6px 6px 6px 12px;
        vertical-align: top;
      }
      .drag-list .drag-area {
        display: inline-block;
        background: rgb(158, 211, 179);
        width: 60px;
        height: 40px;
        vertical-align: top;
        float: right;
        cursor: move;
      }
      .code {
        background: rgb(255, 255, 255);
        border: 1px solid rgb(196, 196, 196);
        width: 600px;
        margin: 22px auto;
        position: relative;
      }
      .code::before {
        content: 'Code';
        background: rgb(80, 80, 80);
        width: 96%;
        position: absolute;
        padding: 8px 2%;
        color: rgb(255, 255, 255);
      }
      .code pre {
        margin-top: 50px;
        padding: 0 13px;
        font-size: 1em;
      }
      .slider-tip{
      	display: none;
      }
      .my-pref{
      	background-color: #FABD0D;
	    width: 20%;
     	text-align: center;
	    border-radius: 33px;
	    margin-bottom: 7px;
      }
      .nav-tabs a{
      	color: #000000;
      }
      .nav-tabs a:hover{
      	background-color: #e4e4e4;
      }
#map {
        height: 80%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #description {
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
      }

      #infowindow-content .title {
        font-weight: bold;
      }

      #infowindow-content {
        display: none;
      }

      #map #infowindow-content {
        display: inline;
      }

      .pac-card {
        margin: 10px 10px 0 0;
        border-radius: 2px 0 0 2px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        outline: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
        background-color: #fff;
        font-family: Roboto;
      }

      #pac-container {
        padding-bottom: 12px;
        margin-right: 12px;
      }

      .pac-controls {
        display: inline-block;
        padding: 5px 11px;
      }

      .pac-controls label {
        font-family: Roboto;
        font-size: 13px;
        font-weight: 300;
      }

      #pac-input {
        background-color: #fff;
        font-family: 'Montserrat',sans-serif;
        font-size: 25px;
        font-weight: 300;
        margin-left: 12px;
        padding: 0 11px 0 13px;
        text-overflow: ellipsis;
        width: 80%;
        top:10px !important;
      }

      #pac-input:focus {
        border-color: #4d90fe;
      }

      #title {
        color: #fff;
        background-color: #4d90fe;
        font-size: 25px;
        font-weight: 500;
        padding: 6px 12px;
      }
      #target {
        width: 345px;
      }
    </style>
<?php $datepref=explode(",",$content->date_pref); ?>
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
						<a href="<?php echo base_url('admin/Admin/user_gallery/'.$content->id);?>" class="button btn-theme full-rounded btn nextxBtn btn-lg mt-20 animated right-icn">Edit Gallery</a>
					</div>
				</div>
    	</div>
    	<input type="hidden" name="user_id" value="<?php echo $content->id?>">
        <div class="col-lg-8">
			<div class="form">
		    	<ul class="nav nav-tabs">
					<li class="active"><a data-toggle="tab" href="#home"><?php echo(lang("lbl_profile_information")) ?></a></li>
					<li><a data-toggle="tab" href="#menu1"><?php echo(lang("lbl_preference")) ?></a></li>
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
										<!-- <div class="row">
											<div class="col-sm-6">
											   <div class="radio <?php echo ($content->gender=="male")? "checked":""; ?>" >  
												  <label>
												  <?php if($content->gender=="male") { ?><span class="icons">
															<span class="first-icon fa fa-circle-o"></span>
															<span class="second-icon fa fa-dot-circle-o"></span>
												  </span>	<?php } ?>
														<input type="radio" name="gender" disabled id="blankRadio1" <?php echo ($content->gender=="male")? "checked":""; ?> value="male" aria-label="..." ><?php echo lang('lbl_admin_user_detail_male'); ?>
												  </label>
												</div>		  
											</div>
											<div class="col-sm-6">
											   <div class="radio <?php echo ($content->gender=="female")? "checked":""; ?>" >  
												  <label>
												   <?php if($content->gender=="female") { ?><span class="icons">
															<span class="first-icon fa fa-circle-o"></span>
															<span class="second-icon fa fa-dot-circle-o"></span>
													</span>	<?php } ?>
													<input type="radio" name="gender" disabled id="blankRadi2"  <?php echo ($content->gender=="female")? "checked":""; ?> value="female" aria-label="..." ><?php echo lang('lbl_admin_user_detail_female'); ?>
												  </label>
												</div>
											</div>
										</div> -->
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
										<label for="disabledSelect"><?php echo lang('lbl_admin_user_detail_kids'); ?>:</label>
										<select id="kids" name="kids" class="form-control" >
											<option selected value=""><?php echo lang('lbl_register_kids'); ?></option>
											<option <?php echo ($content->kids==1)?'selected':''; ?> value="1"><?php echo lang('lbl_register_kids'); ?> - 1</option>
											<option <?php echo ($content->kids==2)?'selected':''; ?> value="2"><?php echo lang('lbl_register_kids'); ?> - 2</option>
											<option <?php echo ($content->kids==3)?'selected':''; ?> value="3"><?php echo lang('lbl_register_kids'); ?> - 3</option>
											<option <?php echo ($content->kids==4)?'selected':''; ?> value="4"><?php echo lang('lbl_register_kids'); ?> - 4</option>
											<option <?php echo ($content->kids==5)?'selected':''; ?> value="5"><?php echo lang('lbl_register_kids'); ?> - 5</option>
											<option <?php echo ($content->kids==6)?'selected':''; ?> value="6"><?php echo lang('lbl_register_kids'); ?> - 6</option>
											<option <?php echo ($content->kids==7)?'selected':''; ?> value="7"><?php echo lang('lbl_register_kids'); ?> - 7</option>
											<option <?php echo ($content->kids=="None")?'selected':''; ?> value="None">None</option>
											<option <?php echo ($content->kids=="One Day")?'selected':''; ?> value="One Day">One Day</option>
											<option <?php echo ($content->kids=="I Don''t Want Kids")?'selected':''; ?> value="I Don''t Want Kids">I dont want kids</option>
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
									<div class="form-group">
										<label for="disabledSelect"><?php echo lang('lbl_admin_user_detail_religion'); ?>:</label>
										<select id="religion" name="religion" class="form-control" >
											<option  value=""><?php echo lang('lbl_admin_user_detail_select_religion'); ?></option>
										<?php 
										foreach($religion as $relig)
										{?>
											<option <?php echo ($relig['id']==$content->religion)?'selected':''?> value="<?php echo $relig['id']; ?>">
											<?php 
												/*if(strcmp($_SESSION['site_lang'],"french")==0)
													echo $relig['french'];
												elseif(strcmp($_SESSION['site_lang'],"russian")==0)
													echo $relig['russian'];
												else
													echo $relig['english'];*/
												echo $relig['name'];
											?>
											</option>
								<?php 	}	?>
										</select>
									</div>		
									<div class="form-group">
										  <label for="disabledSelect"><?php echo lang('lbl_admin_user_detail_ethnicity'); ?>:</label>
										  <select  class="form-control" id="ethnicity" name="ethnicity">
											<option value=""><?php echo lang('lbl_admin_user_detail_select_ethnicity'); ?></option>
								<?php 	foreach($ethnicity as $eathni)
										{?>
											<option <?php echo ($eathni['id']==$content->ethnicity)?'selected':''?> value="<?php echo $eathni['id']; ?>">
											<?php echo $eathni['name']; ?>
											</option>
								<?php 	} ?>
										  </select>
									</div>			    
								</div>
							</div>
						</div>
					</div>
					<div id="menu1" class="tab-pane fade">
						<div class="form-group">
							<label for="exampleInputEmail1"><?php echo lang('lbl_admin_user_detail_date_preferance'); ?>:</label><br/>
							<ul id="sortable" class="sortable" style="list-style:none">
								<?php 
								$dateprefvalue=array("Coffee","Drink","Food","Fun");
								$i=1;
								foreach($datepref as $date)
								{ ?>
									<li class="datepref" id="<?php echo $i;?>" value="<?php echo $i;?>" style="border:0px;list-style:none;margin-right: 25px;float: left;">
										<div class="Preference-icon">
											<img class="img-center" alt="#" src="<?php echo base_url("Newassets/images/dateimg$date.png"); ?>">
										</div>
										<div class="text"><?php echo $dateprefvalue[$date-1] ?></div>
									</li>
									<?php 	
									$i++;
								} ?>
								<input type="hidden" id="datepref" name="datepref" value="1,2,3,4">
								<!-- <li class=" datepref" id="1" value="1" style="border:0px;list-style:none;margin-right: 25px;float: left;">
									<div class="Preference-icon">
										<img class="img-center" alt="#" src="<?php echo base_url('Newassets/images/dateimg1.png')?>">
									</div>
									<div class="text"><?php echo lang('lbl_register_coffee'); ?></div>
								</li>
								<li class=" datepref " id="2" value="2"  style="border:0px;list-style:none;margin-right: 25px;float: left;">
									<div class="Preference-icon">
										<img class="img-center" alt="#" src="<?php echo base_url('Newassets/images/dateimg2.png')?>">
									</div>
									<div class="text"><?php echo lang('lbl_register_drink'); ?></div>
								</li>
								<li class=" datepref " id="3" value="3"  style="border:0px;list-style:none;margin-right: 25px;float: left;" >
									<div class="Preference-icon">
										<img class="img-center" alt="#" src="<?php echo base_url('Newassets/images/dateimg3.png')?>">
									</div>
									<div class="text"><?php echo lang('lbl_register_food'); ?>	</div>
								</li>
								<li class=" datepref " id="4" value="4"  style="border:0px;list-style:none;margin-right: 25px;float: left;">
									<div class="Preference-icon">
										<img class="img-center" alt="#" src="<?php echo base_url('Newassets/images/dateimg4.png')?>">
									</div>
									<div class="text"><?php echo lang('lbl_register_fun'); ?></div>
								</li> -->
							</ul>
						<?php 
						/*$dateprefvalue=array("Coffee","Drink","Food","Fun");
						foreach($datepref as $date)
						{ ?>
							<div class=" col-sm-3">
								<div class="Preference-icon"><img class="img-center" alt="#" src="<?php echo base_url("Newassets/images/dateimg$date.png"); ?>"></div>
								<div class="text"><?php echo $dateprefvalue[$date-1] ?></div>
							</div>
							<?php 	
						} */?>
						</div>
						<div class="form-group">
							<label for="exampleInputEmail1"><?php echo lang('lbl_admin_user_detail_interested'); ?>:</label>
							<div class="row">
								<div class="col-sm-4">
									<div class="radio <?php echo ($content->gender_pref=="female")? "checked":""; ?>">
										<label  class="Intrested">
											<input type="radio"  name="gender_pref"  id="gender_male" value="female" <?php echo ($content->gender_pref=="female")? "checked":""; ?>/>
								<?php 	if($content->gender_pref=="female") 
										{ ?>
											<span class="icons">
												<span class="first-icon fa fa-circle-o"></span>
												<span class="second-icon fa fa-dot-circle-o"></span>
											</span>	<?php } ?>
											<img class="img-fluid fmale" alt="#" src="<?php echo base_url('images/fmail.png'); ?>">
											<img class="img-fluid fmale2" alt="#" src="<?php echo base_url('images/fmail1.png'); ?>">
										</label>
									</div>
								</div>
								<div class="col-sm-3">  
									<div class="radio <?php echo ($content->gender_pref=="male")? "checked":""; ?>">
										<label  class="Intrested"> <input type="radio"  name="gender_pref" id="gender_fmale" value="male" <?php echo ($content->gender_pref=="male")? "checked":""; ?>/>
											<?php if($content->gender_pref=="male") { ?><span class="icons">
											<span class="first-icon fa fa-circle-o"></span>
											<span class="second-icon fa fa-dot-circle-o"></span>
											</span>	<?php } ?>
											<img class="img-fluid male2" alt="#" src="<?php echo base_url('images/mail1.png'); ?>">
											<img class="img-fluid male1" alt="#" src="<?php echo base_url('images/mail.png'); ?>">
										</label>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="disabledSelect"><?php echo lang('lbl_admin_user_detail_age_preferance'); ?>:</label><br><br>
							<input type="hidden" class="form-control" name="age-min" id="age-min" value="<?php echo $content->min_age_pref; ?>">
							<input type="hidden" class="form-control" name="age-max" id="age-max" value="<?php echo $content->max_age_pref; ?>">
							<div id="age-pre" class="my-pref"> <?php echo $content->min_age_pref; ?>-<?php echo $content->max_age_pref; ?></div>
							<div id="age-Preference"></div>
						</div>
						<div class="form-group">
							<label for="disabledSelect"><?php echo lang('lbl_admin_user_detail_distance_pref'); ?>:</label> <br/> <br/>
							<input type="hidden" class="form-control" name="dist-min" id="dist-min"  value="<?php echo $content->min_dist_pref;; ?>">
							<input type="hidden" class="form-control" name="dist-max" id="dist-max"  value="<?php echo $content->max_dist_pref; ?>">
							<div id="dist-pre" class="my-pref"><?php echo $content->min_dist_pref; ?>-<?php echo $content->max_dist_pref; ?></div>
							<div id="Distance-Preference"></div>
						</div>
						<div class="form-group">
							<label for="disabledSelect"><?php echo lang('lbl_admin_user_detail_religion'); ?>:</label>
							<select id="religion" name="religion_pref" class="form-control" multiple>
								<option  value=""><?php echo lang('lbl_admin_user_detail_select_religion'); ?></option>
							<?php 
							foreach($religion as $relig)
							{?>
								<option <?php echo (strpos($content->religion_pref, $relig['id']) !== FALSE?'selected':'')?> value="<?php echo $relig['id']; ?>">
								<?php 
									echo $relig['name'];
								?>
								</option>
					<?php 	}	?>
							</select>
						</div>
						<div class="form-group">
							  <label for="disabledSelect"><?php echo lang('lbl_admin_user_detail_ethnicity'); ?>:</label>
							  <select  class="form-control" id="ethnicity" name="ethnicity_pref" multiple>
								<option value=""><?php echo lang('lbl_admin_user_detail_select_ethnicity'); ?></option>
					<?php 	foreach($ethnicity as $eathni)
							{?>
								<option <?php echo (strpos($content->ethnicity_pref, $eathni['id']) !== FALSE?'selected':'')?> value="<?php echo $eathni['id']; ?>">
								<?php echo $eathni['name']; ?>
								</option>
					<?php 	} ?>
							  </select>
						</div>
						<div class="form-group">
							<label for="disabledSelect"><?php echo lang('lbl_admin_user_detail_question'); ?>:</label>
							<select id="question" name="question" class="form-control">
								<option value=""><?php echo lang('lbl_admin_user_detail_select_question'); ?></option>
					<?php 	foreach($questions as $question)
							{?>
								<option <?php echo ($question['id']==$content->que_id)?'selected':''?> value="<?php echo $question['id']; ?>">
								<?php echo $question['name'];?>
								</option>
					<?php 	} ?>
							</select>
						</div>
						<div class="form-group">
							<label for="disabledSelect"><?php echo lang('lbl_admin_user_detail_question_answer'); ?>:</label>
							<input type="text" class="form-control" id="question_ans"  value="<?php echo $content->que_ans; ?>" name="question_ans">
						</div>
						<div class="form-group">
							<label><?php echo lang('lbl_admin_user_detail_access_location'); ?></label>
							<input type="radio" name="access_loc"  <?php echo ($content->access_location=='1')?'checked':'' ?> value="1"><?php echo lang('lbl_admin_user_detail_yes'); ?> 
							<input type="radio" name="access_loc"  <?php echo ($content->access_location=='0')?'checked':'' ?> value="0"><?php echo lang('lbl_admin_user_detail_no'); ?> 
						</div>
					</div>
				</div>
				
				<div class="form-group mb-0 text-center">
					<button type="submit" class="button btn-theme full-rounded btn nextxBtn btn-lg mt-20 animated right-icn" id="btnstep3"><?php echo lang('lbl_register_submit'); ?><i class="glyph-icon flaticon-hearts" aria-hidden="true"></i></button>
				</div>
			</div>
		</div>
    </div>
</div>
</form>
<script>
  var user_lat=<?php echo $content->location_lat; ?>;
  var user_long=<?php echo $content->location_long; ?>;
   var user_id=<?php echo $content->id; ?>;
</script>
<script>
$( function() {
    $( "#age-Preference" ).slider({
		range: true,
		min: 18,
		max: 100,
		values: [<?php echo $content->min_age_pref." , ". $content->max_age_pref;  ?>],
		slide: function( event, ui ) {
				$( "#age-min" ).val(  ui.values[ 0 ]  );
				$( "#age-max" ).val(  ui.values[ 1 ] );
				$( "#age-pre" ).html( ui.values[ 0 ]+" - "+ ui.values[ 1 ] );

		}
    });
	$( "#agepref" ).html( $( "#age-Preference" ).slider( "values", 0 ) +
      " -  " + $( "#age-Preference" ).slider( "values", 1 ) );
	//set initial valu to field
	$( "#age-min" ).val(  $( "#age-Preference" ).slider( "values", 0 ));
	$( "#age-max" ).val( $( "#age-Preference" ).slider( "values", 1 ) );
    $( "#Distance-Preference" ).slider({
		range: true,
		min: 0,
		max: 200,
		values: [ <?php echo $content->min_dist_pref." , ". $content->max_dist_pref;  ?> ],
		slide: function( event, ui ) {
				$( "#dist-min" ).val(  ui.values[ 0 ]  );
				$( "#dist-max" ).val(  ui.values[ 1 ] );
				$( "#dist-pre" ).html( ui.values[ 0 ]+" - "+ ui.values[ 1 ] );
		}
    });
	$(document).ready( function(){
		var sliderValues = [
			$( "#age-Preference" ).slider( "values", 0 ),
			$( "#age-Preference" ).slider( "values", 1 ),
			$( "#Distance-Preference" ).slider( "values", 0 ),
			$( "#Distance-Preference" ).slider( "values", 1 )
		];
		console.log(sliderValues);
		var tmp=0;
		$( '.ui-slider-handle' ).each(function( index ) {
			var target = $(this); 
			var curValue = sliderValues[tmp++];
			$(target).html('<div class="tooltip top slider-tip"><div class="tooltip-arrow"></div><div class="tooltip-inner">' + curValue+ '</div></div>');
		});
	});
	$( "#distpref" ).html( $( "#Distance-Preference" ).slider( "values", 0 ) +
      " - " + $( "#Distance-Preference" ).slider( "values", 1 ) );
	//set initial valu to field
	$( "#dist-min" ).val(  $( "#Distance-Preference" ).slider( "values", 0 )  );
	$( "#dist-max" ).val(  $( "#Distance-Preference" ).slider( "values", 1 )  );
});

</script>

<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $googleapiskey['value'];?>&amp;libraries=geometry,places"  type="text/javascript"></script>
<script>
  function initialize() {
                       var input = document.getElementById('address');
                       var autocomplete = new google.maps.places.Autocomplete(input);
               }
               google.maps.event.addDomListener(window, 'load', initialize);
  </script>
<!-- 
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_API_KEY;?>&libraries=places&callback=initAutocomplete" async defer></script> -->
<?php /*
if(!empty($googleapis)){	
	?>
	<script src="<?php  echo $googleapis; ?>" type="text/javascript"></script>
	<?php
}*/
?>