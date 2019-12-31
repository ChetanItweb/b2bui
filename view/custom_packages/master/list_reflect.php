<?php

include "../../../model/model.php";

$dest_id = $_POST['dest_id'];

?>

<div class="row mg_tp_20"> <div class="col-md-12 no-pad"> <div class="table-responsive">
	
<table class="table" id="tbl_tour_list" style="margin: 20px 0 !important;">

	<thead>

		<tr class="table-heading-row">

			<th>S_No.</th>

			<th>Package_Name(Code)</th>

			<th>Days/Nights</th>

			<th>Clone</th>

			<th>View</th>

			<th>Edit</th>

		</tr>

	</thead>

	<tbody>

		<?php 

		$count = 0;

		$query = "select * from custom_package_master where 1 "; 

		if($dest_id != '') { 

			$query .= " and dest_id = '$dest_id'";

		}



		$sq_tours = mysql_query($query);

		while($row_tour = mysql_fetch_assoc($sq_tours)){
			$bg = ($row_tour['clone'] == 'yes') ? 'warning' : '';

			$status = $row_tour['status'];

			if($status == 'Inactive'){ $bg ='danger';}

			?>

			<tr class="<?php  echo $bg; ?>">

				<td><?= ++$count ?></td>

				<td><?= $row_tour['package_name'].'('.$row_tour['package_code'] .')' ?></td>

				<td><?= $row_tour['total_days'].'D/'.$row_tour['total_nights'].'N' ?></td>
				<td><button class="btn btn-warning btn-sm" onclick="package_clone(<?= $row_tour['package_id'] ?>)" title="Copy"><i class="fa fa-files-o"></i></button></td>

				<td>

					<button class="btn btn-info btn-sm" onclick="view_modal(<?= $row_tour['package_id'] ?>)" title="View Information"><i class="fa fa-eye"></i></button>

				</td>

				<td>

					<button class="btn btn-info btn-sm" id="update_btn<?= $row_tour['package_id'] ?>" onclick="update_modal(<?= $row_tour['package_id'] ?>)"  title="Edit Package"><i class="fa fa-pencil-square-o"></i></button>

				</td>

			</tr>

			<?php

		}

		?>

	</tbody>

</table>

</div> </div> </div>

<script>

$('#tbl_tour_list').dataTable({

		"pagingType": "full_numbers"

	});

</script>

<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>