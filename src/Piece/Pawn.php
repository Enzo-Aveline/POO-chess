<?php

class Pawn extends Piece 
{

    public function __construct(PieceColor $color, Position $position) {
        
        parent::__construct($color,$position);
        $this->type = PieceType::PAWN;

    }

    public function isValidMovementShape(Position $target):bool{
        $distanceRow = $target->getRow() - $this->position->getRow();
        $distanceCol = abs($target->getColumn() - $this->position->getColumn());

        $direction = ($this->color === PieceColor::WHITE) ? 1 : -1;
        //ligne de depart
        $startRow = ($this->color === PieceColor::WHITE) ? 1 : 6;


        //on gere les cas un par un
        // 1. on avance d'une ligne
        $oneStep = ($distanceRow === $direction && $distanceCol === 0);

        // 2. on avance de deux ligne si c'est le premier coup
        $isAtStart = ($this->position->getRow() === $startRow);
        $twoSteps = ($isAtStart && $distanceRow === 2 * $direction && $distanceCol === 0);

        // 3. on prend en diagonal
        $isCapture = ($distanceRow === $direction && $distanceCol === 1);

        return $oneStep || $twoSteps || $isCapture;
    }
}
