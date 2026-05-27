<?php

class ChessException extends Exception{
    public function __construct(string $message = "Erreur lors de la partie d'échecs"){
        parent::__construct($message);
    }
}