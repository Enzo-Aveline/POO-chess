<?php

/**
 * Interface Renderable
 * toute classe qui peut s'afficher en string doit implementer cette interface
 * utilise par Piece et Board pour generer l'affichage du plateau
 */
interface Renderable
{
    /** retourne une representation en string de l'objet */
    public function render(): string;

}