<?php

class DB {

    private $pdo_o;

    function __construct($settings_a) {

        $dsn_s = 'mysql:host=' . $settings_a['host'] . ';dbname=' . $settings_a['db'] . ';charset=' . $settings_a['charset'];
        $options_a = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false
        ];
        try {
             $this->pdo_o = new PDO($dsn_s, $settings_a['user'], $settings_a['password'], $options_a);
        } catch (\PDOException $error_o) {
             throw new \PDOException($error_o->getMessage(), (int)$error_o->getCode());
        }
    }

    public function add($params_o) {

        //$stmt_o = $this->pdo_o->prepare("SELECT * FROM `users` WHERE `lastname` = :lastname");
        $sql_s = "INSERT INTO ads(type, category, county, header, body, price, email, sha1) "
               . "VALUES ('$params_o->type', $params_o->category, $params_o->county, '$params_o->header', '$params_o->body', $params_o->price, '$params_o->email', '$params_o->sha1')";
        
        $stmt_o = $this->pdo_o->prepare($sql_s);
        // $stmt_o->execute([
        //     'lastname' => 'Nilsson'
        // ]);
        return $stmt_o->execute();
        // while ($row = $stmt_o->fetch()) {
        //     echo $row['firstname'] . "\n";
        // }
    }

    public function search($params_o) {
        $sql_s = "SELECT id FROM ads WHERE"
        $and_s = "";
        if($params_o->category !== '0') {
            $sql_s .= " category = $params_o->category";
            $and_s = " AND";
        }
        if($params_o->buyOrSell !== '3') {
            $sql_s .= $and_s . " type = $params_o->buyOrSell";
            $and_s = " AND";
        }
        if($params_o->county !== '0') {
            $sql_s .= $and_s . " county = $params_o->county";
            $and_s = " AND";
        }
        if($params_o->text !== '') {
            $sql_s .= $and_s . " body LIKE %$params_o->text%";
        }
        return $sql_s;
    }
} 

?>