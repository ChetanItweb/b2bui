<?php
$sq_c_hotel = mysql_num_rows(mysql_query("select * from package_hotel_accomodation_master where booking_id='$booking_id'"));
if($sq_c_hotel != '0'){
?>
<div class="row mg_bt_20">
	<div class="col-xs-12">
		<div class="profile_box main_block">
        	 	<h3 class="editor_title">Accommodation Details</h3>
				<div class="table-responsive">
                    <table class="table table-bordered no-marg">
	                    <thead>
	                       	<tr class="table-heading-row">
		                       	<th>S_No.</th>
		                       	<th>City</th>
		                       	<th>Hotel_Name</th>
		                       	<th>Check_In_DateTime</th>
		                       	<th>Check_Out_DateTime</th>
		                       	<th>Room</th>
		                       	<th>Category</th>
		                       	<th>Meal_Plan</th>
		                       	<th>Room_Type</th>
		                       	<th>Confirmation_No</th>
	                       </tr>
	                    </thead>
	                    <tbody>
	                       <?php 
	                       		$count = 0;
	                       		$sq_entry = mysql_query("select * from package_hotel_accomodation_master where booking_id='$booking_id'");
	                       		
							    while($row_entry = mysql_fetch_assoc($sq_entry)){
	                       			$city_id = $row_entry['city_id'];
	                       			$hotel_id = $row_entry['hotel_id'];

	                       			$sq_city = mysql_fetch_assoc(mysql_query("select * from city_master where city_id='$city_id'"));
	                       			$sq_hotel_name = mysql_fetch_assoc(mysql_query("select * from hotel_master where hotel_id='$hotel_id'"));
	                       			$count++;

	                       	?>
							<tr class="<?php echo $bg; ?>">
							    <td><?php echo $count; ?></td>
							    <td><?php echo $sq_city['city_name'] ?></td>
								<td><?php echo $sq_hotel_name['hotel_name'].$similar_text; ?></td>
							    <td><?php echo get_datetime_user($row_entry['from_date']); ?></td>
							    <td><?php echo get_datetime_user($row_entry['to_date']) ?></td>
							    <td><?php echo $row_entry['rooms']; ?> </td>
							    <td><?php echo $row_entry['catagory']; ?> </td>
							    <td><?php echo $row_entry['meal_plan']; ?></td>
							    <td><?php echo $row_entry['room_type']; ?></td>
							    <td><?php echo $row_entry['confirmation_no']; ?></td>
							</tr>  
							<script>
								$("#birth_date<?= $offset.$count ?>_d, #expiry_date<?= $offset ?>1").datetimepicker({ timepicker:false, format:'d-m-Y' });
							</script>      
	               			<?php

	               		}

	               	?>
	                </tbody>
                </table>
            	</div>
	    	</div> 
		</div>
</div>
 <?php } ?>

<?php 
$sq_c_package = mysql_fetch_assoc(mysql_query("select * from package_tour_transport_master where booking_id='$booking_id'"));
if($sq_c_package['transport_bus_id'] !=''){
?>
<div class="row mg_bt_20">
	<div class="col-md-12">
		<div class="profile_box main_block">
        	 	<h3 class="editor_title">Transport Details</h3>
				<div class="table-responsive">
                    <table class="table table-bordered no-marg">
	                    <thead>
	                       	<tr class="table-heading-row">
		                       	<th>Vehicle_name</th>
		                       	<th>From_Date</th>
		                       	<th>To_Date</th>
	                       </tr>
	                    </thead>
	                    <tbody>
											<?php
												$sq_entry = mysql_query("select * from package_tour_transport_master where booking_id='$booking_id'");
												while($row_entry = mysql_fetch_assoc($sq_entry)){

													$q_transport = mysql_fetch_assoc(mysql_query("select * from transport_agency_bus_master where bus_id='$row_entry[transport_bus_id]'"));
													?>
													<tr>
														<td><?= $q_transport['bus_name'].$similar_text ?></td>
														<td><?= get_date_user($row_entry['transport_from_date']) ?></td>
														<td><?= get_date_user($row_entry['transport_to_date']) ?></td>
													</tr>
											<?php } ?>
	                    </tbody>
                </table>
            </div>
	    </div> 
	</div>
</div>
<?php } ?>

<?php
$sq_c_package = mysql_fetch_assoc(mysql_query("select * from package_tour_excursion_master where booking_id='$booking_id'"));
if($sq_c_package['city_id'] !=''){
?>
<div class="row mg_bt_20">
	<div class="col-md-12">
		<div class="profile_box main_block">
        	 	<h3 class="editor_title">Excursion Details</h3>
				<div class="table-responsive">
                    <table class="table table-bordered no-marg">
										<thead>
											<tr class="table-heading-row">
												<th>City_Name</th>
												<th>Excursion_name</th>
											</tr>
										</thead>
										<tbody>
										<?php
										$sq_entry = mysql_query("select * from package_tour_excursion_master where booking_id='$booking_id'");
										while($row_entry = mysql_fetch_assoc($sq_entry)){
											$q_city = mysql_fetch_assoc(mysql_query("select * from city_master where city_id='$row_entry[city_id]'"));
											$q_transport = mysql_fetch_assoc(mysql_query("select * from itinerary_paid_services where service_id='$row_entry[exc_id]'"));
											?>
											<tr>
												<td><?= $q_city['city_name'] ?></td>
												<td><?= $q_transport['service_name'] ?></td>
											</tr>
									<?php } ?>
										</tbody>
                </table>
            </div>
	    </div> 
	</div>
</div>
<?php } ?>