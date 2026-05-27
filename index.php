<?php

require_once 'src/Contract/Renderable.php';
require_once 'src/Enum/PieceColor.php';
require_once 'src/Enum/PieceType.php';

require_once 'src/Position.php';
require_once 'src/Move.php';

require_once 'src/Exception/ChessException.php';
require_once 'src/Exception/NoPieceException.php';
require_once 'src/Exception/WrongTurnException.php';
require_once 'src/Exception/InvalidMoveException.php';
require_once 'src/Exception/OccupiedByAllyException.php';

require_once 'src/Piece/Piece.php';
require_once 'src/Piece/Pawn.php';
require_once 'src/Piece/Rook.php';
require_once 'src/Piece/Knight.php';
require_once 'src/Piece/Bishop.php';
require_once 'src/Piece/Queen.php';
require_once 'src/Piece/King.php';

require_once 'src/Factory/PieceFactory.php';
require_once 'src/Board.php';
require_once 'src/Game.php';

$game = new Game();
$game->start();

echo "--- Plateau Initial ---\n";
echo $game->getBoard()->render();
echo "Tour : " . $game->getCurrentPlayer()->name . "\n\n";

try {
    // pion blanc de (6,4) à (4,4)
    echo "Action : Le Blanc joue son pion en E4...\n";
    $move = new Move(new Position(6, 4), new Position(4, 4));
    $game->play($move);

    // pion noir de (1,0) à (2,0)
    echo "Action : Le Noir joue son pion en A6...\n";
    $move = new Move(new Position(1, 0), new Position(2, 0));
    $game->play($move);

    // fou blanc de (7,5) à (4,2)
    echo "Action : Le Blanc joue fou en C4...\n";
    $move = new Move(new Position(7, 5), new Position(4, 2));
    $game->play($move);

    // pion noir de (2,0) à (3,0)
    echo "Action : Le Noir joue son pion en A5...\n";
    $move = new Move(new Position(2, 0), new Position(3, 0));
    $game->play($move);

    // dame blanche de (7,3) à (5,5)
    echo "Action : Le Blanc joue sa dame en F3...\n";
    $move = new Move(new Position(7, 3), new Position(5, 5));
    $game->play($move);

    // cavalier noir de (0,1) à (2,2)
    echo "Action : Le Noir joue son cavalier en C6...\n";
    $move = new Move(new Position(0, 1), new Position(2, 2));
    $game->play($move);

    echo "check ? " . ($game->isCheck($game->getCurrentPlayer()) ? "true" : "false") . "\n"; 

    // dame blanche de (5,5) à (1,5) (échec et mat)
    echo "Action : Le Blanc joue sa dame en F7 (échec)...\n";
    $move = new Move(new Position(5, 5), new Position(1, 5));
    $game->play($move);

    echo "check ? " . ($game->isCheck($game->getCurrentPlayer()) ? "true" : "false") . "\n"; 

    echo "--- Plateau ---\n";
    echo $game->getBoard()->render();
    echo "Tour : " . $game->getCurrentPlayer()->name . "\n\n";

    echo "Action : Le Noir essaie de bouger un pion...\n";
    $move = new Move(new Position(1, 7), new Position(2, 7));
    $game->play($move);

    echo "--- Plateau ---\n";
    echo $game->getBoard()->render();
    echo "Tour : " . $game->getCurrentPlayer()->name . "\n\n";

    // resultat
    echo "--- Plateau ---\n";
    echo $game->getBoard()->render();
    echo "Nouveau tour : " . $game->getCurrentPlayer()->name . "\n";

} catch (ChessException $e) {
    echo "Error : " . $e->getMessage() . "\n";
}

