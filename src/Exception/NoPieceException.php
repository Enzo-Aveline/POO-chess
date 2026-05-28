<?php

/** levee quand on essaie de bouger depuis une case vide */
class NoPieceException extends ChessException{
    public function __construct(string $message = "Aucune pièce n'existe sur cette case"){
        parent::__construct($message);
    }
}
