<?php

require_once __DIR__ . '/../src/Board.php';
require_once __DIR__ . '/../src/Piece/Pawn.php';
require_once __DIR__ . '/../src/Piece/Queen.php';
require_once __DIR__ . '/../src/Piece/King.php';

echo "--- Testing Board Orientation ---\n";
$board = new Board();

// White King at E1 (Row 7, Col 4)
$whiteKing = new King(PieceColor::WHITE, new Position(7, 4));
$board->placePiece($whiteKing);

// Black King at E8 (Row 0, Col 4)
$blackKing = new King(PieceColor::BLACK, new Position(0, 4));
$board->placePiece($blackKing);

// White Queen at D1 (Row 7, Col 3)
$whiteQueen = new Queen(PieceColor::WHITE, new Position(7, 3));
$board->placePiece($whiteQueen);

// Black Queen at D8 (Row 0, Col 3)
$blackQueen = new Queen(PieceColor::BLACK, new Position(0, 3));
$board->placePiece($blackQueen);

echo $board->render();

echo "\n--- Testing Piece Rendering ---\n";
echo "White King render: " . $whiteKing->render() . " (Expected: K)\n";
echo "Black King render: " . $blackKing->render() . " (Expected: k)\n";
echo "White Queen render: " . $whiteQueen->render() . " (Expected: Q)\n";

echo "\n--- Testing Pawn Movement ---\n";
// White Pawn at E2 (Row 6, Col 4)
$whitePawn = new Pawn(PieceColor::WHITE, new Position(6, 4));
$board->placePiece($whitePawn);

// Black Pawn at E7 (Row 1, Col 4)
$blackPawn = new Pawn(PieceColor::BLACK, new Position(1, 4));
$board->placePiece($blackPawn);

echo "White Pawn at (6,4) can move to (5,4)? " . ($whitePawn->canMove($board, new Position(5, 4)) ? "Yes" : "No") . " (Expected: Yes)\n";
echo "White Pawn at (6,4) can move to (4,4)? " . ($whitePawn->canMove($board, new Position(4, 4)) ? "Yes" : "No") . " (Expected: Yes)\n";
echo "Black Pawn at (1,4) can move to (2,4)? " . ($blackPawn->canMove($board, new Position(2, 4)) ? "Yes" : "No") . " (Expected: Yes)\n";
echo "Black Pawn at (1,4) can move to (3,4)? " . ($blackPawn->canMove($board, new Position(3, 4)) ? "Yes" : "No") . " (Expected: Yes)\n";

echo "\n--- Done ---\n";
