<div class="page_wrapper">
	<section>
		<div class="container-fluid text-center">
			<div class="row">
				<div class="col-sm-12 text-uppercase page-title">
					<h3><?php echo lang('lbl_admin_question_list'); ?></h3>
					<div><img class="img-center" src="" alt=""></div>
				</div>
			</div>  
<?php 	if(!empty($_SESSION['success']))
		{
			echo "<h3 class='successful'>$_SESSION[success]</h3>";
		}
		else if(!empty($_SESSION['fail']))
		{
			echo "<h4 class='fail'>$_SESSION[fail]</h4>";
		} ?>    	
<?php 	if(count($religions)!=0)
		{ 
			/*
			on this page we can display blockeduser information which is requested by another user and from here we can be permenant block that users
			*/
			?>
			<div class="row">
				<div class="col-lg-12">
					<div class="panel panel-default">
						<div class="panel-heading"><?php echo lang('lbl_admin_question_list'); ?></div>
						<div class="panel-body">
							<div class="button-group">
								<a class="btn btn-primary" href="<?php echo base_url().'admin/Admin/new_question'  ?>"><?php echo lang('lbl_admin_question_list_add'); ?></a>
							</div>
							<div class="table-responsive">  
							<!-- /.Table start --> 
								<table  class="table table-striped table-bordered table-hover" id="adminuser-table">
									<thead>
										<tr>
											<th><?php echo lang('lbl_admin_question_list_sr_no'); ?></th>
											<th><?php echo lang('lbl_admin_question_list_qus_name'); ?></th>
											<th><?php echo lang('lbl_admin_question_list_action'); ?></th>
										</tr>
									</thead>
									<tbody>
							<?php	$cnt=1;
									foreach($religions as $row)
									{
										?>
										<tr id="item_1" style="cursor:move">
											<td><?php echo $cnt; ?></td>
											<td>
											<?php 
												echo $row['name'];
											?>
											</td>											
											<td>
											<a class="btn btn-primary" href="<?php echo base_url().'admin/Admin/question_edit/'.$row['id'];  ?>"><?php echo lang('lbl_admin_question_list_edit'); ?></a> 
											<a class="btn btn-primary" href="<?php echo base_url().'admin/Admin/question_delete/'.$row['id'];  ?>"><?php echo lang('lbl_admin_question_list_delete'); ?></a> 											
											</td>
										</tr>
										<?php
										$cnt++;
									} ?> 
									</tbody>
								</table>
							<!-- /.Table over -->
							</div>
						</div>
					</div>
				</div>	
				<!-- /.col-lg-3 col-md-6 -->
			</div>
<?php 	} ?>
		</div>
	</section>
</div>