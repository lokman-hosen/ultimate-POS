/*
 * Business registration form (business.partials.register_form).
 * Used by the public sign-up wizard and the superadmin "add business" page.
 * Every check here is repeated on the server (BusinessUtil::registrationValidation, App\Rules\SpanishTaxId).
 */
(function($) {
    var cfg = window.BUSINESS_REGISTER || {};
    var lang = cfg.lang || {};

    // ---------- Localized default messages for jQuery Validate ----------
    if ($.validator) {
        $.extend($.validator.messages, {
            required: lang.required,
            email: lang.email,
            minlength: $.validator.format(lang.minlength),
            equalTo: lang.equal_to,
        });
    }

    // ---------- Spanish DNI / NIE / CIF (pattern + control letter/digit) ----------
    var TAX_PATTERNS = {
        DNI: /^[0-9]{8}[A-Z]$/,
        NIE: /^[XYZ][0-9]{7}[A-Z]$/,
        CIF: /^[ABCDEFGHJNPQRSUVW][0-9]{7}[0-9A-J]$/,
    };
    var DNI_LETTERS = 'TRWAGMYFPDXBNJZSQVHLCKE';

    function isValidTaxId(type, value) {
        value = (value || '').toUpperCase();
        if (!TAX_PATTERNS[type] || !TAX_PATTERNS[type].test(value)) {
            return false;
        }
        if (type === 'DNI') {
            return DNI_LETTERS[parseInt(value.substr(0, 8), 10) % 23] === value[8];
        }
        if (type === 'NIE') {
            var number = { X: '0', Y: '1', Z: '2' }[value[0]] + value.substr(1, 7);
            return DNI_LETTERS[parseInt(number, 10) % 23] === value[8];
        }

        var sum = 0;
        for (var i = 0; i < 7; i++) {
            var n = parseInt(value[i + 1], 10);
            if (i % 2 === 0) {
                n *= 2;
                n = Math.floor(n / 10) + (n % 10);
            }
            sum += n;
        }
        var controlDigit = (10 - (sum % 10)) % 10;
        var controlLetter = 'JABCDEFGHI'[controlDigit];
        var control = value[8];
        if ('NPQRSW'.indexOf(value[0]) !== -1) {
            return control === controlLetter;
        }
        if ('ABEH'.indexOf(value[0]) !== -1) {
            return control === String(controlDigit);
        }
        return control === String(controlDigit) || control === controlLetter;
    }

    function formatMessage(message, replacements) {
        $.each(replacements, function(key, value) {
            message = message.replace(':' + key, value);
        });
        return message;
    }

    function isValidPhone(prefix, number) {
        number = (number || '').replace(/[\s\-.()]/g, '');
        return prefix === '+34' ? /^[0-9]{9}$/.test(number) : /^[0-9]{6,14}$/.test(number);
    }

    if ($.validator) {
        $.validator.addMethod('spanishTaxId', function(value, element) {
            var type = $($(element).data('type-field')).val();
            return this.optional(element) || isValidTaxId(type, value);
        }, function(params, element) {
            var type = $($(element).data('type-field')).val();
            return formatMessage(lang.invalid_document, { type: type, example: (cfg.tax_examples || {})[type] || '' });
        });

        $.validator.addMethod('phoneNumber', function(value, element) {
            return this.optional(element) || isValidPhone($($(element).data('prefix-field')).val(), value);
        }, lang.phone_invalid);

        // Accepts a domain without protocol (example.es); the server adds http://
        $.validator.addMethod('websiteUrl', function(value, element) {
            if (this.optional(element)) {
                return true;
            }
            value = $.trim(value);
            if (!/^[a-z][a-z0-9+.\-]*:\/\//i.test(value)) {
                value = 'http://' + value;
            }
            return $.validator.methods.url.call(this, value, element);
        }, lang.website_invalid);

        $.validator.addMethod('postalCode', function(value, element) {
            return this.optional(element) || /^[0-9]{5}$/.test(value);
        }, lang.postal_code_invalid);

        $.validator.addMethod('postalCodeProvince', function(value, element) {
            var province = $('#province_code').val();
            return this.optional(element) || !province || value.substr(0, 2) === province;
        }, lang.postal_code_province_mismatch);
    }

    // ---------- Address: Community -> Province -> Municipality (INE codes) ----------
    var ineData = null;

    function fillSelect($select, placeholder, items, selected) {
        $select.empty().append($('<option>', { value: '', text: placeholder }));
        $.each(items, function(i, item) {
            $select.append($('<option>', { value: item[0], text: item[1] }));
        });
        if (selected && $select.find('option[value="' + selected + '"]').length) {
            $select.val(selected);
        }
    }

    function findCommunity(code) {
        return $.grep(ineData || [], function(c) { return c.c === code; })[0];
    }

    function refreshProvinces(selected) {
        var community = findCommunity($('#community_code').val());
        var provinces = community ? $.map(community.p, function(p) { return [[p.c, p.n]]; }) : [];
        fillSelect($('#province_code'), cfg.select_province, provinces, selected);
    }

    function refreshMunicipalities(selected) {
        var community = findCommunity($('#community_code').val());
        var provinceCode = $('#province_code').val();
        var province = community ? $.grep(community.p, function(p) { return p.c === provinceCode; })[0] : null;
        fillSelect($('#municipality_code'), cfg.select_municipality, province ? province.m : [], selected);
    }

    function initAddress() {
        var $community = $('#community_code');
        if (!$community.length) {
            return;
        }

        $.getJSON(cfg.ine_data_url, function(data) {
            ineData = data;
            refreshProvinces($('#province_code').data('old'));
            refreshMunicipalities($('#municipality_code').data('old'));
        });

        $community.on('change', function() {
            refreshProvinces();
            refreshMunicipalities();
        });
        $('#province_code').on('change', function() {
            refreshMunicipalities();
            var $zip = $('#zip_code');
            if ($zip.val()) {
                $zip.valid();
            }
        });
    }

    // ---------- Business type: document types, company-only sections ----------
    function setTaxLabelOptions(type) {
        var $select = $('#tax_label_1');
        if (!$select.length) {
            return;
        }
        var options = type === 'company' ? ['CIF'] : ['DNI', 'NIE'];
        var current = $select.val();
        $select.empty();
        $.each(options, function(i, option) {
            $select.append($('<option>', { value: option, text: option }));
        });
        if ($.inArray(current, options) !== -1) {
            $select.val(current);
        }
        $select.trigger('change');
    }

    function toggleBusinessType() {
        var type = $('#business_type').val();
        var isCompany = type === 'company';

        // Hidden sections are disabled so they are neither validated nor submitted
        $('.company-only').toggle(isCompany).find(':input').prop('disabled', !isCompany);
        setTaxLabelOptions(type);
    }

    function updateTaxHints() {
        $('.tax-id-hint').each(function() {
            var type = $($(this).data('for')).val();
            var example = (cfg.tax_examples || {})[type];
            $(this).text(example ? formatMessage(lang.example, { example: example }) : '');
        });
        // Placeholder shows an example for the selected document type
        $('.spanish-tax-id').each(function() {
            var example = (cfg.tax_examples || {})[$($(this).data('type-field')).val()];
            $(this).attr('placeholder', example || '');
        });
    }

    // ---------- Main activity: "Other" shows a text field ----------
    function toggleActivityOther() {
        var isOther = $('#business_sector').val() === 'other';
        $('.business-activity-other').toggle(isOther).find(':input').prop('disabled', !isOther);
    }

    // ---------- WhatsApp same as contact number ----------
    function syncWhatsapp() {
        var same = $('#whatsapp_same_as_mobile').is(':checked');
        var $number = $('#whatsapp_number');
        var $prefix = $('#whatsapp_prefix');
        if (same) {
            $number.val($('#mobile').val());
            $prefix.val($('#mobile_prefix').val());
        }
        $number.prop('readonly', same);
        $prefix.prop('disabled', same);
        if (same) {
            $number.removeClass('error');
            $('#whatsapp_number-error').remove();
        }
    }

    function addRules($form) {
        if (!$form.data('validator')) {
            return;
        }
        var add = function(selector, rules) {
            $form.find(selector).each(function() {
                $(this).rules('add', rules);
            });
        };
        add('.spanish-tax-id', { spanishTaxId: true });
        add('.phone-number', { phoneNumber: true });
        add('#website', { websiteUrl: true });
        add('#zip_code', { postalCode: true, postalCodeProvince: true });
    }

    $(document).ready(function() {
        var $form = $('form#business_register_form');
        if (!$form.length) {
            return;
        }

        // Upper-case document numbers while typing
        $form.on('input', '.spanish-tax-id', function() {
            var pos = this.selectionStart;
            this.value = this.value.toUpperCase().replace(/[\s\-.]/g, '');
            if (pos !== null) {
                this.setSelectionRange(pos, pos);
            }
        });
        // Re-check the number when its document type changes
        $form.on('change', '#tax_label_1, #tax_label_2', function() {
            updateTaxHints();
            var $number = $form.find('.spanish-tax-id[data-type-field="#' + this.id + '"]');
            if ($number.val()) {
                $number.valid();
            }
        });
        $form.on('change', '.phone-prefix', function() {
            var $number = $form.find('.phone-number[data-prefix-field="#' + this.id + '"]');
            if ($number.val()) {
                $number.valid();
            }
        });

        $('#business_type').on('change', toggleBusinessType);
        $('#business_sector').on('change', toggleActivityOther);
        $('#whatsapp_same_as_mobile').on('change', syncWhatsapp);
        $('#mobile, #mobile_prefix').on('input change', function() {
            if ($('#whatsapp_same_as_mobile').is(':checked')) {
                syncWhatsapp();
            }
        });

        // Owner: same as contact person
        $('#same_as_rep').on('change', function() {
            if (!$(this).is(':checked')) {
                return;
            }
            var fullName = $.trim($('#contact_person').val() || '');
            if (fullName) {
                var parts = fullName.split(/\s+/);
                $('#first_name').val(parts[0] || '');
                $('#last_name').val(parts.slice(1).join(' '));
            }
            if ($('#contact_email').val()) {
                $('#email').val($('#contact_email').val());
            }
        });

        toggleBusinessType();
        toggleActivityOther();
        syncWhatsapp();
        updateTaxHints();
        initAddress();

        // Rules are added once the page scripts have initialised jQuery Validate
        setTimeout(function() {
            addRules($form);

            // Server-side errors on the Owner step: open that step directly
            if ($form.hasClass('wizard') && $form.find('.body:eq(1) label.error').length && !$form.find('.body:eq(0) label.error').length) {
                $form.steps('next');
            }
        }, 0);
    });
})(jQuery);
