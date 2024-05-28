(function ($) {
  var unit_amount_input = $("#unitly_unit_amount");

  var total_cost_box = $("#unitly_total_cost span");

  var product_paint = $("#product_painting");
  var total_cost_input = $('input[name="total_cost"]');
  var quantity_box = $('input[name="quantity"]');
  // var add_to_cart_btn = $('input[name="add-to-cart"]');

  var price_per_unit = $("#price_per_unit").val();

  var min_units = parseFloat(unit_amount_input.attr("min"));
  var max_units = parseFloat(unit_amount_input.attr("max"));

  var total_cost = 0;

  function calculate_total_cost() {
    var unit_amount = $("#unitly_unit_amount").val();

    var quantity = quantity_box.val();

    if (unit_amount < min_units || unit_amount > max_units) {
      $(".unit_amount_error").text(
        "Unit amount should be between " + min_units + " and " + max_units
      );

      $(".single_add_to_cart_button").attr("disable", true).addClass("disable");
    } else {
      $(".unit_amount_error").text("");
      $(".single_add_to_cart_button")
        .attr("disable", false)
        .removeClass("disable");
    }

    total_cost = quantity * unit_amount * price_per_unit;

    total_cost = total_cost.toFixed(2);
    total_cost_box.text(total_cost);
    total_cost_input.val(total_cost);
  }

  /**
   * Call the function calculate_cost() on different browser events
   */
  unit_amount_input.on("keyup, click, change", calculate_total_cost);

  quantity_box.on("keyup, click, change", calculate_total_cost);

  calculate_total_cost();

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

  // add_to_cart_btn.on("click", function(e){
  //     e.preventDefault();
  //     alert(1); // not working
  //     send_ajax_data();
  // });
})(jQuery);

jQuery(document).ready(function($) {
  // Function to show popup with product details
  function showPopup(productName, productPrice, productId) {
      // Clear previous popup content
      $('#popup').remove();

      // Create new popup HTML with the product data
      var popupHtml = '<div id="popup" style="display: none;">' +
                          '<h2>' + productName + '</h2>' +
                          '<p>Price: $' + productPrice + '</p>' +
                          '<button class="add-to-cart" data-post-id="' + productId + '">Add to Cart</button>' +
                          '<button class="close-popup">Close</button>' +
                      '</div>';
      // Append the new popup HTML to the body
      $('body').append(popupHtml);

      // Show the pop-up
      $('#popup').addClass('active').show();
  }

  // Listen for clicks on elements with data-product-id attribute
  $('[data-post-id]').click(function(e) {
      e.preventDefault(); // Prevent default action

      var productId = $(this).data('post-id');
      
      // AJAX request to fetch product details
      $.ajax({
          url: my_ajax_object.ajax_url,
          type: 'post',
          data: {
              action: 'get_product_details',
              product_id: productId,
              security: my_ajax_object.ajax_nonce
          },
          success: function(response) {
              // Call function to show popup with product details
              showPopup(response.data.name, response.data.price, response.data.id);
          },
          error: function(xhr, status, error) {
              // Handle errors
              console.error(xhr.responseText);
          }
      });
  });

  // Close the pop-up when the close button is clicked
  $('body').on('click', '.close-popup', function() {
      $('#popup').removeClass('active').hide();
  });

  // Add to Cart functionality
  $('body').on('click', '.add-to-cart', function() {
      var productId = $(this).data('post-id');
      
      // AJAX request to add product to cart
      $.ajax({
          url: my_ajax_object.ajax_url,
          type: 'post',
          data: {
              action: 'add_to_cart',
              product_id: productId,
              security: my_ajax_object.ajax_nonce
          },
          success: function(response) {
              // Update data-counter attribute
              var currentCount = parseInt($('[data-counter]').attr('data-counter'));
              $('[data-counter]').attr('data-counter', currentCount + 1);
              $('[data-counter]').text(currentCount + 1); // Update text content to reflect the new value
          },
          error: function(xhr, status, error) {
              // Handle errors
              console.error(xhr.responseText);
          }
      });
  });
});


