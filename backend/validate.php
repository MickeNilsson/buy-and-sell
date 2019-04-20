<?php

function validate($data_am) {
    $validationErrors_as = [];
    // Validate ad text. It must contain 1 - 500 characters.
    if(!array_key_exists('ad', $data_am) ||
       strlen($data_am['ad']) < 1) {
        $validationErrors_as['ad'] = 'Du måste skriva en annonstext.';
    } elseif(strlen($data_am['ad']) > 500) {
        $validationErrors_as['ad'] = 'Du får skriva högst 500 tecken.';
    }
    // Validate category. It must be an integer and be between 1 - 31 inclusive.
    if(!array_key_exists('category', $data_am) ||
       !is_int($data_am['category']) ||
       $data_am['category'] < 1 ||
       $data_am['category'] > 31) {
        $validationErrors_as['category'] = 'Du måste ange en kategori.';
    }
    // Validate county. It must be an integer and be between 1 - 23 inclusive.
    if(!array_key_exists('county', $data_am) ||
       !is_int($data_am['county']) ||
       $data_am['county'] < 1 ||
       $data_am['county'] > 23) {
        $validationErrors_as['county'] = 'Du måste ange en kommun.';
    }
    // Validate email.
    if(!array_key_exists('email', $data_am) ||
       !filter_var($data_am['email'], FILTER_VALIDATE_EMAIL)) {
        $validationErrors_as['email'] = 'Du måste ange en korrekt e-postadress.';
    }
    // Validate heading. It must contain 1 - 50 characters inclusive.
    if(!array_key_exists('heading', $data_am) ||
       strlen($data_am['heading']) < 1) {
        $validationErrors_as['heading'] = 'Du måste skriva en rubrik.';
    } elseif(strlen($data_am['heading']) > 50) {
        $validationErrors_as['heading'] = 'Du får skriva högst 50 tecken.';
    }
    // Validate price. It must be an integer.
    if(!array_key_exists('price', $data_am) ||
       !is_int($data_am['price'])) {
        $validationErrors_as['price'] = 'Du måste ange ett pris.';
    }
    return $validationErrors_as;
}

?>