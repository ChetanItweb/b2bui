<?php 
$flag = true;
class payment_master{

public function payment_save(){

  $booking_id = $_POST['booking_id'];
  $payment_amount = $_POST['payment_amount'];
  $payment_date = $_POST['payment_date'];
  $payment_mode = $_POST['payment_mode'];
  $bank_name = $_POST['bank_name'];
  $transaction_id = $_POST['transaction_id'];
  $bank_id = $_POST['bank_id'];
  $emp_id = $_POST['emp_id'];
  $branch_admin_id = $_POST['branch_admin_id'];
  $created_at = date('Y-m-d H:i:s');
  $payment_date = date('Y-m-d', strtotime($payment_date));

  if($payment_mode=="Cheque"){ 
      $clearance_status = "Pending"; } 
    else {  $clearance_status = ""; } 

  $financial_year_id = $_SESSION['financial_year_id'];

  begin_t();

  $sq_max = mysql_fetch_assoc(mysql_query("select max(payment_id) as max from car_rental_payment"));
  $payment_id = $sq_max['max']+1;
  
  $sq_payment = mysql_query("insert into car_rental_payment(payment_id, booking_id, financial_year_id, branch_admin_id, emp_id,  payment_date, payment_mode, payment_amount, bank_name, transaction_id, bank_id, clearance_status, created_at) values ('$payment_id', '$booking_id', '$financial_year_id', '$branch_admin_id', '$emp_id', '$payment_date', '$payment_mode', '$payment_amount', '$bank_name', '$transaction_id', '$bank_id', '$clearance_status', '$created_at')");
  if(!$sq_payment){
    rollback_t();
	  echo "error--Sorry, Payment not done!";
	  exit;
  }
  else{

    //Finance save
    $this->finance_save($payment_id, $branch_admin_id);

    //Bank and Cash Book Save
    $this->bank_cash_book_save($payment_id, $branch_admin_id);

    if($GLOBALS['flag']){
      commit_t();
      //Payment email send
      $this->payment_email_notification_send($booking_id, $payment_amount, $payment_mode, $payment_date);

      //Payment sms send
      if($payment_amount != 0){
        $this->payment_sms_notification_send($booking_id, $payment_amount, $payment_mode);
      }

      echo "Car Rental Payment has been successfully saved.";
      exit;  
    }
    else{
      rollback_t();
      exit;
    }
    
  }

}

public function finance_save($payment_id, $branch_admin_id)
{
    $row_spec ='sales';
    $booking_id = $_POST['booking_id'];
    $payment_amount1 = $_POST['payment_amount'];
    $payment_date = $_POST['payment_date'];
    $payment_mode = $_POST['payment_mode'];
    $bank_name = $_POST['bank_name'];
    $transaction_id1 = $_POST['transaction_id'];
    $bank_id1 = $_POST['bank_id'];

    $payment_date1 = date('Y-m-d', strtotime($payment_date));
    $year1 = explode("-", $payment_date1);
    $yr1 =$year1[0];
    $sq_car_info = mysql_fetch_assoc(mysql_query("select * from car_rental_booking where booking_id='$booking_id'"));
    $customer_id = $sq_car_info['customer_id'];  
    global $transaction_master;


    //Getting cash/Bank Ledger
    if($payment_mode == 'Cash') {  $pay_gl = 20; }
    else{ 
      $sq_bank = mysql_fetch_assoc(mysql_query("select * from ledger_master where customer_id='$bank_id1' and user_type='bank'"));
      $pay_gl = $sq_bank['ledger_id'];
     } 

     //Getting customer Ledger
    $sq_cust = mysql_fetch_assoc(mysql_query("select * from ledger_master where customer_id='$customer_id' and user_type='customer'"));
    $cust_gl = $sq_cust['ledger_id'];


  //////Payment Amount///////
    $module_name = "Car Rental Booking";
    $module_entry_id = $booking_id;
    $transaction_id = $transaction_id1;
    $payment_amount = $payment_amount1;
    $payment_date = $payment_date1;
    $payment_particular = get_sales_paid_particular(get_car_rental_booking_payment_id($booking_id,$yr1), $payment_date1, $payment_amount1, $customer_id, $payment_mode, get_car_rental_booking_id($booking_id,$yr1));
    $ledger_particular = get_ledger_particular('By','Cash/Bank');
    $gl_id = $pay_gl;
    $payment_side = "Debit";
    $clearance_status = ($payment_mode!="Cash") ? "Pending" : "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);

    ////////Customer Amount//////
    $module_name = "Car Rental Booking";
    $module_entry_id = $booking_id;
    $transaction_id = $transaction_id1;
    $payment_amount = $payment_amount1;
    $payment_date = $payment_date1;
    $payment_particular = get_sales_particular(get_car_rental_booking_payment_id($booking_id,$yr1), $payment_date1, $payment_amount1, $customer_id);
    $ledger_particular = get_ledger_particular('By','Cash/Bank');
    $gl_id = $cust_gl;
    $payment_side = "Credit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);
}

public function bank_cash_book_save($payment_id, $branch_admin_id)
{
    global $bank_cash_book_master;

    $booking_id = $_POST['booking_id'];
    $payment_amount = $_POST['payment_amount'];
    $payment_date1 = $_POST['payment_date'];
    $payment_mode = $_POST['payment_mode'];
    $bank_name = $_POST['bank_name'];
    $transaction_id = $_POST['transaction_id'];
    $bank_id = $_POST['bank_id'];
    $payment_date1 = date('Y-m-d', strtotime($payment_date1));
    $year1 = explode("-", $payment_date1);
    $yr1 =$year1[0];

    $sq_car_rental_info = mysql_fetch_assoc(mysql_query("select customer_id from car_rental_booking where booking_id='$booking_id'"));

    $module_name = "Car Rental Booking";
    $module_entry_id =$payment_id;
    $payment_date = $payment_date1;
    $payment_amount = $payment_amount;
    $payment_mode = $payment_mode;
    $bank_name = $bank_name;
    $transaction_id = $transaction_id;
    $bank_id = $bank_id;
    $particular = get_sales_paid_particular(get_car_rental_booking_payment_id($payment_id,$yr1), $payment_date1, $payment_amount, $sq_car_rental_info['customer_id'], $payment_mode, get_car_rental_booking_id($booking_id,$yr1));
    $clearance_status = ($payment_mode=="Cheque") ? "Pending" : "";
    $payment_side = "Debit";
    $payment_type = ($payment_mode=="Cash") ? "Cash" : "Bank";
    $bank_cash_book_master->bank_cash_book_master_save($module_name, $module_entry_id, $payment_date, $payment_amount, $payment_mode, $bank_name, $transaction_id, $bank_id, $particular, $clearance_status, $payment_side, $payment_type, $branch_admin_id);

}

public function payment_update()
{
  $payment_id = $_POST['payment_id'];
  $booking_id = $_POST['booking_id'];
  $payment_amount = $_POST['payment_amount'];
  $payment_date = $_POST['payment_date'];
  $payment_mode = $_POST['payment_mode'];
  $bank_name = $_POST['bank_name'];
  $transaction_id = $_POST['transaction_id'];
  $bank_id = $_POST['bank_id'];
  $payment_old_value = $_POST['payment_old_value']; 

  $payment_date = date('Y-m-d', strtotime($payment_date));

  $financial_year_id = $_SESSION['financial_year_id'];

  $sq_payment_info = mysql_fetch_assoc(mysql_query("select * from car_rental_payment where payment_id='$payment_id'"));

  $clearance_status = ($sq_payment_info['payment_mode']=='Cash' && $payment_mode!="Cash") ? "Pending" : $sq_payment_info['clearance_status'];
  if($payment_mode=="Cash"){ $clearance_status = ""; }

  begin_t();
  
  $sq_payment = mysql_query("update car_rental_payment set booking_id='$booking_id', financial_year_id='$financial_year_id', payment_date='$payment_date', payment_mode='$payment_mode', payment_amount='$payment_amount', bank_name='$bank_name', transaction_id='$transaction_id', bank_id='$bank_id', clearance_status='$clearance_status' where payment_id='$payment_id' ");
  if(!$sq_payment){
    rollback_t();
	  echo "error--Sorry, Payment not updated!";
	  exit;
  }
  else{

    //Finance Update
    $this->finance_update($sq_payment_info, $clearance_status);

    //Bank and Cash Book Save
    $this->bank_cash_book_update($clearance_status);

    if($GLOBALS['flag']){
      commit_t();
      echo "Car Rental Payment has been successfully updated.";
      exit;  
    }
    else{
      rollback_t();
      exit;
    }
    
  }

}

public function finance_update($sq_payment_info, $clearance_status1)
{
    $row_spec = 'sales';
    $booking_id = $_POST['booking_id'];
    $payment_id = $_POST['payment_id'];
    $payment_amount1 = $_POST['payment_amount'];
    $payment_date = $_POST['payment_date'];
    $payment_mode = $_POST['payment_mode'];
    $bank_name = $_POST['bank_name'];
    $transaction_id1 = $_POST['transaction_id'];
    $bank_id1 = $_POST['bank_id'];
    $payment_old_value = $_POST['payment_old_value']; 

    $payment_date1 = date('Y-m-d', strtotime($payment_date));
    $year1 = explode("-", $payment_date1);
    $yr1 =$year1[0];

    $sq_car_info = mysql_fetch_assoc(mysql_query("select customer_id from car_rental_booking where booking_id='$booking_id'"));
    $customer_id = $sq_car_info['customer_id'];  
    global $transaction_master;


      //Getting cash/Bank Ledger
      if($payment_mode == 'Cash') {  $pay_gl = 20; }
      else{ 
        $sq_bank = mysql_fetch_assoc(mysql_query("select * from ledger_master where customer_id='$bank_id1' and user_type='bank'"));
        $pay_gl = $sq_bank['ledger_id'];
       } 

       //Getting customer Ledger
    $sq_cust = mysql_fetch_assoc(mysql_query("select * from ledger_master where customer_id='$customer_id' and user_type='customer'"));
    $cust_gl = $sq_cust['ledger_id'];



    if($payment_amount1 > $payment_old_value)
    {
      $balance_amount = $payment_amount1 - $payment_old_value;
      //////Payment Amount///////
        $module_name = "Car Rental Booking";
        $module_entry_id = $booking_id;
        $transaction_id = $transaction_id1;
        $payment_amount = $payment_amount1;
        $payment_date = $payment_date1;
        $payment_particular = get_sales_paid_particular(get_car_rental_booking_id($booking_id,$yr1), $payment_date1, $payment_amount1, $customer_id, $payment_mode, get_car_rental_booking_id($booking_id,$yr1));
        $ledger_particular = get_ledger_particular('By','Cash/Bank');
        $gl_id = $pay_gl;
        $payment_side = "Debit";
        $clearance_status = ($payment_mode!="Cash") ? "Pending" : "";
        $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);

        ////////Balance Amount//////
        $module_name = "Car Rental Booking";
        $module_entry_id = $booking_id;
        $transaction_id = "";
        $payment_amount = $balance_amount;
        $payment_date = $payment_date1;
        $payment_particular = get_sales_particular(get_car_rental_booking_id($booking_id,$yr1), $payment_date1, $balance_amount, $customer_id);
        $ledger_particular = get_ledger_particular('By','Cash/Bank');
        $gl_id = $cust_gl;
        $payment_side = "Credit";
        $clearance_status = "";
        $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);

