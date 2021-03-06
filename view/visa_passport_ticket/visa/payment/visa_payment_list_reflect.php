<?php
include "../../../../model/model.php";
$customer_id = $_POST['customer_id'];
$cust_type = $_POST['cust_type'];
$company_name = $_POST['company_name'];
$visa_id = $_POST['visa_id'];
$payment_mode = $_POST['payment_mode'];
$financial_year_id = $_SESSION['financial_year_id'];
$payment_from_date = $_POST['payment_from_date'];
$payment_to_date = $_POST['payment_to_date'];
$emp_id = $_SESSION['emp_id'];
$role = $_SESSION['role'];
$role_id = $_SESSION['role_id'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$branch_status = $_POST['branch_status'];
?>
<div class="row mg_tp_20"> <div class="col-md-12 no-pad"> <div class="table-responsive">
<table class="table table-bordered no-marg" id="visa_payment_list" style="margin: 20px 0 !important;">
	<thead>
	 <tr class="table-heading-row">
		<th>S_No.</th>
		<th></th>
		<th>Visa_ID</th>
		<th>Customer_Name</th>
		<th>Receipt_Date</th>
		<th>Mode</th>
		<th>Branch_Name</th>
		<th class="text-right">Amount</th>
		<th>Receipt</th>
		<th>Edit</th>
	 </tr>
	</thead>
	<tbody>
		<?php 
		$query = "SELECT * from visa_payment_master where 1 ";		
		if($financial_year_id!=""){
			$query .= " and financial_year_id='$financial_year_id'";
		}
		if($visa_id!=""){
			$query .= " and visa_id='$visa_id'";
		}
		if($payment_mode!=""){
			$query .= " and payment_mode='$payment_mode'";
		}
		if($customer_id!=""){
			$query .= " and visa_id in (select visa_id from visa_master where customer_id='$customer_id')";
		}
		if($payment_from_date!='' && $payment_to_date!=''){
			$payment_from_date = get_date_db($payment_from_date);
			$payment_to_date = get_date_db($payment_to_date);

			$query .=" and payment_date between '$payment_from_date' and '$payment_to_date'";
		}
		if($cust_type != ""){
			$query .= " and visa_id in (select visa_id from visa_master where customer_id in ( select customer_id from customer_master where type='$cust_type' ))";
		}
		if($company_name != ""){
			$query .= " and visa_id in (select visa_id from visa_master where customer_id in ( select customer_id from customer_master where company_name='$company_name' ))";
		}
		if($role == "B2b"){
			$query .= " and visa_id in (select visa_id from visa_master where emp_id ='$emp_id')";
		}
		if($branch_status=='yes'){
			if($role=='Branch Admin' || $role=='Accountant' || $role_id>'7'){
				$query .= " and branch_admin_id = '$branch_admin_id'";
			}
			elseif($role!='Admin' && $role!='Branch Admin' && $role_id!='7' && $role_id<'7'){
				$query .= " and visa_id in (select visa_id from visa_master where emp_id='$emp_id')  and branch_admin_id = '$branch_admin_id'";
			}
		}
		elseif($role!='Admin' && $role!='Branch Admin' && $role_id!='7' && $role_id<'7'){
			$query .= " and visa_id in (select visa_id from visa_master where emp_id='$emp_id' )";
		}
		$query .= " order by visa_id desc";
		$count = 0;
		$total_paid_amt=0;
		$sq_pending_amount=0;
		$sq_cancel_amount=0;
		$sq_paid_amount=0;
		$total_payment=0;
	
		$sq_visa_payment = mysql_query($query);
		$sq_cancel_pay=mysql_fetch_assoc(mysql_query("select sum(payment_amount) as sum from visa_payment_master where clearance_status='Cleared'"));
	
		$sq_pend_pay=mysql_fetch_assoc(mysql_query("select sum(payment_amount) as sum from visa_payment_master where clearance_status='Pending'"));

		while($row_visa_payment = mysql_fetch_assoc($sq_visa_payment)){
			if($row_visa_payment['payment_amount'] != '0'){
			$count++;
			$sq_visa_info = mysql_fetch_assoc(mysql_query("select * from visa_master where visa_id='$row_visa_payment[visa_id]'"));
			$total_sale = $sq_visa_info['visa_total_cost'];
			$sq_pay = mysql_fetch_assoc(mysql_query("select sum(payment_amount) as sum from visa_payment_master where clearance_status!='Cancelled' and visa_id='$row_visa_payment[visa_id]'"));
			$total_pay_amt = $sq_pay['sum'];
			$outstanding =  $total_sale - $total_pay_amt;
			$date = $sq_visa_info['created_at'];
			$yr = explode("-", $date);
			$year = $yr[0];

			$customer_info = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$sq_visa_info[customer_id]'"));
			if($customer_info['type']=='Corporate'){
    	    	$customer_name = $customer_info['company_name'];
    	    }else{
    	    	$customer_name = $customer_info['first_name'].' '.$customer_info['last_name'];
    	    }
			$bg='';

			if($row_visa_payment['clearance_status']=="Pending"){ $bg='warning';
				$sq_pending_amount = $sq_pending_amount + $row_visa_payment['payment_amount'];
			}
			else if($row_visa_payment['clearance_status']=="Cancelled"){ $bg='danger';
				$sq_cancel_amount = $sq_cancel_amount + $row_visa_payment['payment_amount'];
			}		
			
			$sq_paid_amount = $sq_paid_amount + $row_visa_payment['payment_amount'];

			$payment_id_name = "Visa Payment ID";
			$payment_id = get_visa_booking_payment_id($row_visa_payment['payment_id'],$year);
			$receipt_date = date('d-m-Y');
			$booking_id = get_visa_booking_id($row_visa_payment['visa_id'],$year);
			$customer_id = $sq_visa_info['customer_id'];
			$booking_name = "Visa Booking";
			$travel_date = 'NA';
			$payment_amount = $row_visa_payment['payment_amount'];
			$payment_mode1 = $row_visa_payment['payment_mode'];
			$transaction_id = $row_visa_payment['transaction_id'];
			$payment_date = date('d-m-Y',strtotime($row_visa_payment['payment_date']));
			$bank_name = $row_visa_payment['bank_name'];
			$receipt_type = "Visa Receipt";			

			$url1 = BASE_URL."model/app_settings/print_html/receipt_html/receipt_body_html.php?payment_id_name=$payment_id_name&payment_id=$payment_id&receipt_date=$receipt_date&booking_id=$booking_id&customer_id=$customer_id&booking_name=$booking_name&travel_date=$travel_date&payment_amount=$payment_amount&transaction_id=$transaction_id&payment_date=$payment_date&bank_name=$bank_name&confirm_by=$confirm_by&receipt_type=$receipt_type&payment_mode=$payment_mode1&branch_status=$branch_status&outstanding=$outstanding";

			?>
			<tr class="<?= $bg?>">
				<td><?= $count ?></td>
				<td>
					<?php 
					if($row_visa_payment['payment_mode']=="Cash" || $row_visa_payment['payment_mode']=="Cheque"){
						?>
						<input type="checkbox" id="chk_visa_payment_<?= $count ?>" name="chk_visa_payment" value="<?= $row_visa_payment['payment_id'] ?>">
						<?php	
					}
					?>					
				</td>
				<td><?=  get_visa_booking_id($row_visa_payment['visa_id'],$year); ?></td>
				<td><?= $customer_name ?></td>
				<td><?= date('d/m/Y', strtotime($row_visa_payment['payment_date'])) ?></td>
				<td><?= $row_visa_payment['payment_mode'] ?></td>
				<td>
					<?php 
					if($payment_mode=="Cheque"){
						?>
						<input type="text" id="branch_name_<?= $count ?>" name="branch_name_d" class="form-control" placeholder="Branch Name" style="width:120px">
						<?php
					}
					?>
				</td>
				<td class="text-right success"><?= $row_visa_payment['payment_amount'] ?></td>
				<td>
					<a onclick="loadOtherPage('<?= $url1 ?>')" class="btn btn-info btn-sm" title="Print"><i class="fa fa-print"></i></a>
				</td>
				<td>
					<button class="btn btn-info btn-sm" onclick="visa_payment_update_modal(<?= $row_visa_payment['payment_id'] ?>)" title="Edit Detail"><i class="fa fa-pencil-square-o"></i></button>
				</td>
			</tr>
			<?php
			}
		}
		?>
	</tbody>
	<tfoot>
		<tr class="active">
			<th colspan="3" class="text-right">Paid Amount : <?= number_format($sq_paid_amount, 2); ?></th>
			<th colspan="2" class="warning text-right">Pending Clearance : <?= number_format($sq_pending_amount, 2); ?></th>
			<th colspan="2" class="danger text-right">Cancel Amount : <?= number_format($sq_cancel_amount, 2); ?></th>
			<?php $total_payment1 = $sq_paid_amount - $sq_pending_amount - $sq_cancel_amount; ?>
			<th colspan="3" class="success text-right">Total Payment : <?= number_format($total_payment1, 2); ?></th>
		</tr>
	</tfoot>	
</table>

</div> </div> </div>

<?php if($payment_mode=="Cheque" || $payment_mode=="Cash"): ?>
<div class="row mg_tp_20 pd_bt_51">
	<div class="col-md-4">
    <select name="bank_name_reciept" id="bank_name_reciept" title="Bank Name" class="form-control">
      <?php 
      $sq_bank = mysql_query("select * from bank_name_master");
      while($row_bank = mysql_fetch_assoc($sq_bank)){
        ?>
        <option value="<?= $row_bank['label'] ?>"><?= $row_bank['bank_name'] ?></option>
        <?php
      }
      ?>
    </select>
  </div>
	<div class="col-md-4">
	<?php 
	if($payment_mode=="Cheque"){
		?>
		<button class="btn btn-danger ico_left" onclick="cheque_bank_receipt_generate()"><i class="fa fa-file-pdf-o"></i>&nbsp;&nbsp;Bank Receipt</button>
		<?php
	}
	if($payment_mode=="Cash"){
		?>
		<button class="btn btn-danger ico_left" onclick="cash_bank_receipt_generate()"><i class="fa fa-file-pdf-o"></i>&nbsp;&nbsp;Bank Receipt</button>
		<?php
	}	
	?>
	</div>
</div>
<?php endif; ?>
<script>
$('#visa_payment_list').dataTable({
	"pagingType": "full_numbers",
	createdRow: function(row, data, dataIndex){
		// Initialize custom control
		$("input[type='radio'], input[type='checkbox']").labelauty({ label: false, maximum_width: "20px" });
			// ... skipped ...
		}
});
</script>
<script src="<?= BASE_URL ?>js/app/footer_scripts.js"></script>