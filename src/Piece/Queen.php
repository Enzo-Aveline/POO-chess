<?php

class Queen extends Piece 
{

    public function __construct(PieceColor $color, Position $position) {
        
        parent::__construct($color,$position);
        $this->type = PieceType::QUEEN;

    }

    public function isValidMovementShape(Position $target):bool
    {
        // vrai si la difference entre la position actuele et la target entre terme de column et row sont identique
        // ou vrai si on ne bouge que sur la colone ou la ligne 

        $distanceRow = abs($this->position->getRow() - $target->getRow());
        $distanceCol = abs($this->position->getColumn() - $target->getColumn());

        return ($distanceRow === $distanceCol) || ($distanceRow === 0 || $distanceCol === 0);
    }   
}