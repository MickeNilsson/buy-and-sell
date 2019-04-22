(function ($) {
    "use strict";
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

    $("#show-send-message-modal-button").on("click", function (e) {
        $("#send-message-modal").modal("show");
    });

    $("#body").on("keypress", function (e) {
        //console.dir(e.keyCode || e.which);
        var body_o = $(this);
        if (body_o.val().length > 400) {
            body_o.val(body_o.val().substr(0, 400));
        }
        //console.log($('#ad').val().length);
    });

    $("#header").on("keypress", function (e) {
        //console.dir(e.keyCode || e.which);
        var header_o = $(this);
        if (header_o.val().length > 100) {
            header_o.val(header_o.val().substr(0, 100));
        }
        //console.log($('#ad').val().length);
    });

    $("#submit-ad").on("click", function () {

        var ad_o = validateAddAdForm();
        if (!ad_o) {
            return;
        }
        $("#loader").show();
        $("#block").show();
        $.ajax({
            type: "POST",
            url: "http://www.digizone.se/temp/buy-and-sell/backend/api/add-new-ad/",
            data: JSON.stringify(ad_o),
            //contentType: 'application/json; charset=utf-8',
            contentType: "text/plain",
            //dataType: 'json',
            success: function (response_o) {

                $("#loader").hide();
                $("#block").hide();
                if (response_o.status === "error") {
                    for (var key in response_o.description) {
                        if (response_o.description.hasOwnProperty(key)) {
                            if(response_o.description[key] === false) {
                                $("#" + key).addClass("border-danger");
                            }
                        }
                    }
                } else {
                    // $('#add-ad-form').addClass('collapse');
                    // $('#submit-ad').addClass('collapse');
                    // $('#success-text').removeClass('collapse');
                    $('#add-ad-form').hide();
                    $('#submit-ad').hide();
                    $('#success-text').show();
                    //$("#add-ad-modal").modal("hide");
                }
            },
            failure: function (errMsg) {
                alert(errMsg);
            }
        });
    });

    function validateAddAdForm() {
        var ad_o = {};
        ad_o.type = $('input[name="type"]:checked').val();
        var validationError_b = false;
        $(
            "#add-ad-form input, " + "#add-ad-form select, " + "#add-ad-form textarea"
        ).each(function () {
            if ($(this).prop("type") !== "radio") {
                var fieldId_s = $(this).prop("id");
                var fieldValue_m = validateField(fieldId_s);
                if (fieldValue_m === false) {
                    validationError_b = true;
                } else {
                    ad_o[fieldId_s] = fieldValue_m;
                }
            }
        });
        return validationError_b ? false : ad_o;
    }

    $("#add-ad-form").on("change keyup", function (e) {
        validateField(e.target.id);
    });

    function validateField(fieldId_s) {
        var fieldValue_m;
        switch (fieldId_s) {
            case "category":
            case "county":
                fieldValue_m = parseInt($("#" + fieldId_s + " option:selected").val());
                if (fieldValue_m === 0) {
                    fieldValue_m = false;
                }
                break;
            case "header":
                fieldValue_m = $("#header").val();
                if (fieldValue_m.length < 1 || fieldValue_m.length > 200) {
                    fieldValue_m = false;
                }
                break;
            case "body":
                fieldValue_m = $("#body").val();
                if (fieldValue_m.length < 1 || fieldValue_m.length > 400) {
                    fieldValue_m = false;
                }
                break;
            case "price":
                fieldValue_m = parseInt(
                    $("#price")
                        .val()
                        .replace(/\D/g, "")
                );
                if (isNaN(fieldValue_m)) {
                    fieldValue_m = false;
                }
                break;
            case "email":
                fieldValue_m = $("#email").val();
                if (!fieldValue_m.match(/(.+)@(.+){2,}\.(.+){2,}/)) {
                    fieldValue_m = false;
                }
                break;
            case "phone":
                fieldValue_m = $("#phone")
                    .val()
                    .substr(0, 20);
                break;
        }
        if (fieldValue_m === false) {
            $("#" + fieldId_s).addClass("border-danger");
        } else {
            $("#" + fieldId_s).removeClass("border-danger");
        }
        return fieldValue_m;
    }
})(jQuery);
