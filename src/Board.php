<?php

require_once __DIR__ . '/Contract/InterfaceBoard.php';
require_once __DIR__ . '/Position.php';
require_once __DIR__ . '/Piece/Piece.php';
require_once __DIR__ . '/Piece/King.php';
require_once __DIR__ . '/Enum/PieceColor.php';
require_once __DIR__ . '/Enum/PieceType.php';

class Board implements InterfaceBoard{

    private array $pieces = [];


    public function placePiece(Piece $piece): void
    {
        $position = $piece->getPosition();
        $this->pieces[$position->toKey()] = $piece;
    }

    public function getPieceAt(Position $position): ?Piece{
        $key = $position->toKey();
        if ($this->hasPieceAt($position)) {
            return $this->pieces[$key];
        }
        return null;
        //return $this->getPieces()[$position->toKey()];
    }

    public function hasPieceAt(Position $position): bool
    {
        return isset($this->pieces[$position->toKey()]);
    }

    public function removePieceAt(Position $position): void
    {
        unset($this->pieces[$position->toKey()]);
    }

    public function movePiece(Position $from, Position $to): void{
        $move = new Move($from,$to);
        $piece = $this->getPieceAt($from);

        if(!$piece->canMove($this,$to)){
            throw new Exception("Mouvement invalide pour cette piece !");
        }
        
        $this->removePieceAt($from);
        $this->placePiece($piece);
    }

    public function isPathClear(Position $from, Position $to): bool{
        return true;
    }

    public function getPieces(): array{
        return $this->pieces;
    }

    public function getKingPosition(PieceColor $color): ?Position
    {
        $pieces = $this->getPieces();
        foreach ($pieces as $piece) {
            if ($piece->getColor() == $color && $piece->getType() === PieceType::KING) {
                return $piece->getPosition();
            }
        }
        return null;
    }

    public function render(): string
    {
        $result = "";

        // On commence par la ligne 7 (Haut) et on descend vers 0 (Bas)
        for ($row = 7; $row >= 0; $row--) {

            // Petit indicateur de numéro de ligne sur le côté
            $result .= $row . " | ";

            for ($col = 0; $col <= 7; $col++) {
                $pos = new Position($row, $col);
                $piece = $this->getPieceAt($pos);

                if ($piece !== null) {
                    // On appelle le render() de la pièce (ex: ♟, ♜, ♔)
                    $result .= $piece->render() . " ";
                } else {
                    // Case vide : on affiche un point ou un espace
                    $result .= ". ";
                }
            }
            $result .= "\n"; // Saut de ligne après chaque rangée
        }

        // Affichage des lettres de colonnes en bas
        $result .= "    ----------------\n";
        $result .= "     0 1 2 3 4 5 6 7\n";

        return $result;
    }

}


$position=new Position(1,1);
$piece= new King(PieceColor::WHITE,$position);

$board=new Board();
$board->placePiece($piece);
var_dump($board->render());



