
<div class="main_block mg_tp_20" id="images_list_div">

</div>        

<script type="text/javascript">

load_images(<?= $hotel_id ?>);

function load_images(hotel_names)

{

  //var hotel_names = $('#hotel_names').val();
  var base_url = $('#base_url').val();
    $.ajax({

          type:'post',

          url: base_url+'view/custom_packages/master/view/get_hotel_images.php',

          data:{hotel_name : hotel_names },

          success:function(result)

          {

            $('#images_list_div').html(result);

           /* var splitted = result.split(","); // RESULT

           

            $("#images_list").attr('src',splitted[1]); 

            $("#images_list1").attr('src',splitted[2]);

            $("#images_list2").attr('src',splitted[3]);*/

        

          }

  });

}

</script>  