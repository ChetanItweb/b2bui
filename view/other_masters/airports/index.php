<?php include "../../../model/model.php";?>
<div class="row text-right mg_tp_20">
	<div class="col-md-12">
		<button class="btn btn-info btn-sm ico_left" onclick="save_modal()" id="btn_save_modal"><i class="fa fa-plus"></i>&nbsp;&nbsp;Airport</button>
	</div>
</div>

<div id="div_modal"></div>
<div id="div_list" class="loader_parent"></div>
<script>
function save_modal(){
	$('#btn_save_modal').button('loading');
	$.post('airports/save_modal.php', {}, function(data){
		$('#btn_save_modal').button('reset');
		$('#div_modal').html(data);
	});
}
function list_reflect(){
	$('#div_list').append('<div class="loader"></div>');
	$.post('airports/list_reflect.php', {}, function(data){
		$('#div_list').html(data);
	});
}
list_reflect();
function update_modal(airport_id){
	$.post('airports/update_modal.php', { airport_id : airport_id }, function(data){
		$('#div_modal').html(data);
	});
}
</script>