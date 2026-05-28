<?php

/**
 * Le Pion - la piece la plus basique mais avec des regles speciales
 * avance d'une case en avant, 2 au premier coup, capture en diagonale
 * la direction depend de la couleur (blanc monte, noir descend)
 */
class Pawn extends Piece 
{

    /**
     * @param PieceColor $color la couleur du pion
     * @param Position $position sa position initiale
     */
    public function __construct(PieceColor $color, Position $position) {
        
        parent::__construct($color,$position);
        $this->type = PieceType::PAWN;

    }

    /**
     * forme du deplacement du pion :
     * on gere les 3 cas possibles
     * 
     * @param Position $target la case ou on veut aller
     * @return bool true si la forme est valide
     */
    protected function isValidMovementShape(Position $target):bool{
        $distanceRow = $target->getRow() - $this->position->getRow();
        $distanceCol = abs($target->getColumn() - $this->position->getColumn());

        // blanc monte (direction -1), noir descend (direction +1)
        $direction = ($this->color === PieceColor::WHITE) ? -1 : 1;
        //ligne de depart
        $startRow = ($this->color === PieceColor::WHITE) ? 6 : 1;


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

    /**
     * surcharge de canMove pour les regles speciales du pion :
     * - quand il avance tout droit la case doit etre vide (il peut pas capturer en avant)
     * - quand il va en diagonale il faut qu'il y ait une piece ennemie a capturer
     * 
     * @param Board $board le plateau de jeu
     * @param Position $target la case ou le pion veut aller
     * @return bool true si le deplacement est valide
     * @throws InvalidMoveException si le pion avance sur une case occupee ou capture dans le vide
     */
    public function canMove(Board $board, Position $target): bool {
        $distanceCol = abs($target->getColumn() - $this->position->getColumn());

        // avance tout droit : la case cible doit etre vide
        if ($distanceCol === 0 && $board->hasPieceAt($target)) {
            throw new InvalidMoveException;
        }

        // diagonale : il doit y avoir une piece a capturer
        if ($distanceCol === 1 && !$this->canCapture($board, $target)) {
            throw new InvalidMoveException;
        }

        return parent::canMove($board, $target);
    }
}
