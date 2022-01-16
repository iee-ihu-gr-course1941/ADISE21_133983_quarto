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

//elegxo an yparxei header
if (isset($_SERVER['HTTP_X_AUTHENTICATION'])) {
  $headers = trim($_SERVER["HTTP_X_AUTHENTICATION"]);

  //psaxno gia to token stin vasi
  $sql = 'SELECT * FROM players WHERE token = ?';
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$headers]);
  $result = $stmt->fetch();

  if (!$result){
    //an den yparxei to token stin vasi
    echo json_encode(array('message' => 'Invalid Token.'));
  }
  else {
    //an yparxei to token stin vasi
    createGame($result->id,$pdo);
  }

} else {
  //error an den yparxei header
  echo json_encode(array('message' => 'No server headers'));
}

function createGame($p1id,$pdo)
{
  //eisago to paixnidi stin vasi
  $sql = 'INSERT INTO games (p1id,gamestatusid) values(?,1)';
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$p1id]);

  //minima epitixias
  echo json_encode(array('message' => 'Game Created'));
}
?>
