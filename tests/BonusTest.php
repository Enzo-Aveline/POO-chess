<?php

use PHPUnit\Framework\TestCase;

/**
 * Classe de test pour valider l'ensemble des bonus implémentés dans le TP POO Chess.
 * Teste spécifiquement :
 * - L'interdiction d'exposer son propre roi.
 * - La prise en passant.
 * - La promotion automatique.
 * - L'échec et mat.
 */
class BonusTest extends TestCase
{
    private Game $game;
    private Board $board;

    protected function setUp(): void
    {
        $this->game = new Game();
        // Pour les tests spécifiques, on vide souvent le plateau pour créer des situations
        $this->board = $this->game->getBoard();
    }

    private function clearBoard(): void
    {
        $pieces = $this->board->getPieces();
        foreach ($pieces as $piece) {
            $this->board->removePieceAt($piece->getPosition());
        }
    }

    /**
     * Teste qu'un joueur ne peut pas effectuer un mouvement qui mettrait
     * ou laisserait son propre roi en échec (exposerait son roi).
     */
    public function testExposingKingThrowsException()
    {
        $this->clearBoard();
        $factory = new PieceFactory();
        
        // Roi blanc en 7,4
        $this->board->placePiece($factory->create(PieceType::KING, PieceColor::WHITE, new Position(7, 4)));
        // Tour noire en 7,0
        $this->board->placePiece($factory->create(PieceType::ROOK, PieceColor::BLACK, new Position(7, 0)));
        // Cavalier blanc en 7,2 (entre la tour et le roi)
        $this->board->placePiece($factory->create(PieceType::KNIGHT, PieceColor::WHITE, new Position(7, 2)));

        $this->expectException(ExposingKingException::class);
        
        // Le cavalier blanc bouge en 5,3, exposant le roi blanc à la tour noire
        $this->game->play(new Move(new Position(7, 2), new Position(5, 3)));
    }

    /**
     * Teste la règle spéciale de la "Prise en passant" pour les pions.
     */
    public function testEnPassant()
    {
        $this->clearBoard();
        $factory = new PieceFactory();
        
        // Pion noir en 1,4 (ligne de départ)
        $this->board->placePiece($factory->create(PieceType::PAWN, PieceColor::BLACK, new Position(1, 4)));
        
        // Pion blanc en 3,3 (avancé)
        $this->board->placePiece($factory->create(PieceType::PAWN, PieceColor::WHITE, new Position(3, 3)));
        
        // Roi blanc pour avoir le bon tour (c'est au tour des noirs car on force un peu le jeu pour le test)
        // on va juste simuler le double saut noir d'abord
        $this->board->movePiece(new Position(1, 4), new Position(3, 4));
        
        // Maintenant, le pion blanc peut faire une prise en passant en diagonale sur 2,4
        $this->assertTrue($this->board->getPieceAt(new Position(3, 3))->canMove($this->board, new Position(2, 4)));
        
        // On effectue la prise en passant via le board (comme Game::play le ferait après vérifications)
        $this->board->movePiece(new Position(3, 3), new Position(2, 4));
        
        // Le pion adverse en 3,4 doit avoir été supprimé
        $this->assertFalse($this->board->hasPieceAt(new Position(3, 4)));
        $this->assertTrue($this->board->hasPieceAt(new Position(2, 4)));
    }

    /**
     * Teste la règle de la promotion du pion lorsqu'il atteint la dernière ligne.
     */
    public function testPromotion()
    {
        $this->clearBoard();
        $factory = new PieceFactory();
        
        // Pion blanc en 1,0 (prêt à promouvoir)
        $this->board->placePiece($factory->create(PieceType::PAWN, PieceColor::WHITE, new Position(1, 0)));
        // Roi blanc quelque part pour que Game soit content si on fait des checks (optionnel)
        $this->board->placePiece($factory->create(PieceType::KING, PieceColor::WHITE, new Position(7, 4)));

        // On joue le coup
        $this->game->play(new Move(new Position(1, 0), new Position(0, 0)));

        // On vérifie que c'est devenu une reine blanche
        $promotedPiece = $this->board->getPieceAt(new Position(0, 0));
        $this->assertNotNull($promotedPiece);
        $this->assertEquals(PieceType::QUEEN, $promotedPiece->getType());
        $this->assertEquals(PieceColor::WHITE, $promotedPiece->getColor());
    }

    /**
     * Teste l'algorithme de détection de l'échec et mat.
     */
    public function testCheckmate()
    {
        $this->clearBoard();
        $factory = new PieceFactory();
        
        // Couloir de la mort pour le roi noir
        // Roi noir en 0,0
        $this->board->placePiece($factory->create(PieceType::KING, PieceColor::BLACK, new Position(0, 0)));
        // Pions noirs bloquant le roi vers le bas : (1,0) et (1,1)
        $this->board->placePiece($factory->create(PieceType::PAWN, PieceColor::BLACK, new Position(1, 0)));
        $this->board->placePiece($factory->create(PieceType::PAWN, PieceColor::BLACK, new Position(1, 1)));
        
        // Tour blanche vient se mettre en 0,7 pour mater le roi sur la ligne 0
        $this->board->placePiece($factory->create(PieceType::ROOK, PieceColor::WHITE, new Position(0, 7)));

        // On vérifie le mat
        $this->assertTrue($this->game->isCheck(PieceColor::BLACK));
        $this->assertTrue($this->game->isCheckmate(PieceColor::BLACK));
    }
}
