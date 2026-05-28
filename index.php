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

try {
    // === Préparation du terrain ===
    echo "Action : Blanc pion E4...\n";
    $game->play(new Move(new Position(6, 4), new Position(4, 4)));

    echo "Action : Noir pion D5...\n";
    $game->play(new Move(new Position(1, 3), new Position(3, 3)));

    echo "Action : Blanc fou C4...\n";
    $game->play(new Move(new Position(7, 5), new Position(4, 2)));

    echo "Action : Noir cavalier C6...\n";
    $game->play(new Move(new Position(0, 1), new Position(2, 2)));

    echo "Action : Blanc cavalier F3...\n";
    $game->play(new Move(new Position(7, 6), new Position(5, 5)));

    echo "Action : Noir sort sa Dame en D6...\n";
    $game->play(new Move(new Position(0, 3), new Position(2, 3)));

    echo "Action : Blanc fait un coup d'attente (Pion A3)...\n";
    $game->play(new Move(new Position(6, 0), new Position(5, 0)));
    
    echo "Action : Noir fou D7...\n";
    $game->play(new Move(new Position(0, 2), new Position(1, 3)));

    echo "--- Plateau avant les Roques ---\n";
    echo $game->getBoard()->render();

    // === ROQUES ===
    echo "Action : Blanc fait le PETIT ROQUE (Roi E1 -> G1)...\n";
    $game->play(new Move(new Position(7, 4), new Position(7, 6)));

    echo "Action : Noir fait le GRAND ROQUE (Roi E8 -> C8)...\n";
    $game->play(new Move(new Position(0, 4), new Position(0, 2)));

    echo "--- Plateau final avec les 2 roques ---\n";
    echo $game->getBoard()->render();

} catch (ChessException $e) {
    echo "Error : " . $e->getMessage() . "\n";
}
