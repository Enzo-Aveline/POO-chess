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
        //TODO version pas opti à voir plus tard si j'ai le temps
        // Pièces noires en haut (lignes 0 et 1)
        $this->board->placePiece($this->pieceFactory->create(PieceType::ROOK,PieceColor::BLACK,new Position(0,0)));
        $this->board->placePiece($this->pieceFactory->create(PieceType::KNIGHT,PieceColor::BLACK,new Position(0,1)));
        $this->board->placePiece($this->pieceFactory->create(PieceType::BISHOP,PieceColor::BLACK,new Position(0,2)));
        $this->board->placePiece($this->pieceFactory->create(PieceType::QUEEN,PieceColor::BLACK,new Position(0,3)));
        $this->board->placePiece($this->pieceFactory->create(PieceType::KING,PieceColor::BLACK,new Position(0,4)));
        $this->board->placePiece($this->pieceFactory->create(PieceType::BISHOP,PieceColor::BLACK,new Position(0,5)));
        $this->board->placePiece($this->pieceFactory->create(PieceType::KNIGHT,PieceColor::BLACK,new Position(0,6)));
        $this->board->placePiece($this->pieceFactory->create(PieceType::ROOK,PieceColor::BLACK,new Position(0,7)));

        for ($i=0 ; $i<8; $i++){
            $this->board->placePiece($this->pieceFactory->create(PieceType::PAWN,PieceColor::BLACK,new Position(1,$i)));
        }

        // Pièces blanches en bas (lignes 6 et 7)
        $this->board->placePiece($this->pieceFactory->create(PieceType::ROOK,PieceColor::WHITE,new Position(7,0)));
        $this->board->placePiece($this->pieceFactory->create(PieceType::KNIGHT,PieceColor::WHITE,new Position(7,1)));
        $this->board->placePiece($this->pieceFactory->create(PieceType::BISHOP,PieceColor::WHITE,new Position(7,2)));
        $this->board->placePiece($this->pieceFactory->create(PieceType::QUEEN,PieceColor::WHITE,new Position(7,3)));
        $this->board->placePiece($this->pieceFactory->create(PieceType::KING,PieceColor::WHITE,new Position(7,4)));
        $this->board->placePiece($this->pieceFactory->create(PieceType::BISHOP,PieceColor::WHITE,new Position(7,5)));
        $this->board->placePiece($this->pieceFactory->create(PieceType::KNIGHT,PieceColor::WHITE,new Position(7,6)));
        $this->board->placePiece($this->pieceFactory->create(PieceType::ROOK,PieceColor::WHITE,new Position(7,7)));

        for ($i=0 ; $i<8; $i++){
            $this->board->placePiece($this->pieceFactory->create(PieceType::PAWN,PieceColor::WHITE,new Position(6,$i)));
        }
    }

    private function switchPlayer(): void
    {
        $this->currentPlayer = $this->currentPlayer->opposite();
    }
}