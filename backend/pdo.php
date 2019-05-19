<?php






echo extension_loaded('pdo_mysql');



$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     throw new \PDOException($e->getMessage(), (int)$e->getCode());
}



$lastname = 'Nilsson';
$stmt = $pdo->prepare("SELECT * FROM `users` WHERE `lastname` = :lastname");
$stmt->execute([
    'lastname' => $lastname
]);
while ($row = $stmt->fetch()) {
    echo $row['firstname'] . "\n";
}

?>