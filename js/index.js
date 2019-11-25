$(document).ready(function() {
    'use strict';

    var counties_a = [],
        invalidFields_o = {},
        invalidFile_b = true,
        latestSearchResult_a;

    init();

    // Event listeners //////////////////////////////

    $('#search-result').on('click', '.list-group-item', function(e) {

        e.preventDefault();
        console.dir($(this).prop('id'));
        console.dir(e.target.id);
        debugger;
        var chosenItemId_s = $(this).prop('id');
        var numOfItems_i = latestSearchResult_a.length;
        for(var i = 0; i < numOfItems_i; i++) {
            console.dir(latestSearchResult_a[i]);
            if(chosenItemId_s == latestSearchResult_a[i].id) {
                var chosenItem_o = latestSearchResult_a[i];
                console.dir(chosenItem_o);
                $('#item-modal-header').text(chosenItem_o.header);
                if(chosenItem_o.image !== 'no image') {
                    $('#item-modal-image').prop('src', './uploads/' + chosenItem_o.id + '.' + chosenItem_o.image);
                    $('#item-modal-image').show();
                } else {
                    $('#item-modal-image').hide();
                }
                $('#item-modal-price').text(chosenItem_o.price);
                $('#item-modal-published').text(chosenItem_o.published);
                $('#item-modal-county').text(counties_a[chosenItem_o.county]);
                $('#item-modal-body').text(chosenItem_o.body);
            }
        }
    });
    
    $('#image-upload')
        .on('change', validateFileUpload);

    $('#post-new-ad-form')
        .on('change keyup', validateField)
        .on('submit', postNewAd);

    $("#post-new-ad-modal")
        .on('show.bs.modal', resetForm);

    $('#search-button')
        .on('click', search);
    
    $('#search-type .dropdown-item,' +
      '#search-category .dropdown-item,' +
      '#search-county .dropdown-item')
        .on('click', function() {setDropdownButton($(this))});
    
    $('#send-message-button').on('click', function() {

        var message_o = {
            message: $('#send-message-text').val()
        };
        console.dir(message_o);
        $.ajax({
            type: 'POST',
            url: 'http://www.digizone.se/buy-and-sell/api/message/',
            data: JSON.stringify(message_o),
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            success: function (response_o) {
              
                console.dir(response_o);
            },
            failure: function (errMsg) {
                alert(errMsg);
            }
        });
    });

    $('#show-send-message-modal-button').on('click', function () {

        $('#send-message-modal').modal('show');
    });

    // $('#search-category .dropdown-item')
    //     .on('click', function() {set('category')});

    // $('#search-county .dropdown-item')
    //     .on('click', function() {set('county')});
    
    
    
    // Event handlers ///////////////////////////////

    function resetForm() {

        document.getElementById('post-new-ad-form').reset();
        var allFields_s = '#type, #category, #county, #header, #body, #price, #email, #phone';
        $(allFields_s).removeClass('border-danger');
        $('#filename').text('');
    }

    function search() {

        var search_o = {
                text:     $('#search-text').val(),
                category: $('#search-category-button').attr('value'),
                county:   $('#search-county-button').attr('value'),
                type:     $('#search-type-button').attr('value')
            };
        console.dir(search_o);
        $('#loader').show();
        $('#block').show();
        $.ajax({
            type: 'POST',
            url: 'http://www.digizone.se/buy-and-sell/api/search/',
            data: JSON.stringify(search_o),
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            success: function (response_o) {
                $('#loader').hide();
                $('#block').hide();
                $('#search-result').empty();
                window.scrollTo(0, 0);
                console.dir(response_o);
                latestSearchResult_a = response_o.queryResult;
                displaySearchResult(response_o);
            },
            failure: function (errMsg) {
                alert(errMsg);
            }
        });
    }

    function displaySearchResult(response_o) {

        for(var i = 0; i < response_o.queryResult.length; i++) {
            var item_o = response_o.queryResult[i];
            var image_s = item_o.image === 'no image' ? '' : '<img style="max-height:100px;" src="./uploads/' + item_o.id + '.' + item_o.image + '" alt="" />';
            var item_s = '<a id="' + item_o.id + '" data-toggle="modal" data-target="#item-modal" href="#"'
                       + ' class="list-group-item list-group-item-action flex-column align-items-start">'
                       + '<div class="d-flex w-100 justify-content-between">'
                       + '<h5 class="mb-1">' + item_o.header + '</h5>'
                       + '<small>' + item_o.published + '</small>'
                       + '</div>'
                       + image_s
                       + '<div>' + item_o.price + ' kr</div>'
                       + '<small>' + counties_a[item_o.county] + '</small>'
                       + '</a>';
            $('#search-result').append(item_s);
        }   
    }

    function setDropdownButton(menuItem) {

        menuItem.parent().prev()
            .text(menuItem.text())
            .attr('value', menuItem.attr('value'));
    }

    function validateFileUpload() {
        
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
        if(!formIsValid_b) {
            return;
        }
        $('#post-new-ad-button').prop('disabled', true);
        $('#close-new-ad-button').prop('disabled', true);
        var formData_o = new FormData();
        formData_o.set('body', fields_o.body);
        formData_o.set('category', fields_o.category);
        formData_o.set('county', fields_o.county);
        formData_o.set('email', fields_o.email);
        formData_o.set('header', fields_o.header);
        formData_o.set('phone', fields_o.phone);
        formData_o.set('price', fields_o.price);
        formData_o.set('type', fields_o.type);
        formData_o.set('image', document.getElementById('image-upload').files[0]);
        $.ajax({
            url: 'api/post-new-ad/',
            type: 'POST',
            enctype: 'multipart/form-data',
            data: formData_o,
            async: true,
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {

                if(response.status === 'success') {
                    $('#post-new-ad-button').hide();
                    $('#post-new-ad-form').hide();
                    $('#success-text').html('Din annons har nu blivit publicerad. Du hittar den <a href="http://www.digizone.se/buy-and-sell/?id=' + response.id + '">här</a>.');
                    $('#success-text').show();
                } else {
                    $('#post-new-ad-button').hide();
                    $('#post-new-ad-form').hide();
                    $('#success-text').html('Tyvärr så uppstod ett fel, så din annons är inte publicerad.');
                    $('#success-text').show();
                }
                
            },
            failure: function(response) {

            }
        });
    }

    // Helper functions ///////////////////////////////

    function init() {

        $.ajax({
            type: 'GET',
            url: 'http://www.digizone.se/buy-and-sell/api/counties/',
            success: function (response_a) {

                var numOfResponses_i = response_a.length;
                for(var i = 0; i < numOfResponses_i; i++) {
                    counties_a[response_a[i].id] = response_a[i].name;
                }
                console.dir(counties_a);
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
                if(field_o.value.length > 100) {
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
    
    function setInvalid(field_s) {

        $('#' + field_s).addClass('border-danger');
        invalidFields_o[field_s] = true;
    }

    function setValid(field_s) {
        
        $('#' + field_s).removeClass('border-danger');
        delete invalidFields_o[field_s];
    }





















    
    $("#choose-lan .dropdown-item").on("click", function (e) {
        $("#choose-lan-dropdown-button").text(e.target.text);
    });
    // $("#search-category .dropdown-item").on("click", function (e) {
    //     $("#search-category-button").text(e.target.text);
    //     $("#search-category-button").attr(
    //         "data-category",
    //         e.target.dataset.category
    //     );
    // });
    
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

    

    



    $('#search-text').on('keypress', function(event_o) {
        event_o.stopPropagation();
        var code_i = event_o.keyCode || event_o.which;
        if(code_i === 13) {
            event_o.preventDefault();
            $("#search-button").click();
        }
    });       
});