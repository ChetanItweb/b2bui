<?php 
$login_id = $_SESSION['login_id'];
$financial_year_id = $_SESSION['financial_year_id'];
$emp_id = $_SESSION['emp_id'];

//**Enquiries
$assigned_enq_count = mysql_num_rows(mysql_query("select enquiry_id from enquiry_master where assigned_emp_id='$emp_id' and status!='Disabled' and financial_year_id='$financial_year_id'"));

$converted_count = 0;
$closed_count = 0;
$followup_count = 0;

$sq_enquiry = mysql_query("select * from enquiry_master where status!='Disabled' and assigned_emp_id='$emp_id' and financial_year_id='$financial_year_id'");
	while($row_enq = mysql_fetch_assoc($sq_enquiry)){
		$sq_enquiry_entry = mysql_fetch_assoc(mysql_query("select followup_status from enquiry_master_entries where entry_id=(select max(entry_id) as entry_id from enquiry_master_entries where enquiry_id='$row_enq[enquiry_id]')"));
		if($sq_enquiry_entry['followup_status']=="Dropped"){
			$closed_count++;
		}
		if($sq_enquiry_entry['followup_status']=="Converted"){
			$converted_count++;
		}
		if($sq_enquiry_entry['followup_status']=="Active"){
			$followup_count++;
		}
	}


