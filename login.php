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

//travao to username kai password
$username = $data['username'];
$password = $data['password'];

//echo json_encode($username.' '.$password);

//perno tous paixtes me to sigekrimeno username/password
$sql = 'SELECT nickname FROM players WHERE username = ? AND password = ?';
$stmt = $pdo->prepare($sql);
$stmt->execute([$username, $password]);
$result = $stmt->fetch();

if (!$result){
  //an den epistrepsi tpt i vasi vgazo error
  echo json_encode(array('message' => 'Wrong Username Or Password.'));
}
else {
  //arxikopoio ena set xaraktiron kai ena adio string
  $charset = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $length = strlen($charset);
  $randomString = '';

  //ftixno to random token
  for ($i = 0; $i < 45; $i++) {
      $randomString .= $charset[rand(0, $length - 1)];
  }

  //to epistrefo
  echo json_encode(array('nickname' => $result->nickname, 'token' => $randomString));

  //to grafo stin vasi
  $sql = 'UPDATE players SET token = ? WHERE username = ? AND password = ?';
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$randomString, $username, $password]);

}
?>
