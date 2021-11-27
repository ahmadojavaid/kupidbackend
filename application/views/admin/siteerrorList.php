<div class="page_wrapper">
	<section>
        <div class="container-fluid text-center">
			<div class="row">
			   <div class="col-sm-12 text-uppercase page-title">
				   <h3><?php echo lang('lbl_admin_site_error'); ?></h3>
				   <div><img class="img-center" src="<?php echo base_url('images/title-bdr.png'); ?>" alt=""></div>
			   </div>
			</div> 
			<?php
			if(!empty($_SESSION['success']))
			{
				echo "<h3  class='successful'>$_SESSION[success]</h3>";
			}
			else if(!empty($_SESSION['fail']))
			{
				echo "<h3  class='fail'>$_SESSION[fail]</h3>";
			}
			?>    	
			<?php if(count($siteerrors)!=0)
			{ 
				/*
				On this page display error which occured when users perform operation and error occurred
				*/
				?>
				<div class="row">
					<div class="col-lg-12">
						<div class="panel panel-default">
							<div class="panel-heading"><?php echo lang('lbl_admin_site_error_list'); ?></div>
							<div class="panel-body">
								<div class="table table-responsive">  
								<!-- /.Table start --> 
									<table  class="table table-striped table-bordered table-hover" id="adminuser-table">
										<thead>
											<tr>
												<th><?php echo lang('lbl_admin_site_error_no'); ?></th>
												<th><?php echo lang('lbl_admin_site_error_errors'); ?></th>
												<th><?php echo lang('lbl_admin_site_error_client_ip'); ?></th>
												<th><?php echo lang('lbl_admin_site_error_time'); ?></th>
												<th><?php echo lang('lbl_admin_site_error_user'); ?></th>
											</tr>
										</thead>
										<tbody>
							<?php	$cnt=1;
									foreach($siteerrors as $row)
									{
										$users="";
										?>
										<tr id="item_1" style="cursor:move">
											<td><?php echo $cnt; ?></td>
											<td><?php echo $row['erroemsg']; ?></td>
											<td><?php echo $row['client_ip']; ?></td>
											<td><?php echo $row['errortime']; ?></td>
											<td>
												<a href="<?php echo ($row['userid'])? site_url("/admin/admin/user_detail/".$row['userid']) : "#"; ?>" class="btn btn-primary" >
													<?php echo (!empty($row['fname'].$row['lname']))? $row['fname']." ".$row['lname'] : "Guest User"; ?>
												</a>
											</td>
										</tr>
										<?php
										$cnt++;
									}
								?>
										</tbody>
									</table>
								<!-- /.Table over -->
								</div>
							</div>
						</div>
					</div>	
					<!-- /.col-lg-3 col-md-6 -->
				</div>
				<?php 
			} ?>
		</div>
    </section>
</div>