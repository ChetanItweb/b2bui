<?php

include "../../../../model/model.php";

$hotel_id = $_POST['hotel_id'];
$sq_hotel = mysql_fetch_assoc(mysql_query("select * from hotel_master where hotel_id='$hotel_id'"));
$sq_vendor_login = mysql_fetch_assoc(mysql_query("select * from vendor_login where username='$sq_hotel[hotel_name]' and password='$sq_hotel[mobile_no]' and vendor_type='Hotel Vendor'"));

$role = $_SESSION['role'];
$value = '';
$images_url = '';
if($role!='Admin' && $role!="Branch Admin"){ $value="readonly"; }

$sq_hotel_img = mysql_query("select * from hotel_vendor_images_entries where hotel_id='$hotel_id'");
while($row_hotel_img = mysql_fetch_assoc($sq_hotel_img)){
  $images_url .= $row_hotel_img['hotel_pic_url'].',';  
}
?>

<div class="modal fade" id="update_modal" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Update Hotel Supplier Information</h4>
      </div>

      <div class="modal-body">
		<form id="frm_hotel_update">

    <div class="panel panel-default panel-body app_panel_style feildset-panel">
        <legend>Hotel Information</legend>
		    <input type="hidden" id="txt_hotel_id" name="txt_hotel_id" value="<?php echo $hotel_id ?>">
		    <input type="hidden" id="vendor_login_id" name="vendor_login_id" value="<?= $sq_vendor_login['login_id'] ?>">			
		    <div class="row">
		    	<div class="col-md-3 col-sm-6 mg_bt_10">
		            <select id="cmb_city_id" name="cmb_city_id" style="width:100%" title="City Name">
		                <?php $sq_city = mysql_fetch_assoc(mysql_query("select city_name from city_master where city_id='$sq_hotel[city_id]'")); ?>
		                <option value="<?php echo $sq_hotel['city_id'] ?>"><?php echo $sq_city['city_name'] ?></option>
		                <?php get_cities_dropdown(); ?>
		            </select>
		        </div>

		        <div class="col-md-3 col-sm-6 mg_bt_10">
		            <input type="text" value="<?= $sq_hotel['hotel_name'] ?>" onchange="validate_spaces(this.id);"  class="form-control" id="txt_hotel_name" name="txt_hotel_name" placeholder="Hotel Name" title="Hotel Name">        
		        </div>

		        <div class="col-md-3 col-sm-6 mg_bt_10">
		            <input type="text" value="<?= $sq_hotel['mobile_no'] ?>" onchange="mobile_validate(this.id);" class="form-control" id="txt_mobile_no" name="txt_mobile_no" placeholder="Mobile Number" title="Mobile Number">
		        </div>  
            <div class="col-md-3 col-sm-6 mg_bt_10">
                <input type="text" value="<?= $sq_hotel['landline_no'] ?>" id="txt_landline_no" onchange="mobile_validate(this.id);" name="txt_landline_no"placeholder="Landline Number" title="Landline Number">
            </div>
		    </div>

		    <div class="row">
		        <div class="col-md-3 col-sm-6 mg_bt_10">
		            <input type="text" value='<?= $sq_hotel['email_id'] ?>'  class="form-control" id="txt_email_id" name="txt_email_id"  placeholder="Email ID" title="Email ID" onchange="validate_email(this.id)">
		        </div>
		        <div class="col-md-3 col-sm-6 mg_bt_10">
		            <input type="text" value="<?= $sq_hotel['contact_person_name'] ?>"  class="form-control" id="txt_contact_person_name" name="txt_contact_person_name" placeholder="Contact Person Name" title="Contact Person Name">
		        </div>		
            <div class="col-md-3 col-sm-6 mg_bt_10">
                <input type="text" value="<?= $sq_hotel['immergency_contact_no'] ?>"  class="form-control" id="immergency_contact_no" name="immergency_contact_no"  onchange="mobile_validate(this.id);" placeholder="Emergency Contact No" title="Emergency Contact No">
            </div>
            <div class="col-md-3 col-sm-6 mg_bt_10">
                <textarea id="txt_hotel_address" name="txt_hotel_address"  onchange="validate_address(this.id);" placeholder="Hotel Address" title="Hotel Address" class="form-control" rows="1"><?= $sq_hotel['hotel_address'] ?></textarea>
            </div> 
		    </div>

		    <div class="row">
          <div class="col-sm-3 col-xs-6 mg_bt_10">
              <select name="cust_state1" id="cust_state1" title="Select Location" style="width:100%">
               <?php if($sq_hotel['state_id'] != '0'){ ?>
                <?php $sq_state = mysql_fetch_assoc(mysql_query("select * from state_master where id='$sq_hotel[state_id]'"));
                ?>
                <option value="<?= $sq_hotel['state_id'] ?>"><?= $sq_state['state_name'] ?></option>
                <?php } ?>
                <?php get_states_dropdown() ?>
              </select>
            </div>
            <div class="col-md-3 col-sm-6 mg_bt_10">
                <textarea id="country" name="country" placeholder="Country" title="Country" class="form-control" rows="1"><?= $sq_hotel['country'] ?></textarea>
            </div>
            <div class="col-md-3 col-sm-6 mg_bt_10">
                <input type="text" id="website" name="website" placeholder="Website" title="Website" class="form-control" value="<?= $sq_hotel['website'] ?>">
            </div>
          <div class="col-md-3 col-sm-6 mg_bt_10">
            <select name="rating_star" id="rating_star" title="Hotel Type">
            <?php if($sq_hotel['rating_star']!=''){?>
              <option value="<?= $sq_hotel['rating_star'] ?>"><?= $sq_hotel['rating_star'] ?></option>
            <?php } ?>
              <option value="Economy">Economy</option>
              <option value="3 Star">3 Star</option>
              <option value="4 Star">4 Star</option>
              <option value="5 Star">5 Star</option>
              <option value="7 Star">7 Star</option>
              <option value="Other">Other</option>
            </select>
          </div> 
        </div>
      </div>

      <div class="panel panel-default panel-body app_panel_style mg_tp_30 feildset-panel">
          <legend>Bank Information</legend>
          <div class="row"> 
            <div class="col-md-3 col-sm-6 mg_bt_10">
                  <input type="text" value="<?= $sq_hotel['bank_name'] ?>" id="bank_name" name="bank_name" placeholder="Bank Name" title="Bank Name" class="bank_suggest" >
            </div>  
            <div class="col-md-3 col-sm-6 mg_bt_10">
                  <input type="text" value="<?= $sq_hotel['account_name'] ?>" class="form-control" id="account_name1" name="account_name1" placeholder="A/c Name" title="A/c Name">
            </div>
            <div class="col-md-3 col-sm-6 mg_bt_10">
                  <input type="text" value="<?= $sq_hotel['account_no'] ?>" onchange="validate_accountNo(this.id);" class="form-control" id="account_no" name="account_no" placeholder="A/c No" title="A/c No">
            </div>   
            <div class="col-md-3 col-sm-6 mg_bt_10">
                <input type="text" value="<?= $sq_hotel['branch'] ?>" onchange="validate_branch(this.id);" class="form-control" id="branch" name="branch" placeholder="Branch" title="Branch">
            </div>
          </div>
		    	<div class="row"> 
          <div class="col-md-3 col-sm-6 mg_bt_10">
            <input type="text" value="<?= strtoupper($sq_hotel['ifsc_code']) ?>" onchange="validate_IFSC(this.id);" class="form-control" id="ifsc_code" name="ifsc_code" placeholder="IFSC/Swift Code" title="IFSC/Swift Code" style="text-transform: uppercase;">
          </div>
          <div class="col-md-3 col-sm-6 mg_bt_10">
                <input type="text" id="opening_balance" name="opening_balance" placeholder="Opening Balance" title="Opening Balance" value="<?= $sq_hotel['opening_balance'] ?>" <?= $value ?>  onchange="validate_balance(this.id)">
          </div>
          <div class="col-md-3 mg_bt_10">
            <input type="text" id="as_of_date1" name="as_of_date1" placeholder="*As of Date" title="As of Date" value="<?= get_date_user($sq_hotel['as_of_date']) ?>">
          </div>
          <div class="col-md-3 mg_bt_10">
            <select name="side" id="side1" title="Select side" disabled>
            <?php if($sq_hotel['side']!=''){?>
              <option value="<?= $sq_hotel['side'] ?>"><?= $sq_hotel['side'] ?></option>
            <?php } ?>
              <option value="">*Select Side</option>
              <option value="Credit">Credit</option>
              <option value="Debit">Debit</option>
            </select>
          </div>
        </div> 
      <div class="row">
            <div class="col-md-3 col-sm-6 mg_bt_10">
                <input type="text" name="service_tax_no" id="service_tax_no"  onchange="validate_alphanumeric(this.id);" placeholder="Tax No" title="Tax No" value="<?= strtoupper($sq_hotel['service_tax_no'])?>" style="text-transform: uppercase;">
            </div>
            <div class="col-md-3 col-sm-6 mg_bt_10">
             <input type="text" id="supp_pan" name="supp_pan" value="<?= $sq_hotel['pan_no']?>" onchange="validate_alphanumeric(this.id)" placeholder="PAN/TAN No" title="PAN/TAN No" style="text-transform: uppercase;">
            </div> 
          </div>
      </div>
      <div class="row">
        <div class="col-md-3 col-sm-6 mg_bt_10">
          <select name="active_flag" id="active_flag" title="Active Flag">
            <option value="<?= $sq_hotel['active_flag'] ?>"><?= $sq_hotel['active_flag'] ?></option>
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
          </select>
        </div>
        <div class="col-sm-9">
            <div class="div-upload">
              <div id="hotel_upload_btn" class="upload-button1"><span>Upload Images</span></div>
              <span id="id_proof_status" ></span>
              <ul id="files" ></ul>
              <input type="hidden" id="hotel_upload_url" name="hotel_upload_url" value='<?= $images_url ?>'>
           </div>(Upload Maximum 3 images)
        </div>
      </div>
        <div class="row mg_tp_10">  
            <div class="col-sm-6">  
              <span style="color: red;" class="note">Note : Image size should be less than 100KB, resolution : 900X450.</span>
            </div>
        </div>
      <div class="row mg_tp_20 mg_bt_20" id="images_list"></div>

      <div class="row text-center mg_tp_20">
        <div class="col-md-12">
          <button class="btn btn-sm btn-success" id="updte_btn"><i class="fa fa-floppy-o"></i>&nbsp;&nbsp;Update</button> 
        </div>    
      </div>
		</form>

      </div>
    </div>
  </div>

