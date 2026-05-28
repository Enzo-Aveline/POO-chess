<?php


/** levee quand un joueur essaie de bouger une piece qui est pas de sa couleur */
class WrongTurnException extends ChessException{
    public function __construct(string $message = "Vous ne pouvez jouer que votre couleur"){
        parent::__construct($message);
    }
}
