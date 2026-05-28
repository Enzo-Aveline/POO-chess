<?php

require_once 'vendor/autoload.php';

echo "==========================================\n";
echo "=== DEMONSTRATION DES BONUS POO CHESS ===\n";
echo "==========================================\n\n";

// Fonction utilitaire pour vider le plateau
function clearBoard(Board $board) {
    foreach ($board->getPieces() as $piece) {
        $board->removePieceAt($piece->getPosition());
    }
}

$factory = new PieceFactory();

// ---------------------------------------------------------
// 1. L'interdiction d'exposer son propre roi
// ---------------------------------------------------------
echo "------------------------------------------\n";
echo "--- 1. Interdiction d'exposer son roi ---\n";
echo "------------------------------------------\n";
$game = new Game();
$board = $game->getBoard();
clearBoard($board);
// Roi blanc, Tour noire, et Cavalier blanc entre les deux
$board->placePiece($factory->create(PieceType::KING, PieceColor::WHITE, new Position(7, 4)));
$board->placePiece($factory->create(PieceType::ROOK, PieceColor::BLACK, new Position(7, 0)));
$board->placePiece($factory->create(PieceType::KNIGHT, PieceColor::WHITE, new Position(7, 2)));

echo $board->render();
echo "Action : Le Cavalier blanc tente de bouger en (5,3)...\n";
try {
    $game->play(new Move(new Position(7, 2), new Position(5, 3)));
} catch (Exception $e) {
    echo "--> Resultat attendu (Exception) : " . $e->getMessage() . "\n";
}
echo "\n";


// ---------------------------------------------------------
// 2. Le Roque
// ---------------------------------------------------------
echo "------------------------------------------\n";
echo "--- 2. Le Roque (Petit et Grand)      ---\n";
echo "------------------------------------------\n";
$game = new Game();
$game->start();
// On force un peu le plateau en enlevant les pièces gênantes
$board = $game->getBoard();
$board->removePieceAt(new Position(7, 5)); // Fou blanc
$board->removePieceAt(new Position(7, 6)); // Cavalier blanc
$board->removePieceAt(new Position(0, 1)); // Cavalier noir
$board->removePieceAt(new Position(0, 2)); // Fou noir
$board->removePieceAt(new Position(0, 3)); // Reine noire

echo $board->render();
echo "Action : Blanc fait le PETIT ROQUE (Roi (7,4) -> (7,6))...\n";
$game->play(new Move(new Position(7, 4), new Position(7, 6)));

echo "Action : Noir fait le GRAND ROQUE (Roi (0,4) -> (0,2))...\n";
$game->play(new Move(new Position(0, 4), new Position(0, 2)));

echo $board->render();


// ---------------------------------------------------------
// 3. La Prise en Passant
// ---------------------------------------------------------
echo "------------------------------------------\n";
echo "--- 3. La Prise en passant            ---\n";
echo "------------------------------------------\n";
$game = new Game();
$board = $game->getBoard();
clearBoard($board);
// Pion noir en haut, Pion blanc au milieu
$board->placePiece($factory->create(PieceType::PAWN, PieceColor::BLACK, new Position(1, 4)));
$board->placePiece($factory->create(PieceType::PAWN, PieceColor::WHITE, new Position(3, 3)));

// On force le dernier coup à être un double pas du pion noir
echo "Action : Noir avance son pion de (1,4) à (3,4) (double pas)...\n";
$board->movePiece(new Position(1, 4), new Position(3, 4));
echo $board->render();

echo "Action : Blanc capture en passant en (2,4) avec son pion en (3,3)...\n";
// Simulation du tour blanc pour Game::play
$board->placePiece($factory->create(PieceType::KING, PieceColor::WHITE, new Position(7, 7))); // juste pour avoir un roi
$board->placePiece($factory->create(PieceType::KING, PieceColor::BLACK, new Position(0, 7)));
$game->play(new Move(new Position(3, 3), new Position(2, 4)));
echo "--> Résultat : Le pion noir a été capturé ! (la case 3:4 est vide)\n";
echo $board->render();
echo "\n";


// ---------------------------------------------------------
// 4. La Promotion
// ---------------------------------------------------------
echo "------------------------------------------\n";
echo "--- 4. La Promotion                   ---\n";
echo "------------------------------------------\n";
$game = new Game();
$board = $game->getBoard();
clearBoard($board);
$board->placePiece($factory->create(PieceType::PAWN, PieceColor::WHITE, new Position(1, 0))); // Pion blanc juste avant la ligne
$board->placePiece($factory->create(PieceType::KING, PieceColor::WHITE, new Position(7, 7))); 
$board->placePiece($factory->create(PieceType::KING, PieceColor::BLACK, new Position(0, 7)));

echo $board->render();
echo "Action : Blanc avance son pion en (0,0)...\n";
$game->play(new Move(new Position(1, 0), new Position(0, 0)));
echo "--> Résultat : Le pion a été promu en Reine ! (Regardez la case 0:0)\n";
echo $board->render();
echo "\n";


// ---------------------------------------------------------
// 5. L'échec et mat
// ---------------------------------------------------------
echo "------------------------------------------\n";
echo "--- 5. L'échec et mat                 ---\n";
echo "------------------------------------------\n";
$game = new Game();
$board = $game->getBoard();
clearBoard($board);

// Couloir de la mort pour le roi noir
$board->placePiece($factory->create(PieceType::KING, PieceColor::BLACK, new Position(0, 0)));
$board->placePiece($factory->create(PieceType::PAWN, PieceColor::BLACK, new Position(1, 0)));
$board->placePiece($factory->create(PieceType::PAWN, PieceColor::BLACK, new Position(1, 1)));

// Tour blanche qui donne le mat
$board->placePiece($factory->create(PieceType::ROOK, PieceColor::WHITE, new Position(0, 7)));
echo $board->render();

echo "Action : Vérification de l'échec et mat pour le joueur NOIR...\n";
if ($game->isCheckmate(PieceColor::BLACK)) {
    echo "--> Résultat : Le joueur NOIR est bien en ÉCHEC ET MAT !\n";
} else {
    echo "--> Résultat : Pas de mat détecté.\n";
}
echo "\n";
