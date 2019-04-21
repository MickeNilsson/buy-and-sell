<?php

/**
 * Validate is a class that has methods for validating the input from the
 * buy-and-sell web site.
 * 
 * Example usage:
 * 
 * $validate_o = new Validate();
 * $validate_o->validateAll($input_o);
 * 
 * @author Mikael Nilsson <mikael@digizone.se>
 * @access public
 */
class Validate {

    function __construct() {
    }

    /**
     * Validate all input properties at once.
     * 
     * @param object $params_o Object that should contain the following properties:
     *                          
     *                          body - String (1 - 400 characters)
     *                          category - Integer (1 - 31)
     *                          county - Integer (1 - 23)
     *                          header - String (1 - 200 characters)
     *                          price - Integer (>= 0)
     *                          type - String ("buy" or "sell")
     * 
     * @return mixed Returns boolean false if any of the properties doesn't validate,
     *               otherwise returns true.
     */
    public function validateAll($params_o) {
        
        $params_o->body = isset($params_o->body) ? $this->validateBody($params_o->body) : false;
        $params_o->category = isset($params_o->category) ? $this->validateCategory($params_o->category) : false;
        $params_o->county = isset($params_o->county) ? $this->validateCounty($params_o->county) : false;
        $params_o->header = isset($params_o->header) ? $this->validateHeader($params_o->header) : false;
        $params_o->price = isset($params_o->price) ? $this->validatePrice($params_o->price) : false;
        $params_o->type = isset($params_o->type) ? $this->validateType($params_o->type) : false;
        foreach($params_o as $prop_m) {
            if($prop_m === false) {
                return false;
            }
        }
        return true;
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
        if($price_m < 1) {
            return false;
        }
        return $price_m;
    }

    public function validateType($type_m) {

        if(!is_string($type_m)) {
            return false;
        }
        if(!($type_m === 'buy' || $type_m === 'sell')) {
            return false;
        }
        return $type_m;
    }
}

?>