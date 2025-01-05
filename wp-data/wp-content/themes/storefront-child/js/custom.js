jQuery(document).ready(function($) {
    $('#request_button').click(function() {
        var product_id = $(this).data('product-id');
        var button = $(this)

        $.ajax({
            url: myLocalizedData.ajaxurl,
            type: 'POST',
            data: {
                action: 'request_product',
                product_id: product_id,
                security: myLocalizedData.nonce
            },
            success: function(response) { alert('s');
                if(response.success) {
                    button.val('success sent!');
                    button.attr('enable', false)
                } else {
                    alert('שגיאה בשליחת הבקשה: ' + response.data);
                }
            },
            error: function(error) { alert('e');
                console.error("שגיאה בבקשה: ", error);
                alert('שגיאה בשליחת הבקשה');
            }
        });
    });
});
