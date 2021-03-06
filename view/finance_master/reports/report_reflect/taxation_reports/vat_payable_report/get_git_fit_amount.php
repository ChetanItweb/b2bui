<?php 
//GIT Booking
$query = "select * from tourwise_traveler_details where 1 ";
if($from_date !='' && $to_date != ''){
	$from_date = get_date_db($from_date);
	$to_date = get_date_db($to_date);
	$query .= " and form_date between '$from_date' and '$to_date' ";
}
$sq_query = mysql_query($query);
while($row_query = mysql_fetch_assoc($sq_query))
{
	//Total count
 	$sq_count = mysql_fetch_assoc(mysql_query("select count(traveler_id) as booking_count from travelers_details where traveler_group_id ='$row_query[id]'"));

 	//Group cancel or not
 	$sq_group = mysql_fetch_assoc(mysql_query("select status from tour_groups where group_id ='$row_query[tour_group_id]'"));

 	//Cancelled count
 	$sq_cancel_count = mysql_fetch_assoc(mysql_query("select count(traveler_id) as cancel_count from travelers_details where traveler_group_id ='$row_query[id]' and status ='Cancel'"));
	$sq_cust = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$row_query[customer_id]'"));
	if($sq_cust['type'] == 'Corporate'){
		$cust_name = $sq_cust['company_name'];
	}else{
		$cust_name = $sq_cust['first_name'].' '.$sq_cust['last_name'];
	}

 	if($sq_count['booking_count'] != $sq_cancel_count['cancel_count'] || $sq_group['status'] != 'Cancel')
	{
    	$sq_state = mysql_fetch_assoc(mysql_query("select * from state_master where id='$sq_cust[state_id]'"));

    	//Train
    	if($row_query['train_taxation_id'] !='0'){

		if($taxation_id != '0'){
			$query .= " and taxation_id = '$row_query[train_taxation_id]'";
		}
    	$hsn_code = get_service_info('Train');
    	$taxable_amount = $row_query['train_expense'] + $row_query['train_service_charge'];

    	$sq_tax1 = mysql_fetch_assoc(mysql_query("select * from taxation_master where taxation_id='$row_query[train_taxation_id]'"));
    	$sq_tax_name = mysql_fetch_assoc(mysql_query("select * from tax_type_master where tax_type_id='$sq_tax1[tax_type_id]'"));
    	
		$tax_per = $row_query['train_service_tax'];
		$tax_amount = $row_query['train_service_tax_subtotal'];
		?>
		<tr>
			<td><?= $count++ ?></td>
			<td><?= "Group Booking" ?></td>
			<td><?= $cust_name ?></td>
			<td><?= get_group_booking_id($row_query['id']) ?></td>
			<td><?= get_date_user($row_query['form_date']) ?></td>
			<td><?= ($sq_cust['service_tax_no'] == '') ? 'NA' : $sq_cust['service_tax_no'] ?></td>
			<td><?= ($sq_cust['service_tax_no'] == '') ? 'Unregistered' : 'Registered' ?></td>
			<td><?= $row_query['train_service_tax'] ?></td>
			<td><?= number_format($taxable_amount,2) ?></td>
			<td><?php echo $tax_amount;  ?></td>
		</tr>
		<?php } //end if

		//Flight
    	if($row_query['plane_taxation_id'] !='0'){
		if($taxation_id != '0'){
			$query .= " and taxation_id = '$row_query[plane_taxation_id]'";
		}
    	$hsn_code = get_service_info('Flight');
    	$taxable_amount = $row_query['plane_expense'] + $row_query['plane_service_charge'];

    	$sq_tax1 = mysql_fetch_assoc(mysql_query("select * from taxation_master where taxation_id='$row_query[plane_taxation_id]'"));
    	$sq_tax_name = mysql_fetch_assoc(mysql_query("select * from tax_type_master where tax_type_id='$sq_tax1[tax_type_id]'"));
    	
		$tax_per = $row_query['plane_service_tax'];
		$tax_amount = $row_query['plane_service_tax_subtotal'];
		?>
		<tr>
			<td><?= $count++ ?></td>
			<td><?= "Group Booking" ?></td>
			<td><?= $cust_name ?></td>
			<td><?= get_group_booking_id($row_query['id']) ?></td>
			<td><?= get_date_user($row_query['form_date']) ?></td>
			<td><?= ($sq_cust['service_tax_no'] == '') ? 'NA' : $sq_cust['service_tax_no'] ?></td>
			<td><?= ($sq_cust['service_tax_no'] == '') ? 'Unregistered' : 'Registered' ?></td>
			<td><?= $row_query['plane_service_tax'] ?></td>
			<td><?= number_format($taxable_amount,2) ?></td>
			<td><?php echo $tax_amount;  ?></td>
		</tr>
		<?php } //end if
		//Cruise
    	if($row_query['cruise_taxation_id'] !='0'){

		if($taxation_id != '0'){
			$query .= " and taxation_id = '$row_query[cruise_taxation_id]'";
		}
    	$hsn_code = get_service_info('Cruise');
    	$taxable_amount = $row_query['cruise_expense'] + $row_query['cruise_service_charge'];

    	$sq_tax1 = mysql_fetch_assoc(mysql_query("select * from taxation_master where taxation_id='$row_query[cruise_taxation_id]'"));
    	$sq_tax_name = mysql_fetch_assoc(mysql_query("select * from tax_type_master where tax_type_id='$sq_tax1[tax_type_id]'"));
    	
		$tax_per = $row_query['cruise_service_tax'];
		$tax_amount = $row_query['cruise_service_tax_subtotal'];
		?>
		<tr>
			<td><?= $count++ ?></td>
			<td><?= "Group Booking" ?></td>
			<td><?= $cust_name ?></td>
			<td><?= get_group_booking_id($row_query['id']) ?></td>
			<td><?= get_date_user($row_query['form_date']) ?></td>
			<td><?= ($sq_cust['service_tax_no'] == '') ? 'NA' : $sq_cust['service_tax_no'] ?></td>
			<td><?= ($sq_cust['service_tax_no'] == '') ? 'Unregistered' : 'Registered' ?></td>
			<td><?= $row_query['cruise_service_tax'] ?></td>
			<td><?= number_format($taxable_amount,2) ?></td>
			<td><?php echo $tax_amount;  ?></td>
		</tr>
		<?php } //end if
		//Visa
    	if($row_query['visa_taxation_id'] !='0'){
    	if($taxation_id != '0'){
			$query .= " and taxation_id = '$row_query[visa_taxation_id]'";
		}
    	$hsn_code = get_service_info('Visa');
    	$taxable_amount = $row_query['visa_amount'] + $row_query['visa_service_charge'];

    	$sq_tax1 = mysql_fetch_assoc(mysql_query("select * from taxation_master where taxation_id='$row_query[visa_taxation_id]'"));
    	$sq_tax_name = mysql_fetch_assoc(mysql_query("select * from tax_type_master where tax_type_id='$sq_tax1[tax_type_id]'"));
    	
		$tax_per = $row_query['visa_service_tax'];
		$tax_amount = $row_query['visa_service_tax_subtotal'];
		?>
		<tr>
			<td><?= $count++ ?></td>
			<td><?= "Group Booking" ?></td>
			<td><?= $cust_name ?></td>
			<td><?= get_group_booking_id($row_query['id']) ?></td>
			<td><?= get_date_user($row_query['form_date']) ?></td>
			<td><?= ($sq_cust['service_tax_no'] == '') ? 'NA' : $sq_cust['service_tax_no'] ?></td>
			<td><?= ($sq_cust['service_tax_no'] == '') ? 'Unregistered' : 'Registered' ?></td>
			<td><?= $row_query['visa_service_tax'] ?></td>
			<td><?= number_format($taxable_amount,2) ?></td>
			<td><?php echo $tax_amount;  ?></td>
		</tr>
		<?php } //end if
		//Insurance
    	if($row_query['insuarance_taxation_id'] !='0'){    		
    	if($taxation_id != '0'){
			$query .= " and taxation_id = '$row_query[insuarance_taxation_id]'";
		}
    	$hsn_code = get_service_info('Group Tour');
    	$taxable_amount = $row_query['insuarance_amount'] + $row_query['insuarance_service_charge'];

    	$sq_tax1 = mysql_fetch_assoc(mysql_query("select * from taxation_master where taxation_id='$row_query[insuarance_taxation_id]'"));
    	$sq_tax_name = mysql_fetch_assoc(mysql_query("select * from tax_type_master where tax_type_id='$sq_tax1[tax_type_id]'"));
    	
		$tax_per = $row_query['insuarance_service_tax'];
		$tax_amount = $row_query['insuarance_service_tax_subtotal'];
		?>
		<tr>
			<td><?= $count++ ?></td>
			<td><?= "Group Booking" ?></td>
			<td><?= $cust_name ?></td>
			<td><?= get_group_booking_id($row_query['id']) ?></td>
			<td><?= get_date_user($row_query['form_date']) ?></td>
			<td><?= ($sq_cust['service_tax_no'] == '') ? 'NA' : $sq_cust['service_tax_no'] ?></td>
			<td><?= ($sq_cust['service_tax_no'] == '') ? 'Unregistered' : 'Registered' ?></td>
			<td><?= $row_query['insuarance_service_tax'] ?></td>
			<td><?= number_format($taxable_amount,2) ?></td>
			<td><?php echo $tax_amount;  ?></td>
		</tr>
		<?php } //end if
		//Tour
    	if($row_query['tour_taxation_id'] !='0'){ 		
    	if($taxation_id != '0'){
			$query .= " and taxation_id = '$row_query[tour_taxation_id]'";
		}
    	$hsn_code = get_service_info('Group Tour');
    	$taxable_amount = $row_query['tour_fee_subtotal_1'];

    	$sq_tax1 = mysql_fetch_assoc(mysql_query("select * from taxation_master where taxation_id='$row_query[tour_taxation_id]'"));
    	$sq_tax_name = mysql_fetch_assoc(mysql_query("select * from tax_type_master where tax_type_id='$sq_tax1[tax_type_id]'"));
    	
		$tax_per = $row_query['service_tax_per'];
		$tax_amount = $row_query['service_tax'];
		?>
		<tr>
			<td><?= $count++ ?></td>
			<td><?= "Group Booking" ?></td>
			<td><?= $cust_name ?></td>
			<td><?= get_group_booking_id($row_query['id']) ?></td>
			<td><?= get_date_user($row_query['form_date']) ?></td>
			<td><?= ($sq_cust['service_tax_no'] == '') ? 'NA' : $sq_cust['service_tax_no'] ?></td>
			<td><?= ($sq_cust['service_tax_no'] == '') ? 'Unregistered' : 'Registered' ?></td>
			<td><?= $row_query['service_tax_per'] ?></td>
			<td><?= number_format($taxable_amount,2) ?></td>
			<td><?php echo $tax_amount;  ?></td>
		</tr>
		<?php } //end if
}
} 
//FIT Booking
$query = "select * from package_tour_booking_master where 1 ";
if($from_date !='' && $to_date != ''){
	$from_date = get_date_db($from_date);
	$to_date = get_date_db($to_date);
	$query .= " and booking_date between '$from_date' and '$to_date' ";
}
$sq_query = mysql_query($query);
while($row_query = mysql_fetch_assoc($sq_query))
{
	//Total count
 	$sq_count = mysql_fetch_assoc(mysql_query("select count(traveler_id) as booking_count from package_travelers_details where booking_id ='$row_query[booking_id]'"));

 	//Cancelled count
 	$sq_cancel_count = mysql_fetch_assoc(mysql_query("select count(traveler_id) as cancel_count from package_travelers_details where booking_id ='$row_query[booking_id]' and status ='Cancel'"));

	$sq_cust = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$row_query[customer_id]'"));
	if($sq_cust['type'] == 'Corporate'){
		$cust_name = $sq_cust['company_name'];
	}else{
		$cust_name = $sq_cust['first_name'].' '.$sq_cust['last_name'];
	}

 	if($sq_count['booking_count'] != $sq_cancel_count['cancel_count'])
	{
    	$sq_state = mysql_fetch_assoc(mysql_query("select * from state_master where id='$sq_cust[state_id]'"));

    	//Train
    	if($row_query['train_taxation_id'] !='0'){    		
    	if($taxation_id != '0'){
			$query .= " and taxation_id = '$row_query[train_taxation_id]'";
		}
    	$hsn_code = get_service_info('Train');
    	$taxable_amount = $row_query['train_expense'] + $row_query['train_service_charge'];

    	$sq_tax1 = mysql_fetch_assoc(mysql_query("select * from taxation_master where taxation_id='$row_query[train_taxation_id]'"));
    	$sq_tax_name = mysql_fetch_assoc(mysql_query("select * from tax_type_master where tax_type_id='$sq_tax1[tax_type_id]'"));
    	
		$tax_per = $row_query['train_service_tax'];
		$tax_amount = $row_query['train_service_tax_subtotal'];
		?>
		<tr>
			<td><?= $count++ ?></td>
			<td><?= "Package Booking" ?></td>
			<td><?= $cust_name ?></td>
			<td><?= get_package_booking_id($row_query['booking_id']) ?></td>
			<td><?= get_date_user($row_query['booking_date']) ?></td>
			<td><?= ($sq_cust['service_tax_no'] == '') ? 'NA' : $sq_cust['service_tax_no'] ?></td>
			<td><?= ($sq_cust['service_tax_no'] == '') ? 'Unregistered' : 'Registered' ?></td>
			<td><?= $row_query['train_service_tax'] ?></td>
			<td><?= number_format($taxable_amount,2) ?></td>
			<td><?php echo $tax_amount;  ?></td>
		</tr>
		<?php } //end if

		//Flight
    	if($row_query['plane_taxation_id'] !='0'){		
    	if($taxation_id != '0'){
			$query .= " and taxation_id = '$row_query[plane_taxation_id]'";
		}
    	$hsn_code = get_service_info('Flight');
    	$taxable_amount = $row_query['plane_expense'] + $row_query['plane_service_charge'];

    	$sq_tax1 = mysql_fetch_assoc(mysql_query("select * from taxation_master where taxation_id='$row_query[plane_taxation_id]'"));
    	$sq_tax_name = mysql_fetch_assoc(mysql_query("select * from tax_type_master where tax_type_id='$sq_tax1[tax_type_id]'"));
    	
		$tax_per = $row_query['plane_service_tax'];
		$tax_amount = $row_query['plane_service_tax_subtotal'];
		?>
		<tr>
			<td><?= $count++ ?></td>
			<td><?= "Package Booking" ?></td>
			<td><?= $cust_name ?></td>
			<td><?= get_package_booking_id($row_query['booking_id']) ?></td>
			<td><?= get_date_user($row_query['booking_date']) ?></td>
			<td><?= ($sq_cust['service_tax_no'] == '') ? 'NA' : $sq_cust['service_tax_no'] ?></td>
			<td><?= ($sq_cust['service_tax_no'] == '') ? 'Unregistered' : 'Registered' ?></td>
			<td><?= $row_query['plane_service_tax'] ?></td>
			<td><?= number_format($taxable_amount,2) ?></td>
			<td><?php echo $tax_amount;  ?></td>
		</tr>
		<?php } //end if
		//Cruise
    	if($row_query['cruise_taxation_id'] !='0'){
    	if($taxation_id != '0'){
			$query .= " and taxation_id = '$row_query[cruise_taxation_id]'";
		}
    	$hsn_code = get_service_info('Cruise');
    	$taxable_amount = $row_query['cruise_expense'] + $row_query['cruise_service_charge'];

    	$sq_tax1 = mysql_fetch_assoc(mysql_query("select * from taxation_master where taxation_id='$row_query[cruise_taxation_id]'"));
    	$sq_tax_name = mysql_fetch_assoc(mysql_query("select * from tax_type_master where tax_type_id='$sq_tax1[tax_type_id]'"));
    	
		$tax_per = $row_query['cruise_service_tax'];
		$tax_amount = $row_query['cruise_service_tax_subtotal'];
		?>
		<tr>
			<td><?= $count++ ?></td>
			<td><?= "Package Booking" ?></td>
			<td><?= $cust_name ?></td>
			<td><?= get_package_booking_id($row_query['booking_id']) ?></td>
			<td><?= get_date_user($row_query['booking_date']) ?></td>
			<td><?= ($sq_cust['service_tax_no'] == '') ? 'NA' : $sq_cust['service_tax_no'] ?></td>
			<td><?= ($sq_cust['service_tax_no'] == '') ? 'Unregistered' : 'Registered' ?></td>
			<td><?= $row_query['cruise_service_tax'] ?></td>
			<td><?= number_format($taxable_amount,2) ?></td>
			<td><?php echo $tax_amount;  ?></td>
		</tr>
		<?php } //end if
		//Visa
    	if($row_query['visa_taxation_id'] !='0'){
    	if($taxation_id != '0'){
			$query .= " and taxation_id = '$row_query[visa_taxation_id]'";
		}
    	$hsn_code = get_service_info('Visa');
    	$taxable_amount = $row_query['visa_amount'] + $row_query['visa_service_charge'];

    	$sq_tax1 = mysql_fetch_assoc(mysql_query("select * from taxation_master where taxation_id='$row_query[visa_taxation_id]'"));
    	$sq_tax_name = mysql_fetch_assoc(mysql_query("select * from tax_type_master where tax_type_id='$sq_tax1[tax_type_id]'"));
    	
		$tax_per = $row_query['visa_service_tax'];
		$tax_amount = $row_query['visa_service_tax_subtotal'];
		?>
		<tr>
			<td><?= $count++ ?></td>
			<td><?= "Package Booking" ?></td>
			<td><?= $cust_name ?></td>
			<td><?= get_package_booking_id($row_query['booking_id']) ?></td>
			<td><?= get_date_user($row_query['booking_date']) ?></td>
			<td><?= ($sq_cust['service_tax_no'] == '') ? 'NA' : $sq_cust['service_tax_no'] ?></td>
			<td><?= ($sq_cust['service_tax_no'] == '') ? 'Unregistered' : 'Registered' ?></td>
			<td><?= $row_query['visa_service_tax'] ?></td>
			<td><?= number_format($taxable_amount,2) ?></td>
			<td><?php echo $tax_amount;  ?></td>
		</tr>
		<?php } //end if
		//Insurance
    	if($row_query['insuarance_taxation_id'] !='0'){
    	if($taxation_id != '0'){
			$query .= " and taxation_id = '$row_query[insuarance_taxation_id]'";
		}
    	$hsn_code = get_service_info('Package Tour');
    	$taxable_amount = $row_query['insuarance_amount'] + $row_query['insuarance_service_charge'];

    	$sq_tax1 = mysql_fetch_assoc(mysql_query("select * from taxation_master where taxation_id='$row_query[insuarance_taxation_id]'"));
    	$sq_tax_name = mysql_fetch_assoc(mysql_query("select * from tax_type_master where tax_type_id='$sq_tax1[tax_type_id]'"));
    	
		$tax_per = $row_query['insuarance_service_tax'];
		$tax_amount = $row_query['insuarance_service_tax_subtotal'];
		?>
		<tr>
			<td><?= $count++ ?></td>
			<td><?= "Package Booking" ?></td>
			<td><?= $cust_name ?></td>
			<td><?= get_package_booking_id($row_query['booking_id']) ?></td>
			<td><?= get_date_user($row_query['booking_date']) ?></td>
			<td><?= ($sq_cust['service_tax_no'] == '') ? 'NA' : $sq_cust['service_tax_no'] ?></td>
			<td><?= ($sq_cust['service_tax_no'] == '') ? 'Unregistered' : 'Registered' ?></td>
			<td><?= $row_query['insuarance_service_tax'] ?></td>
			<td><?= number_format($taxable_amount,2) ?></td>
			<td><?php echo $tax_amount;  ?></td>
		</tr>
		<?php } //end if
		//Tour
    	if($row_query['tour_service_tax_subtotal'] !='0'){
    	if($taxation_id != '0'){
			$query .= " and taxation_id = '$row_query[tour_service_tax_subtotal]'";
		}
    	$hsn_code = get_service_info('Package Tour');
    	$taxable_amount = $row_query['subtotal'];

    	$sq_tax1 = mysql_fetch_assoc(mysql_query("select * from taxation_master where taxation_id='$row_query[tour_service_tax]'"));
    	$sq_tax_name = mysql_fetch_assoc(mysql_query("select * from tax_type_master where tax_type_id='$sq_tax1[tax_type_id]'"));
    	
		$tax_per = $row_query['tour_service_tax'];
		$tax_amount = $row_query['tour_service_tax_subtotal'];
		?>
		<tr>
			<td><?= $count++ ?></td>
			<td><?= "Package Booking" ?></td>
			<td><?= $cust_name ?></td>
			<td><?= get_package_booking_id($row_query['booking_id']) ?></td>
			<td><?= get_date_user($row_query['booking_date']) ?></td>
			<td><?= ($sq_cust['service_tax_no'] == '') ? 'NA' : $sq_cust['service_tax_no'] ?></td>
			<td><?= ($sq_cust['service_tax_no'] == '') ? 'Unregistered' : 'Registered' ?></td>
			<td><?= $row_query['tour_service_tax'] ?></td>
			<td><?= number_format($taxable_amount,2) ?></td>
			<td><?php echo $tax_amount;  ?></td>
		</tr>
		<?php } //end if
}
} 
?>