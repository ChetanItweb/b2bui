<?php
include "../../../../model/model.php";
$client_modal_type = $_POST['client_modal_type'];
?>
<input type="hidden" id="client_modal_type" name="client_modal_type" value="<?= $client_modal_type ?>">
<div class="modal fade" id="save_modal" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document" style='width:80%'>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Hotel Details</h4>
      </div>
      <div class="modal-body">
		  <form id="frm_hotel_save"> 
        <div class="panel panel-default panel-body app_panel_style feildset-panel">
         <legend>Hotel Information</legend>           
         <div class="row"> 
            <div class="col-md-3 col-sm-6 mg_bt_10">
                <select id="cmb_city_id" name="cmb_city_id" class="city_master_dropdown" style="width:100%" title="Select City Name">
                    <?php get_cities_dropdown(); ?>
                </select>
            </div>
            <div class="col-md-3 col-sm-6 mg_bt_10">
                <input type="text" class="form-control" id="txt_hotel_name" onchange="validate_spaces(this.id);" name="txt_hotel_name" placeholder="*Hotel Name" title="Hotel Name">        
            </div>
            <div class="col-md-3 col-sm-6 mg_bt_10">
                <input type="text" class="form-control" id="txt_mobile_no" onchange="mobile_validate(this.id);" name="txt_mobile_no" placeholder="Mobile Number" title="Mobile Number">
            </div>	
            <div class="col-md-3 col-sm-6 mg_bt_10">
                <input type="text" class="form-control" id="txt_landline_no" onchange="mobile_validate(this.id);" name="txt_landline_no" placeholder="Landline Number" title="Landline Number">
            </div>
         </div>
        <div class="row">
            <div class="col-md-3 col-sm-6 mg_bt_10">
                <input type="text" class="form-control" id="txt_email_id" name="txt_email_id"  placeholder="Email ID" title="Email ID" onchange="validate_email(this.id)">
            </div>
            <div class="col-md-3 col-sm-6 mg_bt_10">
                <input type="text" class="form-control" id="txt_contact_person_name" name="txt_contact_person_name" placeholder="Contact Person Name" title="Contact Person Name">
            </div>  
            <div class="col-md-3 col-sm-6 mg_bt_10">
                <input type="text" id="immergency_contact_no"  onchange="mobile_validate(this.id);" name="immergency_contact_no" placeholder="Emergency Contact No" title="Emergency Contact No" >
            </div>
            <div class="col-md-3 col-sm-6 mg_bt_10">
              <textarea id="txt_hotel_address" onchange="validate_address(this.id);" name="txt_hotel_address" placeholder="Hotel Address" class="form-control" title="Hotel Address" rows="1"></textarea>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3 col-xs-12 mg_bt_10">
                  <select name="state" id="state" title="Select location" style="width:100%">
                    <?php get_states_dropdown() ?>
                  </select>
            </div>
            <div class="col-md-3 col-sm-6 mg_bt_10">
              <input type="text" id="country" name="country" placeholder="Country" title="Country">
            </div>
            <div class="col-md-3 col-sm-6 mg_bt_10">
              <input type="text" id="website" name="website" placeholder="Website" title="Website">
            </div>
            <div class="col-md-3 col-sm-6 mg_bt_10">
              <select name="rating_star" id="rating_star" title="Select Hotel Type">
                <option value="Economy">Economy</option>
                <option value="3 Star">3 Star</option>
                <option value="4 Star">4 Star</option>
                <option value="5 Star">5 Star</option>
                <option value="7 Star">7 Star</option>
                <option value="Other">Other</option>
              </select>
          </div>
		    </div>
          <div class="row">
            <div class="col-md-12 col-sm-6 mg_bt_10">
              <textarea id="description" onchange="validate_address(this.id);" name="description" placeholder="Hotel Description" class="form-control" title="Hotel Description" rows="2"></textarea>
            </div>
          </div>
       </div>
      <div class="panel panel-default panel-body app_panel_style mg_tp_30 feildset-panel">
       <legend>Bank Information</legend>
        <div class="row"> 
          <div class="col-md-3 col-sm-6 mg_bt_10">
            <input type="text" id="bank_name" name="bank_name" placeholder="Bank Name" title="Bank Name" class="bank_suggest" >
          </div>
          <div class="col-md-3 col-sm-6 mg_bt_10">
            <input type="text" id="account_name" name="account_name" placeholder="A/c Name" title="A/c Name" >
          </div> 
          <div class="col-md-3 col-sm-6 mg_bt_10">
            <input type="text" id="account_no" name="account_no" onchange="validate_accountNo(this.id);" placeholder="A/c No" title="A/c No" >
          </div>           
          <div class="col-md-3 col-sm-6 mg_bt_10">
            <input type="text" id="branch" name="branch" onchange="validate_branch(this.id);" placeholder="Branch" title="Branch">
          </div> 
        </div>  

        <div class="row">
          <div class="col-md-3 col-sm-6 mg_bt_10">
            <input type="text" id="ifsc_code" name="ifsc_code" onchange="validate_IFSC(this.id);" placeholder="IFSC/Swift Code" title="IFSC/Swift Code" style="text-transform: uppercase;">
          </div>
          <div class="col-md-3 col-sm-6 mg_bt_10">
              <input type="text" id="opening_balance" name="opening_balance" placeholder="Opening Balance" title="Opening Balance" value="0"  onchange="validate_balance(this.id);">
          </div>
          <div class="col-sm-3 mg_bt_10">
            <input type="text" id="as_of_date" name="as_of_date" placeholder="*As of Date" title="As of Date">
          </div>
          <div class="col-md-3 col-sm-6 mg_bt_10">
            <select name="side" id="side" title="Select side">
              <option value="Credit">Credit</option>
              <option value="Debit">Debit</option>
            </select>
          </div>
        </div>
        <div class="row">
           <div class="col-md-3 col-sm-6 mg_bt_10">
            <input type="text"  class="form-control" onchange="validate_alphanumeric(this.id);"  name="service_tax_no" id="service_tax_no"  placeholder="Tax No" title="Tax No" style="text-transform: uppercase;">
          </div>
          <div class="col-md-3 col-sm-6 mg_bt_10">
             <input type="text" id="supp_pan" name="supp_pan" onchange="validate_alphanumeric(this.id)"  placeholder="PAN/TAN No" title="PAN/TAN No" style="text-transform: uppercase;">
          </div>
          <div class="col-md-3 col-sm-6 mg_bt_10">
              <select name="active_flag" id="active_flag" title="Active Flag" class="hidden">
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
              </select>
          </div>
        </div>
      </div>
      
      <div class="panel panel-default panel-body app_panel_style mg_tp_30 feildset-panel">
        <legend>Hotel Amenities</legend>
        <div class="row">
          <div class="col-md-12 col-sm-4 col-xs-12 mg_bt_10">
            <div class="row">
              <div class="col-md-3">
              <div class="forex_chk" style='float: left;min-width: 200px;margin-bottom: 10px;'>
                <input type="checkbox" id="wifi" name="amenities" value="WIFI">
                <label for="wifi">WIFI</label>
              </div>
              </div>
              <div class="col-md-3">
              <div class="forex_chk" style='float: left;min-width: 200px;margin-bottom: 10px;'>
                <input type="checkbox" id="swm" name="amenities" value="Swimming Pool">
                <label for="swm">Swimming Pool</label>
              </div>
              </div>
              <div class="col-md-3">
              <div class="forex_chk" style='float: left;min-width: 200px;margin-bottom: 10px;'>
                <input type="checkbox" id="tele" name="amenities" value="Television">
                <label for="tele">Television</label>
              </div>
              </div>
              <div class="col-md-3">
              <div class="forex_chk" style='float: left;min-width: 200px;margin-bottom: 10px;'>
                <input type="checkbox" id="coffee" name="amenities" value="Coffee">
                <label for="coffee">Coffee</label>
              </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-3">
              <div class="forex_chk" style='float: left;min-width: 200px;margin-bottom: 10px;'>
                <input type="checkbox" id="air" name="amenities" value="Air Conditioning">
                <label for="air">Air Conditioning</label>
              </div>
              </div>
              <div class="col-md-3">
              <div class="forex_chk" style='float: left;min-width: 200px;margin-bottom: 10px;'>
                <input type="checkbox" id="fit" name="amenities" value="Fitness Facility">
                <label for="fit">Fitness Facility</label>
              </div>
              </div>
              <div class="col-md-3">
              <div class="forex_chk" style='float: left;min-width: 200px;margin-bottom: 10px;'>
                <input type="checkbox" id="fridge" name="amenities" value="Fridge">
                <label for="fridge">Fridge</label>
              </div>
              </div>
              <div class="col-md-3">
              <div class="forex_chk" style='float: left;min-width: 200px;margin-bottom: 10px;'>
                <input type="checkbox" id="wine" name="amenities" value="WINE BAR">
                <label for="wine">WINE BAR</label>
              </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-3">
              <div class="forex_chk" style='float: left;min-width: 200px;margin-bottom: 10px;'>
                <input type="checkbox" id="smoke" name="amenities" value="Smoking Allowed">
                <label for="smoke">Smoking Allowed</label>
              </div>
              </div>
              <div class="col-md-3">
              <div class="forex_chk" style='float: left;min-width: 200px;margin-bottom: 10px;'>
                <input type="checkbox" id="enter" name="amenities" value="Entertainment">
                <label for="enter">Entertainment</label>
              </div>
              </div>
              <div class="col-md-3">
              <div class="forex_chk" style='float: left;min-width: 200px;margin-bottom: 10px;'>
                <input type="checkbox" id="secure" name="amenities" value="Secure Vault">
                <label for="secure">Secure Vault</label>
              </div>
              </div>
              <div class="col-md-3">
              <div class="forex_chk" style='float: left;min-width: 200px;margin-bottom: 10px;'>
                <input type="checkbox" id="pick" name="amenities" value="Pick And Drop">
                <label for="pick">Pick And Drop</label>
              </div>
              </div>
          </div>
          <div class="row">
              <div class="col-md-3">
                <div class="forex_chk" style='float: left;min-width: 200px;margin-bottom: 10px;'>
                  <input type="checkbox" id="room" name="amenities" value="Room Service">
                  <label for="room">Room Service</label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="forex_chk" style='float: left;min-width: 200px;margin-bottom: 10px;'>
                  <input type="checkbox" id="pets" name="amenities" value="Pets Allowed">
                  <label for="pets">Pets Allowed</label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="forex_chk" style='float: left;min-width: 200px;margin-bottom: 10px;'>
                  <input type="checkbox" id="play" name="amenities" value="Play Place">
                  <label for="play">Play Place</label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="forex_chk" style='float: left;min-width: 200px;margin-bottom: 10px;'>
                  <input type="checkbox" id="comp" name="amenities" value="Complimentary Breakfast">
                  <label for="comp">Complimentary Breakfast</label> 
                </div>
              </div>
          </div>
          <div class="row">
              <div class="col-md-3">
                <div class="forex_chk" style='float: left;min-width: 200px;margin-bottom: 10px;'>
                  <input type="checkbox" id="free" name="amenities" value="Free Parking">
                  <label for="free">Free Parking</label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="forex_chk" style='float: left;min-width: 200px;margin-bottom: 10px;'>
                  <input type="checkbox" id="conf" name="amenities" value="Conference Room">
                  <label for="conf">Conference Room</label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="forex_chk" style='float: left;min-width: 200px;margin-bottom: 10px;'>
                  <input type="checkbox" id="fire" name="amenities" value="Fire Place">
                  <label for="fire">Fire Place</label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="forex_chk" style='float: left;min-width: 200px;margin-bottom: 10px;'>
                  <input type="checkbox" id="handi" name="amenities" value="Handicap Accessible">
                  <label for="handi">Handicap Accessible</label>
                </div>
              </div>
          </div>
          <div class="row">
              <div class="col-md-3">
                <div class="forex_chk" style='float: left;min-width: 200px;margin-bottom: 10px;'>
                  <input type="checkbox" id="doorman" name="amenities" value="Doorman">
                  <label for="doorman">Doorman</label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="forex_chk" style='float: left;min-width: 200px;margin-bottom: 10px;'>
                  <input type="checkbox" id="hot" name="amenities" value="HOT TUB">
                  <label for="hot">HOT TUB</label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="forex_chk" style='float: left;min-width: 200px;margin-bottom: 10px;'>
                  <input type="checkbox" id="elev" name="amenities" value="Elevator In Building">
                  <label for="elev">Elevator In Building</label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="forex_chk" style='float: left;min-width: 200px;margin-bottom: 10px;'>
                  <input type="checkbox" id="suita" name="amenities" value="Suitable For Events">
                  <label for="suita">Suitable For Events</label>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="panel panel-default panel-body app_panel_style mg_tp_30 feildset-panel">
      <legend>Hotel Policies</legend>
      <div class="col-md-12 col-sm-6 mg_bt_10">
          <textarea class="feature_editor" name="policies" id="policies" style="width:100% !important" rows="12"></textarea>
      </div>
      </div>
      <div class="row">
          <div class="col-sm-6">
              <div class="div-upload">
                <div id="hotel_upload_btn" class="upload-button1"><span>Upload Images</span></div>
                <span id="id_proof_status" ></span>
                <ul id="files" ></ul>
                <input type="Hidden" id="hotel_upload_url" name="hotel_upload_url">
              </div>(Upload Maximum 3 images)
          </div>
      </div>
      <div class="row mg_tp_10">  
          <div class="col-sm-6">  
            <span style="color: red;" class="note">Note : Image size should be less than 100KB, resolution : 900X450.</span>
          </div>
      </div>
			<div class="row mg_tp_20 text-center">
				<div class="col-md-12">
					<button class="btn btn-sm btn-success" id="btn_save"><i class="fa fa-floppy-o"></i>&nbsp;&nbsp;Save</button>
        </div>    
			</div>

      <input type="hidden" name="hotel_image_path" id="hotel_image_path">
	  </form>
  </div>
  </div>
  </div>
