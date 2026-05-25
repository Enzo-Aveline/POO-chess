<?php


abstract class Piece implements Renderable {
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
                break;
            case PieceType::QUEEN:
                $lettre = "q";
                break;
            case PieceType::ROOK:
                $lettre = "r";
                break;
            case PieceType::BISHOP:
                $lettre = "b";
                break;
            case PieceType::KNIGHT:
                $lettre = "n";
                break;
            case PieceType::PAWN:
                $lettre = "p";
                break;
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

        $targetPiece = $board->getPieceAt($target);
        if ($targetPiece !== null && $targetPiece->getColor() === $this->color){
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