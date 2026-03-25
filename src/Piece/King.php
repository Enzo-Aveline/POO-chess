<?php

class King extends Piece 
{

    public function __construct(PieceColor $color, Position $position) {
        
        parent::__construct($color,$position);
        $this->type = PieceType::KING;

    }

    public function isValidMovementShape(Position $target):bool{
        // vrai si la distance entre ma position actuel et celle ou je veux aller est inferieur ou = a 1 en row et column
        $distanceRow = abs($this->position->getRow() - $target->getRow());
        $distanceCol = abs($this->position->getColumn() - $target->getColumn());

        return $distanceRow <= 1 && $distanceCol <= 1 && ($distanceRow + $distanceCol > 0);
    }
}
