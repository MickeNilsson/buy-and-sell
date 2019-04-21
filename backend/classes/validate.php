<?php

class Validate {

    private $pdo_o;

    function __construct() {
    }

    public function validateAll($params_o) {
        
        $successfulValidation_m = true;
        $successfulValidation_m = $params_o->body = isset($params_o->body) ? $this->validateBody($params_o->body) : false;
        $successfulValidation_m = $params_o->category = isset($params_o->category) ? $this->validateCategory($params_o->category) : false;
        $successfulValidation_m = $params_o->county = isset($params_o->county) ? $this->validateCounty($params_o->county) : false;
        $successfulValidation_m = $params_o->header = isset($params_o->header) ? $this->validateHeader($params_o->header) : false;
        $successfulValidation_m = $params_o->price = isset($params_o->price) ? $this->validateHeader($params_o->price) : false;
        $successfulValidation_m = $params_o->type = isset($params_o->type) ? $this->validateType($params_o->type) : false;
        return $successfulValidation_m;
    }

    public function validateBody($body_m) {

        if(!is_string($body_m)) {
            return false;
        }
        $length_i = strlen($body_m);
        if($length_i < 1 || $length_i > 400) {
            return false;
        }
        return $body_m;
    }

    public function validateCategory($category_m) {

        if(!is_int($category_m)) {
            return false;
        }
        if($category_m < 1 || $category_m > 31) {
            return false;
        }
        return $category_m;
    }

    public function validateCounty($county_m) {

        if(!is_int($county_m)) {
            return false;
        }
        if($county_m < 1 || $county_m > 23) {
            return false;
        }
        return $county_m;
    }

    public function validateHeader($header_m) {

        if(!is_string($header_m)) {
            return false;
        }
        $length_i = strlen($header_m);
        if($length_i < 1 || $length_i > 200) {
            return false;
        }
        return $header_m;
    }

    public function validatePrice($price_m) {

        $price_m = intval($price_m);
        if(!is_int($price_m)) {
            return false;
        }
        if($price_m < 0) {
            return false;
        }
        return $price_m;
    }

    public function validateType($type_m) {

        if(!is_string($type_m)) {
            return false;
        }
        if($type_m !== 'buy' || $type_m !== 'sell') {
            return false;
        }
        return $type_m;
    }


}

?>