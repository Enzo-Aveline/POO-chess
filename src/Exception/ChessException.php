<?php

/** Exception de base pour toutes les erreurs metier du jeu d'echecs */
class ChessException extends Exception{
    public function __construct(string $message = "Erreur lors de la partie d'échecs"){
        parent::__construct($message);
    }
}