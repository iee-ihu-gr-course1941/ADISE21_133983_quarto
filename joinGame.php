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
    joinGame($result->id,$pdo);
  }

} else {
  //error an den yparxei header
  echo json_encode(array('message' => 'No server headers'));
}


function joinGame($p2id,$pdo)
{
    // diavazo ta data apo to post request
    $json = file_get_contents('php://input');

    // ta kano json
    $data = json_decode($json, true);

    $gameId = $data['gameid'];


    if(p2available($gameId,$pdo))
    {
        p2join($gameId, $p2id, $pdo);
    }

    else
    {
        echo json_encode(array('message' => 'Game is full'));
    }

}

function p2available($gameId,$pdo)
{
    $sql = 'SELECT p2id FROM games WHERE id = ?';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$gameId]);
    $result = $stmt->fetch();

    if ($result->p2id == null)
    {
        return true;
    }
    else
    {
        return false;
    }
}

function p2join($gameId, $p2id, $pdo)
{
    $sql = 'UPDATE games SET p2id = ? WHERE id = ?';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$p2id, $gameId]);

    $sql = 'UPDATE games SET gamestatusid = 2 WHERE id = ?';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$gameId]);

    echo json_encode(array('message' => 'You joined the game'));
}
?>