</div>

<script src="<?= BASE_URL ?>js/ajaxupload.3.5.js"></script>
<script src="<?= BASE_URL ?>js/app/field_validation.js"></script>
<script>
$('#save_modal').modal('show');
$('#as_of_date').datetimepicker({ timepicker:false, format:'d-m-Y' });
$("#state").select2();
$("#cmb_city_id").select2({minimumInputLength: 1});

///////////////////////***Hotel Master Save start*********//////////////
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
          }
          img_array.push(response);
        }
        if(img_array.length>3){
          error_msg_alert("You can upload only 3 images"); return false;
        }
          $("#hotel_image_path").val(img_array); 
      }
    });
}

$(function(){
  $('#frm_hotel_save').validate({
    rules:{
            cmb_city_id : { required: true },
            txt_hotel_name : { required: true },
            rating_star :  { required : true },
            as_of_date : { required : true },
            txt_hotel_address : { required : true },
    },
    submitHandler:function(form){
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
      var account_name = $("#account_name").val();
      var opening_balance = $('#opening_balance').val();
      var rating_star = $('#rating_star').val();
      var active_flag = $('#active_flag').val();
      var service_tax_no = $('#service_tax_no').val();
      var state = $('#state').val();
      var side = $('#side').val();
      var supp_pan = $('#supp_pan').val();
      var hotel_image_path = $('#hotel_image_path').val();
      var as_of_date = $('#as_of_date').val();
      var description = $('#description').val();
      var policies = $('#policies').val();
      var amenities = (function() {  var a = ''; $("input[name='amenities']:checked").each(function() { a += this.value+','; });  return a; })();
      amenities = amenities.slice(0,-1);

      var add = validate_address('txt_hotel_address');
      if(!add){
        error_msg_alert('More than 155 characters are not allowed.');
        return false;
      }
      $('#btn_save').button('loading');
      $.post( 
            base_url+"controller/hotel/hotel_master_save_c.php",
            { city_id : city_id, hotel_name : hotel_name, mobile_no : mobile_no, landline_no : landline_no, email_id : email_id, contact_person_name : contact_person_name, immergency_contact_no : immergency_contact_no, hotel_address : hotel_address, country : country, website :website,  opening_balance : opening_balance,rating_star : rating_star, active_flag : active_flag, bank_name : bank_name, account_no: account_no, branch : branch, ifsc_code :ifsc_code, service_tax_no : service_tax_no, state : state,side :side ,account_name : account_name ,supp_pan : supp_pan,hotel_image_path : hotel_image_path,as_of_date : as_of_date,description:description,policies:policies,amenities:amenities},

            function(data){ 
                var msg = data.split('--');
                var result_arr = data.split('==');
                var error_arr = data.split('--');
                if(msg[0]=="error"){
                  msg_alert(data); 
                  $('#btn_save').button('reset');
                }
                else{
                  var client_modal_type = $('#client_modal_type').val();
                  if(client_modal_type=="master"){
                    list_reflect();
                  }
                  else{
                    if(error_arr.length==1){
                      hotel_dropdown_reload(result_arr[1]);  
                    }
                  }
                  msg_alert(data);
                  $('#btn_save').button('reset');
                  $('#save_modal').modal('hide');
                  list_reflect();
                }
            });
    }
  });
});

///////////////////////***Hotel Master Save ens*********//////////////
</script>
<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>