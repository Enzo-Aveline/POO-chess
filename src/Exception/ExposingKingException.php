<?php

/**
 * Exception lancée lorsqu'un joueur tente un mouvement qui exposerait son propre roi
 * (le mettant ainsi en échec, ce qui est interdit par les règles).
 */
class ExposingKingException extends ChessException
{
    protected $message = "Ce mouvement exposerait votre roi.";
}
