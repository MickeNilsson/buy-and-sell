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

    /**
     * Adds a new row in the ads table
     * 
     * @param array $params_aa
     * 
     */
    public function add($params_aa) {

        //$stmt_o = $this->pdo_o->prepare("SELECT * FROM `users` WHERE `lastname` = :lastname");
        // $sql_s = "INSERT INTO ads(type, category, county, header, body, price, email, sha1) "
        //        . "VALUES ('$params_o->type', $params_o->category, $params_o->county, '$params_o->header', '$params_o->body', $params_o->price, '$params_o->email', '$params_o->sha1')";
        $sql_s = "INSERT INTO ads(type, category, county, header, body, price, email) "
               . "VALUES (:type, :category, :county, :header, :body, :price, :email)";
        $stmt_o = $this->pdo_o->prepare($sql_s);
        // $stmt_o->execute([
        //     'lastname' => 'Nilsson'
        // ]);
        return $stmt_o->execute($params_aa);
        // while ($row = $stmt_o->fetch()) {
        //     echo $row['firstname'] . "\n";
        // }
    }

    public function search($params_o) {
        $sql_s = "SELECT id, type, category, county, header, price FROM ads WHERE";
        $and_s = "";
        $placeholders_a = [];
        if($params_o->category !== '0') {
            $sql_s .= " category = :category";
            //$sql_s .= " category = $params_o->category";
            $and_s = " AND";
            $this->array_push_assoc($placeholders_a, 'category', $params_o->category);
        }
        if($params_o->type !== '3') {
            $sql_s .= $and_s . " type = :type";
            //$sql_s .= $and_s . " type = $params_o->type";
            $and_s = " AND";
            $this->array_push_assoc($placeholders_a, 'type', $params_o->type);
        }
        if($params_o->county !== '0') {
            $sql_s .= $and_s . " county = :county";
            //$sql_s .= $and_s . " county = $params_o->county";
            $and_s = " AND";
            $this->array_push_assoc($placeholders_a, 'county', $params_o->county);
        }
        if($params_o->text !== '') {
            $sql_s .= $and_s . " (body LIKE :body OR header LIKE :header)";
            //$sql_s .= $and_s . " body LIKE %$params_o->text%";
            $this->array_push_assoc($placeholders_a, 'body', "%$params_o->text%");
            $this->array_push_assoc($placeholders_a, 'header', "%$params_o->text%");
        }
        //$placeholders_a = ['body' => '%armborst%'];
        //return json_encode($placeholders_a, JSON_UNESCAPED_UNICODE);
        //return $sql_s;
        //$placeholders_a = ['body' => '%armborst%'];
        //$sql_s = "SELECT id FROM ads WHERE body LIKE :body";
        $stmt_o = $this->pdo_o->prepare($sql_s);
        $stmt_o->execute($placeholders_a);
        
        //$stmt_o->execute(['body' => "%$%"]);
        $queryResult_s = '';
        $queryResult_a = [];
        while($row_o = $stmt_o->fetch()) {
            array_push($queryResult_a, $row_o);
        }
        return $queryResult_a;
    }

    private function array_push_assoc(&$array, $key, $value) {
        $array[$key] = $value;
        return $array;
    }
} 

?>