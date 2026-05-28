<?php

/** levee quand on essaie de se deplacer sur une case occupee par une piece alliee */
class OccupiedByAllyException extends ChessException{
    public function __construct(string $message = "la case contient deja une de vos piece"){
        parent::__construct($message);
    }
}
