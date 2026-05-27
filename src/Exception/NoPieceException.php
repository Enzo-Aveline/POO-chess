<?php

class NoPieceException extends ChessException{
    public function __construct(string $message = "Aucune pièce n'existe sur cette case"){
        parent::__construct($message);
    }
}
