<?php 
$flag = true;
class ticket_refund{

public function ticket_refund_save()
{
	$train_ticket_id = $_POST['train_ticket_id'];
	$refund_date = $_POST['refund_date'];
	$refund_amount = $_POST['refund_amount'];
	$refund_mode = $_POST['refund_mode'];
	$bank_name = $_POST['bank_name'];
	$transaction_id = $_POST['transaction_id'];	
	$bank_id = $_POST['bank_id'];
	$entry_id_arr = $_POST['entry_id_arr'];	

	$refund_date = date('Y-m-d', strtotime($refund_date));

	$created_at = date('Y-m-d H:i:s');

	if($refund_mode=="Cheque"){ 
		$clearance_status = "Pending"; } 
	else {  $clearance_status = ""; }

	$financial_year_id = $_SESSION['financial_year_id']; 
	$branch_admin_id = $_SESSION['branch_admin_id'];

	$bank_balance_status = bank_cash_balance_check($refund_mode, $bank_id, $refund_amount);
	if(!$bank_balance_status){ echo bank_cash_balance_error_msg($refund_mode, $bank_id); exit; } 

	begin_t(); 

	$sq_max = mysql_fetch_assoc(mysql_query("select max(refund_id) as max from train_ticket_refund_master"));
	$refund_id = $sq_max['max'] + 1;

	$sq_payment = mysql_query("insert into train_ticket_refund_master (refund_id, train_ticket_id, financial_year_id, refund_date, refund_amount, refund_mode, bank_name, transaction_id, bank_id, clearance_status, created_at) values ('$refund_id', '$train_ticket_id', '$financial_year_id', '$refund_date', '$refund_amount', '$refund_mode', '$bank_name', '$transaction_id', '$bank_id', '$clearance_status', '$created_at') ");

	if($refund_mode == 'Credit Note'){
		$sq_sq_train_info = mysql_fetch_assoc(mysql_query("select * from train_ticket_master where train_ticket_id='$train_ticket_id'"));
  	    $customer_id = $sq_sq_train_info['customer_id'];
  	    
		$sq_max = mysql_fetch_assoc(mysql_query("select max(id) as max from credit_note_master"));
		$id = $sq_max['max'] + 1;

		$sq_payment = mysql_query("insert into credit_note_master (id, financial_year_id, module_name, module_entry_id, customer_id, payment_amount,refund_id,created_at,branch_admin_id) values ('$id', '$financial_year_id', 'Train Ticket Booking', '$train_ticket_id', '$customer_id','$refund_amount','$refund_id','$refund_date','$branch_admin_id') ");
	}
	if(!$sq_payment){
		rollback_t();
		echo "error--Sorry, Refund not saved!";
		exit;
	}
	else{

		for($i=0; $i<sizeof($entry_id_arr); $i++){

			$sq_max = mysql_fetch_assoc(mysql_query("select max(id) as max from train_ticket_refund_entries"));
			$id= $sq_max['max'] + 1;;

			$sq_entry = mysql_query("insert into train_ticket_refund_entries(id, refund_id, entry_id) values ('$id', '$refund_id', '$entry_id_arr[$i]')");
			if(!$sq_entry){
				$GLOBALS['flag'] = false;
				echo "error--Some entries not saved!";
				//exit;
			}

		}

		if($refund_mode != 'Credit Note'){
			//Finance save
	    	$this->finance_save($refund_id);
			
	    }
    	//Bank and Cash Book Save
		$this->bank_cash_book_save($refund_id);
		//refund email to customer
		if($refund_amount!=0){
			$this->refund_mail_send($train_ticket_id,$refund_amount,$refund_date,$refund_mode,$transaction_id);
		}
		if($GLOBALS['flag']){
			commit_t();
			echo "Ticket refund has been successfully saved.";
			exit;
		}
		else{
			rollback_t();
			exit;
		}

	}
}


public function finance_save($refund_id)
{
	$row_spec = 'sales';
	$train_ticket_id = $_POST['train_ticket_id'];
	$refund_date = $_POST['refund_date'];
	$refund_amount = $_POST['refund_amount'];
	$refund_mode = $_POST['refund_mode'];
	$bank_name = $_POST['bank_name'];
	$transaction_id = $_POST['transaction_id'];
	$bank_id = $_POST['bank_id'];	
	$entry_id_arr = $_POST['entry_id_arr'];	

	$refund_date = date('Y-m-d', strtotime($refund_date));
	$year2 = explode("-", $refund_date);
	$yr2 =$year2[0];

	global $transaction_master;

	$sq_train_info = mysql_fetch_assoc(mysql_query("select * from train_ticket_master where train_ticket_id='$train_ticket_id'"));
  	$customer_id = $sq_train_info['customer_id'];
	$year = explode("-", $sq_train_info['created_at']);
	$yr =$year[0];

  	//Getting cash/Bank Ledger
    if($refund_mode == 'Cash') {  $pay_gl = 20; }
    else{ 
	    $sq_bank = mysql_fetch_assoc(mysql_query("select * from ledger_master where customer_id='$bank_id' and user_type='bank'"));
	    $pay_gl = $sq_bank['ledger_id'];
     } 

  	//Getting customer Ledger
	  $sq_cust = mysql_fetch_assoc(mysql_query("select * from ledger_master where customer_id='$customer_id' and user_type='customer'"));
	  $cust_gl = $sq_cust['ledger_id'];


	////////Refund Amount//////
    $module_name = "Train Ticket Booking Refund Paid";
    $module_entry_id = $train_ticket_id;
    $transaction_id = $transaction_id;
    $payment_amount = $refund_amount;
    $payment_date = $refund_date;
    $payment_particular = get_refund_paid_particular(get_train_ticket_booking_id($train_ticket_id,$yr), $refund_date, $refund_amount, $refund_mode,get_train_ticket_booking_refund_id($refund_id,$yr2));
    $ledger_particular = '';
    $gl_id = $pay_gl;
    $payment_side = "Credit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,'',$ledger_particular);  

	////////Refund Amount//////
    $module_name = "Train Ticket Booking Refund Paid";
    $module_entry_id = $train_ticket_id;
    $transaction_id = $transaction_id;
    $payment_amount = $refund_amount;
    $payment_date = $refund_date;
    $payment_particular = get_refund_paid_particular(get_train_ticket_booking_id($train_ticket_id,$yr), $refund_date, $refund_amount, $refund_mode,get_train_ticket_booking_refund_id($refund_id,$yr2));
    $ledger_particular = '';
    $gl_id = $cust_gl;
    $payment_side = "Debit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,'',$ledger_particular);  

}


public function bank_cash_book_save($refund_id)
{
	$train_ticket_id = $_POST['train_ticket_id'];
	$refund_charges = $_POST['refund_charges'];
	$refund_date = $_POST['refund_date'];
	$refund_amount = $_POST['refund_amount'];
	$refund_mode = $_POST['refund_mode'];
	$bank_name = $_POST['bank_name'];
	$transaction_id = $_POST['transaction_id'];	
	$bank_id = $_POST['bank_id'];
	
	$refund_date = date('Y-m-d', strtotime($refund_date));
	$year2 = explode("-", $refund_date);
	$yr2 =$year2[0];

	$sq_train_info = mysql_fetch_assoc(mysql_query("select * from train_ticket_master where train_ticket_id='$train_ticket_id'"));
	$year = explode("-", $sq_train_info['created_at']);
	$yr =$year[0];

	global $bank_cash_book_master;

	$module_name = "Train Ticket Booking Refund Paid";
	$module_entry_id = $refund_id;
	$payment_date = $refund_date;
	$payment_amount = $refund_amount;
	$payment_mode = $refund_mode;
	$bank_name = $bank_name;
	$transaction_id = $transaction_id;
	$bank_id = $bank_id;
	$particular = get_refund_paid_particular(get_train_ticket_booking_id($train_ticket_id,$yr), $refund_date, $refund_amount, $refund_mode, get_train_ticket_booking_refund_id($refund_id,$yr2));
	$clearance_status = ($payment_mode=="Cheque") ? "Pending" : "";
	$payment_side = "Debit";
	$payment_type = ($payment_mode=="Cash") ? "Cash" : "Bank";
	$bank_cash_book_master->bank_cash_book_master_save($module_name, $module_entry_id, $payment_date, $payment_amount, $payment_mode, $bank_name, $transaction_id, $bank_id, $particular, $clearance_status, $payment_side, $payment_type);

}

