<?php 

?>
<!-- Cropping modal -->
    <div class="modal fade" id="avatar-modal" aria-hidden="true" aria-labelledby="avatar-modal-label" role="dialog" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <form class="avatar-form" action="<?php echo base_url('admin/Admin/galleryprofile'); ?>" enctype="multipart/form-data" method="post">
            <div class="modal-header">
				<h4 class="modal-title" id="avatar-modal-label"><?php echo lang("lbl_upload_image");?></h4>
				<button type="button" class="close" data-dismiss="modal" style="margin-top: -25px;font-size: 30px;">&times;</button>
            </div>
            <div class="modal-body">
              <div class="avatar-body">

                <!-- Upload image and data -->
                <div class="avatar-upload">
				  <input type="hidden" class="avatar-user" name="avtar_user" value="<?php echo $userdetail->id ;?>">
                  <input type="hidden" class="avatar-position" name="avatar_pos" value="1">
                  <input type="hidden" class="avatar-src" name="avatar_src">
                  <input type="hidden" class="avatar-data" name="avatar_data">
                  <label id="avatarLabal">Local upload</label>
                  <input onfocusout="validate()" type="file" class="avatar-input" id="avatarInput" name="avatar_file">
                  <span id="avatarInputl" style="color: red"></span>
                </div>

                <!-- Crop and preview -->
                <div class="row">
                  <div class="col-md-12">
                    <div class="avatar-wrapper"></div>
                  </div>
                  <!--div class="col-md-3">
                    <div class="avatar-preview preview-lg"></div>
                  </div-->
                </div>

                <div class="row avatar-btns">
                  <div class="col-md-3">
                    <button type="submit" class="btn btn-primary btn-block avatar-save">Done</button>
                  </div>
                </div>
              </div>
            </div>
            <!-- <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div> -->
          </form>
        </div>
      </div>
    </div><!-- /.modal -->

    <div class="modal fade" id="avatar-modal-2" aria-hidden="true" aria-labelledby="avatar-modal-label" role="dialog" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <form class="avatar-form-2" action="<?php echo base_url('admin/Admin/galleryprofile'); ?>" enctype="multipart/form-data" method="post">
            <div class="modal-header">
			  <h4 class="modal-title" id="avatar-modal-label"><?php echo lang("lbl_upload_image");?></h4>
              <button type="button" class="close" data-dismiss="modal" style="margin-top: -25px;font-size: 30px;">&times;</button>
            </div>
            <div class="modal-body">
              <div class="avatar-body">

                <!-- Upload image and data -->
                <div class="avatar-upload">
				  <input type="hidden" class="avatar-user" name="avtar_user" value="<?php echo $userdetail->id ;?>">
                  <input type="hidden" class="avatar-position" name="avatar_pos" value="2">
                  <input type="hidden" class="avatar-src" name="avatar_src">
                  <input type="hidden" class="avatar-data" name="avatar_data">
                  <label id="avatarLabal">Local upload</label>
                  <input onfocusout="validate()" type="file" class="avatar-input" id="avatarInput" name="avatar_file">
                  <span id="avatarInputl" style="color: red"></span>
                </div>

                <!-- Crop and preview -->
                <div class="row">
                  <div class="col-md-12">
                    <div class="avatar-wrapper"></div>
                  </div>
                  <!--div class="col-md-3">
                    <div class="avatar-preview preview-lg"></div>
                  </div-->
                </div>

                <div class="row avatar-btns">
                  <div class="col-md-3">
                    <button type="submit" class="btn btn-primary btn-block avatar-save">Done</button>
                  </div>
                </div>
              </div>
            </div>
            <!-- <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div> -->
          </form>
        </div>
      </div>
    </div><!-- /.modal -->

    <div class="modal fade" id="avatar-modal-3" aria-hidden="true" aria-labelledby="avatar-modal-label" role="dialog" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <form class="avatar-form-3" action="<?php echo base_url('admin/Admin/galleryprofile'); ?>" enctype="multipart/form-data" method="post">
            <div class="modal-header">
				<h4 class="modal-title" id="avatar-modal-label"><?php echo lang("lbl_upload_image");?></h4>
              <button type="button" class="close" data-dismiss="modal" style="margin-top: -25px;font-size: 30px;">&times;</button>
            </div>
            <div class="modal-body">
              <div class="avatar-body">

                <!-- Upload image and data -->
                <div class="avatar-upload">
				  <input type="hidden" class="avatar-user" name="avtar_user" value="<?php echo $userdetail->id ;?>">
                  <input type="hidden" class="avatar-position" name="avatar_pos" value="3">
                  <input type="hidden" class="avatar-src" name="avatar_src">
                  <input type="hidden" class="avatar-data" name="avatar_data">
                  <label id="avatarLabal">Local upload</label>
                  <input onfocusout="validate()" type="file" class="avatar-input" id="avatarInput" name="avatar_file">
                  <span id="avatarInputl" style="color: red"></span>
                </div>

                <!-- Crop and preview -->
                <div class="row">
                  <div class="col-md-12">
                    <div class="avatar-wrapper"></div>
                  </div>
                  <!--div class="col-md-3">
                    <div class="avatar-preview preview-lg"></div>
                  </div-->
                </div>

                <div class="row avatar-btns">
                  <div class="col-md-3">
                    <button type="submit" class="btn btn-primary btn-block avatar-save">Done</button>
                  </div>
                </div>
              </div>
            </div>
            <!-- <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div> -->
          </form>
        </div>
      </div>
    </div><!-- /.modal -->

    <div class="modal fade" id="avatar-modal-4" aria-hidden="true" aria-labelledby="avatar-modal-label" role="dialog" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <form class="avatar-form-4" action="<?php echo base_url('admin/Admin/galleryprofile'); ?>" enctype="multipart/form-data" method="post">
            <div class="modal-header">
				<h4 class="modal-title" id="avatar-modal-label"><?php echo lang("lbl_upload_image");?></h4>
              <button type="button" class="close" data-dismiss="modal" style="margin-top: -25px;font-size: 30px;">&times;</button>
            </div>
            <div class="modal-body">
              <div class="avatar-body">

                <!-- Upload image and data -->
                <div class="avatar-upload">
				  <input type="hidden" class="avatar-user" name="avtar_user" value="<?php echo $userdetail->id ;?>">
                  <input type="hidden" class="avatar-position" name="avatar_pos" value="4">
                  <input type="hidden" class="avatar-src" name="avatar_src">
                  <input type="hidden" class="avatar-data" name="avatar_data">
                  <label id="avatarLabal">Local upload</label>
                  <input onfocusout="validate()" type="file" class="avatar-input" id="avatarInput" name="avatar_file">
                  <span id="avatarInputl" style="color: red"></span>
                </div>

                <!-- Crop and preview -->
                <div class="row">
                  <div class="col-md-12">
                    <div class="avatar-wrapper"></div>
                  </div>
                  <!--div class="col-md-3">
                    <div class="avatar-preview preview-lg"></div>
                  </div-->
                </div>

                <div class="row avatar-btns">
                  <div class="col-md-3">
                    <button type="submit" class="btn btn-primary btn-block avatar-save">Done</button>
                  </div>
                </div>
              </div>
            </div>
            <!-- <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div> -->
          </form>
        </div>
      </div>
    </div><!-- /.modal -->

    <div class="modal fade" id="avatar-modal-5" aria-hidden="true" aria-labelledby="avatar-modal-label" role="dialog" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <form class="avatar-form-5" action="<?php echo base_url('admin/Admin/galleryprofile'); ?>" enctype="multipart/form-data" method="post">
            <div class="modal-header">
				<h4 class="modal-title" id="avatar-modal-label">Upload an Image</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <div class="avatar-body">

                <!-- Upload image and data -->
                <div class="avatar-upload">
				  <input type="hidden" class="avatar-user" name="avtar_user" value="<?php echo $userdetail->id ;?>">
                  <input type="hidden" class="avatar-position" name="avatar_pos" value="5" >
                  <input type="hidden" class="avatar-src" name="avatar_src">
                  <input type="hidden" class="avatar-data" name="avatar_data">
                  <label id="avatarLabal">Local upload</label>
                  <input onfocusout="validate()" type="file" class="avatar-input" id="avatarInput" name="avatar_file">
                  <span id="avatarInputl" style="color: red"></span>
                </div>

                <!-- Crop and preview -->
                <div class="row">
                  <div class="col-md-12">
                    <div class="avatar-wrapper"></div>
                  </div>
                  <!--div class="col-md-3">
                    <div class="avatar-preview preview-lg"></div>
                  </div-->
                </div>

                <div class="row avatar-btns">
                  <div class="col-md-3">
                    <button type="submit" class="btn btn-primary btn-block avatar-save">Done</button>
                  </div>
                </div>
              </div>
            </div>
            <!-- <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div> -->
          </form>
        </div>
      </div>
    </div><!-- /.modal -->

    <div class="modal fade" id="avatar-modal-6" aria-hidden="true" aria-labelledby="avatar-modal-label" role="dialog" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <form class="avatar-form-6" action="<?php echo base_url('admin/Admin/galleryprofile'); ?>" enctype="multipart/form-data" method="post">
            <div class="modal-header">
				<h4 class="modal-title" id="avatar-modal-label">Upload an Image</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <div class="avatar-body">

                <!-- Upload image and data -->
                <div class="avatar-upload">
					<input type="hidden" class="avatar-user" name="avtar_user" value="<?php echo $userdetail->id ;?>">
                  <input type="hidden" class="avatar-position" name="avatar_pos" value="6">
                  <input type="hidden" class="avatar-src" name="avatar_src">
                  <input type="hidden" class="avatar-data" name="avatar_data">
                  <label id="avatarLabal">Local upload</label>
                  <input onfocusout="validate()" type="file" class="avatar-input" id="avatarInput" name="avatar_file">
                  <span id="avatarInputl" style="color: red"></span>
                </div>

                <!-- Crop and preview -->
                <div class="row">
                  <div class="col-md-12">
                    <div class="avatar-wrapper"></div>
                  </div>
                  <!--div class="col-md-3">
                    <div class="avatar-preview preview-lg"></div>
                  </div-->
                </div>

                <div class="row avatar-btns">
                  <div class="col-md-3">
                    <button type="submit" class="btn btn-primary btn-block avatar-save">Done</button>
                  </div>
                </div>
              </div>
            </div>
            <!-- <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div> -->
          </form>
        </div>
      </div>
    </div><!-- /.modal -->

    <script type="text/javascript">
      
        function validate() {
          console.log($('#avatarInput'));        
          $("#avatarInputl").html("");
          $("#avatarInputl").css("border-color","#F0F0F0");
          if( $("#avatarInput").val() ) 
              var file_size1 = $('#avatarInput')[0].files[0].size;
          if(file_size1!="" || file_size1!=null){
              if(file_size1>1097152) {
                  $("#avatarInputl").html("File size is greater than 1MB");
                  $("#avatarInput").css("border-color","#FF0000");
                  $(".avatar-save").attr("disabled", "disabled");
                  return false;
              } 
              else{
                $("#avatarInputl").html("");  
                  $(".avatar-save").removeAttr("disabled");
                  return true;
              }            
          }
          else{
              return true;
          }
        }
    </script>