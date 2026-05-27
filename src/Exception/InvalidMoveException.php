<?php

class InvalidMoveException extends ChessException{
    public function __construct(string $message = "La forme de votre coup est interdit"){
        parent::__construct($message);
    }
}
