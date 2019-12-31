<?php
include "../../../../model/model.php";
?>
<div class="row text-right mg_bt_20">
	<div class="col-xs-12">
		<button class="btn btn-excel btn-sm pull-right" onclick="excel_report()" data-toggle="tooltip" title="Generate Excel"><i class="fa fa-file-excel-o"></i></button>
	</div>
</div>

<div class="app_panel_content Filter-panel">
	<div class="row">
		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10_xs">
			<select name="booking_id" id="booking_id" style="width:100%" title="Booking ID">
		        <option value="">Booking ID</option>
		        <?php 
		        $sq_booking = mysql_query("select * from car_rental_booking where status='Cancel'");
		        while($row_booking = mysql_fetch_assoc($sq_booking)){
	              $sq_customer = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$row_booking[customer_id]'"));
	              $date = $row_booking['created_at'];
			         $yr = explode("-", $date);
			         $year =$yr[0];
		          ?>
		          <option value="<?= $row_booking['booking_id'] ?>"><?= get_car_rental_booking_id($row_booking['booking_id'],$year).' : '.$sq_customer['first_name'].' '.$sq_customer['last_name'] ?></option>
		          <?php
		        }
		        ?>
		    </select>
		</div>
		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10_xs">
			<input type="text" id="payment_from_date_filter" name="payment_from_date_filter" placeholder="Payment From Date" title="Payment From Date">
		</div>
		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10_xs">
			<input type="text" id="payment_to_date_filter" name="payment_to_date_filter" placeholder="Payment To Date" title="Payment To Date">
		</div>
		<div class="col-md-3 col-sm-6 col-xs-12 text-left">
			<button class="btn btn-sm	 btn-info ico_right" onclick="refund_report_reflect()">Proceed&nbsp;&nbsp;<i class="fa fa-arrow-right"></i></button>
		</div>
	</div>
</div>


<div id="div_report_content" class="main_block"></div>

<script>
$('#payment_from_date_filter, #payment_to_date_filter').datetimepicker({ timepicker:false, format:'d-m-Y' });
$('#booking_id').select2();
function refund_report_reflect()
{
	var booking_id = $('#booking_id').val();
	var payment_from_date = $('#payment_from_date_filter').val();
	var payment_to_date = $('#payment_to_date_filter').val();
	$.post('report/refund_report_reflect.php', { booking_id : booking_id, payment_from_date : payment_from_date, payment_to_date : payment_to_date}, function(data){
		$('#div_report_content').html(data);
	});
}
function excel_report()
{
    var booking_id = $('#booking_id').val();
    var payment_from_date = $('#payment_from_date_filter').val();
	var payment_to_date = $('#payment_to_date_filter').val();
  	window.location = 'report/excel_report.php?booking_id='+booking_id+'&payment_from_date='+payment_from_date+'&payment_to_date='+payment_to_date;
}
refund_report_reflect();
</script>
<script src="<?= BASE_URL ?>js/app/footer_scripts.js"></script>