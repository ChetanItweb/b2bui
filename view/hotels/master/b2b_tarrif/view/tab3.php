<?php
$sq_query = mysql_query("select * from hotel_contracted_tarrif where pricing_id='$pricing_id'");
?>
<div class="panel panel-default panel-body fieldset profile_background">
	<div class="tab-content">
	    <div role="tabpanel" class="tab-pane active" id="basic_information">
	     	<div class="row">
				<div class="col-md-12">
					<div class="profile_box main_block">
          <h3 class="editor_title">Hotel Black-Dated Rates</h3>
            <div class="table-responsive">
                <table class="table table-bordered no-marg">
                  <thead>
                      <tr class="table-heading-row">
                        <th>S_No.</th>
                        <th>Room_Category</th>
                        <th>Valid_From_Date</th>
                        <th>Valid_To_Date</th>
                        <th>Single_Bed</th>
                        <th>Double_Bed</th>
                        <th>Triple_Bed</th>
                        <th>Child_With_Bed</th>
                        <th>Child_Without_Bed</th>
                        <th>First_Child</th>
                        <th>Second_Child</th>
                        <th>Extra_Bed</th>
                        <th>Queen_Bed</th>
                        <th>King_Bed</th>
                        <th>Quad_Bed</th>
                        <th>Twin_Bed</th>
                        <th>Markup(%)</th>
                        <th>Markup_Cost</th>
                        <th>MealPlan</th>
                      </tr>
                  </thead>
                  <tbody>
                  <?php
                  $count=1;
                  while($row_query = mysql_fetch_assoc($sq_query)){ ?>
                    <tr>
                      <td><?= $count++ ?></td>
                      <td><?= $row_query['room_category'] ?></td>
                      <td><?= get_date_user($row_query['from_date']) ?></td>
                      <td><?= get_date_user($row_query['to_date']) ?></td>
                      <td><?= $row_query['single_bed'] ?></td>
                      <td><?= $row_query['double_bed'] ?></td>
                      <td><?= $row_query['triple_bed'] ?></td>
                      <td><?= $row_query['child_with_bed'] ?></td>
                      <td><?= $row_query['child_without_bed'] ?></td>
                      <td><?= $row_query['first_child'] ?></td>
                      <td><?= $row_query['second_child'] ?></td>
                      <td><?= $row_query['extra_bed'] ?></td>
                      <td><?= $row_query['queen_bed'] ?></td>
                      <td><?= $row_query['king_bed'] ?></td>
                      <td><?= $row_query['quad_bed'] ?></td>
                      <td><?= $row_query['twin_bed'] ?></td>
                      <td><?= $row_query['markup_per'] ?></td>
                      <td><?= $row_query['markup'] ?></td>
                      <td><?= $row_query['meal_plan'] ?></td>
                    </tr>
                  <?php } ?>
                  </tbody>
                </table>
              </div>
          </div>
        </div>
        </div>
      </div>
	</div>
</div>
