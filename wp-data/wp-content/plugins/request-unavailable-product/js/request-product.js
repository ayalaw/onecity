jQuery(document).ready(function($) {

    $('#request_button').click(function() {
        const product_id = $(this).data('product-id');
        const button = $(this);

        // Ensure `myLocalizedData` is available and contains necessary data
        if (typeof myLocalizedData === 'undefined' || !myLocalizedData.ajaxurl || !myLocalizedData.nonce) {
            alert('Localization data is missing.');
            return;
        }

        // Disable the button to prevent multiple clicks
        button.prop('disabled', true).text('Processing...');

        $.ajax({
            url: myLocalizedData.ajaxurl, // Set via wp_localize_script in PHP
            type: 'POST',
            data: {
                action: 'request_product',
                product_id: product_id,
                security: myLocalizedData.nonce // Security nonce
            },
            success: function(response) {
                console.log(response);
                if (response.success) {
                    button.text('Success sent!').prop('disabled', true);
                } else {
                    console.error('Error response:', response);
                    button.prop('disabled', false).text('Request Again');
                }
                showFloatingMessage(response.data.message);
            },
            error: function(xhr, status, error) {
                console.log('AJAX Error:', error);
                button.prop('disabled', false).text('Request Again');
                showFloatingMessage('Request failed');
            }
        });
    });


    /**
     * Show a floating message for a specific duration
     * @param {string} message - The message to display
     * @param {number} duration - Duration to display the message in milliseconds
     */
    function showFloatingMessage(message, duration = 3000) {
        const floatingMessage = $('#floating_message');
        floatingMessage.text(message).css('display', 'block');

        // Hide the message after the specified duration
        setTimeout(() => {
            floatingMessage.css('display', 'none');
        }, duration);
    }
});
