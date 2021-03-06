<?php include "../../../../../model/model.php";
global $app_quot_format;
$from_date = $_POST['from_date'];
$to_date = $_POST['to_date'];
$quotation_id = $_POST['quotation_id'];
$emp_id = $_SESSION['emp_id'];
$role = $_SESSION['role'];
$role_id = $_SESSION['role_id'];
$branch_status = $_POST['branch_status'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$financial_year_id = $_SESSION['financial_year_id'];

$query = "select * from flight_quotation_master where financial_year_id='$financial_year_id' ";
if($from_date!='' && $to_date!=""){

	$from_date = date('Y-m-d', strtotime($from_date));
	$to_date = date('Y-m-d', strtotime($to_date));

	$query .= " and created_at between '$from_date' and '$to_date' "; 
}
if($quotation_id!=''){
	$query .= " and quotation_id='$quotation_id'";

}
if($branch_status=='yes'){
	if($role=='Branch Admin' || $role=='Accountant' || $role_id>'7'){
	    $query .= " and branch_admin_id = '$branch_admin_id'";
	}
	elseif($role!='Admin' && $role!='Branch Admin' && $role_id!='7' && $role_id<'7'){
	    $query .= " and emp_id='$emp_id' and branch_admin_id = '$branch_admin_id'";
	}
}
elseif($role!='Admin' && $role!='Branch Admin' && $role_id!='7' && $role_id<'7'){
	$query .= " and emp_id='$emp_id'";
}
$query .=" order by quotation_id desc ";

?>
<div class="row mg_tp_20">
	<div class="col-md-12 no-pad">
		<div class="table-responsive">
			<table class="table table-bordered" id="flight_quotation_table" style="margin: 20px 0 !important;">
				<thead>
				  <tr class="table-heading-row">
					<th>S_No.</th>
					<th class="text-center">ID</th>
					<th>Customer</th>
					<th>Date&nbsp;&nbsp;&nbsp;&nbsp;</th>
					<th>Amount</th>
					<th>Created_by</th>
					<th>Edit</th>
					<th>View</th>
					<th>PDF</th>
				  </tr>
				</thead>
				<tbody>
					<?php 
						$count = 0;
						$quotation_cost = 0;
						$sq_quotation = mysql_query($query);
						while($row_quotation = mysql_fetch_assoc($sq_quotation)){
							$sq_emp =  mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id = '$row_quotation[emp_id]'"));
							$emp_name = ($row_quotation['emp_id'] != 0) ? $sq_emp['first_name'].' '.$sq_emp['last_name'] : 'Admin';
							$quotation_date = $row_quotation['quotation_date'];
							$yr = explode("-", $quotation_date);
							$year =$yr[0];
							$sq_cost =  mysql_fetch_assoc(mysql_query("select * from package_tour_quotation_costing_entries where quotation_id = '$row_quotation[quotation_id]'"));

							$quotation_id = $row_quotation['quotation_id'];
							if($app_quot_format == 2){
								$url1 = BASE_URL."model/app_settings/print_html/quotation_html/quotation_html_2/flight_quotation_html.php?quotation_id=$quotation_id";
							}
							else if($app_quot_format == 3){
								$url1 = BASE_URL."model/app_settings/print_html/quotation_html/quotation_html_3/flight_quotation_html.php?quotation_id=$quotation_id";
							}
							else if($app_quot_format == 4){
								$url1 = BASE_URL."model/app_settings/print_html/quotation_html/quotation_html_4/flight_quotation_html.php?quotation_id=$quotation_id";
							}
							else if($app_quot_format == 5){
								$url1 = BASE_URL."model/app_settings/print_html/quotation_html/quotation_html_5/flight_quotation_html.php?quotation_id=$quotation_id";
							}
							else if($app_quot_format == 6){
								$url1 = BASE_URL."model/app_settings/print_html/quotation_html/quotation_html_6/flight_quotation_html.php?quotation_id=$quotation_id";
							}
							else{
								$url1 = BASE_URL."model/app_settings/print_html/quotation_html/quotation_html_1/flight_quotation_html.php?quotation_id=$quotation_id";
							}
							?>
							<tr>
								<td><?= ++$count ?></td>
								<td><?= get_quotation_id($row_quotation['quotation_id'],$year) ?></td>
								<td><?= $row_quotation['customer_name'] ?></td>
								<td><?= get_date_user($row_quotation['quotation_date']) ?></td>
								<td><?= number_format($row_quotation['quotation_cost'],2) ?></td>
								<td><?= $emp_name ?></td>
								<td>
									<button class="btn btn-info btn-sm" onclick="update_modal(<?= $row_quotation['quotation_id']  ?>)" title="Update Quotation"><i class="fa fa-pencil-square-o"></i></button>
									</form>
								</td>
								<td>
									<a href="quotation_view.php?quotation_id=<?= $row_quotation['quotation_id'] ?>" target="_BLANK" class="btn btn-info btn-sm" title="View Quotation"><i class="fa fa-eye"></i></a>
								</td>
								<td>
									<a onclick="loadOtherPage('<?= $url1 ?>')" class="btn btn-info btn-sm" title="Print"><i class="fa fa-print"></i></a>
								</td>
								
							</tr>
							<?php

						}
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
$('#flight_quotation_table').dataTable({
		"pagingType": "full_numbers"
	});
 
</script>