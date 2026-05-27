<?php

class PieceFactory {

    public function create(PieceType $type, PieceColor $color, Position $position): Piece {
        switch ($type) {
            case PieceType::BISHOP :
                return new Bishop($color, $position);
            case PieceType::KING :
                return new King($color, $position);            
            case PieceType::KNIGHT :
                return new Knight($color, $position);
            case PieceType::PAWN :
                return new Pawn($color, $position);
            case PieceType::QUEEN :
                return new Queen($color, $position);
            case PieceType::ROOK :
                return new Rook($color, $position);
        }
    }
}