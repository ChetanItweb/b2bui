<?php 
class quotation_update{

public function quotation_master_update()
{
	$quotation_id = $_POST['quotation_id'];
	$enquiry_id = $_POST['enquiry_id'];
	$package_id = $_POST['package_id'];
	$tour_name = $_POST['tour_name'];
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
    $total_days = $_POST['total_days'];
    $customer_name = $_POST['customer_name'];
    $email_id = $_POST['email_id'];
    $mobile_no = $_POST['mobile_no'];
    $total_adult = $_POST['total_adult'];
    $total_children = $_POST['total_children'];
    $total_infant = $_POST['total_infant'];
    $total_passangers = $_POST['total_passangers'];
    $children_without_bed = $_POST['children_without_bed'];
    $children_with_bed = $_POST['children_with_bed'];
	$quotation_date = $_POST['quotation_date'];
	$booking_type = $_POST['booking_type'];
	$train_cost = $_POST['train_cost'];
	$flight_cost = $_POST['flight_cost'];
	$cruise_cost = $_POST['cruise_cost'];
	$visa_cost = $_POST['visa_cost'];
	$guide_cost = $_POST['guide_cost'];
	$misc_cost = $_POST['misc_cost'];
	$child_cost = $_POST['child_cost'];
	$adult_cost = $_POST['adult_cost'];
	$infant_cost = $_POST['infant_cost'];
	$child_with = $_POST['child_with'];
	$child_without = $_POST['child_without'];
	$extra_bed = $_POST['extra_bed'];
	$price_str_url = $_POST['price_str_url'];

	//Train
    $train_from_location_arr = $_POST['train_from_location_arr'];
    $train_to_location_arr = $_POST['train_to_location_arr'];
	$train_class_arr = $_POST['train_class_arr'];
	$train_arrival_date_arr = $_POST['train_arrival_date_arr'];
	$train_departure_date_arr = $_POST['train_departure_date_arr'];
	$train_id_arr = $_POST['train_id_arr'];

	//Plane
	$plane_from_city_arr = $_POST['plane_from_city_arr'];
	$plane_to_city_arr = $_POST['plane_to_city_arr'];
    $plane_from_location_arr = $_POST['plane_from_location_arr'];
    $plane_to_location_arr = $_POST['plane_to_location_arr'];
    $airline_name_arr = $_POST['airline_name_arr'];
    $plane_class_arr = $_POST['plane_class_arr'];
    $arraval_arr = $_POST['arraval_arr'];
    $dapart_arr = $_POST['dapart_arr'];
    $plane_id_arr = $_POST['plane_id_arr'];
   
    //Cruise
   	$cruise_departure_date_arr = $_POST['cruise_departure_date_arr'];
    $cruise_arrival_date_arr = $_POST['cruise_arrival_date_arr'];
    $route_arr = $_POST['route_arr'];
    $cabin_arr = $_POST['cabin_arr'];
    $sharing_arr = $_POST['sharing_arr'];
    $c_entry_id_arr = $_POST['c_entry_id_arr'];

    //Hotel
    $city_name_arr = $_POST['city_name_arr'];
    $hotel_name_arr = $_POST['hotel_name_arr'];
    $hotel_stay_days_arr = $_POST['hotel_stay_days_arr'];
    $package_name_arr = $_POST['package_name_arr'];
    $hotel_id_arr = $_POST['hotel_id_arr'];
    $hotel_type_arr = $_POST['hotel_type_arr'];
    $hotel_cost_arr = $_POST['hotel_cost_arr'];
    $extra_bed_arr = $_POST['extra_bed_arr'];
    $extra_bed_cost_arr = $_POST['extra_bed_cost_arr'];
    $total_rooms_arr = $_POST['total_rooms_arr'];

    //Tranport
    $vehicle_name_arr = $_POST['vehicle_name_arr'];
    $start_date_arr = $_POST['start_date_arr'];
    $end_date_arr = $_POST['end_date_arr'];
    $package_name_arr1 = $_POST['package_name_arr1'];
    $transport_id_arr = $_POST['transport_id_arr'];
    $transport_cost_arr1 = $_POST['transport_cost_arr1'];

    //Excursion
    $city_name_arr_e = $_POST['city_name_arr_e'];
    $excursion_name_arr = $_POST['excursion_name_arr'];
    $excursion_amt_arr = $_POST['excursion_amt_arr'];
    $excursion_id_arr = $_POST['excursion_id_arr'];
    
    //Costing
    $tour_cost_arr = $_POST['tour_cost_arr'];
    $transport_cost_arr = $_POST['transport_cost_arr'];
    $markup_cost_arr = $_POST['markup_cost_arr'];
    $markup_subtotal_arr = $_POST['markup_subtotal_arr'];
    $excursion_cost_arr = $_POST['excursion_cost_arr'];
    $taxation_id_arr = $_POST['taxation_id_arr'];
	$service_tax_arr = $_POST['service_tax_arr'];
	$service_tax_subtotal_arr = $_POST['service_tax_subtotal_arr'];
	$total_tour_cost_arr = $_POST['total_tour_cost_arr'];
    $package_name_arr2 = $_POST['package_name_arr2'];
    $costing_id_arr = $_POST['costing_id_arr'];

    //Package Program
    $checked_programe_arr1 = $_POST['checked_programe_arr'];
    $attraction_arr = $_POST['attraction_arr'];
    $program_arr = $_POST['program_arr'];
    $stay_arr = $_POST['stay_arr'];
    $meal_plan_arr = $_POST['meal_plan_arr'];
    $package_p_id_arr = $_POST['package_p_id_arr'];

    $inclusions = $_POST['inclusions'];
	$exclusions = $_POST['exclusions'];
	$costing_type = $_POST['costing_type'];

 	$from_date = get_date_db($from_date);
	$to_date = get_date_db($to_date);
	$quotation_date = get_date_db($quotation_date);

	$terms = addslashes($terms);
	$inclusions = addslashes($inclusions);
	$exclusions = addslashes($exclusions);
	$query = "update package_tour_quotation_master set tour_name = '$tour_name', from_date = '$from_date', to_date = '$to_date', total_days = '$total_days', customer_name = '$customer_name', email_id='$email_id',mobile_no='$mobile_no', total_adult = '$total_adult', total_children = '$total_children', total_infant = '$total_infant', total_passangers = '$total_passangers', children_without_bed = '$children_without_bed', children_with_bed = '$children_with_bed', quotation_date='$quotation_date', booking_type = '$booking_type', train_cost = '$train_cost', flight_cost = '$flight_cost',cruise_cost='$cruise_cost', visa_cost = '$visa_cost', guide_cost= '$guide_cost',misc_cost='$misc_cost', price_str_url= '$price_str_url', enquiry_id= '$enquiry_id',inclusions='$inclusions',exclusions='$exclusions',costing_type='$costing_type' where quotation_id = '$quotation_id'";
	$sq_quotation = mysql_query($query);
	if($sq_quotation){	

		$this->train_entries_update($quotation_id, $train_from_location_arr, $train_to_location_arr, $train_class_arr, $train_arrival_date_arr, $train_departure_date_arr, $train_id_arr);
		$this->plane_entries_update($quotation_id,$plane_from_city_arr,$plane_to_city_arr, $plane_from_location_arr, $plane_to_location_arr, $plane_class_arr,$airline_name_arr, $arraval_arr, $dapart_arr, $plane_id_arr);
		$this->cruise_entries_update($quotation_id, $cruise_departure_date_arr, $cruise_arrival_date_arr, $route_arr, $cabin_arr, $sharing_arr,$c_entry_id_arr);
		$this->hotel_entries_update($quotation_id, $city_name_arr, $hotel_name_arr,$hotel_type_arr, $hotel_stay_days_arr,$hotel_id_arr,$hotel_cost_arr,$extra_bed_arr,$extra_bed_cost_arr, $total_rooms_arr, $package_id);		
		$this->tranport_entries_update($quotation_id, $vehicle_name_arr, $start_date_arr, $end_date_arr, $transport_id_arr,$transport_cost_arr1, $package_id);		
		$this->costing_entries_update($tour_cost_arr,$transport_cost_arr, $markup_cost_arr,$markup_subtotal_arr, $taxation_id_arr,$service_tax_arr,$service_tax_subtotal_arr,$total_tour_cost_arr, $costing_id_arr,$excursion_cost_arr,$adult_cost,$child_cost,$infant_cost,$child_with,$child_without,$extra_bed);
		$this->excursion_entries_save($quotation_id,$city_name_arr_e, $excursion_name_arr, $excursion_amt_arr,$excursion_id_arr);	
		$this->program_entries_save($quotation_id,$attraction_arr, $program_arr, $stay_arr,$meal_plan_arr,$package_p_id_arr,$checked_programe_arr1);	

		echo "Quotation has been successfully updated.";	
		exit;
	}
	else{
		echo "error--Quotation not updated!";
		exit;
	}

}


public function program_entries_save($quotation_id,$attraction_arr, $program_arr, $stay_arr,$meal_plan_arr,$package_p_id_arr,$checked_programe_arr1)
{
		for($i=0; $i<sizeof($program_arr); $i++)
		{
			$attraction = addslashes($attraction_arr[$i]);
			$program = addslashes($program_arr[$i]);
			$stay = addslashes($stay_arr[$i]);
			$meal_plan = addslashes($meal_plan_arr[$i]);

				if($checked_programe_arr1[$i]=="true")
				{

					if($package_p_id_arr[$i] == '')
					{
						$sq_max = mysql_fetch_assoc(mysql_query("select max(id) as max from package_quotation_program"));
						$id = $sq_max['max']+1;

						$sq_plane = mysql_query("insert into package_quotation_program (id, quotation_id, attraction, day_wise_program, stay,meal_plan ) values ('$id', '$quotation_id', '$attraction','$program', '$stay','$meal_plan')");
						if(!$sq_plane){
							echo "error--Tour Itinerary not saved!";
							exit;
					    }
				    }
				    else{
				    	$sq_train = mysql_query("update package_quotation_program set attraction='$attraction', day_wise_program='$program', stay='$stay', meal_plan='$meal_plan' where id='$package_p_id_arr[$i]' ");
						if(!$sq_train){
							echo "error--Tour Itinerary not updated!";
							exit;
						}
				    }
				}else{
					$query = "Delete from package_quotation_program where id='$package_p_id_arr[$i]'";
					$sq_hotel = mysql_query($query);
					if(!$sq_hotel){
						echo "error--Itinarary not updated!";
						exit;
					 }
				}
	   }
}

public function train_entries_update($quotation_id, $train_from_location_arr, $train_to_location_arr, $train_class_arr, $train_arrival_date_arr, $train_departure_date_arr, $train_id_arr)
{
	for($i=0; $i<sizeof($train_from_location_arr); $i++){

		$train_arrival_date_arr[$i] = date('Y-m-d H:i', strtotime($train_arrival_date_arr[$i]));
		$train_departure_date_arr[$i] = date('Y-m-d H:i', strtotime($train_departure_date_arr[$i]));

		if($train_id_arr[$i] != ""){
			$sq_train = mysql_query("update package_tour_quotation_train_entries set from_location='$train_from_location_arr[$i]', to_location='$train_to_location_arr[$i]', class='$train_class_arr[$i]', arrival_date='$train_arrival_date_arr[$i]', departure_date='$train_departure_date_arr[$i]' where id='$train_id_arr[$i]' ");
			if(!$sq_train){
				echo "error--Train information not updated!";
				exit;
			}
		}
		else{
			$sq_max = mysql_fetch_assoc(mysql_query("select max(id) as max from package_tour_quotation_train_entries"));
			$id = $sq_max['max']+1;

			$sq_train = mysql_query("insert into package_tour_quotation_train_entries ( id, quotation_id, from_location, to_location, class, arrival_date, departure_date ) values ( '$id', '$quotation_id', '$train_from_location_arr[$i]', '$train_to_location_arr[$i]', '$train_class_arr[$i]', '$train_arrival_date_arr[$i]', '$train_departure_date_arr[$i]' )");
			if(!$sq_train){
				echo "error--Train information not saved!";
				exit;
			}
		}
	}
}

public function plane_entries_update($quotation_id,$plane_from_city_arr,$plane_to_city_arr, $plane_from_location_arr, $plane_to_location_arr, $plane_class_arr,$airline_name_arr, $arraval_arr, $dapart_arr, $plane_id_arr)
{
	for($i=0; $i<sizeof($plane_from_location_arr); $i++){
			$arraval_arr[$i] = date('Y-m-d H:i:s', strtotime($arraval_arr[$i]));
		    $dapart_arr[$i] = date('Y-m-d H:i:s', strtotime($dapart_arr[$i]));
			if($plane_id_arr[$i]=="")
			{
				$sq_max = mysql_fetch_assoc(mysql_query("select max(id) as max from package_tour_quotation_plane_entries"));
				$id = $sq_max['max']+1;

				$sq_plane = mysql_query("insert into package_tour_quotation_plane_entries ( id, quotation_id,from_city,to_city, from_location, to_location,airline_name, class, arraval_time, dapart_time) values ( '$id', '$quotation_id', '$plane_from_city_arr[$i]', '$plane_to_city_arr[$i]', '$plane_from_location_arr[$i]', '$plane_to_location_arr[$i]','$airline_name_arr[$i]', '$plane_class_arr[$i]', '$arraval_arr[$i]', '$dapart_arr[$i]' )");
				if(!$sq_plane)
				{
					echo "Flight not inserted.";
					exit;
				}
			}else
			{
				$sq_update=mysql_query("UPDATE `package_tour_quotation_plane_entries` SET `from_city`= '$plane_from_city_arr[$i]',`to_city`='$plane_to_city_arr[$i]', `from_location`='$plane_from_location_arr[$i]',`to_location`='$plane_to_location_arr[$i]',airline_name='$airline_name_arr[$i]',`class`='$plane_class_arr[$i]',`arraval_time`='$arraval_arr[$i]',`dapart_time`='$dapart_arr[$i]' WHERE `id`='$plane_id_arr[$i]'");
				if(!$sq_update)
				{
					echo "Flight not updated";
					exit;
				}
			}
	}

}


public function cruise_entries_update($quotation_id, $cruise_departure_date_arr, $cruise_arrival_date_arr, $route_arr, $cabin_arr, $sharing_arr,$c_entry_id_arr)
{
	for($i=0; $i<sizeof($cruise_departure_date_arr); $i++)
	{
			$cruise_departure_date_arr[$i] = date('Y-m-d H:i:s', strtotime($cruise_departure_date_arr[$i]));
		    $cruise_arrival_date_arr[$i] = date('Y-m-d H:i:s', strtotime($cruise_arrival_date_arr[$i]));
			if($c_entry_id_arr[$i]=="0")
			{
				$sq_max = mysql_fetch_assoc(mysql_query("select max(id) as max from package_tour_quotation_cruise_entries"));
				$id = $sq_max['max']+1;

				$sq_cruise = mysql_query("insert into package_tour_quotation_cruise_entries ( id, quotation_id, dept_datetime, arrival_datetime,route, cabin, sharing) values ( '$id', '$quotation_id', '$cruise_departure_date_arr[$i]', '$cruise_arrival_date_arr[$i]','$route_arr[$i]', '$cabin_arr[$i]', '$sharing_arr[$i]')");
				if(!$sq_cruise){
					echo "error--Cruise information not saved!";
					exit;
				}
			}else
			{
				$sq_update=mysql_query("UPDATE `package_tour_quotation_cruise_entries` SET `dept_datetime`='$cruise_departure_date_arr[$i]',`arrival_datetime`='$cruise_arrival_date_arr[$i]',route='$route_arr[$i]',`cabin`='$cabin_arr[$i]',`sharing`='$sharing_arr[$i]' WHERE `id`='$c_entry_id_arr[$i]'");
				if(!$sq_update)
				{
					echo "Cruise not updated";
					exit;
				}
			}
	}
}

public function hotel_entries_update($quotation_id, $city_name_arr, $hotel_name_arr,$hotel_type_arr, $hotel_stay_days_arr,$hotel_id_arr,$hotel_cost_arr,$extra_bed_arr,$extra_bed_cost_arr, $total_rooms_arr, $package_id)
{
   $sq_hotel =true;
	for($i=0; $i<sizeof($city_name_arr); $i++){
			if($hotel_id_arr[$i]=="")
			{
				$sq_max = mysql_fetch_assoc(mysql_query("select max(id) as max from package_tour_quotation_hotel_entries"));
				$id = $sq_max['max']+1;

				$sq_hotel = mysql_query("insert into package_tour_quotation_hotel_entries ( id, quotation_id, city_name, hotel_name, hotel_type, total_days, package_id, total_rooms, hotel_cost, extra_bed, extra_bed_cost) values ( '$id', '$quotation_id', '$city_name_arr[$i]', '$hotel_name_arr[$i]','$hotel_type_arr[$i]', '$hotel_stay_days_arr[$i]', '$package_id', '$total_rooms_arr[$i]', '$hotel_cost_arr[$i]', '$extra_bed_arr[$i]', '$extra_bed_cost_arr[$i]' )");
				if(!$sq_hotel)
				{
					echo "Hotel information not inserted.";
					exit;
				}
			}
			else
			{
				$query = "update package_tour_quotation_hotel_entries set city_name='$city_name_arr[$i]', hotel_name='$hotel_name_arr[$i]',hotel_type = '$hotel_type_arr[$i]', total_days='$hotel_stay_days_arr[$i]',total_rooms='$total_rooms_arr[$i]', hotel_cost='$hotel_cost_arr[$i]', extra_bed='$extra_bed_arr[$i]', extra_bed_cost='$extra_bed_cost_arr[$i]' where id='$hotel_id_arr[$i]'";
				$sq_hotel = mysql_query($query);
				if(!$sq_hotel){
					echo "error--Hotel information not updated!".sizeof($city_name_arr);
					exit;
				 }
			}
	}

	
}

public function tranport_entries_update($quotation_id, $vehicle_name_arr, $start_date_arr, $end_date_arr, $transport_id_arr,$transport_cost_arr1, $package_id)
{
	for($i=0; $i<sizeof($vehicle_name_arr); $i++)
	{
		$start_date_arr[$i] = date('Y-m-d H:i:s', strtotime($start_date_arr[$i]));
	    $end_date_arr[$i] = date('Y-m-d H:i:s', strtotime($end_date_arr[$i]));
		if($transport_id_arr[$i]=="")
			{
				$sq_max = mysql_fetch_assoc(mysql_query("select max(id) as max from package_tour_quotation_transport_entries2"));
				$id = $sq_max['max']+1;

				$sq_trans = mysql_query("insert into package_tour_quotation_transport_entries2 ( id, quotation_id, vehicle_name, start_date, end_date, package_id, transport_cost) values ( '$id', '$quotation_id', '$vehicle_name_arr[$i]', '$start_date_arr[$i]','$end_date_arr[$i]', '$package_id','$transport_cost_arr1[$i]')");
				if(!$sq_trans)
				{
					echo "Transport information not inserted.";
					exit;
				}
		}
		else
		{
			$sq_trans = mysql_query("update package_tour_quotation_transport_entries2 set vehicle_name='$vehicle_name_arr[$i]', start_date='$start_date_arr[$i]', end_date='$end_date_arr[$i]',transport_cost='$transport_cost_arr1[$i]' where id='$transport_id_arr[$i]'");
			if(!$sq_trans){
				echo "error--Transport information not updated!";
				exit;
			}
		}

	}

}

public function excursion_entries_save($quotation_id,$city_name_arr_e, $excursion_name_arr, $excursion_amt_arr,$excursion_id_arr)
{
	for($i=0; $i<sizeof($city_name_arr_e); $i++){

		if($excursion_id_arr[$i] != ""){
			$sq_exc = mysql_query("update package_tour_quotation_excursion_entries set city_name='$city_name_arr_e[$i]', excursion_name='$excursion_name_arr[$i]', excursion_amount='$excursion_amt_arr[$i]' where id='$excursion_id_arr[$i]' ");
			if(!$sq_exc){
				echo "error--Excursion information not updated!";
				exit;
			}
		}
		else{
			$sq_max = mysql_fetch_assoc(mysql_query("select max(id) as max from package_tour_quotation_excursion_entries"));
			$id = $sq_max['max']+1;

			$sq_exc = mysql_query("insert into package_tour_quotation_excursion_entries ( id, quotation_id, city_name, excursion_name, excursion_amount ) values ( '$id', '$quotation_id', '$city_name_arr_e[$i]','$excursion_name_arr[$i]', '$excursion_amt_arr[$i]')");
			if(!$sq_exc){
				echo "error--Excursion information not saved!";
				exit;
			}
		}
	}
}


public function costing_entries_update($tour_cost_arr,$transport_cost_arr, $markup_cost_arr,$markup_subtotal_arr, $taxation_id_arr,$service_tax_arr,$service_tax_subtotal_arr,$total_tour_cost_arr, $costing_id_arr,$excursion_cost_arr,$adult_cost,$child_cost,$infant_cost,$child_with,$child_without,$extra_bed)
{
	for($i=0; $i<sizeof($tour_cost_arr); $i++){

			$sq_plane = mysql_query("update package_tour_quotation_costing_entries set tour_cost='$tour_cost_arr[$i]', markup_cost='$markup_cost_arr[$i]',markup_subtotal = '$markup_subtotal_arr[$i]',excursion_cost ='$excursion_cost_arr[$i]', taxation_id='$taxation_id_arr[$i]',service_tax = '$service_tax_arr[$i]',service_tax_subtotal = '$service_tax_subtotal_arr[$i]',total_tour_cost = '$total_tour_cost_arr[$i]',transport_cost='$transport_cost_arr[$i]',adult_cost = '$adult_cost', child_cost = '$child_cost',extra_bedc = '$extra_bed', infant_cost = '$infant_cost',child_with = '$child_with', child_without = '$child_without' where id='$costing_id_arr[$i]'");
			if(!$sq_plane){
				echo "error--Costing information not updated!";
				exit;
			}

	}

}

}
?>