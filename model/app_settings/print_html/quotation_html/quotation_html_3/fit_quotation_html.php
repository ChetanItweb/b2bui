<?php 
//Generic Files
include "../../../../model.php"; 
include "printFunction.php";
global $app_quot_img,$similar_text,$qout_note;

$quotation_id = $_GET['quotation_id'];
$sq_terms_cond = mysql_fetch_assoc(mysql_query("select * from terms_and_conditions where type='Package Quotation' and active_flag ='Active'")); 

$sq_quotation = mysql_fetch_assoc(mysql_query("select * from package_tour_quotation_master where quotation_id='$quotation_id'"));
$transport_agency_id = $sq_quotation['transport_agency_id'];
$sq_transport1 = mysql_fetch_assoc(mysql_query("select * from transport_agency_master where transport_agency_id='$transport_agency_id'"));
$sq_package_name = mysql_fetch_assoc(mysql_query("select * from custom_package_master where package_id = '$sq_quotation[package_id]'"));

$sq_transport = mysql_fetch_assoc(mysql_query("select * from package_tour_quotation_transport_entries2 where quotation_id='$quotation_id'"));
$sq_costing = mysql_fetch_assoc(mysql_query("select * from package_tour_quotation_costing_entries where quotation_id='$quotation_id'"));
$sq_package_program = mysql_query("select * from  package_quotation_program where quotation_id='$quotation_id'");

$sq_login = mysql_fetch_assoc(mysql_query("select * from roles where id='$sq_quotation[login_id]'"));
$sq_emp_info = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id='$sq_login[emp_id]'"));

$sq_customer = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$customer_id'"));
$quotation_date = $sq_quotation['quotation_date'];
$yr = explode("-", $quotation_date);
$year =$yr[0];

if($sq_emp_info['first_name']==''){
  $emp_name = 'Admin';
}
else{
  $emp_name = $sq_emp_info['first_name'].' '.$sq_emp_info['last_name'];
}
$tour_cost = $sq_costing['tour_cost'] + $sq_costing['transport_cost'] + $sq_costing['excursion_cost'] + $sq_costing['markup_subtotal'];
$quotation_cost= $sq_costing['total_tour_cost'] + $sq_quotation['train_cost'] + $sq_quotation['cruise_cost']+ $sq_quotation['flight_cost'] + $sq_quotation['visa_cost'] + $sq_quotation['guide_cost'] + $sq_quotation['misc_cost'];

$transport_name = mysql_fetch_assoc(mysql_query("select * from transport_agency_bus_master where bus_id='$sq_transport[vehicle_name]'"));

