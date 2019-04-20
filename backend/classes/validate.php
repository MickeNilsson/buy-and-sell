<?php

class Validate {

    private $pdo_o;

    function __construct() {
    }

    public function validateAll($params_o) {
        
        $params_o->type = $this->validateType($params_o->type);
        $params_o->category = $this->validateCategory($params_o->category);
        $params_o->county = $this->validateCounty($params_o->county);
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


}

?>