?>
<div class="app_panel"> 
<div class="dashboard_panel panel-body">

      <div class="dashboard_widget_panel dashboard_widget_panel_first main_block mg_bt_25">
            <div class="row">

              <div class="col-md-6">
                <div class="dashboard_widget main_block mg_bt_10_xs">
                  <div class="dashboard_widget_title_panel main_block widget_red_title" onclick="window.open('<?= BASE_URL ?>view/attractions_offers_enquiry/enquiry/index.php', 'My Window');">
                    <div class="dashboard_widget_icon">
                      <i class="fa fa-bullseye" aria-hidden="true"></i>
                    </div>
                    <div class="dashboard_widget_title_text">
                      <h3>Leads</h3>
                      <p>Total Leads Summary</p>
                    </div>
                  </div>
                  <div class="dashboard_widget_conetent_panel main_block">
                    <div class="col-sm-4" style="border-right: 1px solid #e6e4e5">
                      <div class="dashboard_widget_single_conetent">
                        <span class="dashboard_widget_conetent_amount"><?php echo $assigned_enq_count; ?></span>
                        <span class="dashboard_widget_conetent_text widget_blue_text">Total</span>
                      </div>
                    </div>
                    <div class="col-sm-4" style="border-right: 1px solid #e6e4e5">
                      <div class="dashboard_widget_single_conetent">
                        <span class="dashboard_widget_conetent_amount"><?php echo $followup_count; ?></span>
                        <span class="dashboard_widget_conetent_text widget_yellow_text">Active</span>
                      </div>
                    </div>
                    <div class="col-sm-4 last_block">
                      <div class="dashboard_widget_single_conetent">
                        <span class="dashboard_widget_conetent_amount"><?php echo $converted_count; ?></span>
                        <span class="dashboard_widget_conetent_text widget_green_text ">Converted</span>
                      </div>
                    </div>
                  </div>  
                </div>
              </div>


              <?php 
              $total_tour_fee = 0; $incentive_total = 0;
              $sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id = '$emp_id'"));
              $cur_date= date('Y/m/d H:i:s');
              $search_form = date('Y-m-01 H:i:s',strtotime($cur_date));
              $search_to =  date('Y-m-t H:i:s',strtotime($cur_date));
                //Completed Target                
               $sq_group_bookings = mysql_query("select * from tourwise_traveler_details where emp_id = '$emp_id' and (form_date between '$search_form' and '$search_to')");
               while($row_group_bookings = mysql_fetch_assoc($sq_group_bookings)){

                  $total_tour_fee = $total_tour_fee + $row_group_bookings['total_tour_fee'] + $row_group_bookings['total_travel_expense'];
               }

               $sq_package_booking = mysql_query("select * from package_tour_booking_master where emp_id ='$emp_id' and (booking_date between '$search_form' and '$search_to')");
                while($row_package_booking = mysql_fetch_assoc($sq_package_booking)){
                  $total_tour_fee = $total_tour_fee + $row_package_booking['actual_tour_expense'] + $row_package_booking['total_travel_expense'];
                }

              // Incentive
              $sq_incentive1 = mysql_query("select * from booker_incentive_group_tour where emp_id='$emp_id'");  
              while($row_group_bookings = mysql_fetch_assoc($sq_incentive1)){
                  $incentive_total = $incentive_total + $row_group_bookings['basic_amount'];
               }
              $sq_incentive2 = mysql_query("select * from booker_incentive_package_tour where emp_id='$emp_id'");
              while($row_package_booking = mysql_fetch_assoc($sq_incentive2)){
                  $incentive_total = $incentive_total + $row_package_booking['basic_amount'];
               }
               $target = ($sq_emp['target']!='')?$sq_emp['target']:'0';
               
              ?>
              <div class="col-md-6">
                <div class="dashboard_widget main_block">
                  <div class="dashboard_widget_title_panel main_block widget_purp_title" onclick="window.open('<?= BASE_URL ?>view/attractions_offers_enquiry/enquiry/index.php', 'My Window');">
                    <div class="dashboard_widget_icon">
                      <i class="fa fa-star-half-o" aria-hidden="true"></i>
                    </div>
                    <div class="dashboard_widget_title_text">
                      <h3>achievements</h3>
                      <p>Total Achievements Summary</p>
                    </div>
                  </div>
                  <div class="dashboard_widget_conetent_panel main_block">
                    <div class="col-sm-4" style="border-right: 1px solid #e6e4e5">
                      <div class="dashboard_widget_single_conetent">
                        <span class="dashboard_widget_conetent_amount"><?php echo number_format($target,2); ?></span>
                        <span class="dashboard_widget_conetent_text widget_blue_text">Target</span>
                      </div>
                    </div>
                    <div class="col-sm-4" style="border-right: 1px solid #e6e4e5">
                      <div class="dashboard_widget_single_conetent">
                        <span class="dashboard_widget_conetent_amount"><?php echo number_format($total_tour_fee,2); ?></span>
                        <span class="dashboard_widget_conetent_text widget_green_text">Completed</span>
                      </div>
                    </div>
                    <div class="col-sm-4 last_block">
                      <div class="dashboard_widget_single_conetent">
                        <span class="dashboard_widget_conetent_amount"><?php echo number_format($incentive_total,2); ?></span>
                        <span class="dashboard_widget_conetent_text widget_red_text">Incentives</span>
                      </div>
                    </div>
                  </div>  
                </div>
              </div>
      </div>
   </div>

   <!-- dashboard_tab -->

          <div class="row">
            <div class="col-md-12">
              <div class="dashboard_tab text-center main_block">

                <!-- Nav tabs -->
                <ul class="nav nav-tabs responsive" role="tablist">
                  <li role="presentation" class="active"><a href="#oncoming_tab" aria-controls="oncoming_tab" role="tab" data-toggle="tab">Ongoing Tours</a></li>
                  <li role="presentation"><a href="#upcoming_tab" aria-controls="upcoming_tab" role="tab" data-toggle="tab">Upcoming Tours</a></li>
                  <li role="presentation"><a href="#enquiry_tab" aria-controls="enquiry_tab" role="tab" data-toggle="tab">Followups</a>
                  <li role="presentation"><a href="#task_tab" aria-controls="task_tab" role="tab" data-toggle="tab">Task</a></li>
                  <li role="presentation"><a href="#incentive_tab" aria-controls="incentive_tab" role="tab" data-toggle="tab">Incentive</a></li></li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content responsive main_block">
                    <!-- Ongoing FIT Tours -->
                    <div role="tabpanel" class="tab-pane active" id="oncoming_tab">
                    <?php 
                    $count = 1;
                    $today = date('Y-m-d');                 
                    ?>
                    <div class="dashboard_table dashboard_table_panel main_block">
                      <div class="row text-left">
                        <div class="col-md-12">
                          <div class="dashboard_table_heading main_block">
                            <div class="col-md-10 no-pad">
                              <h3>Package Tours</h3>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="dashboard_table_body main_block">
                            <div class="col-md-12 no-pad table_verflow"> 
                              <div class="table-responsive">
                                <table class="table table-hover" style="margin: 0 !important;border: 0;">
                                  <thead>
                                    <tr class="table-heading-row">
                                      <th>S_No.</th>
                                      <th>Tour_Name</th>
                                      <th>Tour_Date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                      <th>Customer_Name</th>
                                      <th>Mobile</th>
                                      <th>Booked By&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                      <?php
                      $query1 = "select * from package_tour_booking_master where tour_status!='Disabled' and financial_year_id='$financial_year_id' and emp_id = '$emp_id' and tour_from_date <= '$today' and tour_to_date >= '$today'";
                              
                            $sq_query = mysql_query($query1);
                            while($row_query=mysql_fetch_assoc($sq_query))
                            {
                              $sq_cancel_count = mysql_num_rows(mysql_query("select * from package_travelers_details where booking_id='$row_query[booking_id]' and status='Cancel'"));
                              $sq_count = mysql_num_rows(mysql_query("select * from package_travelers_details where booking_id='$row_query[booking_id]'"));
                              if($sq_cancel_count != $sq_count){
                              $sq_cust = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id = '$row_query[customer_id]'"));
                              $sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id = '$row_query[emp_id]'"));
                      ?>
                                      <tr class="<?= $bg ?>">
                                        <td><?php echo $count++; ?></td>
                                        <td><?php echo $row_query['tour_name']; ?></td>
                                        <td><?= get_date_user($row_query['tour_from_date']).' To '.get_date_user($row_query['tour_to_date']); ?></td>
                                        <td><?php echo $sq_cust['first_name'].' '.$sq_cust['last_name']; ?></td>
                                        <td><?php echo $row_query['mobile_no']; ?></td>
                                        <td><?= ($row_query['emp_id']=='0') ? "Admin" : $sq_emp['first_name'].' '.$sq_emp['last_name'] ?></td>
                                      </tr>
                                    <?php 
                                   } }
                                       ?>
                                  </tbody>
                                </table>
                              </div> 
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    </div>
                    <!-- Ongoing FIT Tour summary End -->
                  <!-- Upcoming FIT Tours -->
                  <div role="tabpanel" class="tab-pane" id="upcoming_tab">
                      <?php 
                      $count = 1;
                      $today = date('Y-m-d-h-i-s');
                  	  $add7days = date('Y-m-d-h-i-s', strtotime('+7 days'));                        
                      ?>
                      <div class="dashboard_table dashboard_table_panel main_block">
                        <div class="row text-left">
                          <div class="col-md-12">
                            <div class="dashboard_table_heading main_block">
                              <div class="col-md-10 no-pad">
                                <h3>Package Tours</h3>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-12">
                            <div class="dashboard_table_body main_block">
                              <div class="col-md-12 no-pad table_verflow"> 
                                <div class="table-responsive">
                                  <table class="table table-hover" style="margin: 0 !important;border: 0;">
                                    <thead>
                                      <tr class="table-heading-row">
                                        <th>S_No.</th>
                                        <th>Tour_Name</th>
                                        <th>Tour_Date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                        <th>Customer_Name</th>
                                        <th>Mobile</th>
                                        <th>Booked By&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                        <?php
                        $query = "select * from package_tour_booking_master where tour_status!='Disabled' and financial_year_id='$financial_year_id' and tour_from_date between '$today' and '$add7days'";                              
                        $sq_query = mysql_query($query);

                        while($row_query=mysql_fetch_assoc($sq_query))
                        {
                          $sq_cancel_count = mysql_num_rows(mysql_query("select * from package_travelers_details where booking_id='$row_query[booking_id]' and status='Cancel'"));
                          $sq_count = mysql_num_rows(mysql_query("select * from package_travelers_details where booking_id='$row_query[booking_id]'"));
                          if($sq_cancel_count != $sq_count){
                          $sq_cust = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id = '$row_query[customer_id]'"));
                          $sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id = '$row_query[emp_id]'"));
                            ?>
                                    <tr class="<?= $bg ?>">
                                      <td><?php echo $count++; ?></td>
                                      <td><?php echo $row_query['tour_name']; ?></td>
                                      <td><?= date('d-m-Y', strtotime($row_query['tour_from_date'])) ?></td>
                                      <td><?php echo $sq_cust['first_name'].' '.$sq_cust['last_name']; ?></td>
                                      <td><?php echo $row_query['mobile_no']; ?></td>
                                      <td><?php echo $sq_emp['first_name'].' '.$sq_emp['last_name'];?></td>
                                    </tr>
                                  <?php 
                                  } }
                                         ?>
                                    </tbody>
                                  </table>
                                </div> 
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                  </div>
            <!-- Upcoming FIT Tour summary End -->

            <!-- Enquiry & Followup summary -->
                  <div role="tabpanel" class="tab-pane" id="enquiry_tab">
                      <?php 
                        $count = 0;
                        $rightnow = date('Y-m-d h:i:s');
                    	$add7days = date('Y-m-d h:i:s', strtotime('+7 days'));
                    
                        $query = "SELECT * FROM `enquiry_master` where status!='Disabled' and financial_year_id='$financial_year_id' and assigned_emp_id='$emp_id' and followup_date between '$rightnow' and '$add7days'";
             
                        $sq_enquiries = mysql_query($query);
                        ?>
                        <div class="dashboard_table dashboard_table_panel main_block mg_bt_25">
                          <div class="row text-left">
                            <div class="col-md-12">
                              <div class="dashboard_table_heading main_block">
                                <div class="col-md-10 no-pad">
                                  <h3>Enquiry & Followup</h3>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-12">
                              <div class="dashboard_table_body main_block">
                                <div class="col-md-12 no-pad table_verflow"> 
                                  <div class="table-responsive">
                                    <table class="table table-hover" style="margin: 0 !important;border: 0;">
                                      <thead>
                                        <tr class="table-heading-row">
                                          <th>S_No.</th>
                                          <th>enquiry_id</th>
                                          <th>Customer_Name</th>
                                          <th>Tour</th>
                                          <th>Enquiry_date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                          <th>Followup_DateTime&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                          <th>Mobile</th>
                                          <th>Type</th>
                                          <th>Followup&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                          <th>History</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                      <?php
                                       while($row = mysql_fetch_assoc($sq_enquiries)){ 
                                        $count++;
                                        $assigned_emp_id = $row['assigned_emp_id'];
                                        $sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id='$assigned_emp_id'"));
                                        $enquiry_content = $row['enquiry_content'];
                                        $enquiry_content_arr1 = json_decode($enquiry_content, true);
                                        $status_count = mysql_num_rows(mysql_query("select * from enquiry_master_entries where enquiry_id='$row[enquiry_id]' "));
                                        if($status_count>0){
                                          $enquiry_status = mysql_fetch_assoc(mysql_query("select * from enquiry_master_entries where entry_id=(select max(entry_id) from enquiry_master_entries where enquiry_id='$row[enquiry_id]') "));
                                          $bg = ($enquiry_status['followup_status']=='Converted') ? "success" : "";
                                          $bg = ($enquiry_status['followup_status']=='Dropped') ? "danger" : $bg;
                                          $bg = ($enquiry_status['followup_status']=='Active') ? "warning" : $bg;

                                          if($enquiry_status_filter!=""){
                                            if($enquiry_status['followup_status']!=$enquiry_status_filter){
                                              continue;
                                            }
                                          }
                                        }
                                        else{
                                          $bg = "";
                                        }
                                      ?>
                                        <tr class="<?= $bg ?>">
                                          <td><?php echo $count; ?></td>
                                          <td><?= get_enquiry_id($row['enquiry_id']) ?></td>          
                                          <td><?php echo $row['name']; ?></td>
                                          <td><?php echo($row['enquiry_type']) ?></td>
                                          <td><?php echo get_datetime_user($row['enquiry_date']); ?></td>
                                          <td><?= get_datetime_user($enquiry_status['followup_date']); ?></td>
                                          <td><?php echo $row['mobile_no']; ?></td>
                                          <td><?php echo $row['enquiry']; ?></td>
                                          <td><a class="btn btn-info btn-sm" href="<?= BASE_URL ?>view/attractions_offers_enquiry/enquiry/followup/index.php?enquiry_id=<?php echo $row['enquiry_id'] ?>" title="Update Enquiry" target="_blank"><i class="fa fa-reply-all"></i></a></td>
                                          <td><button class="btn btn-info btn-sm" onclick="display_history('<?php echo $row['enquiry_id']; ?>');" title="Followup History" ><i class="fa fa-history"></i></button></td>
                                        </tr>
                                        <?php } ?>
                                      </tbody>
                                    </table>
                                  </div> 
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                     <div id="history_data"></div>
                  </div>
            <!-- Enquiry & Followup summary End -->

                      <!-- Weekly Task -->
                  <div role="tabpanel" class="tab-pane" id="task_tab">
                    <?php
                    $assigned_task_count = mysql_num_rows(mysql_query("select task_id from tasks_master where emp_id='$emp_id' and task_status!='Disabled'"));
                    $can_task_count = mysql_num_rows(mysql_query("select task_id from tasks_master where emp_id='$emp_id' and task_status='Cancelled'"));
                    $completed_task_count = mysql_num_rows(mysql_query("select task_id from tasks_master where emp_id='$emp_id' and task_status='Completed'"));
                    ?>
                    <div class="dashboard_table dashboard_table_panel main_block">
                      <div class="row text-left">
                          <div class="col-md-12">
                            <div class="dashboard_table_heading main_block">
                              <div class="col-md-12 no-pad">
                                <h3>Allocated Tasks</h3>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-12">
                            <div class="dashboard_table_body main_block">
                              <div class="col-sm-9 no-pad table_verflow table_verflow_two"> 
                                <div class="table-responsive no-marg-sm">
                                  <table class="table table-hover" style="margin: 0 !important;border: 0;">
                                    <thead>
                                      <tr class="table-heading-row">
                                        <th>Task_Name</th>
                                        <th>Task_Type</th>
                                        <th>ID</th>
                                        <th>Assign_Date</th>
                                        <th>Due_Date&Time</th>
                                        <th>Status</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $sq_task = mysql_query("select * from tasks_master where emp_id='$emp_id' and (task_status='Created' or task_status='Incomplete') order by task_id");
                                    while($row_task = mysql_fetch_assoc($sq_task)){ 
                                        $count++;
                                        if($row_task['task_status'] == 'Created'){
                                          $bg='warning';
                                        }
                                        elseif($row_task['task_status'] == 'Incomplete' ){
                                          $bg='danger';
                                        }
                                    ?>
                                        <tr class="odd">
                                          <td><?php echo $row_task['task_name']; ?></td>
                                          <td><?php echo $row_task['task_type']; ?></td>
                                          <td><?php echo ($row_task['task_type_field_id']!='')?$row_task['task_type_field_id']:'NA'; ?></td>
                                          <td><?php echo get_date_user($row_task['created_at']); ?></td>
                                          <td><?php echo get_datetime_user($row_task['due_date']); ?></td>
                                          <td><span class="<?= $bg ?>"><?php echo $row_task['task_status']; ?></span></td>
                                        </tr>
                                      <?php } ?>
                                    </tbody>
                                  </table>
                                </div>
                              </div>
                              <div class="col-sm-3 no-pad">
                                <div class="table_side_widget_panel main_block">
                                  <div class="table_side_widget_content main_block">
                                    <div class="col-xs-12" style="border-bottom: 1px solid hsla(180, 100%, 30%, 0.25)">
                                      <div class="table_side_widget">
                                        <div class="table_side_widget_amount"><?= $assigned_task_count ?></div>
                                        <div class="table_side_widget_text widget_blue_text">Total Task</div>
                                      </div>
                                    </div>
                                    <div class="col-xs-6" style="border-bottom: 1px solid hsla(180, 100%, 30%, 0.25)">
                                      <div class="table_side_widget">
                                        <div class="table_side_widget_amount"><?= $completed_task_count ?></div>
                                        <div class="table_side_widget_text widget_green_text">Task Completed</div>
                                      </div>
                                    </div>
                                    <div class="col-xs-6" style="border-bottom: 1px solid hsla(180, 100%, 30%, 0.25)">
                                      <div class="table_side_widget">
                                        <div class="table_side_widget_amount"><?= $can_task_count ?></div>
                                        <div class="table_side_widget_text widget_red_text">Task Cancelled</div>
                                      </div>
                                    </div>
                                    <div class="col-xs-12">
                                      <div class="table_side_widget">
                                        <div class="table_side_widget_amount"><?= $assigned_task_count-$completed_task_count-$can_task_count ?></div>
                                        <div class="table_side_widget_text widget_yellow_text">Task Pending</div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                      </div>
                    </div> 
                  </div>
                <!-- Weekly Task  End-->
                <!-- Monthly Incentive -->
                  <div role="tabpanel" class="tab-pane" id="incentive_tab">
                      <div class="dashboard_table dashboard_table_panel main_block">
                        <div class="row text-left">
                          <div class="col-md-12">
                            <div class="dashboard_table_heading main_block">
                              <div class="col-md-8 no-pad">
                                <h3 style="cursor: pointer;" onclick="window.open('<?= BASE_URL ?>view/booker_incentive/index.php', 'My Window');">Incentive/Commission</h3>
                              </div>
                              <div class="col-md-2 col-xs-12 no-pad-sm mg_bt_10_sm_xs">
                                  <input type="text" id="from_date" name="from_date" class="form-control" placeholder="From Date" title="From Date" onchange="booking_list_reflect()">
                              </div>
                              <div class="col-md-2 col-xs-12 no-pad-sm">
                                  <input type="text" id="to_date" name="to_date" class="form-control" placeholder="To Date" title="To Date" onchange="booking_list_reflect()">
                              </div>
                            </div>
                          </div>
                          <div class="col-md-12">
                            <div class="dashboard_table_body main_block">
                              <div class="col-md-12 no-pad  table_verflow"> 
                                  <div id="div_booker_incentive_reflect">
                                  </div>                     
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                  </div>
                  <!-- Incentive End --> 

                </div>

              </div>
            </div>
          </div>

     </div>
  </div>
<script type="text/javascript">
$('#from_date, #to_date').datetimepicker({ timepicker:false, format:'d-m-Y' });
  function booking_list_reflect()
  {
    var from_date = $('#from_date').val();
    var to_date = $('#to_date').val();
    $.post('sales/incentive_list_reflect.php', { from_date : from_date, to_date : to_date }, function(data){
      $('#div_booker_incentive_reflect').html(data);
    });
  }
  booking_list_reflect();
	function display_history(enquiry_id)
	{
		$.post('admin/followup_history.php', { enquiry_id : enquiry_id }, function(data){
		$('#history_data').html(data);
		});
	}
</script>
<script type="text/javascript">
    (function($) {
        fakewaffle.responsiveTabs(['xs', 'sm']);
    })(jQuery);
  </script>