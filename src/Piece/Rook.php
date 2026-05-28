<?php

/**
 * La Tour - bouge en ligne droite (horizontale ou verticale)
 * sans limite de distance
 */
class Rook extends Piece 
{

    /**
     * @param PieceColor $color la couleur de la tour
     * @param Position $position sa position initiale
     */
    public function __construct(PieceColor $color, Position $position) {
        
        parent::__construct($color,$position);
        $this->type = PieceType::ROOK;

    }

    /**
     * forme du deplacement de la tour :
     * vrai si on bouge que sur la colone ou la ligne (une des deux distances est 0)
     * 
     * @param Position $target la case ou on veut aller
     * @return bool true si la forme est valide
     */
    protected function isValidMovementShape(Position $target):bool
    {
        $distanceRow = abs($this->position->getRow() - $target->getRow());
        $distanceCol = abs($this->position->getColumn() - $target->getColumn());
        return ($distanceRow === 0 || $distanceCol === 0);
    }
}