        //Reverse first payment amount
        $module_name = "Car Rental Booking";
        $module_entry_id = $booking_id;
        $transaction_id = $transaction_id1;
        $payment_amount = $payment_old_value;
        $payment_date = $payment_date1;
        $payment_particular = get_sales_paid_particular(get_car_rental_booking_id($booking_id,$yr1), $payment_date1, $payment_old_value, $customer_id, $payment_mode, get_car_rental_booking_id($booking_id,$yr1));
        $ledger_particular = get_ledger_particular('By','Cash/Bank');
        $gl_id = $pay_gl;
        $payment_side = "Credit";
        $clearance_status = ($payment_mode!="Cash") ? "Pending" : "";
        $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);
    }
    else if($payment_amount1 < $payment_old_value){
      $balance_amount = $payment_old_value - $payment_amount1;
      //////Payment Amount///////
        $module_name = "Car Rental Booking";
        $module_entry_id = $booking_id;
        $transaction_id = $transaction_id1;
        $payment_amount = $payment_amount1;
        $payment_date = $payment_date1;
        $payment_particular = get_sales_paid_particular(get_car_rental_booking_id($booking_id,$yr1), $payment_date1, $payment_amount1, $customer_id, $payment_mode, get_car_rental_booking_id($booking_id,$yr1));
        $ledger_particular = get_ledger_particular('By','Cash/Bank');
        $gl_id = $pay_gl;
        $payment_side = "Debit";
        $clearance_status = ($payment_mode!="Cash") ? "Pending" : "";
        $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);

        ////////Balance Amount//////
        $module_name = "Car Rental Booking";
        $module_entry_id = $booking_id;
        $transaction_id = "";
        $payment_amount = $balance_amount;
        $payment_date = $payment_date1;
        $payment_particular = get_sales_particular(get_car_rental_booking_id($booking_id,$yr1), $payment_date1, $balance_amount, $customer_id);
        $ledger_particular = get_ledger_particular('By','Cash/Bank');
        $gl_id = $cust_gl;
        $payment_side = "Debit";
        $clearance_status = "";
        $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular); 

      //Reverse first payment amount
      $module_name = "Car Rental Booking";
      $module_entry_id = $booking_id;
      $transaction_id = $transaction_id1;
      $payment_amount = $payment_old_value;
      $payment_date = $payment_date1;
      $payment_particular = get_sales_paid_particular(get_car_rental_booking_id($booking_id,$yr1), $payment_date1, $payment_old_value, $customer_id, $payment_mode, get_car_rental_booking_id($booking_id,$yr1));
      $ledger_particular = get_ledger_particular('By','Cash/Bank');
      $gl_id = $pay_gl;
      $payment_side = "Credit";
      $clearance_status = ($payment_mode!="Cash") ? "Pending" : "";
      $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular); 
    }
    else{
      //Do nothing
    }
}

