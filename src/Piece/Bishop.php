<?php

class Bishop extends Piece 
{

    public function __construct(PieceColor $color, Position $position) {
        
        parent::__construct($color,$position);
        $this->type = PieceType::BISHOP;

    }

    public function isValidMovementShape(Position $target):bool
    {
        //vrai si la difference entre la position actuele et la target entre terme de column et row sont identique
        $distanceRow = abs($this->position->getRow() - $target->getRow());
        $distanceCol = abs($this->position->getColumn() - $target->getColumn());

        return $distanceRow === $distanceCol && $distanceRow > 0;
    }
}
