<?php
//kodikoi ktlp gia tin sindesi stin vasi
$host = 'localhost';
$user = 'root';
$password = '123456';
$dbname = 'quartoDB';

// rithmisi tou dsn
$dsn = 'mysql:host='. $host .';dbname='. $dbname;

// neo pdo
$pdo = new PDO($dsn, $user, $password);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

// diavazo ta data apo to post request
$json = file_get_contents('php://input');

// ta kano json
$data = json_decode($json, true);

//travao ta stoixeia tou xristi
$username = $data['username'];
$password = $data['password'];
$nickname = $data['nickname'];

// echo json_encode($data) . "\n";
// echo json_encode($username) . "\n";
// echo json_encode($password) . "\n";
// echo json_encode($nickname) . "\n";

//eisagogi xristi
$sql = 'INSERT INTO players (username, password, nickname) values(?, ?, ?)';
$stmt = $pdo->prepare($sql);
$insert_result = $stmt->execute([$username, $password, $nickname]);

//an mpike o xristis vgazo minima i error an den mpike
if(!$insert_result) {
    echo "error " . $pdo->errorCode() . json_encode($pdo->errorInfo());
} else {
    echo json_encode(array('message' => 'User created'));
}
?>
