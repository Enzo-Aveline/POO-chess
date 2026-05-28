<?php 

/**
 * Enum pour les couleurs des pieces
 * WHITE = blanc, BLACK = noir
 */
enum PieceColor{
    case WHITE;
    case BLACK;

    /** 
     * retourne la couleur opposee (blanc -> noir, noir -> blanc) 
     * 
     * @return PieceColor la couleur opposee
     */
    public function opposite(): PieceColor {
        return $this === self::WHITE ? self::BLACK : self::WHITE;
    }
}