</div>
<script src="<?= BASE_URL ?>js/ajaxupload.3.5.js"></script>
<script>
$('#update_modal').modal('show');
$('#as_of_date1').datetimepicker({ timepicker:false, format:'d-m-Y' });

$(document).ready(function() {
    $("#cust_state1").select2();   
    $("#cmb_city_id").select2({minimumInputLength: 1});   
});


function load_images(hotel_id)
{
    var base_url = $("#base_url").val();
    $.ajax({
          type:'post',
          url: base_url+'view/custom_packages/master/package/get_hotel_img.php',
          data:{hotel_name : hotel_id },
          success:function(result){
           $('#images_list').html(result);
          }
  });
}
load_images(<?= $hotel_id ?>);



function delete_image(image_id,hotel_name)
{
    var base_url = $("#base_url").val();
    $.ajax({
          type:'post',
          url: base_url+'controller/custom_packages/delete_hotel_image.php',
          data:{ image_id : image_id },
          success:function(result)
          {
            msg_alert(result);
            load_images(hotel_name);
          }
  });    
}



upload_hotel_pic_attch();
function upload_hotel_pic_attch()
{
    var img_array = new Array(); 

    var btnUpload=$('#hotel_upload_btn');
    $(btnUpload).find('span').text('Upload Images');
    $("#hotel_upload_url").val('');

    new AjaxUpload(btnUpload, {
      action: 'hotel/upload_hotel_images.php',
      name: 'uploadfile',
      onSubmit: function(file, ext){  
        if (! (ext && /^(jpg|png|jpeg)$/.test(ext))){ 
         error_msg_alert('Only JPG, PNG or GIF files are allowed');
         return false;
        }
        $(btnUpload).find('span').text('Uploading...');
      },
      onComplete: function(file, response){
        if(response==="error"){          
          error_msg_alert("File is not uploaded.");           
          $(btnUpload).find('span').text('Upload Images');
        }else
        { 
          if(response=="error1")
          {
            $(btnUpload).find('span').text('Upload Images');
            error_msg_alert('Maximum size exceeds');
            return false;
          }else
          {
              $(btnUpload).find('span').text('Uploaded'); 
              $("#hotel_upload_url").val(response);
               upload_pic();            
          }
          // img_array.push(response); 
        }
          // $("#hotel_image_path").val(img_array); 
      }
    });
}

