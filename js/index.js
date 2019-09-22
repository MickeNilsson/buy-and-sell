$(document).ready(function() {
    'use strict';

    var invalidFields_o = {},
        invalidFile_b = true;

    // Event listeners //////////////////////////////

    $('#post-new-ad-form')
        .on('change keyup', validateField)
        .on('submit', postNewAd);
    
    $('#image-upload')
        .on('change', prepareFileUpload);
    
    // Event handlers ///////////////////////////////

    function prepareFileUpload(e) {
        
        var file_o = document.getElementById('image-upload').files[0];
        console.dir(file_o);
        if(file_o.type !== 'image/jpeg' && file_o.type !== 'image/png') {
            $('#filename').text('Filen måste vara av typen jpg eller png');
            invalidFile_b = true;
            return;
        }
        if(file_o.size > 10000000) {
            $('#filename').text('Filen måste vara mindre än 10MB stor');
            invalidFile_b = true;
            return;
        }
        invalidFile_b = false;
        $('#filename').text(file_o.name);
    }

    function postNewAd(e) {

        e.preventDefault();
        e.stopPropagation();
        var fieldIsValid_b,
            fields_o = {
                'body': null,
                'category': null,
                'county': null,
                'email': null,
                'header': null,
                'phone': null,
                'price': null,
                'type': null
            },
            field_s,
            formIsValid_b = true;
        for(field_s in fields_o) {
            if(fields_o.hasOwnProperty(field_s)) {
                fields_o[field_s] = document.getElementById(field_s);
                fieldIsValid_b = validateField(fields_o[field_s]);
                if(fieldIsValid_b) {
                    fields_o[field_s] = fields_o[field_s].value || $('input[name="type"]:checked').val();   
                } else {
                    formIsValid_b = false;
                }
            }
        }
        console.dir(fields_o);
        // if(!formIsValid_b) {
        //     return;
        // }
        if(!invalidFile_b) {
            var formData_o = new FormData();
            var imageToUpload_o = document.getElementById('image-upload').files[0];
            // If the user has added an image, upload it
            if(imageToUpload_o) {
                formData_o.set('image', imageToUpload_o);
                $.ajax({
                    url: './api/upload-image/index.php',
                    type: 'POST',
                    data: formData_o,
                    async: true,
                    cache: false,
                    contentType: false,
                    enctype: 'multipart/form-data',
                    processData: false,
                    success: function (response) {
                        console.dir(response);
                        $("#loader").hide();
                        $("#block").hide();
                    }
                });
            } else {
                $("#loader").hide();
                $("#block").hide();
            }
        }
        
        return;
        console.dir(response_o);
        $('#loader, #block').show();
        $.ajax({
            type: 'POST',
            url: './backend/api/add/index.php',
            data: JSON.stringify(fields_o),
            contentType: 'text/plain',
            success: function (response_o) {
                var formData_o = new FormData();
                var imageToUpload_o = document.getElementById('image-upload').files[0];
                // If the user has added an image, upload it
                if(imageToUpload_o) {
                    formData_o.set('image', imageToUpload_o);
                    $.ajax({
                        url: './api/upload-image/index.php',
                        type: 'POST',
                        data: formData_o,
                        async: true,
                        cache: false,
                        contentType: false,
                        enctype: 'multipart/form-data',
                        processData: false,
                        success: function (response) {
                            console.dir(response);
                            $("#loader").hide();
                            $("#block").hide();
                        }
                    });
                } else {
                    $("#loader").hide();
                    $("#block").hide();
                }
                
                console.dir(response_o);
                // if (response_o.status === "error") {
                //     for (var key in response_o.description) {
                //         if (response_o.description.hasOwnProperty(key)) {
                //             if (response_o.description[key] === false) {
                //                 $("#" + key).addClass("border-danger");
                //             }
                //         }
                //     }
                // } else {
                //     // $('#post-new-ad-form').addClass('collapse');
                //     // $('#submit-ad').addClass('collapse');
                //     // $('#success-text').removeClass('collapse');
                //     $("#post-new-ad-form").hide();
                //     $("#submit-ad").hide();
                //     $("#success-text").show();
                //     //$("#post-new-ad-modal").modal("hide");
                // }
            },
            failure: function (errMsg) {
                alert(errMsg);
            }
        });
    }

    function validateField(e) {
        var field_o = e.target || e;
        var valid_b = true;
        switch(field_o.id) {
            case 'body':
                if(field_o.value.length > 400) {
                    field_o.value = field_o.value.substr(0, 400);
                } else if(field_o.value.length === 0) {
                    valid_b = false;
                }
                break;
            case 'header':
                if(field_o.value.length > 50) {
                    field_o.value = field_o.value.substr(0, 50);
                } else if(field_o.value.length === 0) {
                    valid_b = false;
                }
                break;
            case 'price':
                if(field_o.value.length > 10) {
                    field_o.value = field_o.value.substr(0, 10);
                }
                if(isNaN(parseInt(field_o.value))) {
                    field_o.value = '';
                    valid_b = false;
                } else {
                    field_o.value = parseInt(field_o.value);
                }
                break;
            case 'category': case 'county': 
                if(field_o.value === '0') {
                    valid_b =  false;
                }
                break;
            case 'email': 
                valid_b = field_o.value.match(/(.+)@(.+){2,}\.(.+){2,}/);
                break;
            case 'phone':
                if(field_o.value.length > 20) {
                    field_o.value = field_o.value.substr(0, 20);
                }
                break;
            case 'sell': case 'buy': case 'rent-out': case 'type':
                var isChecked_b = $('input[name="type"]:checked').length;
                field_o.id = 'type';
                if(!isChecked_b) {
                    valid_b = false;
                }
                break;
        }
        if(valid_b) {
            setValid(field_o.id);
        } else {
            setInvalid(field_o.id);
        }
        return valid_b;
    }
        


    // Helper functions
    function setInvalid(field_s) {
        $('#' + field_s).addClass('invalid');
        invalidFields_o[field_s] = true;
    }

    function setValid(field_s) {
        $('#' + field_s).removeClass('invalid');
        delete invalidFields_o[field_s];
    }





















    $("#search-county .dropdown-item").on("click", function (e) {
        $("#search-county-button").text(e.target.text);
        $("#search-county-button").attr("data-county", e.target.dataset.county);
    });
    $("#choose-lan .dropdown-item").on("click", function (e) {
        $("#choose-lan-dropdown-button").text(e.target.text);
    });
    $("#search-category .dropdown-item").on("click", function (e) {
        $("#search-category-button").text(e.target.text);
        $("#search-category-button").attr(
            "data-category",
            e.target.dataset.category
        );
    });
    $("#search-buy-or-sell .dropdown-item").on("click", function (e) {
        $("#search-buy-or-sell-button").text(e.target.text);
        $("#search-buy-or-sell-button").attr(
            "data-buy-or-sell",
            e.target.dataset.type
        );
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

    $("#post-new-ad-modal").on("hidden.bs.modal", function () {
        resetForm();
    });

    



    $('#search-text').on('keypress', function(event_o) {
        event_o.stopPropagation();
        var code_i = event_o.keyCode || event_o.which;
        if(code_i === 13) {
            event_o.preventDefault();
            $("#search-button").click();
        }
    });

    $("#search-button").on("click", function (e) {
        var text_s = $("#search-text").val();
        var category_s = $("#search-category-button").attr("data-category");
        var county_s = $("#search-county-button").attr("data-county");
        var type_s = $("#search-buy-or-sell-button").attr("data-buy-or-sell");
        var search_o = {
            text: text_s,
            category: category_s,
            county: county_s,
            type: type_s
        };
        console.dir(search_o);
        $("#loader").show();
        $("#block").show();
        $.ajax({
            type: "POST",
            url: "http://www.digizone.se/buy-and-sell/backend/api/search/",
            data: JSON.stringify(search_o),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (response_o) {
                $("#loader").hide();
                $("#block").hide();
                console.dir(response_o);
            },
            failure: function (errMsg) {
                alert(errMsg);
            }
        });
    });

    

    function resetForm() {
        $("#success-text").hide();
        $("#post-new-ad-form").show();
        $("#submit-ad").show();
        $("#sell")
            .prop("checked", true)
            .removeClass("border-danger");
        $("#category")
            .prop("selectedIndex", 0)
            .removeClass("border-danger");
        $("#county")
            .prop("selectedIndex", 0)
            .removeClass("border-danger");
        $("#header")
            .val("")
            .removeClass("border-danger");
        $("#body")
            .val("")
            .removeClass("border-danger");
        $("#price")
            .val("")
            .removeClass("border-danger");
        $("#email")
            .val("")
            .removeClass("border-danger");
        $("#phone")
            .val("")
            .removeClass("border-danger");
    }    
});