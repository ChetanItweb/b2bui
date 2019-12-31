<?php
$login_id = $_SESSION['login_id'];
$financial_year_id = $_SESSION['financial_year_id'];
$emp_id = $_SESSION['emp_id'];

//**Enquiries
$added_enq_count = mysql_num_rows(mysql_query("select enquiry_id from enquiry_master where login_id='$login_id' and status!='Disabled'"));
$assigned_enq_count = mysql_num_rows(mysql_query("select enquiry_id from enquiry_master where assigned_emp_id='$emp_id' and status!='Disabled' and financial_year_id='$financial_year_id'"));


$converted_count = 0;
$closed_count = 0;
$followup_count = 0;

$sq_enquiry = mysql_query("select * from enquiry_master where status!='Disabled' and financial_year_id='$financial_year_id' and assigned_emp_id='$emp_id'");
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

                  <div class="dashboard_widget_title_panel main_block widget_red_title">

                    <div class="dashboard_widget_icon">

                      <i class="fa fa-bullseye" aria-hidden="true"></i>

                    </div>

                    <div class="dashboard_widget_title_text" onclick="window.open('<?= BASE_URL ?>view/attractions_offers_enquiry/enquiry/index.php', 'My Window');">

                      <h3>Leads</a></h3>

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

                        <span class="dashboard_widget_conetent_text widget_green_text">Converted</span>

                      </div>

                    </div>

                  </div>  

                </div>

              </div>



              <?php 

              $total_tour_fee = 0; $incentive_total = 0;

              $sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id = '$emp_id'"));

              if($sq_emp['target'] == ''){ $sq_emp['target'] =0; }

              $cur_date= date('Y/m/d H:i:s');

              $search_form = date('Y-m-01 H:i:s',strtotime($cur_date));

              $search_to =  date('Y-m-t H:i:s',strtotime($cur_date));
                //Completed Target                

               $sq_group_bookings = mysql_query("select * from tourwise_traveler_details where emp_id = '$emp_id' and  tour_group_status != 'Cancel' and (form_date between '$search_form' and '$search_to')");

               $total_group  = 0;
               while($row_group_bookings = mysql_fetch_assoc($sq_group_bookings))
               {
                  // Group booking cancel
                  $cancel_esti_count1=mysql_num_rows(mysql_query("SELECT * from refund_traveler_estimate where tourwise_traveler_id='$row_group_bookings[id]'"));
                  if($cancel_esti_count1 != '0'){
                    $tour_total_amount1 = 0;
                  }
                  else
                  { 
                    $tour_total_amount1 = $row_group_bookings['total_tour_fee'] + $row_group_bookings['total_travel_expense']; 
                  }

                  
                  $total_group = $total_group + $tour_total_amount1;
               }

               $sq_package_booking = mysql_query("select * from package_tour_booking_master where emp_id ='$emp_id' and tour_status !='Cancel' and (booking_date between '$search_form' and '$search_to')");
               $total_package = 0;
               while($row_package_booking = mysql_fetch_assoc($sq_package_booking)){

                  $sq_can_count = mysql_num_rows(mysql_query("select * from package_refund_traveler_estimate where booking_id='$row_package_booking[booking_id]'"));

                  if($sq_can_count == '0')
                  {  
                    //Tour Total
                    $tour_amount= ($row_package_booking['actual_tour_expense']!="") ? $row_package_booking['actual_tour_expense']: 0;
                    //Travel Total 
                    $travel_amount= ($row_package_booking['total_travel_expense']!="") ? $row_package_booking['total_travel_expense']: 0;
                    $total_tour_amount = ($tour_amount + $travel_amount);
                  } 
                  else{
                     $total_tour_amount = 0;
                  }
                  $total_package += $total_tour_amount;
                }

                $completed_amount = $total_package + $total_group;
              // Incentive

              $sq_incentive1 = mysql_query("select * from booker_incentive_group_tour where emp_id='$emp_id'");  

              while($row_group_bookings = mysql_fetch_assoc($sq_incentive1)){

                  $incentive_total = $incentive_total + $row_group_bookings['basic_amount'];

               }

              $sq_incentive2 = mysql_query("select * from booker_incentive_package_tour where emp_id='$emp_id'");

              while($row_package_booking = mysql_fetch_assoc($sq_incentive2)){

                  $incentive_total = $incentive_total + $row_package_booking['basic_amount'];

               }

               

              ?>

              <div class="col-md-6">

                <div class="dashboard_widget main_block">

                  <div class="dashboard_widget_title_panel main_block widget_purp_title">

                    <div class="dashboard_widget_icon">

                      <i class="fa fa-star-half-o" aria-hidden="true"></i>

                    </div>

                    <div class="dashboard_widget_title_text" onclick="window.open('<?= BASE_URL ?>view/booker_incentive/booker_incentive.php', 'My Window');">

                      <h3>achievements</h3>

                      <p>Total Achievements Summary</p>

                    </div>

                  </div>

                  <div class="dashboard_widget_conetent_panel main_block">

                    <div class="col-sm-4" style="border-right: 1px solid #e6e4e5">

                      <div class="dashboard_widget_single_conetent">

                        <span class="dashboard_widget_conetent_amount"><?php echo number_format($sq_emp['target'],2); ?></span>

                        <span class="dashboard_widget_conetent_text widget_blue_text">Target</span>

                      </div>

                    </div>

                    <div class="col-sm-4" style="border-right: 1px solid #e6e4e5">

                      <div class="dashboard_widget_single_conetent">

                        <span class="dashboard_widget_conetent_amount"><?php echo number_format($completed_amount,2); ?></span>

                        <span class="dashboard_widget_conetent_text widget_green_text">Completed</span>

                      </div>

                    </div>

                    <div class="col-sm-4 last_block">

                      <div class="dashboard_widget_single_conetent">

                        <span class="dashboard_widget_conetent_amount"><?php echo number_format($incentive_total,2); ?></span>

                        <span class="dashboard_widget_conetent_text widget_yellow_text">Incentives</span>

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
              <div class="dashboard_tab text-center">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs responsive" role="tablist">
                  <li role="presentation" class="active"><a href="#follow_tab" aria-controls="follow_tab" role="tab" data-toggle="tab">Followups</a></li>
                  <li role="presentation"><a href="#incent_tab" aria-controls="incent_tab" role="tab" data-toggle="tab">Incentive</a></li></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content responsive main_block">
                  <!-- Enquiry & Followup summary -->
                  <div role="tabpanel" class="tab-pane active" id="follow_tab">
                        <div class="dashboard_table dashboard_table_panel main_block mg_bt_25">
                          <div class="row text-left">
                            <div class="col-md-12">
                              <div class="dashboard_table_heading main_block">
                                <div class="col-md-10 no-pad">
                                  <h3 style="cursor: pointer;" onclick="window.open('<?= BASE_URL ?>view/attractions_offers_enquiry/enquiry/index.php', 'My Window');">Enquiry & Followup</h3>
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

                                          <th>History</th>

                                        </tr>

                                      </thead>

                                      <tbody>

                                          <?php
                                          $count = 0;
                                          $rightnow = date('Y-m-d');
                                          $add7days = date('Y-m-d', strtotime('+7 days'));
                                          $query = "SELECT * FROM `enquiry_master` where status!='Disabled' and financial_year_id='$financial_year_id' and assigned_emp_id='$emp_id'";
                                          $sq_enquiries = mysql_query($query);
                                          while($row = mysql_fetch_assoc($sq_enquiries)){ 
                                            
                                            $date = $row['enquiry_date'];
                                            $yr = explode("-", $date);
                                            $year =$yr[0];
                                            $sq3 = mysql_query("select * from enquiry_master_entries where entry_id =(select max(entry_id) as entry_id from enquiry_master_entries where enquiry_id='$row[enquiry_id]') and followup_date between '$rightnow' and '$add7days'");
                                            while ($row_sq_4=mysql_fetch_assoc($sq3)){
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
                                              $status_count1 = mysql_num_rows(mysql_query("select * from enquiry_master_entries where enquiry_id='$row[enquiry_id]' and followup_type='' "));
                                              if($status_count1==1){
                                                $followup_date1 = $row['followup_date'];
                                              }
                                              else{
                                                $enquiry_status1 = mysql_fetch_assoc(mysql_query("select * from enquiry_master_entries where entry_id=(select max(entry_id) from enquiry_master_entries where enquiry_id='$row[enquiry_id]') "));
                                                $followup_date1 = $enquiry_status1['followup_date'];
                                              }
                                          ?>

                                          <tr class="<?= $bg ?>">

                                            <td><?php echo $count; ?></td>

                                            <td><?= get_enquiry_id($row['enquiry_id'],$year) ?></td>          

                                            <td><?php echo $row['name']; ?></td>

                                            <td><?php echo($row['enquiry_type'])?></td>

                                            <td><?php echo get_datetime_user($row['enquiry_date']); ?></td>

                                            <td><?= get_datetime_user($followup_date1) ?></td>

                                            <td><?php echo $row['mobile_no']; ?></td>

                                            <td><?php echo $row['enquiry']; ?></td>

                                            <td><button class="btn btn-info btn-sm" onclick="display_history('<?php echo $row['enquiry_id']; ?>');" title="Followup History" ><i class="fa fa-history"></i></button></td>

                                          </tr>
                                            <?php }
                                          } ?>

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

                  <!-- Incentive -->
                  <div role="tabpanel" class="tab-pane" id="incent_tab">
                      <div class="dashboard_table dashboard_table_panel main_block">

                        <div class="row text-left">

                          <div class="col-md-12">

                            <div class="dashboard_table_heading main_block">

                              <div class="col-md-8 no-pad">

                                <h3 style="cursor: pointer;" onclick="window.open('<?= BASE_URL ?>view/booker_incentive/index.php', 'My Window');">Incentive/Commission</h3>

                              </div>

                              <div class="col-md-2 col-xs-12 mg_bt_10_sm_xs no-pad-sm">

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

    $.post('agent/incentive_list_reflect.php', { from_date : from_date, to_date : to_date }, function(data){

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