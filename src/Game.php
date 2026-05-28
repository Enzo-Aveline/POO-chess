<?php

class Game {
    private Board $board;
    private PieceColor $currentPlayer;
    private PieceFactory $pieceFactory;

    public function __construct()
    {
        $this->board = new Board;
        $this->currentPlayer = PieceColor::WHITE;
        $this->pieceFactory = new PieceFactory();
    }

    public function start(): void
    {
        $this->setupPieces();
    }

    public function getBoard(): Board
    {
        return $this->board;
    }

    public function getCurrentPlayer(): PieceColor
    {
        return $this->currentPlayer;
    }

    public function play(Move $move): void
    {
        $piece = $this->board->getPieceAt($move->getFrom());

        if($piece === null){
            throw new NoPieceException;
        }

        if($piece->getColor() !== $this->currentPlayer){
            throw new WrongTurnException;
        }

        if(!$piece->canMove($this->board,$move->getTo())){
            throw new InvalidMoveException;
        }

        //verification occupiedByAllyException fait dans canMove()
        //verification InvalidMoveException fait dans canMove()

        $this->board->movePiece($move->getFrom(), $move->getTo());
        $this->switchPlayer();
    }

    public function isCheck(PieceColor $color): bool
    {
        $kingPosition = $this->board->getKingPosition($color);
        $pieces = $this->board->getPieces();
        foreach($pieces as $piece){
            if ($piece->getColor() === $color) {
                continue;
            }
            try {
                if($piece->canMove($this->board,$kingPosition)){
                    return true;
                }
            } catch (ChessException $e) {}
        }
        return false;
    }

    private function setupPieces(): void
    {
        // l'ordre des pieces sur la ligne du fond (col 0 a 7)
        $backRow = [
            PieceType::ROOK,
            PieceType::KNIGHT,
            PieceType::BISHOP,
            PieceType::QUEEN,
            PieceType::KING,
            PieceType::BISHOP,
            PieceType::KNIGHT,
            PieceType::ROOK,
        ];

        // noir en haut (ligne 0), blanc en bas (ligne 7)
        $setup = [
            PieceColor::BLACK => ['backRow' => 0, 'pawnRow' => 1],
            PieceColor::WHITE => ['backRow' => 7, 'pawnRow' => 6],
        ];

        foreach ($setup as $color => $rows) {
            // on place les pieces du fond
            foreach ($backRow as $col => $type) {
                $this->board->placePiece(
                    $this->pieceFactory->create($type, $color, new Position($rows['backRow'], $col))
                );
            }
            // on place les pions
            for ($i = 0; $i < 8; $i++) {
                $this->board->placePiece(
                    $this->pieceFactory->create(PieceType::PAWN, $color, new Position($rows['pawnRow'], $i))
                );
            }
        }
    }

    private function switchPlayer(): void
    {
        $this->currentPlayer = $this->currentPlayer->opposite();
    }
}