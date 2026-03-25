<?php

class Knight extends Piece 
{

    public function __construct(PieceColor $color, Position $position) {
        
        parent::__construct($color,$position);
        $this->type = PieceType::KNIGHT;

    }

    public function isValidMovementShape(Position $target):bool
    {
        // vrai si la difference entre la position actuele et la target entre terme de column et row sont identique
        // ou vrai si on ne bouge que sur la colone ou la ligne 

        $distanceRow = abs($this->position->getRow() - $target->getRow());
        $distanceCol = abs($this->position->getColumn() - $target->getColumn());

        return ($distanceRow === 2 && $distanceCol === 1) || ($distanceRow === 1 && $distanceCol === 2);
    }   
}