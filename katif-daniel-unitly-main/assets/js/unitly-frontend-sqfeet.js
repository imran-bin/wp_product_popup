(function ($) {
  var width_box = $("#product_width");
  var height_box = $("#product_height");

  var unit_amount_input = $("#unitly_unit_amount");

  var total_cost_box = $("#unitly_total_cost span");

  var calc_area_box = $("#calc_area span");
  var product_paint = $("#product_painting");
  var total_cost_input = $('input[name="total_cost"]');
  var quantity_box = $('input[name="quantity"]');
  // var add_to_cart_btn = $('input[name="add-to-cart"]');

  var price_per_unit = $("#price_per_unit").val();

  var area = 0;
  var total_cost = 0;
  var add_paint = 0;
  var count = 0;

  var unit_type = "";

  function calculate_total_cost() {
    var productWidth = width_box.val();
    var productHeight = height_box.val();
    var unit_amount = $("#unitly_unit_amount").val();

    var quantity = quantity_box.val();

    area = (productHeight / 12) * (productWidth / 12);
    var area = area.toFixed(2);
    calc_area_box.text(area);

    if (0 != add_paint) {
      total_cost = quantity * (area * price_per_unit + add_paint);
    } else {
      total_cost = quantity * area * price_per_unit;
    }

    total_cost = total_cost.toFixed(2);
    total_cost_box.text(total_cost);
    total_cost_input.val(total_cost);
  }

  /**
   * Send the "total_cost" value to "Modify_Cart_Item.php" via ajax
   */
  // function send_ajax_data(){
  //     $.ajax({
  //         url: UnitlyWoo_ajax_obj.ajaxurl,
  //         data: {
  //             'action' : 'add_cart_item_data',
  //             'total_cost' : quantity * area * price_per_unit,
  //             'add_paint' : UnitlyWoo_ajax_obj.add_paint
  //         },
  //         success:function(data) {
  //             console.log(data);
  //         },
  //         error: function(errorThrown){
  //             console.log(errorThrown);
  //         }
  //     });
  // }

  /**
   * Call the function calculate_cost() on different browser events
   */
  product_paint.on("click", function (e) {
    count++;
    if (count % 2 == 0) {
      add_paint = 0;
    } else {
      add_paint = painting_cost;
    }
    calculate_total_cost();
    console.log(count);
  });

  unit_amount_input.on("keyup", calculate_total_cost);
  width_box.on("keyup", calculate_total_cost);
  height_box.on("keyup", calculate_total_cost);

  quantity_box.on({
    keyup: function () {
      calculate_total_cost();
    },
    click: function () {
      calculate_total_cost();
    },
  });

  // add_to_cart_btn.on("click", function(e){
  //     e.preventDefault();
  //     alert(1); // not working
  //     send_ajax_data();
  // });

  calculate_total_cost();
})(jQuery);
