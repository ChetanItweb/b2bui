<?php
include "../../../model/model.php";
/*======******Header******=======*/
require_once('../../layouts/admin_header.php');
?>
<?= begin_panel('Email CMS',11) ?>

  <div class="app_panel_content Filter-panel">
      <div class="row">
          <div class="col-md-3 col-sm-6 mg_bt_10_xs">
              <select id="email_type_filter" name="email_type_filter" style="width:100%" onchange="get_email_for_content(this.id)" title="Select Type">
                  <option value="">Select Type</option>
                  <option value="1">Transactional</option>
                  <option value="2">Reminder</option>
              </select>
          </div>
          <div class="col-md-3 col-sm-6 mg_bt_10_xs">
              <select id="email_for_filter" name="email_for_filter" style="width:100%" title="Select Email for">
                  <option value="">Select Email For</option>
              </select>
          </div>
          <div class="col-md-3 col-sm-6">
              <select name="active_flag_filter" id="active_flag_filter" title="Active Flag">
                  <option value="">Active Flag</option>
                  <option value="Active">Active</option>
                  <option value="Inactive">Inactive</option>
              </select>
          </div>
          <div class="col-md-3 col-sm-6 col-xs-12">
            <button class="btn btn-sm btn-info ico_right" onclick="list_reflect()">Proceed&nbsp;&nbsp;<i class="fa fa-arrow-right"></i></button>
          </div>
      </div>
  </div>


<div id="div_modal_sms"></div>
<div id="div_cms_list" class="main_block"></div>
</div>
</div>
<script src="<?= BASE_URL ?>js/app/field_validation.js"></script>
<script>
$('#email_type_filter,#email_for_filter').select2();
function list_reflect()
{
	var active_flag = $('#active_flag_filter').val();
	var email_type = $('#email_type_filter').val();
  var email_for = $('#email_for_filter').val();
	$.post('list_reflect.php', { active_flag : active_flag, email_type : email_type, email_for : email_for }, function(data){
		$('#div_cms_list').html(data);
	});
}
list_reflect();

function update_modal(entry_id)
{
	$.post('update_modal.php', {entry_id : entry_id}, function(data){
		$('#div_modal_sms').html(data);
	});
}
function get_email_for_content(type)
{
  var type = $('#'+type).val();
  $.post('get_email_for_content.php', {type : type}, function(data){
    $('#email_for_filter').html(data);
  });
}
</script>

<?php require_once('../../layouts/admin_footer.php'); ?>