?>

    <!-- landingPage -->
    <section class="landingSec main_block">

      <div class="landingPageTop main_block">
        <img src="<?= $app_quot_img?>" class="img-responsive">
        <h1 class="landingpageTitle"><?= $sq_package_name['package_name']?> <em><?='('.$sq_package_name['package_code'].')' ?></em></h1>
        <span class="landingPageId"><?= get_quotation_id($quotation_id,$year) ?></span>
        <div class="landingdetailBlock">
          <div class="detailBlock text-center" style="border-top:0px;">
            <div class="detailBlockIcon detailBlockBlue">
                <i class="fa fa-calendar"></i>
            </div>
            <div class="detailBlockContent">
              <h3 class="contentValue"><?= get_date_user($sq_quotation['quotation_date']) ?></h3>
              <span class="contentLabel">QUOTATION DATE</span>
            </div>
          </div>

          <div class="detailBlock text-center">
            <div class="detailBlockIcon detailBlockGreen">
                <i class="fa fa-hourglass-half"></i>
            </div>
            <div class="detailBlockContent">
              <h3 class="contentValue"><?php echo ($sq_quotation['total_days']-1).'N/'.$sq_quotation['total_days'].'D' ?></h3>
              <span class="contentLabel">DURATION</span>
            </div>
          </div>

          <div class="detailBlock text-center">
            <div class="detailBlockIcon detailBlockYellow">
                <i class="fa fa-users"></i>
            </div>
            <div class="detailBlockContent">
              <h3 class="contentValue"><?= $sq_quotation['total_passangers'] ?></h3>
              <span class="contentLabel">TOTAL GUEST</span>
            </div>
          </div>

          <div class="detailBlock text-center">
            <div class="detailBlockIcon detailBlockRed">
                <i class="fa fa-tag"></i>
            </div>
            <div class="detailBlockContent">
              <h3 class="contentValue"><?= number_format($quotation_cost,2) ?></h3>
              <span class="contentLabel">PRICE</span>
            </div>
          </div>
        </div>
      </div>

      <div class="ladingPageBottom main_block side_pad">

        <div class="row">
          <div class="col-md-4">
            <div class="landigPageCustomer mg_tp_10">
              <h3 class="customerFrom">Prepare for</h3>
              <span class="customerName mg_tp_10"><i class="fa fa-user"></i> : <?= $sq_quotation['customer_name'] ?></span><br>
              <span class="customerMail mg_tp_10"><i class="fa fa-envelope"></i> : <?= $sq_quotation['email_id'] ?></span><br>
              <span class="customerMobile mg_tp_10"><i class="fa fa-phone"></i> : <?= $sq_quotation['mobile_no'] ?></span><br>
              <span class="generatorName mg_tp_10">Prepare By <?= $emp_name?></span><br>
            </div>
          </div>
          <div class="col-md-2">
          </div>
          <div class="col-md-6">
            <div class="print_header_logo main_block">
              <img src="<?= $admin_logo_url ?>" class="img-responsive">
            </div>
            <div class="print_header_contact text-right main_block">
              <span class="title"><?php echo $app_name; ?></span><br>
              <?php if($app_address != ''){?><p class="address no-marg"><?php echo $app_address; ?></p><?php }?>
              <?php if($app_contact_no != ''){?><p class="no-marg"><i class="fa fa-phone" style="margin-right: 5px;"></i><?php echo $app_contact_no; ?></p><?php }?>
              <?php if($app_email_id != ''){?><p class="no-marg"><i class="fa fa-envelope" style="margin-right: 5px;"></i><?php echo $app_email_id; ?></p><?php }?>
              <?php if($app_website != ''){?><p><i class="fa fa-globe" style="margin-right: 5px;"></i><?php echo $app_website; ?></p><?php }?>
            </div>
          </div>
        </div>
        
      </div>
    </section>


    <!-- Itinerary -->
    
    <section class="itinerarySec main_block side_pad">
          
      <div class="print_itinenary main_block no-pad no-marg">
        <?php
          $count = 1;
          while($row_itinarary = mysql_fetch_assoc($sq_package_program)){
            $sq_day_image = mysql_fetch_assoc(mysql_query("select * from package_tour_quotation_master where quotation_id='$row_itinarary[quotation_id]' and package_id='$row_itinarary[package_id]'"));
            $day_url1 = explode(',',$sq_day_image['pckg_daywise_url']);
            $daywise_image = 'http://itourscloud.com/quotation_format_images/dummy-image.jpg';
            for($count1 = 0; $count1<sizeof($day_url1);$count1++){
                $day_url2 = explode('=',$day_url1[$count1]);
                if($day_url2[1]==$row_itinarary['day_count'] && $day_url2[0]==$row_itinarary['package_id']){
                  $daywise_image = $day_url2[2];
                }
            }
            if($count%2!=0){
        ?>

        <section class="singleItinenrary leftItinerary col-md-12 no-pad mg_tp_30 mg_bt_30">
          <div class="col-md-6">
            <div class="itneraryImg">
              <img src="<?= $daywise_image ?>" class="img-responsive">
              <h5>Day-<?= $count ?></h5>
              <div class="itineraryDetail">
                <ul>
                  <li><span><i class="fa fa-bed"></i> : </span><?=  $row_itinarary['stay'] ?></li>
                  <li><span><i class="fa fa-cutlery"></i> : </span><?= $row_itinarary['meal_plan'] ?></li>
                </ul>
              </div>
            </div>
            </div>
            <div class="col-md-6">
              <div class="itneraryText">
                <div class="dayCount">
                  <span><i class="fa fa-map-marker"></i> <?= $row_itinarary['attraction'] ?></span>
                </div>
                <div class="dayWiseProgramDetail">
                  <p><?= $row_itinarary['day_wise_program'] ?></p>
                </div>
              </div>
            </div> 
         </section>

        <hr class="main_block no-marg">

        <?php }else{ ?>

        <section class="singleItinenrary rightItinerary col-md-12 no-pad mg_tp_30 mg_bt_30">
            <div class="col-md-6">
              <div class="itneraryText">
                <div class="dayCount">
                  <span><i class="fa fa-map-marker"></i> <?= $row_itinarary['attraction'] ?></span>
                </div>
                <div class="dayWiseProgramDetail">
                  <p><?= $row_itinarary['day_wise_program'] ?></p>
                </div>
              </div>
            </div> 
          <div class="col-md-6">
            <div class="itneraryImg">
              <img src="<?= $daywise_image ?>" class="img-responsive">
              <h5>Day-<?= $count ?></h5>
              <div class="itineraryDetail">
                <ul>
                  <li><span><i class="fa fa-bed"></i> : </span><?=  $row_itinarary['stay'] ?></li>
                  <li><span><i class="fa fa-cutlery"></i> : </span><?= $row_itinarary['meal_plan'] ?></li>
                </ul>
              </div>
            </div>
            </div>
         </section>

        <hr class="main_block no-marg">
            
        <?php } $count++; } ?>
      </div>

    </section>

  <!-- Count queries -->
  <?php
  $sq_package_count = mysql_num_rows(mysql_query("select * from  package_quotation_program where quotation_id='$quotation_id'"));
  $sq_hotel_count = mysql_num_rows(mysql_query("select * from package_tour_quotation_hotel_entries where quotation_id='$quotation_id'"));
  $sq_transport_count = mysql_num_rows(mysql_query("select * from package_tour_quotation_transport_entries2 where quotation_id='$quotation_id'"));
  $sq_train_count = mysql_num_rows(mysql_query("select * from package_tour_quotation_train_entries where quotation_id='$quotation_id'"));
  $sq_plane_count = mysql_num_rows(mysql_query("select * from package_tour_quotation_plane_entries where quotation_id='$quotation_id'"));
  $sq_cruise_count = mysql_num_rows(mysql_query("select * from package_tour_quotation_cruise_entries where quotation_id='$quotation_id'"));
  ?>

    <!-- traveling Information -->
  <?php if($sq_transport_count != 0 || $sq_train_count != 0 || $sq_plane_count != 0 || $sq_train_count != 0){?>
    <section class="travelingDetails main_block mg_tp_30">
          <!-- transport -->
          <?php
          if($sq_transport_count>0){ ?>
          <section class="transportDetails main_block side_pad">
            <div class="row">
              <div class="col-md-8">
                <div class="table-responsive mg_tp_30">
                  <table class="table table-bordered no-marg" id="tbl_emp_list">
                    <thead>
                      <tr class="table-heading-row">
                        <th>VEHICLE</th>
                        <th>FROM DATE</th>
                        <th>TO DATE</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
                      $count = 0;
                      $sq_hotel = mysql_query("select * from package_tour_quotation_transport_entries2 where quotation_id='$quotation_id'");
                      while($row_hotel = mysql_fetch_assoc($sq_hotel))
                      {
                        $transport_name = mysql_fetch_assoc(mysql_query("select * from transport_agency_bus_master where bus_id='$row_hotel[vehicle_name]'"));
                        ?>
                        <tr>
                          <td><?= $transport_name['bus_name'].$similar_text ?></td>
                          <td><?= get_date_user($row_hotel['start_date']) ?></td>
                          <td><?= get_date_user($row_hotel['end_date']) ?></td>
                        </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>  
              <div class="col-md-4">
                <div class="transportImg">
                  <img src="<?= BASE_URL ?>images/quotation/car.png" class="img-responsive">
                </div>
              </div>
            </div>
          </section>
          <?php } ?>
          
          <!-- flight -->
          <?php
          if($sq_plane_count>0){ ?>
          <section class="transportDetails main_block side_pad mg_tp_30">
            <div class="row">
              <div class="col-md-8">
                <div class="table-responsive mg_tp_30">
                  <table class="table table-bordered no-marg" id="tbl_emp_list">
                    <thead>
                      <tr class="table-heading-row">
                        <th>From</th>
                        <th>To</th>
                        <th>Airline</th>
                        <th>Departure</th>
                        <th>Arrival</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php 
                        $sq_plane = mysql_query("select * from package_tour_quotation_plane_entries where quotation_id='$quotation_id'");
                        while($row_plane = mysql_fetch_assoc($sq_plane)){
                        $sq_airline = mysql_fetch_assoc(mysql_query("select * from airline_master where airline_id='$row_plane[airline_name]'"));
                      ?>   
                      <tr>
                        <td><?= $row_plane['from_location'] ?></td>
                        <td><?= $row_plane['to_location'] ?></td>
                        <td><?= $sq_airline['airline_name'].' ('.$sq_airline['airline_code'].')' ?></td>
                        <td><?= date('d-m-Y H:i:s', strtotime($row_plane['dapart_time'])) ?></td>
                        <td><?= date('d-m-Y H:i:s', strtotime($row_plane['arraval_time'])) ?></td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div> 
              <div class="col-md-4">
                <div class="transportImg">
                  <img src="<?= BASE_URL ?>images/quotation/flight.png" class="img-responsive">
                </div>
              </div> 
            </div>
          </section>
          <?php } ?>

          
          <!-- Hotel -->
          <?php if($sq_hotel_count != 0){?>
          <section class="transportDetails main_block side_pad mg_tp_30">
            <div class="row">
              <div class="col-md-8">
                <div class="table-responsive mg_tp_30">
                  <table class="table table-bordered no-marg" id="tbl_emp_list">
                    <thead>
                      <tr class="table-heading-row">
                      <th>City</th>
                      <th>Hotel Name</th>
                      <th>Total Nights</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php $sq_hotel = mysql_query("select * from package_tour_quotation_hotel_entries where quotation_id='$quotation_id'");
                      while($row_hotel = mysql_fetch_assoc($sq_hotel)){
                        $hotel_name = mysql_fetch_assoc(mysql_query("select * from hotel_master where hotel_id='$row_hotel[hotel_name]'"));
                        $city_name = mysql_fetch_assoc(mysql_query("select * from city_master where city_id='$row_hotel[city_name]'"));
                      ?>
                      <tr>
                          <?php
                          $sq_count_h = mysql_num_rows(mysql_query("select * from hotel_vendor_images_entries where hotel_id='$row_hotel[hotel_name]' "));
                          if($sq_count_h ==0){
                            $download_url =  BASE_URL.'images/dummy-image.jpg';
                          }
                          else{
                            $sq_hotel_image = mysql_query("select * from hotel_vendor_images_entries where hotel_id = '$row_hotel[hotel_name]'");
                            while($row_hotel_image = mysql_fetch_assoc($sq_hotel_image)){      
                                $image = $row_hotel_image['hotel_pic_url']; 
                                $newUrl = preg_replace('/(\/+)/','/',$image);
                                $newUrl = explode('uploads', $newUrl);
                                $download_url = BASE_URL.'uploads'.$newUrl[1];
                              }
                          }
                        ?>
                          <td><?php echo $city_name['city_name']; ?></td>
                          <td><?php echo $hotel_name['hotel_name'].$similar_text; ?></td>
                          <td></span><?php echo $row_hotel['total_days']; ?></td>
                        </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>  
              <div class="col-md-4">
                <div class="transportImg">
                  <img src="<?= BASE_URL ?>images/quotation/hotel.png" class="img-responsive">
                </div>
              </div>
            </div>
          </section>
          <?php } ?>
          
          <!-- train -->
          <?php
          if($sq_train_count>0){ ?>
          <section class="transportDetails main_block side_pad mg_tp_30">
            <div class="row">
              <div class="col-md-8">
                <div class="table-responsive mg_tp_30">
                  <table class="table table-bordered no-marg" id="tbl_emp_list">
                    <thead>
                      <tr class="table-heading-row">
                        <th>From</th>
                        <th>To</th>
                        <th>Class</th>
                        <th>Departure</th>
                        <th>Arrival</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php 
                      $sq_train = mysql_query("select * from package_tour_quotation_train_entries where quotation_id='$quotation_id'");
                      while($row_train = mysql_fetch_assoc($sq_train)){  
                        ?>
                        <tr>
                          <td><?= $row_train['from_location'] ?></td>
                          <td><?= $row_train['to_location'] ?></td>
                          <td><?= $row_train['class'] ?></td>
                          <td><?= date('d-m-Y H:i', strtotime($row_train['departure_date'])) ?></td>
                          <td><?= date('d-m-Y H:i', strtotime($row_train['arrival_date'])) ?></td>
                        </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>  
              <div class="col-md-4">
                <div class="transportImg">
                  <img src="<?= BASE_URL ?>images/quotation/train.png" class="img-responsive">
                </div>
              </div>
            </div>
          </section>
          <?php } ?>
          
          <!-- cruise -->
          <?php
          if($sq_cruise_count>0){ ?>
          <section class="transportDetails main_block side_pad mg_tp_30">
            <div class="row">
              <div class="col-md-8">
                <div class="table-responsive mg_tp_30">
                  <table class="table table-bordered no-marg" id="tbl_emp_list">
                    <thead>
                      <tr class="table-heading-row">
                        <th>Departure</th>
                        <th>Arrival</th>
                        <th>Route</th>
                        <th>Cabin</th>
                        <th>Sharing</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php 
                      $sq_cruise = mysql_query("select * from package_tour_quotation_cruise_entries where quotation_id='$quotation_id'");
                      while($row_cruise = mysql_fetch_assoc($sq_cruise)){  
                        ?>
                        <tr>
                          <td><?= date('d-m-Y H:i', strtotime($row_cruise['dept_datetime'])) ?></td>
                          <td><?= date('d-m-Y H:i', strtotime($row_cruise['arrival_datetime'])) ?></td>
                          <td><?= $row_cruise['route'] ?></td>
                          <td><?= $row_cruise['cabin'] ?></td>
                          <td><?= $row_cruise['sharing'] ?></td>
                        </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="col-md-4">
                <div class="transportImg">
                  <img src="<?= BASE_URL ?>images/quotation/cruise.png" class="img-responsive">
                </div>
              </div>  
            </div>
          </section>
          <?php } ?>
    </section>
  <?php }?>



    <!-- Inclusion Exclusion --><!-- Terms and Conditions -->
    <section class="incluExcluTerms main_block fullHeightLand">
      <!-- Inclusion Exclusion -->
      <div class="row side_pad">
        <div class="col-md-1 mg_tp_30">
        </div>
        <?php if($sq_quotation['inclusions'] != ''){ ?>
        <div class="col-md-5 mg_tp_30">
          <div class="incluExcluTermsTabPanel main_block">
              <h3 class="incexTitle">Inclusions</h3>
              <div class="tabContent">
                  <pre class="real_text"><?= $sq_quotation['inclusions'] ?></pre>
              </div>
          </div>
        </div>
        <?php } ?>
        <?php if($sq_quotation['exclusions'] != ''){ ?>
        <div class="col-md-5 mg_tp_30">
          <div class="incluExcluTermsTabPanel main_block">
              <h3 class="incexTitle">Exclusions</h3>
              <div class="tabContent">
                  <pre class="real_text"><?= $sq_quotation['exclusions'] ?></pre>
              </div>
          </div>
        </div>
        <?php }?>
      </div>
    </section>



    <!-- Terms and Conditions -->
    <?php if($sq_terms_cond['terms_and_conditions'] != ''){ ?>
    <section class="incluExcluTerms main_block fullHeightLand">

      <!-- Terms and Conditions -->
      <div class="row">
        
        <div class="col-md-12 mg_tp_30">
          <div class="incluExcluTermsTabPanel main_block">
              <h3 class="tncTitle">Terms &amp; Conditions</h3>
              <div class="tncContent">
                  <pre class="real_text"><?php echo $sq_terms_cond['terms_and_conditions']; ?></pre>      
              </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
        <div class="tncContent">
          <pre class="real_text"><?php echo $quot_note; ?></pre>
        </div></div>
      </div>
      
    </section>
    <?php } ?>


    <!-- Ending Page -->
    <section class="endPageSection main_block">

      <div class="row">
        
        <!-- Costing -->
        <div class="col-md-4 constingBankingPanel endPageLeft fullHeightLand">
              <h3 class="costBankTitle text-right">COSTING Details</h3>
              <!-- Group Costing -->
              <?php if($sq_quotation['costing_type'] == 1){ ?>
              <div class="col-md-12 text-right mg_bt_20">
                <div class="icon main_block"><img src="<?= BASE_URL ?>images/quotation/p4/tourCost.png" class="img-responsive"></div>
                <h4 class="no-marg"><?= number_format($tour_cost,2) ?></h4>
                <p>TOUR COST</p>
              </div>
              <div class="col-md-12 text-right mg_bt_20">
                <div class="icon main_block"><img src="<?= BASE_URL ?>images/quotation/p4/tax.png" class="img-responsive"></div>
                <h4 class="no-marg"><?= number_format($sq_costing['service_tax_subtotal'],2) ?></h4>
                <p>TAX</p>
              </div>
              <div class="col-md-12 text-right mg_bt_20">
                <div class="icon main_block"><img src="<?= BASE_URL ?>images/quotation/p4/travelCost.png" class="img-responsive"></div>
                <h4 class="no-marg"><?= number_format($sq_quotation['train_cost'] + $sq_quotation['flight_cost'] + $sq_quotation['cruise_cost'] + $sq_quotation['visa_cost']+ $sq_quotation['guide_cost']+ $sq_quotation['misc_cost'],2) ?></h4>
                <p>TRAVEL COST</p>
              </div>
              <div class="col-md-12 text-right mg_bt_20">
                <div class="icon main_block"><img src="<?= BASE_URL ?>images/quotation/p4/quotationCost.png" class="img-responsive"></div>
                <h4 class="no-marg"><?= number_format($quotation_cost,2) ?></h4>
                <p>QUOTATION COST</p>
              </div>
              <?php }
              else{ ?>
              <!-- Group costing end -->
              <!-- Per person Costing -->
              <div class="col-md-12 text-right">
                <div class="icon main_block"><img src="<?= BASE_URL ?>images/quotation/p4/adultCost.png" class="img-responsive"></div>
                <h4 class="no-marg"><?= number_format($sq_costing['adult_cost'],2) ?></h4>
                <p>ADULT COST</p>
              </div>
              <div class="col-md-12 text-right">
                <div class="icon main_block"><img src="<?= BASE_URL ?>images/quotation/p4/childCost.png" class="img-responsive"></div>
                <h4 class="no-marg"><?= number_format($sq_costing['child_cost'],2) ?></h4>
                <p>CHILD COST</p>
              </div>
              <div class="col-md-12 text-right">
                <div class="icon main_block"><img src="<?= BASE_URL ?>images/quotation/p4/infantCost.png" class="img-responsive"></div>
                <h4 class="no-marg"><?= number_format($sq_costing['infant_cost'],2) ?></h4>
                <p>INFANT COST</p>
              </div>
              <div class="col-md-12 text-right">
                <div class="icon main_block"><img src="<?= BASE_URL ?>images/quotation/p4/child-with-bed.png" class="img-responsive"></div>
                <h4 class="no-marg"><?= number_format($sq_costing['child_with'],2) ?></h4>
                <p>CHILD WITH BED</p>
              </div>
              <div class="col-md-12 text-right mg_bt_20">
                <div class="icon main_block"><img src="<?= BASE_URL ?>images/quotation/p4/child-with-out-bed.png" class="img-responsive"></div>
                <h4 class="no-marg"><?= number_format($sq_costing['child_without'],2) ?></h4>
                <p>CHILD W/O BED</p>
              </div>
              <div class="col-md-12 text-right">
                <div class="icon main_block"><img src="<?= BASE_URL ?>images/quotation/p4/extra-bed.png" class="img-responsive"></div>
                <h4 class="no-marg"><?= number_format($sq_costing['extra_bedc'],2) ?></h4>
                <p>EXTRA BED</p>
              </div>
              <?php } ?>
              <!-- Per person Costing End -->
        </div>
        
        <!-- Guest Detail -->
        <div class="col-md-4 passengerPanel endPagecenter fullHeightLand">
              <h3 class="costBankTitle text-center">Total Guest</h3>
              <div class="col-md-12 text-center mg_bt_30">
                <div class="icon"><img src="<?= BASE_URL ?>images/quotation/Icon/adultIcon.png" class="img-responsive"></div>
                <h4 class="no-marg"><?= $sq_quotation['total_adult'] ?></h4>
                <p>Adult</p>
              </div>
              <div class="col-md-12 text-center mg_bt_30">
                <div class="icon"><img src="<?= BASE_URL ?>images/quotation/Icon/childIcon.png" class="img-responsive"></div>
                <h4 class="no-marg"><?= $sq_quotation['total_children'] ?></h4>
                <p>Child</p>
              </div>
              <div class="col-md-12 text-center mg_bt_30">
                <div class="icon"><img src="<?= BASE_URL ?>images/quotation/Icon/infantIcon.png" class="img-responsive"></div>
                <h4 class="no-marg"><?= $sq_quotation['total_infant'] ?></h4>
                <p>Infant</p>
              </div>
        </div>
        
        <!-- Bank Detail -->
        <div class="col-md-4 constingBankingPanel endPageright fullHeightLand">
              <h3 class="costBankTitle text-left">BANK DETAILS</h3>
              <div class="col-md-12 text-left mg_bt_20">
                <div class="icon"><img src="<?= BASE_URL ?>images/quotation/p4/bankName.png" class="img-responsive"></div>
                <h4 class="no-marg"><?= $bank_name_setting ?></h4>
                <p>BANK NAME</p>
              </div>
              <div class="col-md-12 text-left mg_bt_20">
                <div class="icon"><img src="<?= BASE_URL ?>images/quotation/p4/branchName.png" class="img-responsive"></div>
                <h4 class="no-marg"><?= $bank_branch_name ?></h4>
                <p>BRANCH</p>
              </div>
              <div class="col-md-12 text-left mg_bt_20">
                <div class="icon"><img src="<?= BASE_URL ?>images/quotation/p4/accName.png" class="img-responsive"></div>
                <h4 class="no-marg"><?= $acc_name ?></h4>
                <p>A/C NAME</p>
              </div>
              <div class="col-md-12 text-left mg_bt_20">
                <div class="icon"><img src="<?= BASE_URL ?>images/quotation/p4/accNumber.png" class="img-responsive"></div>
                <h4 class="no-marg"><?= $bank_acc_no ?></h4>
                <p>A/C NO</p>
              </div>
              <div class="col-md-12 text-left mg_bt_20">
                <div class="icon"><img src="<?= BASE_URL ?>images/quotation/p4/code.png" class="img-responsive"></div>
                <h4 class="no-marg"><?= $bank_ifsc_code ?></h4>
                <p>IFSC</p>
              </div>
              <div class="col-md-12 text-left">
                <div class="icon"><img src="<?= BASE_URL ?>images/quotation/p4/code.png" class="img-responsive"></div>
                <h4 class="no-marg"><?= $bank_swift_code ?></h4>
                <p>Swift Code</p>
              </div>
        </div>
      
      </div>

    </section>

  </body>
</html>