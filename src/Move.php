<?php

/**
 * Value Object qui represente un coup : une case de depart et une case d'arrivee
 * c'est juste un conteneur, il fait aucune verification
 */
class Move {
    private Position $from;
    private Position $to;

    /**
     * @param Position $from la case de depart
     * @param Position $to la case d'arrivee
     */
    public function __construct(Position $from, Position $to){
        $this->from = $from;
        $this->to = $to;
    }

    /** 
     * retourne la position de depart du coup 
     * 
     * @return Position la case de depart
     */
    public function getFrom(): Position{
        return $this->from;
    }

    /** 
     * retourne la position d'arrivee du coup 
     * 
     * @return Position la case d'arrivee
     */
    public function getTo(): Position{
        return $this->to;
    }

}