 public function refund_mail_send($train_ticket_id,$refund_amount,$refund_date,$refund_mode,$transaction_id)
 {


global $app_email_id, $app_name, $app_contact_no, $admin_logo_url, $app_website;
  global $mail_em_style, $mail_em_style1, $mail_font_family, $mail_strong_style, $mail_color;
   
  $sq_sq_train_info = mysql_fetch_assoc(mysql_query("select * from train_ticket_master where train_ticket_id='$train_ticket_id'"));
  $date = $sq_sq_train_info['created_at'];
  $yr = explode("-", $date);
  $year =$yr[0];
  $cust_email = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$sq_sq_train_info[customer_id]'"));

  $content = '
 <tr>
    <td>
      <table cellspacing="0" style="width:100%">
	      <tr>
	        <td colspan="3" style="padding: 5px;border: 1px solid #c1c1c1;text-align: center;font-weight: 600;background: #ddd;font-size: 14px;color: #4e4e4e;">
	        Transaction Details.
	        </td>
	      </tr>
	      <tr>
          	<td style="background: rgba(255, 255, 255, 0.23);color: #000;">
	          <p style="line-height: 24px;padding: 0 0 0 15px;">Booking ID : '.$sq_sq_train_info['train_ticket_id'].'</p>
	          <p style="line-height: 24px;padding: 0 0 0 15px;">Refund Amount : '.$refund_amount.'</p>
	          <p style="line-height: 24px;padding: 0 0 0 15px;">Refund Date : '.get_date_user($refund_date).'</p>
	          <p style="line-height: 24px;padding: 0 0 0 15px;">Payment Mode : '.$refund_mode.'</p>';           
			  if($transaction_id!= ''){ $content .= '<p style="line-height: 24px;padding: 0 0 0 15px;">Cheque No/ID : '.$transaction_id.'</p>'; }
			  $content .= '  
	        </td>
	      </tr>
      </table>  
    </td>
 </tr>
  ';
$subject = 'Train Ticket Cancellation Refund ('.get_train_ticket_booking_id($sq_sq_train_info['train_ticket_id'],$year).' )';
  global $model;
 
  $model->app_email_send('35',$cust_email['email_id'], $content,$subject);
 }
}
?>