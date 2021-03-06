<?php
include "../../../../model/model.php";
$quotation_for = $_POST['quotation_for'];
$from_date = $_POST['from_date'];
$to_date = $_POST['to_date'];
$emp_id = $_SESSION['emp_id'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$branch_status = $_POST['branch_status']; 
$role = $_SESSION['role'];
$role_id = $_SESSION['role_id'];

$query = "select * from vendor_request_master where 1 ";

if($quotation_for!=""){
	$query .= " and quotation_for='$quotation_for'";
}
if($from_date!="" && $to_date!=""){
	$from_date = get_date_db($from_date);
	$to_date = get_date_db($to_date);
	$query .= " and quotation_date between '$from_date' and '$to_date'";
}

include "../../../../model/app_settings/branchwise_filteration.php";

$query .= " order by request_id desc";

?>
<div class="row mg_tp_20"><div class="col-md-12 no-pad"><div class="table-responsive">
	
<table class="table table-bordered table-hover bg_white" id="tbl_req_list1" style="margin: 20px 0 !important;">
	<thead>
		<tr class="table-heading-row">
			<th>S_No.</th>
			<th>Date</th>
			<th>Quotation_ID</th>
			<th>Customer Name</th>
			<th>Interested_Tour</th>
			<th>Sent_by</th>
			<th>View</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		$count = 0;
		$sq_req = mysql_query($query);
		while($row_req = mysql_fetch_assoc($sq_req)){
			$booking_date = $row_req['quotation_date'];
	        $yr = explode("-", $booking_date);
	        $year =$yr[0];
			$sq_enq = mysql_fetch_assoc(mysql_query("select * from enquiry_master where enquiry_id='$row_req[enquiry_id]'"));
			$enquiry_content = $sq_enq['enquiry_content'];
			$enquiry_content_arr1 = json_decode($enquiry_content, true);
			$sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id='$row_req[emp_id]'"));
			$bg = ($row_req['bid_status']=="Close") ? "danger" : "";
			?>
			<tr class="<?= $bg ?>">
				<td><?= ++$count ?></td>
				<td><?= date('d-m-Y', strtotime($row_req['quotation_date'])) ?>
				<td><?= ge_vendor_request_id($row_req['request_id'],$year) ?></td>
				<td><?= $sq_enq['name'] ?></td>
				<td><?= $enquiry_content_arr1[0]['value'] ?></td>
				<td><?= ($sq_emp['first_name']=="") ? 'Admin' : $sq_emp['first_name'].' '.$sq_emp['last_name']  ; ?></td>
				<td>
					<button class="btn btn-info btn-sm" onclick="vendor_request_view_modal(<?= $row_req['request_id'] ?>)" title="View"><i class="fa fa-eye"></i></button>
				</td>
			</tr>			
			<?php

		}
		?>
	</tbody>
</table>

</div> </div> </div>

<script>
$('#tbl_req_list1').dataTable({
		"pagingType": "full_numbers"
	});
</script>
