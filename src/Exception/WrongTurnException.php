<?php


class WrongTurnException extends ChessException{
    public function __construct(string $message = "Vous ne pouvez jouer que votre couleur"){
        parent::__construct($message);
    }
}
