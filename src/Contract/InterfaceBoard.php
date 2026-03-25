<?php

interface InterfaceBoard
{
    public function placePiece(Piece $piece): void;
    public function getPieceAt(Position $position): ?Piece; // voir la piece a une position 
    public function hasPieceAt(Position $position): bool; // voir si une cas est libre
    public function removePieceAt(Position $position): void;
    public function movePiece(Position $from, Position $to): void;
    public function isPathClear(Position $from, Position $to): bool; // regarder si le chemain de la piece est libre
    public function getPieces(): array;
    public function getKingPosition(PieceColor $color): ?Position;
    public function render(): string;

}