<?php
include "../../../../model/model.php";
?>

<div class="row text-right mg_bt_10">
    <div class="col-sm-6 text-left">
        <button class="btn btn-info btn-sm ico_left pull-left" style="margin-right:10px" onclick="display_format_modal();"><i class="fa fa-download" aria-hidden="true"></i>&nbsp;&nbsp;CSV Format</button>
        <div class="div-upload  mg_bt_20" id="div_upload_button">
              <div id="vendor_csv_upload" class="upload-button1"><span id="vendor_status1">CSV</span></div>
              <span id="vendor_status"></span>
              <ul id="files" ></ul>
              <input type="hidden" id="txt_vendor_csv_upload_dir" name="txt_vendor_csv_upload_dir">
        </div>
    </div>
    <div class="col-sm-6 text_left_sm_xs">
      <button class="btn btn-excel btn-sm" onclick="excel_report()" data-toggle="tooltip" title="Generate Excel"><i class="fa fa-file-excel-o"></i></button>&nbsp;&nbsp;
    	<button class="btn btn-info btn-sm ico_left" onclick="generic_city_save_modal()" id="btn_city_save_modal"><i class="fa fa-plus"></i>&nbsp;&nbsp;City</button>
    	&nbsp;&nbsp;
        <button class="btn btn-info btn-sm ico_left" id="btn_save_modal" onclick="save_modal()"><i class="fa fa-plus"></i>&nbsp;&nbsp;Hotel Supplier</button>
    </div>
</div>

<div class="app_panel_content Filter-panel">
    <div class="row">
        <div class="col-md-3 col-sm-6 mg_bt_10_xs">
            <select id="city_id_filter" name="city_id_filter" style="width:100%" title="Select City Name" onchange="list_reflect()">
                <?php get_cities_dropdown(); ?>
            </select>
        </div>
        <div class="col-md-3 col-sm-6">
            <select name="active_flag_filter" id="active_flag_filter" title="Active Flag" onchange="list_reflect()">
                <option value="">Active Flag</option>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>
        </div>
    </div>
</div>


<div id="div_modal_content"></div>
<div id="div_hotels_list" class="main_block loader_parent"></div>
<div id="div_view_modal"></div>
</div>
</div>
<script src="<?= BASE_URL ?>js/app/field_validation.js"></script>

<script src="<?= BASE_URL ?>js/ajaxupload.3.5.js"></script>
<script>
$('#city_id_filter').select2({minimumInputLength: 1});
vendor_csv_upload();
function vendor_csv_upload()
{   
    var type="id_proof";
    var btnUpload=$('#vendor_csv_upload');
    var status=$('#vendor_status');
    new AjaxUpload(btnUpload, {
      action: 'hotel/upload_vendor_csv_file.php',
      name: 'uploadfile',
      onSubmit: function(file, ext){

         if(!confirm('Do you want to import this file?')){
            return false;
          }else{
            status.text('Uploading...');
          }

         if (! (ext && /^(csv)$/.test(ext))){ 
                    // extension is not allowed 
         error_msg_alert('Only CSV files are allowed');
          return false;
        }
      },
      onComplete: function(file, response){
        //Add uploaded file to list
        if(response==="error"){          
          alert("File is not uploaded.");           
          //$('<li></li>').appendTo('#files').html('<img src="./uploads/'+file+'" alt="" /><br />'+file).addClass('success');
        } else{
          ///$('<li></li>').appendTo('#files').text(file).addClass('error');
          document.getElementById("txt_vendor_csv_upload_dir").value = response;
          vendor_form_csv_save();
          //On completion clear the status
          status.text('');
        }
      }
    });

}

function vendor_form_csv_save()
{
    var vendor_csv_dir = document.getElementById("txt_vendor_csv_upload_dir").value;

    var base_url = $('#base_url').val();
    var status=$('#vendor_status1');
    status.text('Uploading...');

    $.ajax({
        type:'post',
        url: base_url+'controller/hotel/vendor_csv_save.php',
        data:{ vendor_csv_dir : vendor_csv_dir },
        success:function(result){
            msg_alert(result);
            
            list_reflect();
            status.text('CSV');
        }
    });
}


function list_reflect()
{
  $('#div_hotels_list').append('<div class="loader"></div>');
	var active_flag = $('#active_flag_filter').val();
	var city_id = $('#city_id_filter').val();
	$.post('hotel/list_reflect.php', { active_flag : active_flag, city_id : city_id }, function(data){
		$('#div_hotels_list').html(data);
	});
}
list_reflect();

function save_modal()
{
	$('#btn_save_modal').button('loading');
	$.post('hotel/save_modal.php', {}, function(data){
		$('#btn_save_modal').button('reset');
		$('#div_modal_content').html(data);
	});
}

function update_modal(hotel_id)
{
	$.post('hotel/update_modal.php', {hotel_id : hotel_id}, function(data){
		$('#div_modal_content').html(data);
	});
}

function view_modal(hotel_id)
{
    $.post('hotel/view/index.php', {hotel_id : hotel_id}, function(data){
        $('#div_view_modal').html(data);
    });
}

function view_tarrif_modal(hotel_id)
{
    $.post('hotel/view_tarrif_modal.php', {hotel_id : hotel_id}, function(data){
        $('#div_view_modal').html(data);
    });
}

function display_format_modal()
{
    var base_url = $('#base_url').val();
    window.location = base_url+"images/csv_format/hotel_vendor.csv";
}

function excel_report()
{
    var active_flag = $('#active_flag_filter').val();
    var city_id = $('#city_id_filter').val();

    window.location = 'hotel/excel_report.php?active_flag='+active_flag+'&city_id='+city_id;
}

</script>
