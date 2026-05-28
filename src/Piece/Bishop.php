<?php

/**
 * Le Fou - bouge en diagonale sans limite de distance
 */
class Bishop extends Piece 
{

    /**
     * @param PieceColor $color la couleur du fou
     * @param Position $position sa position initiale
     */
    public function __construct(PieceColor $color, Position $position) {
        
        parent::__construct($color,$position);
        $this->type = PieceType::BISHOP;

    }

    /**
     * forme du deplacement du fou :
     * vrai si la distance en row et en col sont identiques (= diagonale)
     * et qu'on bouge d'au moins 1 case
     * 
     * @param Position $target la case ou on veut aller
     * @return bool true si la forme est valide
     */
    protected function isValidMovementShape(Position $target):bool
    {
        $distanceRow = abs($this->position->getRow() - $target->getRow());
        $distanceCol = abs($this->position->getColumn() - $target->getColumn());

        return $distanceRow === $distanceCol && $distanceRow > 0;
    }
}
