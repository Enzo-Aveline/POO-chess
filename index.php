<?php

// Chargement de toutes les classes
require_once __DIR__ . '/src/Contract/Renderable.php';
require_once __DIR__ . '/src/Enum/PieceColor.php';
require_once __DIR__ . '/src/Enum/PieceType.php';
require_once __DIR__ . '/src/Position.php';
require_once __DIR__ . '/src/Move.php';
require_once __DIR__ . '/src/Piece/Piece.php';
require_once __DIR__ . '/src/Piece/King.php';
require_once __DIR__ . '/src/Piece/Queen.php';
require_once __DIR__ . '/src/Piece/Rook.php';
require_once __DIR__ . '/src/Piece/Bishop.php';
require_once __DIR__ . '/src/Piece/Knight.php';
require_once __DIR__ . '/src/Piece/Pawn.php';
require_once __DIR__ . '/src/Board.php';
require_once __DIR__ . '/src/Exception/ChessException.php';
require_once __DIR__ . '/src/Exception/InvalidMoveException.php';
require_once __DIR__ . '/src/Exception/NoPieceException.php';
require_once __DIR__ . '/src/Exception/WrongTurnException.php';
require_once __DIR__ . '/src/Exception/OccupiedByAllyException.php';
require_once __DIR__ . '/src/Factory/PieceFactory.php';
require_once __DIR__ . '/src/Game.php';

// 1. Créer une instance de Game
$game = new Game();

// 2. Démarrer la partie
$game->start();

// 3. Afficher le plateau initial
echo "=== PLATEAU INITIAL ===\n";
echo $game->getBoard()->render();
echo "\n";

// 4. Jouer quelques coups de démonstration
$moves = [
    // Coup 1 : Pion blanc e2 -> e4 (6,4 -> 4,4)
    new Move(new Position(6, 4), new Position(4, 4)),

    // Coup 2 : Pion noir e7 -> e5 (1,4 -> 3,4)
    new Move(new Position(1, 4), new Position(3, 4)),

    // Coup 3 : Fou blanc f1 -> c4 (7,5 -> 4,2)
    new Move(new Position(7, 5), new Position(4, 2)),

    // Coup 4 : Cavalier noir b8 -> c6 (0,1 -> 2,2)
    new Move(new Position(0, 1), new Position(2, 2)),

    // Coup 5 : Reine blanche d1 -> h5 (7,3 -> 3,7)
    new Move(new Position(7, 3), new Position(3, 7)),

    // Coup 6 : Cavalier noir g8 -> f6 (0,6 -> 2,5)
    new Move(new Position(0, 6), new Position(2, 5)),

    // Coup 7 : Reine blanche h5 -> f7 (3,7 -> 1,5) — capture le pion et échec au roi !
    new Move(new Position(3, 7), new Position(1, 5)),
];

foreach ($moves as $i => $move) {
    $coupNum = $i + 1;
    try {
        $joueur = $game->getCurrentPlayer()->name;
        echo "Coup $coupNum ($joueur) : " . $move->getFrom()->toKey() . " -> " . $move->getTo()->toKey() . "\n";
        $game->play($move);
    } catch (NoPieceException $e) {
        echo "  ❌ Erreur NoPiece : " . $e->getMessage() . "\n";
    } catch (WrongTurnException $e) {
        echo "  ❌ Erreur WrongTurn : " . $e->getMessage() . "\n";
    } catch (OccupiedByAllyException $e) {
        echo "  ❌ Erreur OccupiedByAlly : " . $e->getMessage() . "\n";
    } catch (InvalidMoveException $e) {
        echo "  ❌ Erreur InvalidMove : " . $e->getMessage() . "\n";
    } catch (ChessException $e) {
        echo "  ❌ Erreur Chess : " . $e->getMessage() . "\n";
    }
}

// 5. Afficher le plateau après les coups
echo "\n=== PLATEAU APRES LES COUPS ===\n";
echo $game->getBoard()->render();

// Vérification de l'échec
echo "\n=== VERIFICATION D'ECHEC ===\n";
if ($game->isCheck(PieceColor::BLACK)) {
    echo "Le roi NOIR est en ECHEC !\n";
} else {
    echo "Le roi noir n'est pas en échec.\n";
}
if ($game->isCheck(PieceColor::WHITE)) {
    echo "Le roi BLANC est en ECHEC !\n";
} else {
    echo "Le roi blanc n'est pas en échec.\n";
}

// Démonstration d'erreurs métier
echo "\n=== TESTS D'ERREURS METIER ===\n";

// Test NoPieceException : case vide
try {
    echo "Test NoPiece : jouer depuis une case vide (3,3)...\n";
    $game->play(new Move(new Position(3, 3), new Position(4, 3)));
} catch (NoPieceException $e) {
    echo "  ✅ NoPieceException attrapée : " . $e->getMessage() . "\n";
}

// Test WrongTurnException : jouer la mauvaise couleur
try {
    echo "Test WrongTurn : les blancs essaient de jouer une pièce noire...\n";
    // C'est le tour des noirs, on essaye de jouer un pion blanc
    $game->play(new Move(new Position(6, 0), new Position(5, 0)));
} catch (WrongTurnException $e) {
    echo "  ✅ WrongTurnException attrapée : " . $e->getMessage() . "\n";
}

// Test InvalidMoveException : mouvement invalide
try {
    echo "Test InvalidMove : un pion noir essaie d'aller en arrière...\n";
    $game->play(new Move(new Position(1, 0), new Position(0, 0)));
} catch (InvalidMoveException $e) {
    echo "  ✅ InvalidMoveException attrapée : " . $e->getMessage() . "\n";
} catch (OccupiedByAllyException $e) {
    echo "  ✅ OccupiedByAllyException attrapée : " . $e->getMessage() . "\n";
}
