<?php 
include('header.php');
check_auth();
check_admin_auth();

if(isset($_GET['status']) && $_GET['status']!='' && isset($_GET['id']) && $_GET['id']>0){
	$status=get_safe_value($_GET['status']);
	$id=get_safe_value($_GET['id']);
	
	if($status=="active"){
		$status=1;
	}else{
		$status=0;
	}
	
	mysqli_query($con,"update users set status='$status' where id='$id'");
	redirect('users.php');
}

$res=mysqli_query($con,"select * from users where role='1' order by added_on desc");
?>
<div class="page-wrapper">
<div class="page-breadcrumb">
   <div class="row align-items-center">
      <div class="col-md-6 col-8 align-self-center">
         <h3 class="page-title mb-0 p-0">Users</h3>
         <h5 class="mb-0 p-0">
         <a href="manage_users.php">Add User</a>
         </h3>
      </div>
   </div>
</div>
<div class="container-fluid">
   <div class="row">
      <div class="col-12">
         <div class="card">
            <div class="card-body">
			  <?php if(mysqli_num_rows($res)>0){?>	
               <div class="table-responsive">
                  <table class="table user-table">
                     <thead>
                        <tr>
                           <th class="border-top-0">#</th>
                           <th class="border-top-0">Name</th>
                           <th class="border-top-0">Email</th>
                           <th class="border-top-0">Total QR/Total Used</th>
                           <th class="border-top-0">Total Hit/Total Used</th>
						   <th class="border-top-0">Added On</th>
                           <th class="border-top-0">Action</th>
                        </tr>
                     </thead>
                     <tbody>
						<?php 
						$i=1;
						while($row=mysqli_fetch_assoc($res)){
						$getUserTotalQR=getUserTotalQR($row['id']);	
						$totalUserQRHitListRes=getUserTotalQRHit($row['id']);
							?>
                        <tr>
                           <td><?php echo $i++?></td>
                           <td><?php echo $row['name']?></td>
                           <td><?php echo $row['email']?></td>
                           <td><?php echo $row['total_qr']?>/<?php echo $getUserTotalQR['total_qr']?></td>
						   <td><?php echo $row['total_hit']?>/<?php echo $totalUserQRHitListRes['total_hit']?></td>
						   <td><?php echo getCustomDate($row['added_on'])?></td>
                           <td>
								<a href="manage_users.php?id=<?php echo $row['id']?>">Edit</a>&nbsp;	
								<?php	
								$status="active";
								$strStatus="Deactive";
								if($row['status']==1){
									$status="deactive";
									$strStatus="Active";
								}
								?>
								
								<a href="?id=<?php echo $row['id']?>&status=<?php echo $status?>"><?php echo $strStatus?></a>
						   </td>
                        </tr>
						<?php } ?>
                     </tbody>
                  </table>
               </div>
			  <?php } else{
				echo "No data found";  
			  }
			  ?>
            </div>
         </div>
      </div>
   </div>
</div>
<?php include('footer.php')?>