<?php include_once("../../../model/model.php");?>
<div class="row mg_tp_20"> <div class="col-md-12 no-pad"> <div class="table-responsive">
<table class="table table-hover" id="tbl_list" style="margin: 20px 0 !important;">
	<thead>
		<tr class="table-heading-row">
			<th>S_No.</th>
			<th>City</th>
			<th>Airport</th>
			<th>Code</th>
			<th>Edit</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		$count = 0;
		$sq_airport = mysql_query("select * from airport_master");
		while($row_airport = mysql_fetch_assoc($sq_airport)){
			$bg = ($row_airport['flag']=="Inactive") ? "danger" : "";
			$sq_city = mysql_fetch_assoc(mysql_query("select * from city_master where city_id='$row_airport[city_id]'")); ?>
			<tr class="<?= $bg ?>">
				<td><?= ++$count ?></td>
				<td><?= $sq_city['city_name'] ?></td>
				<?php $row_airport_nam = clean($row_airport['airport_name']); ?>
				<td><?= $row_airport_nam ?></td>
				<td><?= strtoupper($row_airport['airport_code']) ?></td>
				<td>
					<button class="btn btn-info btn-sm" onclick="update_modal(<?= $row_airport['airport_id'] ?>)" title="Edit Airport"><i class="fa fa-pencil-square-o"></i></button>
				</td>
			</tr>
			<?php } ?>
	</tbody>
</table>
</div></div></div>
<script>
$('#tbl_list').dataTable({
	"pagingType": "full_numbers"
});
</script>