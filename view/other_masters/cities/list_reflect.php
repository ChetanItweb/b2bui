<?php include "../../../model/model.php";?>
<div id="div_city_list">
  <div class="row mg_tp_20"> <div class="col-md-12 no-pad"> <div class="table-responsive">
      <table id="list_table" class="table table-hover" style="margin: 20px 0 !important;">
        <thead>
          <tr class="table-heading12 table-heading-row">
            <th>City_Id</th>
            <th>City</th>
            <th>Edit</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $count=0;
          $sq = mysql_query("select * from city_master");
          while($row=mysql_fetch_assoc($sq)){
            $count++;
            $bg = ($row['active_flag']=="Inactive") ? "danger" : "";
           ?>
           <tr class="<?= $bg ?>">
              <td><?php echo $row['city_id'] ?></td>
              <td><?php echo $row['city_name'] ?></td>
              <td>
                <a href="javascript:void(0)" onclick="city_master_update_modal(<?php echo $row['city_id'] ?>)" class="btn btn-info btn-sm" title="Edit city"><i class="fa fa-pencil-square-o"></i></a>
              </td>
           </tr>
           <?php } ?>
        </tbody>
      </table>
  </div></div></div>
</div>
<script>
$('#list_table').dataTable({
    "pagingType": "full_numbers"
});
</script>