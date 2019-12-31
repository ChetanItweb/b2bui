<?php
$login_id = $_SESSION['login_id'];
$emp_id = $_SESSION['emp_id'];
$financial_year_id = $_SESSION['financial_year_id'];

//**Enquiries
$assigned_enq_count = mysql_num_rows(mysql_query("select enquiry_id from enquiry_master where assigned_emp_id='$emp_id' and financial_year_id='$financial_year_id' and status!='Disabled'"));

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

  <div class="dashboard_enqury_widget_panel main_block mg_bt_25">
            <div class="row">
                <div class="col-sm-3 col-xs-6" onclick="window.open('<?= BASE_URL ?>view/attractions_offers_enquiry/enquiry/index.php', 'My Window');">
                  <div class="single_enquiry_widget main_block blue_enquiry_widget mg_bt_10_sm_xs">
                    <div class="col-xs-3 text-left">
                      <i class="fa fa-cubes"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                      <span class="single_enquiry_widget_amount"><?php echo $assigned_enq_count; ?></span>
                    </div>
                    <div class="col-sm-12 single_enquiry_widget_amount">
                      Total Enquiries
                    </div>
                  </div>
                </div>
                <div class="col-sm-3 col-xs-6" onclick="window.open('<?= BASE_URL ?>view/attractions_offers_enquiry/enquiry/index.php', 'My Window');">
                  <div class="single_enquiry_widget main_block mg_bt_10_sm_xs yellow_enquiry_widget">
                    <div class="col-xs-3 text-left">
                      <i class="fa fa-comments-o"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                      <span class="single_enquiry_widget_amount"><?php echo $followup_count; ?></span>
                    </div>
                    <div class="col-sm-12 single_enquiry_widget_amount">
                      Active Enquiries
                    </div>
                  </div>
                </div>
                <div class="col-sm-3 col-xs-6" onclick="window.open('<?= BASE_URL ?>view/attractions_offers_enquiry/enquiry/index.php', 'My Window');">
                  <div class="single_enquiry_widget main_block green_enquiry_widget ">
                    <div class="col-xs-3 text-left">
                      <i class="fa fa-check-square-o"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                      <span class="single_enquiry_widget_amount"><?php echo $converted_count; ?></span>
                    </div>
                    <div class="col-sm-12 single_enquiry_widget_amount">
                      Converted Enquiries
                    </div>
                  </div>
                </div>
                <div class="col-sm-3 col-xs-6">
                  <div class="single_enquiry_widget main_block red_enquiry_widget" onclick="window.open('<?= BASE_URL ?>view/attractions_offers_enquiry/enquiry/index.php', 'My Window');">
                    <div class="col-xs-3 text-left">
                      <i class="fa fa-tint"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                      <span class="single_enquiry_widget_amount"><?php echo $closed_count; ?></span>
                    </div>
                    <div class="col-sm-12 single_enquiry_widget_amount">
                      Dropped Enquiries
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
                  <li role="presentation" class="active"><a href="#fol-w_tab" aria-controls="fol-w_tab" role="tab" data-toggle="tab">Followups</a></li>
                  <li role="presentation"><a href="#tsk_tab" aria-controls="tsk_tab" role="tab" data-toggle="tab">Task</a></li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content responsive main_block">

                  <!-- enquiry & followups -->
                  <div role="tabpanel" class="tab-pane active" id="fol-w_tab">
                     <div class="dashboard_table dashboard_table_panel main_block">
                        <div class="row text-left">
                          <div class="col-md-12">
                            <div class="dashboard_table_heading main_block">
                              <div class="col-md-12 no-pad">
                                <h3>Weekly Followups</h3>
                                <?php 
                              $count = 0;
                              $rightnow = date('Y-m-d-h-i-s');
                              $add7days = date('Y-m-d-h-i-s', strtotime('+7 days'));
                              $query = "SELECT * FROM `enquiry_master` where status!='Disabled' and financial_year_id='$financial_year_id' and assigned_emp_id='$emp_id' and followup_date between '$rightnow' and '$add7days'";
                              $sq_enquiries = mysql_query($query);
                                ?>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-12">
                            <div class="dashboard_table_body main_block">
                              <div class="col-md-12 no-pad  table_verflow"> 
                                <div class="table-responsive">
                                  <table class="table table-hover" style="margin: 0 !important;border: 0;">
                                    <thead>
                                      <tr class="table-heading-row">
                                          <th>S_No.</th>
                                          <th>Customer_Name</th>
                                          <th>Tour</th>
                                          <th>Mobile</th>
                                          <th>Followup_DateTime&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                         <!--  <th>Assigned_To&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th> -->
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
                                          <td><?php echo $row['name']; ?></td>
                                          <td><?php echo($row['enquiry_type']) ?></td>
                                          <td><?php echo $row['mobile_no']; ?></td>
                                          <td><?= get_datetime_user($row['followup_date']); ?></td>
                                       <!--    <td><?php echo $sq_emp['first_name'].' '.$sq_emp['last_name']; ?></td> -->
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
                        <div id="history_data"></div>
                     </div>
                  </div>
                  <!-- enquiry & followups End -->

                <!-- Weekly Task -->
                  <div role="tabpanel" class="tab-pane" id="tsk_tab">
                      <?php
                    $assigned_task_count = mysql_num_rows(mysql_query("select task_id from tasks_master where emp_id='$emp_id'"));
                    $completed_task_count = mysql_num_rows(mysql_query("select task_id from tasks_master where emp_id='$emp_id' and task_status='Completed'"));
                    $incomplete_task_count = mysql_num_rows(mysql_query("select task_id from tasks_master where emp_id='$emp_id' and task_status='Incomplete'"));
                    $sq_task = mysql_query("select * from tasks_master where emp_id='$emp_id' order by task_id desc");
                    ?>
                      <div class="dashboard_table dashboard_table_panel main_block">
                        <div class="row text-left">
                            <div class="col-md-12">
                              <div class="dashboard_table_heading main_block">
                                <div class="col-md-12 no-pad">
                                  <h3 style="cursor: pointer;" onclick="window.open('<?= BASE_URL ?>view/tasks/index.php', 'My Window');">Weekly Task</h3>
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
                                          <th>Booking_ID/Enq_No.</th>
                                          <th>Assign_Date</th>
                                          <th>Due_DateTime</th>
                                          <th>Status</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                      <?php 
                                       while($row_task = mysql_fetch_assoc($sq_task)){ 
                                          $count++;
                                          if($row_task['task_status'] == 'Completed'){
                                            $bg='success';
                                          }
                                          elseif($row_task['task_status'] == 'Incomplete' ){
                                            $bg='danger';
                                          }
                                      ?>
                                          <tr class="odd">
                                            <td><?php echo $row_task['task_name']; ?></td>
                                            <td><?php echo $row_task['task_type']; ?></td>
                                            <td><?php echo $row_task['task_type_field_id']; ?></td>
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
                                      <div class="col-xs-12" style="border-bottom: 1px solid hsla(180, 100%, 30%, 0.25)">
                                        <div class="table_side_widget">
                                          <div class="table_side_widget_amount"><?= $completed_task_count ?></div>
                                          <div class="table_side_widget_text widget_green_text">task Completed</div>
                                        </div>
                                      </div>
                                      <div class="col-xs-12">
                                        <div class="table_side_widget">
                                          <div class="table_side_widget_amount"><?= $incomplete_task_count ?></div>
                                          <div class="table_side_widget_text widget_red_text">Task Pending</div>
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
                    <!-- Weekly Task End -->
                </div>
              </div>
            </div>
          </div>
      </div>
</div>
     
<script type="text/javascript">
function display_history(enquiry_id){
  $.post('admin/followup_history.php', { enquiry_id : enquiry_id }, function(data){
  $('#history_data').html(data);
  });
}
</script>