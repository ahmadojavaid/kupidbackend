<?php
//echo "<pre>";
//print_r($sampledatas);
?>

<div class="container">  
	<div class="col-lg-12">
	<form action="<?php echo site_url()."admin/admin/sampledata_edit" ?>" method="post">
		<div class="row">
			<div class="col-sm-4">
				<button name="bulkedit" class="btn btn-primary"><?php echo lang("lbl_bulk_edit");?></button>
			</div>
			<div class="col-sm-8">
				<?php echo lang("lbl_sample_data");?>:
				<input type="checkbox" data-toggle="toggle" data-on="Enable" data-off="Disable" name="sampletoggle" id="sampletoggle" <?php if($sampledata['status']) echo('checked="checked"') ?>>
				<input type="hidden" name="sampledataenable" id="sampledataenable">
			</div>
		</div>
		<div class="row">
			<table class="table">
				<thead>
					<tr>
						<th><input type="checkbox" name="chkbxall" class="chkbxall"></th>
						<th><?php echo lang('firstname')?></th>
						<th><?php echo lang('lastname')?></th>
						<th><?php echo lang('gender')?></th>
					</tr>
				</thead>
				<tbody>
					<?php 
					foreach ($sampledatas as $data) {
						?>
						<tr style="text-align: center;">
							<td><input type="checkbox" name="sampledata[]" class="chkbx" value="<?php echo $data['id']?>"></td>
							<td><?php echo $data['fname']?></td>
							<td><?php echo $data['lname']?></td>
							<td><?php echo $data['gender']?></td>
						</tr>
						<?php
					}
					?>
				</tbody>
			</table>
		</div>
	</form>
	</div>
</div>
</script>
<script type="text/javascript">
$(document).ready(function(){
    $('.chkbxall').on('click',function(){
        if(this.checked){
            $('.chkbx').each(function(){
                this.checked = true;
            });
        }else{
             $('.chkbx').each(function(){
                this.checked = false;
            });
        }
    });
    
    $('.chkbx').on('click',function(){
        if($('.chkbx:checked').length == $('.chkbx').length){
            $('.chkbxall').prop('checked',true);
        }else{
            $('.chkbxall').prop('checked',false);
        }
    });

	var sampletog="<?php echo $sampledata['status']?>";
	$("#sampledataenable").val(sampletog);
	$("#sampletoggle").change(function(){
		if(sampletog=="1"){
			sampletog="0";
			$.ajax({
				url:"<?php echo(base_url("admin/admin/updatedata"))?>",
				type:"post",
				data:{sampledataenable:sampletog},
				success:function(data){
					console.log(data);
				}
			});
		}
		else{
			sampletog="1";
			$.ajax({
				url:"<?php echo(base_url("admin/admin/updatedata"))?>",
				type:"post",
				data:{sampledataenable:sampletog},
				success:function(data){
					console.log(data);
				}
			});
		}
		$("#sampledataenable").val(sampletog);
	});
});
</script>
