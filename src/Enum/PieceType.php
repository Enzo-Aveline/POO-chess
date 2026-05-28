<?php 

/**
 * Enum pour les types de pieces du jeu d'echecs
 * chaque piece a son propre type
 */
enum PieceType{
    case KING;
    case QUEEN;
    case ROOK;
    case BISHOP;
    case KNIGHT;
    case PAWN;
}