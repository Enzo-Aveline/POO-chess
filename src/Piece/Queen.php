<?php

/**
 * La Dame - la piece la plus puissante
 * elle combine les mouvements de la tour et du fou
 * bouge en ligne droite ou en diagonale sans limite de distance
 */
class Queen extends Piece 
{

    /**
     * @param PieceColor $color la couleur de la dame
     * @param Position $position sa position initiale
     */
    public function __construct(PieceColor $color, Position $position) {
        
        parent::__construct($color,$position);
        $this->type = PieceType::QUEEN;

    }

    /**
     * forme du deplacement de la dame :
     * vrai si elle bouge en diagonale (meme distance en row et col)
     * ou en ligne droite (une des deux distances est 0)
     * 
     * @param Position $target la case ou on veut aller
     * @return bool true si la forme est valide
     */
    protected function isValidMovementShape(Position $target):bool
    {
        $distanceRow = abs($this->position->getRow() - $target->getRow());
        $distanceCol = abs($this->position->getColumn() - $target->getColumn());

        return ($distanceRow === $distanceCol) || ($distanceRow === 0 || $distanceCol === 0);
    }   
}