
<div class="container">
	<div class="col-lg-8">		
		<form action="<?php echo site_url("admin/admin/update_sampledata")?>" method="post">
			<input type="hidden" name="user_ids" value="<?php echo $user_ids;?>">
			<div class="row">
				<div class="form-group">
					<label class="divider-3 mb-3"><?php echo lang('lbl_register_gender'); ?>:</label>
					<select class="form-control" name="gender">
						<option value=""><?php echo lang('lbl_select_gender'); ?></option>
						<option value="<?php echo lang('lbl_admin_user_detail_male'); ?>"><?php echo lang('lbl_admin_user_detail_male'); ?> 
						</option>
						<option value="<?php echo lang('lbl_admin_user_detail_female'); ?>"><?php echo lang('lbl_admin_user_detail_female'); ?> 
						</option>
					</select>
				</div>
				<div class="form-group">
					<label for="disabledSelect"><?php echo lang('lbl_admin_user_detail_religion'); ?>:</label>
					<select id="religion" name="religion" class="form-control" >
						<option  value=""><?php echo lang('lbl_admin_user_detail_select_religion'); ?></option>
					<?php 
					foreach($religion as $relig)
					{?>
						<option value="<?php echo $relig['id']; ?>">
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
						<option value="<?php echo $eathni['id']; ?>">
						<?php echo $eathni['name']; ?>
						</option>
			<?php 	} ?>
					  </select>
				</div>	
				<div class="form-group">
					<label class="divider-3 mb-3"><?php echo lang('lbl_location'); ?>:</label>
					<input type="text" class="form-control" id="address" name="address"  placeholder="<?php echo lang('lbl_register_address'); ?>" value="">
					<?php 
						if(empty($googleapiskey['value'])){
							?>
								<br><?php echo lang("lbl_google_api_mgs")?>
							<?php
						}
					?>
				</div>
				<?php echo lang('lbl_menu_name_preferences'); ?>
				<div class="form-group">
					<label class="divider-3 mb-3"><?php echo lang('lbl_admin_user_detail_interested'); ?>:</label>
					<select class="form-control" name="gender_pref">
						<option value=""><?php echo lang('lbl_select_gender'); ?></option>
						<option value="<?php echo lang('lbl_admin_user_detail_male'); ?>"><?php echo lang('lbl_admin_user_detail_male'); ?> 
						</option>
						<option value="<?php echo lang('lbl_admin_user_detail_female'); ?>"><?php echo lang('lbl_admin_user_detail_female'); ?> 
						</option>
					</select>
				</div>
				<div class="form-group">
					<label class="divider-3 mb-3"><?php echo lang('lbl_register_age_preferances'); ?>:</label>
					<br>
					<?php echo lang('lbl_register_min_age'); ?> : <input type="number" min="18" max="100" class="form-control" name="min_age">
					<?php echo lang('lbl_register_max_age'); ?> : <input type="number" min="18" max="100" class="form-control" name="max_age">
				</div>
				<div class="form-group">
					<label class="divider-3 mb-3"><?php echo lang('lbl_register_distance_preferances'); ?>:</label>
					<br>
					<?php echo lang('lbl_register_min_distance'); ?> : <input type="number" min="0" max="200" class="form-control" name="min_distance"> 
					<?php echo lang('lbl_register_max_distance'); ?> : <input type="number" min="0" max="200" class="form-control" name="max_distance">
				</div>
				<div class="form-group">
					<label for="disabledSelect"><?php echo lang('lbl_admin_user_detail_religion'); ?>:</label>
					<select id="religion" name="religion_pref[]" class="form-control" multiple>
						<option  value=""><?php echo lang('lbl_admin_user_detail_select_religion'); ?></option>
					<?php 
					foreach($religion as $relig)
					{?>
						<option value="<?php echo $relig['id']; ?>">
						<?php 
							echo $relig['name'];
						?>
						</option>
			<?php 	}	?>
					</select>
				</div>
				<div class="form-group">
					  <label for="disabledSelect"><?php echo lang('lbl_admin_user_detail_ethnicity'); ?>:</label>
					  <select  class="form-control" id="ethnicity" name="ethnicity_pref[]" multiple>
						<option value=""><?php echo lang('lbl_admin_user_detail_select_ethnicity'); ?></option>
			<?php 	foreach($ethnicity as $eathni)
					{?>
						<option value="<?php echo $eathni['id']; ?>">
						<?php echo $eathni['name']; ?>
						</option>
			<?php 	} ?>
					  </select>
				</div>
				<div class="section-field text-uppercase text-center mt-20">
					<button type="submit" class="button  btn-lg btn-theme full-rounded animated right-icn"><i class="glyph-icon flaticon-hearts" aria-hidden="true"></i><?php echo lang('lbl_profile_update'); ?></button>
				</div>
			</div>
		</form>
	</div>
</div>
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $googleapiskey['value'];?>&amp;libraries=geometry,places"  type="text/javascript"></script>
  <script>
  function initialize() {
                       var input = document.getElementById('address');
                       var autocomplete = new google.maps.places.Autocomplete(input);
               }
               google.maps.event.addDomListener(window, 'load', initialize);
</script>
<script>/*
var directionsDisplay,
    directionsService,
    map;

function initialize() {
  var directionsService = new google.maps.DirectionsService();
  directionsDisplay = new google.maps.DirectionsRenderer();
  var chicago = new google.maps.LatLng(41.850033, -87.6500523);
  var mapOptions = { zoom:7, mapTypeId: google.maps.MapTypeId.ROADMAP, center: chicago }
  map = new google.maps.Map(document.getElementById("address"), mapOptions);
  directionsDisplay.setMap(map);
}
*/
</script>

