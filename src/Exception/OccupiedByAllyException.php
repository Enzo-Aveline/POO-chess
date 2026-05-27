<?php

class OccupiedByAllyException extends ChessException{
    public function __construct(string $message = "la case contient deja une de vos piece"){
        parent::__construct($message);
    }
}