function upload_pic(){

  var base_url = $("#base_url").val();
  var hotel_upload_url = $('#hotel_upload_url').val();
  var hotel_names = $('#txt_hotel_id').val();
  $.ajax({
          type:'post',
          url: base_url+'controller/hotel/hotel_img_save_c.php',
          data:{ hotel_upload_url : hotel_upload_url,hotel_names : hotel_names },
          success:function(result)
          {
            msg_alert(result);
            load_images(hotel_names);
          }
  });
}
///////////////////////***Hotel Master Update start*********//////////////

$(function(){

    $('#frm_hotel_update').validate({
    rules:{
            cmb_city_id : { required: true }, 
            txt_hotel_name : { required: true },
            side : { required : true },
            rating_star : { required : true},
            as_of_date1 : { required : true},
    },

    submitHandler:function(form){
      var hotel_id = $("#txt_hotel_id").val();
      var vendor_login_id = $('#vendor_login_id').val();
      var base_url = $("#base_url").val();
      var city_id = $("#cmb_city_id").val();
      var hotel_name = $("#txt_hotel_name").val();
      var mobile_no = $("#txt_mobile_no").val();
      var landline_no = $('#txt_landline_no').val();
      var email_id = $("#txt_email_id").val();
      var contact_person_name = $("#txt_contact_person_name").val();
      var immergency_contact_no =$("#immergency_contact_no").val();
      var hotel_address = $("#txt_hotel_address").val();
      var country = $("#country").val();
      var website = $("#website").val();
      var bank_name = $("#bank_name").val();
      var branch = $("#branch").val();
      var ifsc_code = $("#ifsc_code").val();
      var account_no = $("#account_no").val();
      var account_name = $("#account_name1").val();
      var opening_balance = $('#opening_balance').val();
      var rating_star = $('#rating_star').val();
      var active_flag = $('#active_flag').val();
      var service_tax_no = $('#service_tax_no').val();
      var state = $('#cust_state1').val();
      var side1 = $('#side1').val();
      var supp_pan = $('#supp_pan').val();
      var as_of_date = $('#as_of_date1').val();
      var add = validate_address('txt_hotel_address');
      if(!add){
        error_msg_alert('More than 155 characters are not allowed.');
        return false;
      }
      $('#updte_btn').button('loading');
      $.post(
            base_url+"controller/hotel/hotel_master_update_c.php",
            { hotel_id : hotel_id, vendor_login_id : vendor_login_id, city_id : city_id, hotel_name : hotel_name, mobile_no : mobile_no, landline_no : landline_no, email_id : email_id, contact_person_name : contact_person_name, immergency_contact_no : immergency_contact_no, hotel_address : hotel_address, country : country, website :website,  opening_balance : opening_balance,rating_star : rating_star, active_flag : active_flag, bank_name : bank_name, account_no: account_no, branch : branch, ifsc_code :ifsc_code, service_tax_no : service_tax_no, state : state,side1 : side1,account_name : account_name,supp_pan : supp_pan,as_of_date : as_of_date },

            function(data) {  
                msg_alert(data);
                $('#updte_btn').button('reset');
                $('#update_modal').modal('hide');
                list_reflect();
            });
    }
  });
});

///////////////////////***Hotel Master Update end*********//////////////

</script>

<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>