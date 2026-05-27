<?php

class Pawn extends Piece 
{

    public function __construct(PieceColor $color, Position $position) {
        
        parent::__construct($color,$position);
        $this->type = PieceType::PAWN;

    }

    protected function isValidMovementShape(Position $target):bool{
        $distanceRow = $target->getRow() - $this->position->getRow();
        $distanceCol = abs($target->getColumn() - $this->position->getColumn());

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

    public function canMove(Board $board, Position $target): bool {
        $distanceCol = abs($target->getColumn() - $this->position->getColumn());

        // Avance : la case cible doit être vide
        if ($distanceCol === 0 && $board->hasPieceAt($target)) {
            throw new InvalidMoveException;
        }

        // Diagonale : il doit y avoir une pièce à capturer
        if ($distanceCol === 1 && !$this->canCapture($board, $target)) {
            throw new InvalidMoveException;
        }

        return parent::canMove($board, $target);
    }
}
