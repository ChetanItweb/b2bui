<?php 

$flag = true;

class visa_cancel{



public function visa_cancel_save()

{

	$entry_id_arr = $_POST['entry_id_arr'];
	for($i=0; $i<sizeof($entry_id_arr); $i++){

		$sq_cancel = mysql_query("update visa_master_entries set status='Cancel' where entry_id='$entry_id_arr[$i]'");

		if(!$sq_cancel){

			echo "error--Sorry, Cancelation not done!";

			exit;

		}

	}

	//Cancelation notification mail send

	$this->cancel_mail_send($entry_id_arr);



	//Cancelation notification sms send

	$this->cancelation_message_send($entry_id_arr);



	echo "Visa has been successfully cancelled.";

	

}

public function cancel_mail_send($entry_id_arr)

{

	$sq_entry = mysql_fetch_assoc(mysql_query("select * from visa_master_entries where entry_id='$entry_id_arr[0]'"));

	$sq_visa_info = mysql_fetch_assoc(mysql_query("select * from visa_master where visa_id='$sq_entry[visa_id]'"));

	$sq_customer = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$sq_visa_info[customer_id]'"));


	$date = $sq_visa_info['created_at'];
    $yr = explode("-", $date);
    $year =$yr[0];

	$content1 = '';



	for($i=0; $i<sizeof($entry_id_arr); $i++)

	{

	$sq_entry = mysql_fetch_assoc(mysql_query("select * from visa_master_entries where entry_id='$entry_id_arr[$i]'"));



	$content1 .= '<tr>

	                <td style="color: #22262e;font-size: 14px;text-align: left;padding-left: 10px;font-weight: 500;">'.($i+1).'</td>

	                <td style="color: #22262e;font-size: 14px;text-align: left;padding-left: 10px;font-weight: 500;">'.$sq_entry['first_name'].' '.$sq_entry['last_name'].'</td>

	              </tr>

	';



	}



	global $mail_em_style, $mail_font_family, $mail_strong_style, $mail_color;

	$content = '	                    

        <tr>

          <td>

            <table cellspacing="0" style="background: #fff; color: #22262e; font-size: 13px;width:100%; margin:20px 0;">

                <tr>

                  <th style="padding-left: 10px;border: 1px solid #c1c1c1;text-align: left;font-weight: 500;background: #ddd;font-size: 14px;color: #22262E;">Sr.No</th>

                  <th style="padding-left: 10px;border: 1px solid #c1c1c1;text-align: left;font-weight: 500;background: #ddd;font-size: 14px;color: #22262E;">Passenger Name</th>

                </tr>

                '.$content1.'

            </table>

          </td>

        </tr>
	';


	$subject = 'Visa Cancellation Confirmation ('.get_visa_booking_id($sq_entry['visa_id'],$year).' )';
	global $model;

	$model->app_email_send('30',$sq_customer['email_id'], $content,$subject);

}



public function cancelation_message_send($entry_id_arr)

{

	$sq_entry = mysql_fetch_assoc(mysql_query("select * from visa_master_entries where entry_id='$entry_id_arr[0]'"));

	$sq_visa_info = mysql_fetch_assoc(mysql_query("select * from visa_master where visa_id='$sq_entry[visa_id]'"));

	$sq_customer = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$sq_visa_info[customer_id]'"));



	$message = 'We are accepting your cancellation request for Visa booking.';

  	global $model;

  	$model->send_message($sq_customer['contact_no'], $message);

}



}

?>