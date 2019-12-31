<?php
include "../../../../model/model.php";
$emp_id = $_SESSION['emp_id'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$role_id = $_SESSION['role_id'];
$role = $_SESSION['role'];
$branch_status = $_POST['branch_status'];
?>

<div class="row text-right mg_bt_20">
	<div class="col-md-12">
	    <button class="btn btn-excel btn-sm" onclick="excel_report()" data-toggle="tooltip" title="Generate Excel"><i class="fa fa-file-excel-o"></i></button>
		<button class="btn btn-info btn-sm ico_left" onclick="visa_payment_save()"><i class="fa fa-plus"></i>&nbsp;&nbsp;Receipt</button>
	</div>
</div>

<div class="app_panel_content Filter-panel">
	<div class="row">
		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
		        <select name="cust_type_filter" id="cust_type_filter" style="width:100%" onchange="dynamic_customer_load(this.value,'company_filter'); company_name_reflect();" title="Customer Type">
		            <?php get_customer_type_dropdown(); ?>
		            
		            
		            
                    
		        </select>
	    </div>
	    <div id="company_div" class="hidden">
		</div>
		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10" id="customer_div">
	    </div> 

		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
			<select name="visa_id_filter" id="visa_id_filter" style="width:100%" title="Booking ID">
		        <option value="">Booking ID</option>
						<?php
						$query = "select * from visa_master where 1";
						include "../../../../model/app_settings/branchwise_filteration.php";
						$query .= " order by visa_id desc";
						$sq_visa = mysql_query($query);
		        while($row_visa = mysql_fetch_assoc($sq_visa)){

		        $date = $row_visa['created_at'];
				      $yr = explode("-", $date);
				      $year =$yr[0];
		          $sq_customer = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$row_visa[customer_id]'"));
		          ?>
		          <option value="<?= $row_visa['visa_id'] ?>"><?= get_visa_booking_id($row_visa['visa_id'],$year).' : '.$sq_customer['first_name'].' '.$sq_customer['last_name'] ?></option>
		          <?php
		        }
		        ?>
		    </select>
		</div>
		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
			<select name="payment_mode_filter" id="payment_mode_filter" class="form-control" title="Mode">
				<?php get_payment_mode_dropdown(); ?>
			</select>
		</div>
		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10 hidden">
			<select name="financial_year_id_filter" id="financial_year_id_filter" title="Financial Year">
				<?php get_financial_year_dropdown(); ?>
			</select>
		</div>
		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
			<input type="text" id="payment_from_date_filter" name="payment_from_date_filter" placeholder="From Date" title="From Date">
		</div>
		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
			<input type="text" id="payment_to_date_filter" name="payment_to_date_filter" placeholder="To Date" title="To Date">
		</div>
	</div>
	<div class="row text-center mg_tp_10">
		<div class="col-xs-12">
			<button class="btn btn-sm btn-info ico_right" onclick="visa_payment_list_reflect()">Proceed&nbsp;&nbsp;<i class="fa fa-arrow-right"></i></button>
		</div>
	</div>
</div>

<div id="div_visa_payment_save"></div>
<div id="div_visa_payment_update"></div>
<div id="div_visa_payment_list" class="main_block loader_parent"></div>


<script>
	$('#payment_from_date_filter, #payment_to_date_filter').datetimepicker({ timepicker:false, format:'d-m-Y' });
	$('#customer_id_filter, #visa_id_filter,#cust_type_filter').select2();
	dynamic_customer_load('','');
	function visa_payment_save(vis_id)
	{
		var branch_status = $('#branch_status').val();
		$.post('payment/save_payment_modal.php', { branch_status : branch_status }, function(data){
			$('#div_visa_payment_save').html(data);
		});
	}	

	function visa_payment_list_reflect()
	{
		$('#div_visa_payment_list').append('<div class="loader"></div>');
		var customer_id = $('#customer_id_filter').val();
		var visa_id = $('#visa_id_filter').val();
		var payment_mode = $('#payment_mode_filter').val();
		var financial_year_id = $('#financial_year_id_filter').val();
		var payment_from_date = $('#payment_from_date_filter').val();
		var payment_to_date = $('#payment_to_date_filter').val();
		var cust_type = $('#cust_type_filter').val();
		var company_name = $('#company_filter').val();
		var branch_status = $('#branch_status').val();
		$.post('payment/visa_payment_list_reflect.php', { customer_id : customer_id, visa_id : visa_id, payment_mode : payment_mode, financial_year_id : financial_year_id, payment_from_date : payment_from_date, payment_to_date : payment_to_date, cust_type : cust_type, company_name : company_name, branch_status : branch_status  }, function(data){
			$('#div_visa_payment_list').html(data);
		});
	}
	visa_payment_list_reflect();

	function visa_payment_update_modal(payment_id)
	{	
		var branch_status = $('#branch_status').val();
		$.post('payment/visa_payment_update_modal.php', { payment_id : payment_id, branch_status : branch_status  }, function(data){
			$('#div_visa_payment_update').html(data);
		});
	}
	function excel_report()
	{
		var customer_id = $('#customer_id_filter').val()
		var visa_id = $('#visa_id_filter').val()
		var from_date = $('#payment_from_date_filter').val();
		var to_date = $('#payment_to_date_filter').val();
		var payment_mode = $('#payment_mode_filter').val();
		var financial_year_id = $('#financial_year_id_filter').val();
		var cust_type = $('#cust_type_filter').val();
		var company_name = $('#company_filter').val();
		var branch_status = $('#branch_status').val();

		window.location = 'payment/excel_report.php?customer_id='+customer_id+'&visa_id='+visa_id+'&from_date='+from_date+'&to_date='+to_date+'&payment_mode='+payment_mode+'&financial_year_id='+financial_year_id+'&cust_type='+cust_type+'&company_name='+company_name+'&branch_status='+branch_status;
	}
</script>
<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>