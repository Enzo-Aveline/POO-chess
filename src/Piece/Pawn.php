<?php

class Pawn extends Piece 
{

    public function __construct(PieceColor $color, Position $position) {
        
        parent::__construct($color,$position);
        $this->type = PieceType::PAWN;

    }

    protected function isValidMovementShape(Position $target): bool {
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
        // Vérifie qu'on ne reste pas sur place
        if ($this->position->equals($target)) {
            return false;
        }

        if (!$this->isValidMovementShape($target)) {
            return false;
        }

        // Vérifie allié sur la case cible
        $targetPiece = $board->getPieceAt($target);
        if ($targetPiece !== null && $targetPiece->getColor() === $this->color) {
            return false;
        }

        $distanceRow = $target->getRow() - $this->position->getRow();
        $distanceCol = abs($target->getColumn() - $this->position->getColumn());
        $direction = ($this->color === PieceColor::WHITE) ? -1 : 1;

        // Mouvement en diagonale = capture obligatoire
        if ($distanceCol === 1) {
            if ($targetPiece === null) {
                return false; // Pas de pièce à capturer
            }
            return true;
        }

        // Mouvement en avant : la case cible doit être vide
        if ($targetPiece !== null) {
            return false;
        }

        // Vérifier le chemin pour l'avance de 2 cases
        if (abs($distanceRow) === 2) {
            $intermediateRow = $this->position->getRow() + $direction;
            $intermediate = new Position($intermediateRow, $this->position->getColumn());
            if ($board->hasPieceAt($intermediate)) {
                return false;
            }
        }

        return true;
    }
}
