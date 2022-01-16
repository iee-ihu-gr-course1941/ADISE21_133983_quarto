<?php
class GameBoard
{
    //string me to pioni pou einai selected
    public $selectedPiece;

    //int 1 gia p1 2 gia p2
    public $currentPlayer;

    //ta pionia
    //black/white
    //tall/short
    //round/square
    //hollow/solid
    public $initialPieces = array("wsrh", "wsss", "wssh", "wsrs", "wtrh", "wtss", "wtsh", "wtrs",
                                    "bsrh", "bsss", "bssh", "bsrs", "btrh", "btss", "btsh", "btrs");

    //pia einai diathesima
    public $availablePieces;

    //to game board
    public $board;

    //constructor
    public function __construct()
    {
        $this->selectedPiece = "";
        $this->availablePieces = $this->initialPieces;

        $this->board = array
                (
                  array("", "", "", ""),
                  array("", "", "", ""),
                  array("", "", "", ""),
                  array("", "", "", "")
                );

        $this->currentPlayer = 1;
    }



    public function getSelectedPiece() {
        return $selectedPiece;
    }

    public function setSelectedPiece($selectedPiece) {
        $this->$selectedPiece = $selectedPiece;
    }

    public function getAvailablePieces() {
        return $availablePieces;
    }

    public function getBoard() {
        return $board;
    }

    public function getCurrentPlayer() {
        return $currentPlayer;
    }

    public function setCurrentPlayer($currentPlayer) {
        $this->currentPlayer = $currentPlayer;
    }

    private function placePiece($x, $y, $piece)
    {
        //elegxos an einai diathesimo to pioni
        if (!in_array ($piece, $availablePieces)) {
          return false;
        }

        //elegxos an to x einai entos orion
        if ($x < 1 or $x > 4) {
          return false;
        }

        //elegxos an to y einai entos orion
        if ($y < 1 or $y > 4) {
          return false;
        }

        //elegxos an i thesi sto board einai keni
        if (!$board[$x][$y] == "") {
          return false;
        }

        //topotheto to pioni kai epistrefo true
        $board[$x-1][$y-1] = $piece;
        return true;
    }

    private function selectPiece($black, $tall, $round, $solid)
    {
        //o paixtis mou dinei ta xaraktiristika tou piece
        //kai sintheto to string

        $piece = "";
        $piece .= $black ? "b" : "w";
        $piece .= $tall ? "t" : "s";
        $piece .= $round ? "r" : "s";
        $piece .= $solid ? "s" : "h";

        return $piece;
    }
}
?>
