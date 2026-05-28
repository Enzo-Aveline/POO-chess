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

        // L'interdiction d'exposer son propre roi
        if ($this->wouldExposeKing($move, $this->currentPlayer)) {
            throw new ExposingKingException;
        }

        // Règles supplémentaires pour le Roque
        if ($piece->getType() === PieceType::KING && abs($move->getTo()->getColumn() - $move->getFrom()->getColumn()) === 2) {
            if ($this->isCheck($this->currentPlayer)) {
                throw new InvalidMoveException("Le roque est impossible : le roi est en échec");
            }
            $direction = ($move->getTo()->getColumn() > $move->getFrom()->getColumn()) ? 1 : -1;
            $passThroughPos = new Position($move->getFrom()->getRow(), $move->getFrom()->getColumn() + $direction);
            $passThroughMove = new Move($move->getFrom(), $passThroughPos);
            if ($this->wouldExposeKing($passThroughMove, $this->currentPlayer)) {
                throw new InvalidMoveException("Le roque est impossible : le roi passe par une case contrôlée");
            }
        }

        $this->board->movePiece($move->getFrom(), $move->getTo());

        // La promotion (automatique en Reine)
        $movedPiece = $this->board->getPieceAt($move->getTo());
        if ($movedPiece !== null && $movedPiece->getType() === PieceType::PAWN) {
            $promotionRow = ($this->currentPlayer === PieceColor::WHITE) ? 0 : 7;
            if ($move->getTo()->getRow() === $promotionRow) {
                $this->board->removePieceAt($move->getTo());
                $this->board->placePiece($this->pieceFactory->create(PieceType::QUEEN, $this->currentPlayer, $move->getTo()));
            }
        }

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

    /**
     * Simule un mouvement pour vérifier s'il exposerait le roi de la couleur donnée.
     * Utilisé pour empêcher un joueur de se mettre lui-même en échec.
     *
     * @param Move $move Le mouvement à simuler.
     * @param PieceColor $color La couleur du joueur.
     * @return bool True si le mouvement expose le roi, False sinon.
     */
    public function wouldExposeKing(Move $move, PieceColor $color): bool
    {
        $originalBoard = $this->board;
        $this->board = clone $originalBoard;
        
        try {
            // on simule le mouvement
            // on ne fait pas les vérifications de Game::play, juste Board::movePiece
            $this->board->movePiece($move->getFrom(), $move->getTo());
        } catch (ChessException $e) {
            // ignoré
        }
        
        $isExposed = $this->isCheck($color);
        
        $this->board = $originalBoard;
        
        return $isExposed;
    }

    /**
     * Vérifie si un joueur est échec et mat.
     * Un joueur est échec et mat s'il est en échec et qu'aucun de ses
     * mouvements possibles ne permet de parer l'échec.
     *
     * @param PieceColor $color La couleur du joueur à vérifier.
     * @return bool True si c'est échec et mat.
     */
    public function isCheckmate(PieceColor $color): bool
    {
        if (!$this->isCheck($color)) {
            return false;
        }

        $pieces = $this->board->getPieces();
        foreach ($pieces as $piece) {
            if ($piece->getColor() !== $color) {
                continue;
            }

            for ($row = 0; $row <= 7; $row++) {
                for ($col = 0; $col <= 7; $col++) {
                    $targetPos = new Position($row, $col);
                    try {
                        if ($piece->canMove($this->board, $targetPos)) {
                            $move = new Move($piece->getPosition(), $targetPos);
                            if (!$this->wouldExposeKing($move, $color)) {
                                return false; // On a trouvé un coup valide
                            }
                        }
                    } catch (ChessException $e) {
                        // Mouvement invalide, on ignore
                    }
                }
            }
        }

        return true; // Aucun coup valide trouvé, c'est mat
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
            ['color' => PieceColor::BLACK, 'backRow' => 0, 'pawnRow' => 1],
            ['color' => PieceColor::WHITE, 'backRow' => 7, 'pawnRow' => 6],
        ];

        foreach ($setup as $rows) {
            $color = $rows['color'];
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