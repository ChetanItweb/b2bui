<?php
$flag = true;
class hotel_master{
public function vendor_csv_save(){

    $vendor_csv_dir = $_POST['vendor_csv_dir'];
    $created_at = date('Y-m-d');
    $flag = true;

    $vendor_csv_dir = explode('uploads', $vendor_csv_dir);
    $vendor_csv_dir = BASE_URL.'uploads'.$vendor_csv_dir[1];
    $timestamp = date('U');
    
    begin_t();
    $count = 1;
    $validCount=0;
    $invalidCount=0;
    $unprocessedArray=array();
    $arrResult  = array();
    $handle = fopen($vendor_csv_dir, "r");
    if(empty($handle) === false){
        while(($data = fgetcsv($handle, ",")) !== FALSE){
            if($count == 1){ $count++; continue; }
            if($count>0){   
                $sq_max_id = mysql_fetch_assoc(mysql_query("select max(hotel_id) as max from hotel_master"));
                $hotel_id = $sq_max_id['max']+1;

                $city_id = $data[0];
                $hotel_name = $data[1];
                $mobile = $data[2];
                $landline = $data[3];
                $email = $data[4];
                $contact_person = $data[5];
                $emergency_contact = $data[6];
                $hotel_address = $data[7];
                $state_id= $data[8];
                $country = $data[9];
                $website = $data[10];
                $bank_name = $data[11];
                $account_name = $data[12];
                $account_no = $data[13];
                $branch = $data[14];
                $ifsc_code = $data[15];
                $gst_no = $data[16];
                $supp_pan = $data[17];
                $opening_balance = $data[18];
                $as_on_date = $data[19];
                $hotel_type = $data[20];
                $side = $data[21];
                
                $as_on_date = get_date_db($as_on_date);
                if(preg_match('/^[0-9]*$/', $city_id) && preg_match('/^[0-9 \s]{6,20}+$/', $mobile) && preg_match('/^[0-9]*$/', $state_id) && preg_match('/^[0-9]*$/', $opening_balance) && !empty($side) && !empty($as_on_date) && ($as_on_date!='1970-01-01') && (strlen($mobile)<=20)){
                          $hotel_name_count = mysql_num_rows(mysql_query("select hotel_name from hotel_master where hotel_name='$hotel_name' and city_id='$city_id' and mobile_no='$mobile'"));
                          if($hotel_name_count == 0){
                              $validCount++;
                              
	                            $hotel_name = addslashes($hotel_name);
                              $query = "insert into hotel_master ( hotel_id, city_id, hotel_name, mobile_no, landline_no, email_id, contact_person_name, immergency_contact_no, hotel_address, country, website, opening_balance, rating_star,active_flag , bank_name, account_name ,account_no, branch, ifsc_code, service_tax_no, state_id,side,pan_no,as_of_date) values ('$hotel_id', '$city_id', '$hotel_name', '$mobile', '$landline', '$email', '$contact_person', '$emergency_contact', '$hotel_address', '$country', '$website', '$opening_balance', '$hotel_type', 'Active','$bank_name','$account_name','$account_no','$branch','$ifsc_code','$gst_no','$state_id','$side','$supp_pan','$as_on_date')";
                             
                              $sq_enquiry = mysql_query($query);
                              if($sq_enquiry){                                     
                                //Login save
                                $vendor_login_master = new vendor_login_master;
                                $vendor_login_master->vendor_login_save($hotel_name, $mobile, 'Hotel Vendor',$hotel_id, 'Active', $email,$opening_balance,$side,$as_on_date);
                              }   
                              else{
                                $flag = false;
                                echo "error--Supplier Information Not Saved.";
                                //exit;
                              } 
                         }
                         else{
                            $invalidCount++;
                            array_push($unprocessedArray, $data);
                         }
                     }
                     else{
                        $invalidCount++;
                        array_push($unprocessedArray, $data);
                     }
            }
            $count++;  
        }
        fclose($handle);
        if(isset($unprocessedArray) && !empty($unprocessedArray))
        {
          $filePath='../../download/unprocessed_hotel_records'.$created_at.''.$timestamp.'.csv';
          $save = preg_replace('/(\/+)/','/',$filePath);
          $downloadurl='../../../download/unprocessed_hotel_records'.$created_at.''.$timestamp.'.csv';
          header("Content-type: text/csv ; charset:utf-8");
          header("Content-Disposition: attachment; filename=file.csv");
          header("Pragma: no-cache");
          header("Expires: 0");
          $output = fopen($save, "w");  
          fputcsv($output, array('city_id' , 'Hotel_name' , 'Mobile' , 'landline' , 'Email' , 'Contact_Person' , 'Emergency_Contact_No' ,'Hotel_address', 'state_id' , 'Country' , 'Website' , 'Bank_Name' , 'Account_Name' , 'Account_No' , 'Branch' , 'IFSC_swift_Code' , 'Tax_No', 'PAN_TAN_No' , 'Opening_balance' ,'As of Date', 'Hotel_Type' , 'Balance_Side'));   
          
           foreach($unprocessedArray as $row)
           {
             fputcsv($output, $row);  
           }
            
          fclose($output); 
          echo "<script> window.location ='$downloadurl'; </script>";  
        } 

    }

    if($flag){
      commit_t();
      if($validCount>0)
      {
          echo  $validCount." Records successfully imported<br>
        ".$invalidCount." Records failed.";

      }
      else
      {
        echo "No Supplier information imported";
      }
      exit;
    }
    else{
      rollback_t();
      exit;
    }

  }

///////////////////////***Hotel Master save start*********//////////////
function hotel_master_save($city_id, $hotel_name, $mobile_no, $landline_no, $email_id, $contact_person_name, $immergency_contact_no, $hotel_address, $country, $website, $opening_balance,$rating_star, $active_flag, $bank_name,$account_name,$account_no,$branch, $ifsc_code, $service_tax_no ,$state,$side,$supp_pan,$hotel_image_path,$as_of_date,$description,$policies,$amenities)
{

  $city_id = mysql_real_escape_string($city_id);
  //$hotel_name = mysql_real_escape_string($hotel_name);
  $mobile_no = mysql_real_escape_string($mobile_no);
  $email_id = mysql_real_escape_string($email_id);
  $contact_person_name = mysql_real_escape_string($contact_person_name);
  $immergency_contact_no = mysql_real_escape_string($immergency_contact_no);
  $hotel_address = mysql_real_escape_string($hotel_address);
  $country = mysql_real_escape_string($country);
  $website = mysql_real_escape_string($website);
  $opening_balance = mysql_real_escape_string($opening_balance);
  $rating_star = mysql_real_escape_string($rating_star);
  $active_flag = mysql_real_escape_string($active_flag);
  $bank_name = mysql_real_escape_string($bank_name);
  $account_name = mysql_real_escape_string($account_name);
  $account_no = mysql_real_escape_string($account_no);
  $branch = mysql_real_escape_string($branch);
  $ifsc_code = mysql_real_escape_string($ifsc_code);
  $service_tax_no = mysql_real_escape_string($service_tax_no);
  $state = mysql_real_escape_string($state);
  $side = mysql_real_escape_string($side);
  $supp_pan = mysql_real_escape_string($supp_pan);
  $hotel_image_path = mysql_real_escape_string($hotel_image_path);
  $as_of_date = mysql_real_escape_string($as_of_date);
  $as_of_date = get_date_db($as_of_date);
  $description = mysql_real_escape_string($description);
  $policies = mysql_real_escape_string($policies);
  $amenities = mysql_real_escape_string($amenities);
  begin_t();

  $hotel_name = addslashes($hotel_name);
  $hotel_name1 = ltrim($hotel_name);
  $hotel_name_count = mysql_num_rows(mysql_query("select hotel_name from hotel_master where hotel_name='$hotel_name1' and city_id='$city_id'  and mobile_no='$mobile'"));

  if($hotel_name_count>0){
    echo "error--Hotel name already exist in this city!";
    exit;
  }


  $max_hotel_id1 = mysql_fetch_assoc(mysql_query("select max(hotel_id) as max from hotel_master"));
  $max_hotel_id = $max_hotel_id1['max']+1;

  $q = "insert into hotel_master ( hotel_id, city_id, hotel_name, mobile_no, landline_no, email_id, contact_person_name, immergency_contact_no, hotel_address, country, website, opening_balance, rating_star , bank_name,account_name, account_no, branch, ifsc_code, service_tax_no,active_flag, state_id,side,pan_no,as_of_date,description,policies,amenities) values ( '$max_hotel_id', '$city_id', '$hotel_name', '$mobile_no', '$landline_no', '$email_id', '$contact_person_name', '$immergency_contact_no', '$hotel_address', '$country', '$website', '$opening_balance','$rating_star', '$bank_name','$account_name','$account_no','$branch','$ifsc_code', '$service_tax_no', '$active_flag','$state','$side','$supp_pan','$as_of_date','$description','$policies','$amenities')";
  $sq = mysql_query($q);
  if(!$sq){
    rollback_t();
    echo "error--Hotel details not saved!";
    exit;
  } 

  else
  {    
    //Login save
    $vendor_login_master = new vendor_login_master;
    $vendor_login_master->vendor_login_save($hotel_name, $mobile_no, 'Hotel Vendor',$max_hotel_id, $active_flag, $email_id,$opening_balance,$side,$as_of_date);

    $hotel_image_array = explode(",",$hotel_image_path);

    for($i=0; $i<sizeof($hotel_image_array);$i++)
    {
        $sq_count=mysql_num_rows(mysql_query("select * from hotel_vendor_images_entries where hotel_id='$max_hotel_id'"));
        if($sq_count<3)
        {
          if($hotel_image_array[$i] != ''){
            $max_img_entry_id = mysql_fetch_assoc(mysql_query("select max(id) as max from hotel_vendor_images_entries"));
            $max_entry_id = $max_img_entry_id['max']+1;
            $sq_img = mysql_query("INSERT INTO `hotel_vendor_images_entries`(`id`, `hotel_id`, `hotel_pic_url`) VALUES ('$max_entry_id','$max_hotel_id','$hotel_image_array[$i]')");
          }
        }
        else{
          echo "error--Sorry,You can Upload upto 3 images.";
        }
     }  

    if($GLOBALS['flag']){
      commit_t();
      echo "Hotel has been successfully saved.";
      exit;
    }

    else{
      rollback_t();
      exit;

    }
  }
}

///////////////////////***Hotel Master save end*********//////////////

///////////////////////***Hotel Master update start*********//////////////
function hotel_master_update( $hotel_id, $vendor_login_id, $city_id, $hotel_name, $mobile_no, $landline_no, $email_id, $contact_person_name, $immergency_contact_no, $hotel_address, $country, $website, $opening_balance,$rating_star, $active_flag, $bank_name,$account_name ,$account_no, $branch, $ifsc_code, $service_tax_no,$state,$side1,$supp_pan,$as_of_date,$description,$policies,$amenities)
{
  $city_id = mysql_real_escape_string($city_id);
  //$hotel_name = mysql_real_escape_string($hotel_name);
  $mobile_no = mysql_real_escape_string($mobile_no);
  $email_id = mysql_real_escape_string($email_id);
  $contact_person_name = mysql_real_escape_string($contact_person_name);
  $immergency_contact_no = mysql_real_escape_string($immergency_contact_no);
  $hotel_address = mysql_real_escape_string($hotel_address);
  $country = mysql_real_escape_string($country);
  $website = mysql_real_escape_string($website);
  $opening_balance = mysql_real_escape_string($opening_balance);
  $rating_star = mysql_real_escape_string($rating_star);
  $active_flag = mysql_real_escape_string($active_flag);
  $bank_name = mysql_real_escape_string($bank_name);
  $account_name = mysql_real_escape_string($account_name);
  $account_no = mysql_real_escape_string($account_no);
  $branch = mysql_real_escape_string($branch);
  $ifsc_code = mysql_real_escape_string($ifsc_code);
  $service_tax_no = mysql_real_escape_string($service_tax_no);
  $state = mysql_real_escape_string($state);
  $side1 = mysql_real_escape_string($side1);
  $supp_pan = mysql_real_escape_string($supp_pan);
  $as_of_date = mysql_real_escape_string($as_of_date);
  $as_of_date = get_date_db($as_of_date);
  $description = mysql_real_escape_string($description);
  $policies = mysql_real_escape_string($policies);
  $amenities = mysql_real_escape_string($amenities);
  begin_t();
   
  $hotel_name = addslashes($hotel_name);
  $sq = mysql_query("update hotel_master set city_id='$city_id', hotel_name='$hotel_name', mobile_no='$mobile_no', landline_no='$landline_no', email_id='$email_id', contact_person_name='$contact_person_name', immergency_contact_no='$immergency_contact_no', hotel_address='$hotel_address', country='$country',website = '$website', opening_balance='$opening_balance',rating_star = '$rating_star', active_flag='$active_flag', bank_name='$bank_name',account_name='$account_name',account_no='$account_no', branch='$branch', ifsc_code='$ifsc_code',  service_tax_no='$service_tax_no', state_id='$state',side='$side1',pan_no='$supp_pan',as_of_date='$as_of_date',description='$description',policies='$policies',amenities='$amenities' where hotel_id='$hotel_id' ");
 // sundry_creditor_balance_update();
  if(!$sq)
  {
    rollback_t();
    echo "error--Hotel details not saved!";
    exit;
  } 

  else
  {

    $vendor_login_master = new vendor_login_master;
    $vendor_login_master->vendor_login_update($vendor_login_id, $hotel_name, $mobile_no, $hotel_id, $active_flag, $email_id,'Hotel Vendor',$opening_balance,$side1,$as_of_date);
    if($GLOBALS['flag']){
      commit_t();
      echo "Hotel has been successfully updated.";
      exit;
    }
    else{
      rollback_t();
      exit;
    }
  } 
}

///////////////////////***Hotel Master update end*********//////////////

}
?>