<?php

/**
 * Le Cavalier - bouge en L (2+1 ou 1+2)
 * c'est la seule piece qui peut sauter par dessus les autres
 */
class Knight extends Piece 
{

    /**
     * @param PieceColor $color la couleur du cavalier
     * @param Position $position sa position initiale
     */
    public function __construct(PieceColor $color, Position $position) {
        
        parent::__construct($color,$position);
        $this->type = PieceType::KNIGHT;

    }

    /**
     * forme du deplacement du cavalier :
     * vrai si on fait un L : 2 cases dans une direction et 1 dans l'autre
     * (2 row + 1 col) ou (1 row + 2 col)
     * 
     * @param Position $target la case ou on veut aller
     * @return bool true si la forme est valide
     */
    protected function isValidMovementShape(Position $target):bool
    {
        $distanceRow = abs($this->position->getRow() - $target->getRow());
        $distanceCol = abs($this->position->getColumn() - $target->getColumn());

        return ($distanceRow === 2 && $distanceCol === 1) || ($distanceRow === 1 && $distanceCol === 2);
    }   
}