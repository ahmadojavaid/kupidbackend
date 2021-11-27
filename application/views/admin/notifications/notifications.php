<div class="page_wrapper">
	<section>
        <div class="container-fluid text-center">
			<div class="row">
			   <div class="col-sm-12 text-uppercase page-title">
				   <h3><?php echo lang('lbl_admin_notification'); ?></h3>
				   <div><img class="img-center" src="<?php echo base_url('images/title-bdr.png'); ?>" alt=""></div>
			   </div>
			</div>
			<?php
			if(!empty($_SESSION['success']))
			{
				echo "<h3 class='successful'>$_SESSION[success]</h3>";
			}
			else if(!empty($_SESSION['fail']))
			{
				echo "<h4 class='fail'>$_SESSION[fail]</h4>";
			}
			/*
			here we can be display the notification list which is send by admin to selected online users 
			*/
			?>    	
			<div class="row">
				<div class="col-lg-12">
					<div class="panel panel-default">
						<div class="panel-heading"><?php echo lang('lbl_admin_notification'); ?></div>
						<div class="panel-body">
							<div class="button-group">
								<a class="btn btn-primary" href="<?php echo base_url().'admin/Admin/new_notifications'  ?>"><?php echo lang('lbl_admin_notification_add'); ?></a>
							</div>
							<div class="table-responsive">  
							<!-- /.Table start --> 
								<table  class="table table-striped table-bordered table-hover" id="adminuser-table">
									<thead>
										<tr>
											<th><?php echo lang('lbl_admin_notification_no'); ?></th>
											<th><?php echo lang('lbl_admin_notification_title'); ?></th>
											<th><?php echo lang('lbl_admin_notification_message'); ?></th>
											<th><?php echo lang('lbl_admin_notification_users'); ?></th>
											<th><?php echo lang('lbl_admin_notification_created_date'); ?></th>
										</tr>
									</thead>
									<tbody>
					<?php		$cnt=1;
								foreach($notification as $row)
								{
									$dt=date_create($row['created_date']);
									$dt=date_format($dt,"d-m-Y");
									$users="";
									?>
										<tr id="item_<?php echo $cnt; ?>" style="cursor:move">
											<td><?php echo $cnt; ?></td>
											<td><?php echo $row['title']; ?></td>
											<td><?php echo $row['message']; ?></td>
											<td>
											<?php
											$ids=explode(",",$row['users']);
											foreach($ids as $id)
											{
												$sql="select fname from users where id=".$id;
												$res=$this->db->query($sql);
												$res=$res->result_array();
												foreach($res as $row1)
													$users.=$row1['fname'].",";
											}
											$users=substr($users,0,strlen($users)-1);
											echo $users;
											?>
											</td>
											<td><?php echo $dt; ?></td>
										</tr>
						<?php		$cnt++;
								}	?>
									</tbody>
								</table>
							<!-- /.Table over -->
							</div>
						</div>
					</div>
				</div>	
				<!-- /.col-lg-3 col-md-6 -->
			</div>
		</div>
    </section>
</div>