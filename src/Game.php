<?php

class Game
{
    private Board $board;
    private PieceColor $currentPlayer;
    private PieceFactory $pieceFactory;

    public function __construct()
    {
        $this->board = new Board();
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

    /**
     * Joue un coup.
     *
     * Ordre imposé :
     * 1. Récupérer la pièce source -> NoPieceException
     * 2. Vérifier le tour du joueur -> WrongTurnException
     * 3. Vérifier le déplacement via la pièce -> InvalidMoveException / OccupiedByAllyException
     * 4. Déplacer la pièce
     * 5. Changer le joueur courant
     */
    public function play(Move $move): void
    {
        $from = $move->getFrom();
        $to = $move->getTo();

        // 1. Récupérer la pièce source
        $piece = $this->board->getPieceAt($from);
        if ($piece === null) {
            throw new NoPieceException("Aucune pièce sur la case " . $from->toKey());
        }

        // 2. Vérifier le tour du joueur
        if ($piece->getColor() !== $this->currentPlayer) {
            throw new WrongTurnException("Ce n'est pas le tour de cette couleur.");
        }

        // 3. Vérifier la case cible pour allié
        $targetPiece = $this->board->getPieceAt($to);
        if ($targetPiece !== null && $targetPiece->getColor() === $piece->getColor()) {
            throw new OccupiedByAllyException("La case cible est occupée par un allié.");
        }

        // 4. Vérifier le déplacement via la pièce
        if (!$piece->canMove($this->board, $to)) {
            throw new InvalidMoveException("Mouvement invalide pour cette pièce.");
        }

        // 5. Déplacer la pièce
        $this->board->movePiece($from, $to);

        // 6. Changer le joueur courant
        $this->switchPlayer();
    }

    /**
     * Détecte si le roi de la couleur donnée est en échec.
     */
    public function isCheck(PieceColor $color): bool
    {
        // 1. Retrouver la position du roi
        $kingPosition = $this->board->getKingPosition($color);
        if ($kingPosition === null) {
            return false;
        }

        // 2. Récupérer toutes les pièces adverses
        $opponentColor = $color->opposite();
        $pieces = $this->board->getPieces();

        // 3. Tester si l'une d'elles peut atteindre la case du roi
        foreach ($pieces as $piece) {
            if ($piece->getColor() === $opponentColor) {
                if ($piece->canMove($this->board, $kingPosition)) {
                    return true; // 4. Menace trouvée
                }
            }
        }

        // 5. Aucune menace
        return false;
    }

    /**
     * Place toutes les pièces sur le plateau dans la position initiale.
     */
    private function setupPieces(): void
    {
        // Ordre des pièces sur la ligne arrière : tour, cavalier, fou, reine, roi, fou, cavalier, tour
        $backRank = [
            PieceType::ROOK,
            PieceType::KNIGHT,
            PieceType::BISHOP,
            PieceType::QUEEN,
            PieceType::KING,
            PieceType::BISHOP,
            PieceType::KNIGHT,
            PieceType::ROOK,
        ];

        // Pièces noires - ligne 0
        for ($col = 0; $col <= 7; $col++) {
            $piece = $this->pieceFactory->create(
                $backRank[$col],
                PieceColor::BLACK,
                new Position(0, $col)
            );
            $this->board->placePiece($piece);
        }

        // Pions noirs - ligne 1
        for ($col = 0; $col <= 7; $col++) {
            $piece = $this->pieceFactory->create(
                PieceType::PAWN,
                PieceColor::BLACK,
                new Position(1, $col)
            );
            $this->board->placePiece($piece);
        }

        // Pions blancs - ligne 6
        for ($col = 0; $col <= 7; $col++) {
            $piece = $this->pieceFactory->create(
                PieceType::PAWN,
                PieceColor::WHITE,
                new Position(6, $col)
            );
            $this->board->placePiece($piece);
        }

        // Pièces blanches - ligne 7
        for ($col = 0; $col <= 7; $col++) {
            $piece = $this->pieceFactory->create(
                $backRank[$col],
                PieceColor::WHITE,
                new Position(7, $col)
            );
            $this->board->placePiece($piece);
        }
    }

    private function switchPlayer(): void
    {
        $this->currentPlayer = $this->currentPlayer->opposite();
    }
}
