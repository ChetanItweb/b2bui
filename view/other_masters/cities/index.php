<?php include "../../../model/model.php";?>
<div class="row text-right mg_tp_20"> <div class="col-md-12">
  <button class="btn btn-info btn-sm ico_left" onclick="generic_city_save_modal('master')" id="btn_city_save_modal"><i class="fa fa-plus"></i>&nbsp;&nbsp;City</button>
</div> </div>

<div id="div_list_content" class="loader_parent"></div>
<div id="div_city_list_update_modal"></div>

<script>
function list_reflect(){
  $('#div_list_content').append('<div class="loader"></div>');	
  $.post('cities/list_reflect.php', {}, function(data){
      $('#div_list_content').html(data);
  });
}
list_reflect();
function city_master_update_modal(city_id){
  $('#div_city_list_update_modal').load('cities/update_modal.php', { city_id : city_id }).hide().fadeIn(500);
}
</script>