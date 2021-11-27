<div class="page_wrapper">
    <div class="row padd-bottom"><div class="col-sm-12"><h1 class="page-header"><?php echo $type; ?></h1></div></div>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading"><?php echo $type; ?></div>
				<div class="panel-body">
					<div class="table-responsive">  
					<!-- /.Table start --> 
						<table  class="table table-striped table-bordered table-hover" id="adminuser-table">
							<thead>
								<tr>
									<th>No.</th>
									<th>User Name</th>
									<th>E-Mail</th>
								</tr>
							</thead>
							<tbody>
					<?php	$cnt=1;
							foreach($users as $row)
							{
								$users="";
							?>
								<tr id="item_1" style="cursor:move">
									<td><?php echo $cnt; ?></td>
									<td><?php echo $row['fname']." ".$row['lname']; ?></td>
									<td><?php echo $row['email']; ?></td>                      
								</tr>
					<?php		$cnt++;
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
</div>