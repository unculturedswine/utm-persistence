jQuery(document).ready(function($) {
    // Add domain row
    $('#utm-domains-wrapper').on('click', '.utm-add-domain', function(e) {
        e.preventDefault();
        var newRow = '<div class="utm-domain-row">' +
            '<input type="text" name="utm_domains[]" value="" class="regular-text" placeholder="example.com" />' +
            '<button type="button" class="button utm-remove-row">Remove</button>' +
            '</div>';
        $('.utm-domains-list').append(newRow);
    });
    
    // Add parameter row
    $('#utm-params-wrapper').on('click', '.utm-add-param', function(e) {
        e.preventDefault();
        var newRow = '<div class="utm-param-row">' +
            '<input type="text" name="utm_params[]" value="" class="regular-text" placeholder="utm_source" />' +
            '<button type="button" class="button utm-remove-row">Remove</button>' +
            '</div>';
        $('.utm-params-list').append(newRow);
    });
    
    // Remove row
    $(document).on('click', '.utm-remove-row', function(e) {
        e.preventDefault();
        $(this).closest('div[class*="-row"]').fadeOut(300, function() {
            $(this).remove();
        });
    });
    
    // Handle form submission
    $('#utm-persistence-settings-form').on('submit', function(e) {
        e.preventDefault();
        
        var domains = [];
        var params = [];
        
        // Collect domains
        $('input[name="utm_domains[]"]').each(function() {
            var value = $(this).val().trim();
            if (value) {
                domains.push(value);
            }
        });
        
        // Collect parameters
        $('input[name="utm_params[]"]').each(function() {
            var value = $(this).val().trim();
            if (value) {
                params.push(value);
            }
        });
        
        // Save via AJAX
        $.ajax({
            url: utmPersistenceAdmin.ajaxurl,
            type: 'POST',
            data: {
                action: 'utm_persistence_save_settings',
                nonce: utmPersistenceAdmin.nonce,
                domains: domains,
                params: params
            },
            success: function(response) {
                if (response.success) {
                    showNotice(response.data.message, 'success');
                } else {
                    showNotice('Error saving settings', 'error');
                }
            },
            error: function() {
                showNotice('Error saving settings', 'error');
            }
        });
    });
    
    // Show notice
    function showNotice(message, type) {
        var $notice = $('#utm-settings-notice');
        $notice.removeClass('notice-success notice-error').addClass('notice-' + type);
        $notice.find('p').text(message);
        $notice.fadeIn(300);
        
        setTimeout(function() {
            $notice.fadeOut(300);
        }, 3000);
    }
});