public function bank_cash_book_update($clearance_status)
{
    global $bank_cash_book_master;

    $booking_id = $_POST['booking_id'];
    $payment_id = $_POST['payment_id'];
    $booking_id = $_POST['booking_id'];
    $payment_amount = $_POST['payment_amount'];
    $payment_date = $_POST['payment_date'];
    $payment_mode = $_POST['payment_mode'];
    $bank_name = $_POST['bank_name'];
    $transaction_id = $_POST['transaction_id'];
    $bank_id = $_POST['bank_id'];

    $payment_date1 = date('Y-m-d', strtotime($payment_date));
    $year1 = explode("-", $payment_date1);
    $yr1 =$year1[0];
    $sq_car_rental_info = mysql_fetch_assoc(mysql_query("select customer_id from car_rental_booking where booking_id='$booking_id'"));

    $module_name = "Car Rental Booking";
    $module_entry_id =$payment_id;
    $payment_date = $payment_date1;
    $payment_amount = $payment_amount;
    $payment_mode = $payment_mode;
    $bank_name = $bank_name;
    $transaction_id = $transaction_id;
    $bank_id = $bank_id;
    $particular = get_sales_paid_particular(get_car_rental_booking_payment_id($payment_id,$yr1), $payment_date1, $payment_amount, $sq_car_rental_info['customer_id'], $payment_mode, get_car_rental_booking_id($booking_id,$yr1));
    $clearance_status = $clearance_status;
    $payment_side = "Debit";
    $payment_type = ($payment_mode=="Cash") ? "Cash" : "Bank";
    $bank_cash_book_master->bank_cash_book_master_update($module_name, $module_entry_id, $payment_date, $payment_amount, $payment_mode, $bank_name, $transaction_id, $bank_id, $particular, $clearance_status, $payment_side, $payment_type);

}

