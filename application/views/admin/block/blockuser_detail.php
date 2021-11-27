<div class="page_wrapper">
	<section>
		<div class="container-fluid text-center">
			<div class="row">
				<div class="col-sm-12 text-uppercase page-title">
					<h3><?php echo lang('lbl_admin_block_request_user'); ?></h3>
					<div><img class="img-center" src="<?php echo base_url('images/title-bdr.png'); ?>" alt=""></div>
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
	<?php 	if(count($block)!=0)
			{ 
				 /*
					on this page we can display blockeduser information which is requested by another user and from here we can be permenant block that users
				*/
				?>
				<div class="row">
					<div class="col-lg-12">
						<div class="panel panel-default">
							<div class="panel-heading"><?php echo lang('lbl_admin_block_request_user'); ?></div>
							<div class="panel-body">
								<div class="table-responsive">  
								<!-- /.Table start --> 
									<table  class="table table-striped table-bordered table-hover" id="adminuser-table">
										<thead>
											<tr>
												<th><?php echo lang('lbl_admin_block_request_no'); ?></th>
												<th><?php echo lang('lbl_admin_block_request_blocker'); ?></th>
												<th><?php echo lang('lbl_admin_block_request_blocked'); ?></th>
												<th><?php echo lang('lbl_admin_block_request_block_date'); ?></th>
												<th><?php echo lang('lbl_admin_block_request_action'); ?></th>
											</tr>
										</thead>
										<tbody>
								<?php	$cnt=1;
										foreach($block as $row)
										{
											$dt=date_create($row['created_date']);
											$dt=date_format($dt,"d-m-Y");
											$users="";
											?>
											<tr id="item_1" style="cursor:move">
												<td><?php echo $cnt; ?></td>
												<td><?php echo $row['Block_From']; ?></td>
												<td><?php echo $row['Block_To']; ?></td>
												<td><?php echo $dt; ?></td>
												<td>
												<a class="btn btn-primary" href="<?php echo base_url().'admin/Admin/block_user/'.$row['id'].'/block';  ?>"><?php echo lang('lbl_admin_block_request_permanant'); ?></a>                    
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