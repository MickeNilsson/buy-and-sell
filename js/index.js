(function ($) {
    'use strict';
    $("#lan .dropdown-item").on("click", function (e) {
        $("#lanDropDownMenuButton").text(e.target.text);
    });
    $("#choose-lan .dropdown-item").on("click", function (e) {
        $("#choose-lan-dropdown-button").text(e.target.text);
    });
    $("#kategori .dropdown-item").on("click", function (e) {
        $("#kategoriDropDownMenuButton").text(e.target.text);
    });
    $("#buy-or-sell-dropdown .dropdown-item").on("click", function (e) {
        $("#buy-or-sell-dropdown-button").text(e.target.text);
    });
    $("#sort-dropdown .dropdown-item").on("click", function (e) {
        $("#sort-dropdown-button").text(e.target.text);
    });
    $("#add-pic-button").on("click", function (e) {
        $("#add-pic-file-input").show();
        $(this).hide();
    });
    /* $("#add-phone-button").on("click", function (e) {
        $("#add-phone-text-input").show();
        $(this).hide();
    }); */

    $("a").on("click", function (e) {
        e.preventDefault();
    });

    $('#show-send-message-modal-button').on('click', function(e) {
        $('#send-message-modal').modal('show');
    });

    $('#ad').on('keypress', function(e) {
        //console.dir(e.keyCode || e.which);
        var ad = $(this);
        if(ad.val().length > 200) {
            ad.val(ad.val().substr(0, 10));
        }
        //console.log($('#ad').val().length);
    });

    $('#submit-ad').on('click', function() {
        var ad_o = validateAddAdForm();
        console.dir(ad_o);
        if(!ad_o) {
            return;
        }
        $('#loader').show();
        $('#block').show();
        $.ajax({
            type: 'POST',
            url: 'http://www.digizone.se/temp/buy-and-sell/backend/add-ad.php',
            data: JSON.stringify(ad_o),
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            success: function(data) {
                console.dir(data);
                setTimeout(function() {
                    $('#loader').hide();
                    $('#block').hide();
                }, 2000);
            },
            failure: function(errMsg) {
                alert(errMsg);
            }
        });
    });

    function validateAddAdForm() {
        var ad_o = {};
        var validationError_b = false;
        $('#add-ad-form input, ' +
          '#add-ad-form select, ' +
          '#add-ad-form textarea').each(function() {
            if($(this).prop('type') !== 'radio') {
                var fieldId_s = $(this).prop('id');
                var fieldValue_m = validateField(fieldId_s);
                if(fieldValue_m === false) {
                    validationError_b = true;
                } else {
                    ad_o[fieldId_s] = fieldValue_m;
                }
            }
        });
        return validationError_b ? false : ad_o;
    }

    $('#add-ad-form').on('change keyup', function(e) {
        validateField(e.target.id);
    });

    function validateField(fieldId_s) {
        var fieldValue_m;
        switch(fieldId_s) {
            case 'category':
            case 'county':
                fieldValue_m = parseInt($('#' + fieldId_s + ' option:selected').val());
                if(fieldValue_m === 0) {
                    fieldValue_m = false;
                }
                break;
            case 'heading':
                fieldValue_m = $('#heading').val();
                if(fieldValue_m.length < 1 || fieldValue_m.length > 50) {
                    fieldValue_m = false;
                }
                break;
            case 'ad':
                fieldValue_m = $('#ad').val();
                if(fieldValue_m.length < 1 || fieldValue_m.length > 500) {
                    fieldValue_m = false;
                }
                break;
            case 'price':
                fieldValue_m = parseInt($('#price').val().replace(/\D/g,''));
                if(isNaN(fieldValue_m)) {
                    fieldValue_m = false;
                }
                break;
            case 'email':
                fieldValue_m = $('#email').val();
                if(!fieldValue_m.match(/(.+)@(.+){2,}\.(.+){2,}/)) {
                    fieldValue_m = false;
                }
                break;
            case 'phone':
                fieldValue_m = $('#phone').val().substr(0, 20);
                break;
        }
        if(fieldValue_m === false) {
            $('#' + fieldId_s).addClass('border-danger');
        } else {
            $('#' + fieldId_s).removeClass('border-danger');
        }
        return fieldValue_m;
    }
})(jQuery);