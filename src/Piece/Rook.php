<?php

class Rook extends Piece 
{

    public function __construct(PieceColor $color, Position $position) {
        
        parent::__construct($color,$position);
        $this->type = PieceType::ROOK;

    }

    public function isValidMovementShape(Position $target):bool
    {
        //vrai si on ne bouge que sur la colone ou la ligne 

        $distanceRow = abs($this->position->getRow() - $target->getRow());
        $distanceCol = abs($this->position->getColumn() - $target->getColumn());
        return ($distanceRow === 0 || $distanceCol === 0);
    }
}
