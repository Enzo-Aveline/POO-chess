<?php


abstract class Piece {
    protected PieceColor $color;
    protected Position $position;
    protected PieceType $type;

    public function __construct(PieceColor $color, Position $position) {
        $this->color = $color;
        $this->position = $position;
    }
    
    public function getColor(): PieceColor {
        return $this->color;
    }
    
    public function getPosition(): Position {
        return $this->position;
    }
    
    public function setPosition(Position $position): void {
        $this->position = $position;
    }
    
    public function getType(): PieceType {
        return $this->type;
    }
    
    public function render(): string {
        switch ($this->type) {
            case PieceType::KING:
                $lettre = "k";
            case PieceType::QUEEN:
                $lettre = "q";
            case PieceType::ROOK:
                $lettre = "r";
            case PieceType::BISHOP:
                $lettre = "b";
            case PieceType::KNIGHT:
                $lettre = "n";
            case PieceType::PAWN:
                $lettre = "p";
        }
        if ($this->color === PieceColor::WHITE){
            $lettre = strtoupper($lettre);
        }
        return $lettre;
    }
    
    public function canMove(Board $board, Position $target): bool {
        if ($this->position->equals($target)){
            return false;
        }

        if (!$this->isValidMovementShape($target)){
            return false;
        }

        if ($board->getPieceAt($target)->color === $this->color){
            return false;
        }
        
        if ($this->type !== PieceType::KNIGHT){
            if(!$board->isPathClear($this->position,$target)){
                return false;
            }
        }

        return true;
    }
    
    abstract protected function isValidMovementShape(Position $target): bool;
    
    protected function canCapture(Board $board, Position $target): bool {
        return $board->hasPieceAt($target);
    }
    
}