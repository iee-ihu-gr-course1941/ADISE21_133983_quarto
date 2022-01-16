<?php

include 'GameBoardClass.php';

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

$gameId = $data['gameid'];


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

    //kratao to player id gia tous epomenous elegxous
    $playerId = $result->id;

    //elegxos an thelei na paixei sto sosto game
    if (checkGame($pdo, $playerId, $gameId, $data))
    {
        //an einai sto sosto game elegxo an einai i seira tou
        checkTurn($pdo, $playerId, $gameId, $data);
    }
    else
    {
        //an den einai to sosto game vgazo error
        echo json_encode(array('message' => 'Wrong Game'));
    }

  }

} else {
  //error an den yparxei header
  echo json_encode(array('message' => 'No server headers'));
}

function checkGame($pdo, $playerId, $gameId)
{
    //einai sto sosto game?

    //pare to game id
    //einai to player id = me to p1id i to p2id?
    //an nai return true alios false

    //pairno sta stoixeia tou paixnidiou pou thelei na peksei
    $sql = 'SELECT * FROM games WHERE id = ?';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$gameId]);
    $result = $stmt->fetch();

    //elegxo an einai ontos mesa sto paixnidi
    if ($playerId == $result->p1id or $playerId == $result->p2id)
    {
        return true;
    }
}

function checkTurn($pdo, $playerId, $gameId, $data)
{
    //pairno ta stoixeia tou paixnidiou
    $sql = 'SELECT * FROM games WHERE id = ?';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$gameId]);
    $result = $stmt->fetch();

    //metatropi apo json se assoc array
    $jsonResult = json_decode($result->board, true);
    $currentPlayer = $jsonResult['currentPlayer'];

    //diavazo ta id gia p1 kai p2
    $p1id = $jsonResult = json_decode($result->p1id, true);
    $p2id = $jsonResult = json_decode($result->p2id, true);

    if ($currentPlayer == 1 and $playerId == $p1id)
    {
        selectPiece($pdo, $data, $result);
    }
    elseif ($currentPlayer == 2 and $playerId == $p2id)
    {
        selectPiece($pdo, $data, $result);
    }
    else
    {
        echo json_encode(array('message' => 'Wrong Turn'));
    }
}
//pernao to pdo gia na doulepso tin vasi
//ti eidos einai to pio true i false yparxei sto sto $data
//sto result einai to game apo opou tha paro to board
function selectPiece($pdo, $data, $result)
{
    //pairno to board
    $decodedData = json_decode($result->board);
    //kano neo gameboard kai meta to gemizo
    $board = new GameBoard();
    $board->init($decodedData);
    //kalo tin methodo gia to selectpiece kai kratao to string pou dinei
    $piece = $board->selectPiece($data['black'], $data['tall'], $data['round'], $data['solid']);

    if(!$board->setSelectedPiece($piece))
    {
        echo json_encode(array('message' => 'Piece is NOT selected'));
    }

    else
    {
        // elegxo pios paizei kai allazo tin paiksia
        if ($board->getCurrentPlayer() == 1)
        {
            $board->setCurrentPlayer(2)
        }
        else
        {
            $board->setCurrentPlayer(1)
        }

        //minima oti dialekse pioni
        echo json_encode(array('message' => 'Piece selected'));
    }

    

}

?>
