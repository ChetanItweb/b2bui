<?php

include_once('../model.php');
global $model;
 
	$today=date('Y-m-d');
	$exp_date = date('Y-m-d', strtotime('+7 days', strtotime($today)));

		$sq_emplyee = mysql_query("SELECT * from emp_master where expiry_date='$exp_date' and active_flag='Active'");
		while($row_emp=mysql_fetch_assoc($sq_emplyee))
		{
			$emp_id = $row_emp['emp_id'];
			$name = $row_emp['first_name']." ".$row_emp['last_name'];
			$visa_country_name = $row_emp['visa_country_name'];
			$issue_date = $row_emp['issue_date'];
			$expiry_date = $row_emp['expiry_date'];
			$renewal_amount = $row_emp['renewal_amount'];

			$sq_count = mysql_num_rows(mysql_query("SELECT * from  remainder_status where remainder_name = 'user_visa_renewal' and date='$today' and status='Done'"));
			if($sq_count==0)
			{
				 email($name, $visa_country_name, $issue_date, $expiry_date, $renewal_amount);
			}
		}
		if($sq_count==0){
			$row=mysql_query("SELECT max(id) as max from remainder_status");
		 	$value=mysql_fetch_assoc($row);
		 	$max=$value['max']+1;
			$sq_check_status=mysql_query("INSERT INTO `remainder_status`(`id`, `remainder_name`, `date`, `status`) VALUES ('$max','user_visa_renewal','$today','Done')");
		}
		

	function email($name, $visa_country_name, $issue_date, $expiry_date, $renewal_amount)
	{
	global $app_email_id, $app_name, $app_contact_no, $admin_logo_url, $app_website, $mail_strong_style;

	$content=' 
      <tr>
        <td>
          <table style="width:100%;">
           	<tr>
              <td><strong style="'.$mail_strong_style.'">Employee name :</strong> '.$name.'</td>
            </tr>
           	<tr>
              <td><strong style="'.$mail_strong_style.'">Country :</strong> '.$visa_country_name.'</td>
            </tr>
           	<tr>
              <td><strong style="'.$mail_strong_style.'">Issue Date :</strong> '.get_date_user($issue_date).'</td>
            </tr>
           	<tr>
              <td><strong style="'.$mail_strong_style.'">Expiry Date :</strong> '.get_date_user($expiry_date).'</td>
            </tr>
           	<tr>
              <td><strong style="'.$mail_strong_style.'">Renewal Amount : '.$renewal_amount.'</strong></td>
            </tr>
          </table>
        </td>
      </tr>
    '; 
    $subject = 'User Visa Status ( Customer Name :'.$name.' ).'; 
    global $model;
 
	$model->app_email_send('91',$app_email_id, $content, $subject);
	}

?>