//////////////////////////////////**Payment email notification send start**/////////////////////////////////////
public function payment_email_notification_send($booking_id, $payment_amount, $payment_mode, $payment_date)
{
   $sq_car_rental = mysql_fetch_assoc(mysql_query("select * from car_rental_booking where booking_id='$booking_id'"));
   $total_amount = $sq_car_rental['total_fees'];
   $date = $sq_car_rental['created_at'];
   $yr = explode("-", $date);
   $year =$yr[0];
   $sq_customer_info = mysql_fetch_assoc(mysql_query("select email_id from customer_master where customer_id='$sq_car_rental[customer_id]'"));
   $email_id = $sq_customer_info['email_id'];

   $sq_total_amount = mysql_fetch_assoc(mysql_query("select sum(payment_amount) as sum from car_rental_payment where booking_id='$booking_id'"));
   $paid_amount = $sq_total_amount['sum'];

   $payment_id = get_car_rental_booking_payment_id($payment_id,$year);

  $subject = 'Payment Acknowledgement (Booking ID : '.get_car_rental_booking_id($booking_id,$year).' )';
   global $model;
   $model->generic_payment_mail('46',$payment_amount, $payment_mode, $total_amount, $paid_amount, $payment_date, $email_id,$subject, $payment_id);
}
//////////////////////////////////**Payment email notification send end**/////////////////////////////////////

//////////////////////////////////**Payment sms notification send start**/////////////////////////////////////
public function payment_sms_notification_send($booking_id, $payment_amount, $payment_mode)
{
  $sq_car_rental = mysql_fetch_assoc(mysql_query("select customer_id from car_rental_booking where booking_id='$booking_id'"));
  $customer_id = $sq_car_rental['customer_id'];

  $sq_customer_info = mysql_fetch_assoc(mysql_query("select contact_no from customer_master where customer_id='$customer_id'"));
  $mobile_no = $sq_customer_info['contact_no'];

  $message = "Acknowledge your payment of Rs. ".$payment_amount.", ".$payment_mode." which we received for Car Rental installment.";
    global $model;
    $model->send_message($mobile_no, $message);
}
//////////////////////////////////**Payment sms notification send end**/////////////////////////////////////

}
?>