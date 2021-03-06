<?php
include "../../../../model/model.php";
include_once('../../../layouts/fullwidth_app_header.php'); 

?>
<div class="bk_tab_head bg_light">
    <ul> 
        <li>
            <a href="javascript:void(0)" id="tab_1_head" class="active">
                <span class="num" title="Packaqge Information">1<i class="fa fa-check"></i></span><br>
                <span class="text">Package Information</span>
            </a>
        </li>
        <li>
            <a href="javascript:void(0)" id="tab_2_head">
                <span class="num" title="hotel_images">2<i class="fa fa-check"></i></span><br>
                <span class="text">Hotel Images</span>
            </a>
        </li>    
        <li>
            <a href="javascript:void(0)" id="tab_3_head">
                <span class="num" title="Gallary">3<i class="fa fa-check"></i></span><br>
                <span class="text">Sightseeing Gallery</span>
            </a>
        </li>               
    </ul>
</div>

<div class="bk_tabs">
        <div id="tab_1" class="bk_tab active">
            <?php include_once("save_modal.php"); ?>  
        </div>    
        <div id="tab_2" class="bk_tab">
             <?php include_once("hotel_image_modal.php"); ?>
        </div>
        <div id="tab_3" class="bk_tab">
             <?php include_once("gallary_modal.php"); ?>
        </div>
</div>
<script src="<?= BASE_URL ?>js/app/field_validation.js"></script>

<script>

function total_cost()
{
    var tour_cost = $('#tour_cost').val();
    var service_tax = $('#service_tax').val();
    var markup_cost = $('#markup_cost').val();
    var total_tour_cost = $('#total_tour_cost').val();

    if(tour_cost==""){ tour_cost = 0;}
    if(service_tax==""){service_tax = 0;}
    if(markup_cost==""){ markup_cost = 0;}
    if(total_tour_cost==""){total_tour_cost = 0;}

    var total = parseFloat(tour_cost) + parseFloat(markup_cost);

    var service_tax_amount = (parseFloat(total)/100) * parseFloat(service_tax);

    total_tour_cost = parseFloat(total) + parseFloat(service_tax_amount);

    $('#service_tax_subtotal').val(service_tax_amount.toFixed(2));

    $('#total_tour_cost').val(total_tour_cost);

    
}

function display_image(entry_id)
{
  $.post('display_image_modal.php', {entry_id : entry_id}, function(data){
    $('#div_modal').html(data);
  });
}
function incl_reflect(cmb_tour_type,offset='')
{
  var tour_type = $("#"+cmb_tour_type).val();
  alert(tour_type);
  var base_url = $("#base_url").val();
  $.post(base_url+'view/tours/master/inc/inclusion_reflect.php', {tour_type : tour_type,type:'package' }, function(data){
      alert(data);
        var incl_arr = JSON.parse(data);
        var incl_id = 'inclusions'+offset;
        var excl_id = 'exclusions'+offset;
        var $iframe = $('#'+incl_id+'-wysiwyg-iframe');
            $iframe.contents().find("body").html('');
          $iframe.ready(function() {
            $iframe.contents().find("body").append(incl_arr['includes']);
        });

        var $iframe1 = $('#'+excl_id+'-wysiwyg-iframe');
            $iframe1.contents().find("body").html('');
          $iframe1.ready(function() {
            $iframe1.contents().find("body").append(incl_arr['excludes']);
        });
    });
}
function get_transport_cost(transport_vehicle){
    var vehicle_id = $("#"+transport_vehicle).val();
    var offset = transport_vehicle.substring(12);
    $.post('get_transport_cost.php', {vehicle_id : vehicle_id}, function(data){
        $('#cost'+offset).val(data);
    });
}
</script>
<?php 
include_once('../../../layouts/fullwidth_app_footer.php');
?>