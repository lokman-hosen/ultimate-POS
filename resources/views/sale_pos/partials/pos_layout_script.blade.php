@php
    $pos_form_id = $form_id ?? 'add_pos_sell_form';
@endphp
<script>
(function() {
    // 1. Error Placement for POS Quantity Validation
    function setupPosValidatorErrorPlacement() {
        if (typeof $.validator !== 'undefined') {
            $.validator.setDefaults({
                errorPlacement: function(error, element) {
                    if (element.hasClass('pos_quantity')) {
                        var row = element.closest('tr.product_row');
                        var target = row.find('.pos-row-qty-error-target');
                        if (target.length) {
                            target.empty().append(error);
                            return;
                        }
                    }
                    error.insertAfter(element);
                }
            });
        }
        $('form#add_pos_sell_form, form#edit_pos_sell_form').each(function() {
            var validator = $(this).data('validator');
            if (validator) {
                validator.settings.errorPlacement = function(error, element) {
                    if (element.hasClass('pos_quantity')) {
                        var row = element.closest('tr.product_row');
                        var target = row.find('.pos-row-qty-error-target');
                        if (target.length) {
                            target.empty().append(error);
                            return;
                        }
                    }
                    error.insertAfter(element);
                };
            }
        });
    }

    // 2. Live Quantity Badge Updater for Product Cards
    var _badgeTimer = null;
    function updateProductCardBadges() {
        clearTimeout(_badgeTimer);
        _badgeTimer = setTimeout(function() {
            try {
                var cartQuantities = {};
                $('#pos_table tbody tr.product_row').each(function() {
                    var row = $(this);
                    var variationId = row.find('.row_variation_id').val();
                    var qtyInput = row.find('input.pos_quantity');
                    var qty = 0;
                    if (typeof __read_number === 'function') {
                        qty = __read_number(qtyInput);
                    } else {
                        qty = parseFloat(qtyInput.val()) || 0;
                    }

                    if (variationId) {
                        cartQuantities[variationId] = (cartQuantities[variationId] || 0) + qty;
                    }
                });

                $('div.product_box').each(function() {
                    var box = $(this);
                    var variationId = box.data('variation_id');
                    if (!variationId) return;

                    var inCartQty = cartQuantities[variationId] || 0;
                    var badge = box.find('.pos-card-qty-badge');

                    if (inCartQty > 0) {
                        var formattedQty = (inCartQty % 1 === 0) ? inCartQty.toString() : inCartQty.toFixed(2);
                        if (badge.length === 0) {
                            box.prepend('<span class="pos-card-qty-badge">' + formattedQty + '</span>');
                        } else {
                            if (badge.text() !== formattedQty) {
                                badge.text(formattedQty);
                            }
                        }
                        if (!box.hasClass('pos-card-in-cart')) {
                            box.addClass('pos-card-in-cart');
                        }
                    } else {
                        if (badge.length > 0) {
                            badge.remove();
                        }
                        if (box.hasClass('pos-card-in-cart')) {
                            box.removeClass('pos-card-in-cart');
                        }
                    }
                });
            } catch(e) {
                console.error("Error updating POS product badges:", e);
            }
        }, 30);
    }

    $(document).ready(function() {
        setupPosValidatorErrorPlacement();
        setTimeout(function() {
            setupPosValidatorErrorPlacement();
            updateProductCardBadges();
        }, 200);

        // Quantity input change / keyup handler
        $(document).on('input change keyup', 'input.pos_quantity', function(e) {
            var input = $(this);
            
            if (e.type === 'change') {
                var qty = 0;
                if (typeof __read_number === 'function') {
                    qty = __read_number(input);
                } else {
                    qty = parseFloat(input.val()) || 0;
                }
                if (qty <= 0) {
                    var row = input.closest('tr.product_row');
                    row.find('.pos_remove_row').trigger('click');
                    return;
                }
            }

            if (typeof input.valid === 'function') {
                var isValid = input.valid();
                if (isValid) {
                    var row = input.closest('tr.product_row');
                    row.find('.pos-row-qty-error-target').empty();
                }
            }
            updateProductCardBadges();
        });

        // Quantity stepper buttons handler
        $(document).on('click', '.quantity-up, .quantity-down', function() {
            var btn = $(this);
            var row = btn.closest('tr.product_row');
            var input = row.find('input.pos_quantity');
            setTimeout(function() {
                if (typeof input.valid === 'function') {
                    var isValid = input.valid();
                    if (isValid) {
                        row.find('.pos-row-qty-error-target').empty();
                    }
                }
                updateProductCardBadges();
            }, 50);
        });

        // Row removal listener
        $(document).on('click', '.pos_remove_row', function() {
            setTimeout(updateProductCardBadges, 60);
        });

        // Product selection listener
        $(document).on('click', 'div.product_box', function() {
            setTimeout(updateProductCardBadges, 100);
        });

        // Update badges when AJAX loads products or cart rows
        $(document).ajaxComplete(function(event, xhr, settings) {
            if (settings && settings.url && (
                settings.url.indexOf('get-product-suggestion') !== -1 ||
                settings.url.indexOf('get_featured_products') !== -1 ||
                settings.url.indexOf('get_product_row') !== -1
            )) {
                updateProductCardBadges();
            }
        });

        // Observe cart tbody changes (rows added / removed)
        var cartBody = document.querySelector('#pos_table tbody');
        if (cartBody && typeof MutationObserver !== 'undefined') {
            var cartObserver = new MutationObserver(function() {
                updateProductCardBadges();
            });
            cartObserver.observe(cartBody, { childList: true });
        }
    });
})();
</script>
<style>
.product_box {
    position: relative !important;
}
.pos-card-qty-badge {
    position: absolute !important;
    top: 4px !important;
    right: 4px !important;
    min-width: 21px !important;
    height: 21px !important;
    padding: 0 5px !important;
    border-radius: 9999px !important;
    background: #2563eb !important;
    color: #ffffff !important;
    font-size: 11px !important;
    font-weight: 700 !important;
    line-height: 1 !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    border: 2px solid #ffffff !important;
    box-shadow: 0 2px 6px rgba(37, 99, 235, 0.45) !important;
    z-index: 10 !important;
    pointer-events: none !important;
    animation: posBadgePop 0.18s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
}
.pos-card-in-cart {
    border-color: #93c5fd !important;
    box-shadow: 0 2px 8px rgba(37, 99, 235, 0.15) !important;
}
@keyframes posBadgePop {
    0% {
        transform: scale(0.5);
        opacity: 0.3;
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}
</style>
