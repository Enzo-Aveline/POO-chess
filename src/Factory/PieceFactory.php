<?php

/**
 * Design Pattern : Factory
 * cree les pieces du jeu en fonction du type demande
 * c'est Game qui utilise cette factory pour placer les pieces sur le plateau
 */
class PieceFactory {

    /**
     * cree et retourne une piece selon le type, la couleur et la position donnees
     * 
     * @param PieceType $type le type de piece a creer (KING, QUEEN, etc.)
     * @param PieceColor $color la couleur de la piece
     * @param Position $position la position ou placer la piece
     * @return Piece la piece creee
     */
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