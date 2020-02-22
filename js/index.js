$(document).ready(function() {
    'use strict';

    var counties_a = [],
        invalidFields_o = {},
        invalidFile_b = true,
        latestSearchResult_a,
        popoverIsVisible = false;

    init();
    var terms_s = '<p>buyandsell.se bär inte ansvaret för annonsens innehåll.</p>'
                + '<p>buyandsell.se frånsäger sig ångerrätt\n\n (som är normalt sätt 14 dagar vid köp av varor eller tjänst via internet).</p>'
                + '<p>Olagliga varor eller tjänster som (vapen, alkohol, tobak, narkotika, pornografi, läkemedel) kommer raderas och polisanmälas.</p>'
                + '<p>Det är förbjudet att lägga upp i annonsen stötande eller kränkande för folkgrupper och/eller enskilda individer bilder eller text.</p>';
    var options = {
        animation: true,
        content: terms_s,
        html: true,
        placement: 'top'
    };

    $('#terms').popover(options);

    // Event listeners //////////////////////////////

    $(document).on('click', function(e) {

        var popoverIsVisible = $('#terms').attr('aria-describedby')
        if(popoverIsVisible) {
            $('#terms').popover('hide');
        }
    });

    $('#search-result').on('click', '.list-group-item', function(e) {

        e.preventDefault();
        console.dir($(this).prop('id'));
        console.dir(e.target.id);
        var chosenItemId_s = $(this).prop('id');
        $('#item-modal-header').data('item-id', chosenItemId_s);
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
                if(chosenItem_o.price === -1) {
                    $('#item-modal-price').parent().hide();
                } else {
                    $('#item-modal-price').parent().show();
                    $('#item-modal-price').text(chosenItem_o.price);
                }
                
                $('#item-modal-published').text(chosenItem_o.published);
                $('#item-modal-county').text(counties_a[chosenItem_o.county]);
                var type_s = '';
                switch(chosenItem_o.type) {
                    case 1: type_s = 'Säljes'; break;
                    case 2: type_s = 'Köpes'; break;
                    case 3: type_s = 'Uthyres'; break;
                }
                $('#item-modal-type').text(type_s);
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

        var itemId_s = $('#item-modal-header').data('item-id');
        if(!itemId_s) {
            itemId_s = qs('id');
        }
        var message_o = {
            message: $('#send-message-text').val(),
            itemId: parseInt(itemId_s)
        };
        $(this).prop('disabled', true);
        $.ajax({
            type: 'POST',
            url: 'http://www.digizone.se/buy-and-sell/api/message/',
            data: JSON.stringify(message_o),
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            success: function (response_o) {
              
                $('#send-message-text').parent().hide();
                $('#send-message-button').hide();
                console.dir(response_o);
                if(response_o.status === 'ok') {
                    $('#send-message-response')
                        .text('Ditt meddelande har nu skickats till annonsören.')
                        .show();
                } else {
                    $('#send-message-response')
                        .text('Något gick fel. Ditt meddelande har inte skickats till annonsören.')
                        .show();
                }
                
            },
            failure: function (errMsg) {
                alert(errMsg);
            }
        });
    });

    $('#show-send-message-modal-button').on('click', function () {

        $('#send-message-text')
            .val('')
            .parent().show();
        $('#send-message-button')
            .prop('disabled', false)    
            .show();
        $('#send-message-response').hide();
        $('#send-message-modal').modal('show');
    });

    $('#sort-dropdown .dropdown-item').on('click', sort);

    $('#terms').on('click', function(e) {

        e.stopPropagation();
        if(popoverIsVisible) {
            $('#terms').popover('hide');
        } else {
            $('#terms').popover('show');
        }
        popoverIsVisible = !popoverIsVisible;
    });
    
    $('#terms-checkbox').on('change', function() {

        if($(this).is(':checked')) {
            $('#post-new-ad-button').prop('disabled', false);
        } else {
            $('#post-new-ad-button').prop('disabled', true);
        }
    });

    $('#type').on('change', 'input[type=radio]', function(e) {

        $(this).removeClass('border-danger');
        console.log('type_s');
        console.dir(e.target);
        var type_i =  e.target.value;
        console.log(type_i);
        switch(type_i) {
            case '1':
                $('label[for="price"] > span').show();
                break;
            case '2':
                $('label[for="price"] > span').hide();
                $('#price').removeClass('border-danger');
                break;
            case '3':
                $('label[for="price"] > span').show();
        }
    });
    
    // Event handlers ///////////////////////////////

    function resetForm() {

        document.getElementById('post-new-ad-form').reset();
        $('#post-new-ad-button').prop('disabled', true);
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
                $('#navbarCollapse').removeClass('show');
                $('.navbar-toggler').attr('aria-expanded', 'false').addClass('collapsed');
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

    function sort(e) {

        var sortCriteria_s = e.target.text;
        $('#sort-dropdown-button').text(sortCriteria_s);
        var asc_i = 1, propName_s;
        switch(sortCriteria_s) {
            case 'Bokstavsordning':
                propName_s = 'header';
                break;
            case 'Pris':
                propName_s = 'price';
                break;
            case 'Senaste':
                propName_s = 'published';
                asc_i = -1;
        }
        latestSearchResult_a.sort(function(a, b) {

            var x = a[propName_s];
            var y = b[propName_s];
            if (x < y) {return -asc_i;}
            if (x > y) {return asc_i;}
            return 0;
        });
        displaySearchResult({queryResult: latestSearchResult_a});
    }

    function displaySearchResult(response_o) {

        var searchResult_s = '';
        for(var i = 0; i < response_o.queryResult.length; i++) {
            var item_o = response_o.queryResult[i];
            var type_s = '';
            switch(item_o.type) {
                case 1: type_s = 'Säljes'; break;
                case 2: type_s = 'Köpes'; break;
                case 3: type_s = 'Uthyres'; break;
            }
            var image_s = item_o.image === 'no image' ? '' : '<img style="max-height:100px;" src="./uploads/' + item_o.id + '.' + item_o.image + '" alt="" />';
            var item_s = '<a id="' + item_o.id + '" data-toggle="modal" data-target="#item-modal" href="#"'
                       + ' class="list-group-item list-group-item-action flex-column align-items-start">'
                       + '<div class="d-flex w-100 justify-content-between">'
                       + '<h5 class="mb-1">' + item_o.header + '</h5>'
                       + '<small>' + item_o.published + '</small>'
                       + '</div>'
                       + image_s
                       + (item_o.price !== -1 ? '<div>' + item_o.price + ' kr</div>' : '')
                       + '<small>' + counties_a[item_o.county] +'</small>'
                       + '<small style="float:right">' + type_s + '</small>'
                       + '</a>';
            searchResult_s += item_s;    
        }
        $('#search-result').html(searchResult_s);  
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
                    console.log(field_s);
                    if(field_s === 'type') {
                        fields_o[field_s] = $('input[name="type"]:checked').val();
                    } else {
                        fields_o[field_s] = fields_o[field_s].value
                    }   
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
            url: 'api/ad/',
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
                if($('label[for="price"] > span').is(':visible')) {
                    if(isNaN(parseInt(field_o.value))) {
                        field_o.value = '';
                        valid_b = false;
                    } else {
                        field_o.value = parseInt(field_o.value);
                    }
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

    function qs(key) {
        key = key.replace(/[*+?^$.\[\]{}()|\\\/]/g, "\\$&"); // escape RegEx meta chars
        var match = location.search.match(new RegExp("[?&]"+key+"=([^&]+)(&|$)"));
        return match && decodeURIComponent(match[1].replace(/\+/g, " "));
    }





















    
    
    
    
    $("#add-pic-button").on("click", function (e) {
        $("#add-pic-file-input").show();
        $(this).hide();
    });

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