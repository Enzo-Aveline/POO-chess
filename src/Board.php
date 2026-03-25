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

    public function getPieceAt(Position $position): ?Piece
    {
        return $this->pieces[$position->toKey()];
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
        return "";
    }

}


$position=new Position(1,1);
$position2 = new Position(2,3);
$piece= new King(PieceColor::WHITE,$position);

$board=new Board();
$board->placePiece($piece);
var_dump($board->getKingPosition(PieceColor::WHITE));



