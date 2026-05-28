<?php


/**
 * Classe abstraite Piece - la classe mere de toutes les pieces du jeu
 * elle implemente Renderable pour pouvoir s'afficher sur le plateau
 * 
 * Design Pattern : Template Method
 * canMove() contient la logique commune a toutes les pieces
 * isValidMovementShape() est la partie qui change selon la piece (abstract)
 */
abstract class Piece implements Renderable {
    protected PieceColor $color;
    protected Position $position;
    protected PieceType $type;
    protected bool $hasMoved;

    /**
     * @param PieceColor $color la couleur de la piece (WHITE ou BLACK)
     * @param Position $position la position initiale sur le plateau
     */
    public function __construct(PieceColor $color, Position $position) {
        $this->color = $color;
        $this->position = $position;
        $this->hasMoved = false;
    }

    /** 
     * verifie si la piece a deja bouge au moins une fois (utile pour le roque et le pion)
     * 
     * @return bool true si la piece a deja bouge
     */
    public function hasMoved(): bool {
        return $this->hasMoved;
    }
    
    /** @return PieceColor la couleur de la piece */
    public function getColor(): PieceColor {
        return $this->color;
    }
    
    /** @return Position la position actuelle de la piece sur le plateau */
    public function getPosition(): Position {
        return $this->position;
    }
    
    /**
     * change la position de la piece et marque qu'elle a bouge
     * c'est Board::movePiece() qui appelle ca
     * 
     * @param Position $position la nouvelle position
     */
    public function setPosition(Position $position): void {
        $this->position = $position;
        $this->hasMoved = true;
    }
    
    /** @return PieceType le type de la piece (KING, QUEEN, ROOK, etc.) */
    public function getType(): PieceType {
        return $this->type;
    }
    
    /**
     * affiche la piece en une lettre
     * majuscule = blanc, minuscule = noir
     * ex: K = roi blanc, p = pion noir
     * 
     * @return string la lettre qui represente la piece
     */
    public function render(): string {
        switch ($this->type) {
            case PieceType::KING:
                $lettre = "k";
                break;
            case PieceType::QUEEN:
                $lettre = "q";
                break;
            case PieceType::ROOK:
                $lettre = "r";
                break;
            case PieceType::BISHOP:
                $lettre = "b";
                break;
            case PieceType::KNIGHT:
                $lettre = "n";
                break;
            case PieceType::PAWN:
                $lettre = "p";
                break;
        }
        if ($this->color === PieceColor::WHITE){
            $lettre = strtoupper($lettre);
        }
        return $lettre;
    }
    
    /**
     * Template Method : verifie si la piece peut bouger vers la case cible
     * 
     * l'ordre des verifications :
     * 1. la piece reste pas sur place
     * 2. la forme du deplacement est valide (delegue a isValidMovementShape)
     * 3. la case cible est pas occupee par un allie
     * 4. le chemin est libre (sauf pour le cavalier qui saute par dessus)
     * 
     * @param Board $board le plateau de jeu
     * @param Position $target la case ou on veut aller
     * @return bool true si le deplacement est valide
     * @throws InvalidMoveException si la forme ou le chemin est pas bon
     * @throws OccupiedByAllyException si un allie est sur la case cible
     */
    public function canMove(Board $board, Position $target): bool {
        // 1. on bouge pas sur place
        if ($this->position->equals($target)){
            return false;
        }

        // 2. on verifie que la forme du deplacement est valide pour cette piece
        if (!$this->isValidMovementShape($target)){
            throw new InvalidMoveException;
        }

        // 3. on verifie que la case cible est pas occupee par un allie
        $targetPiece = $board->getPieceAt($target);
        if ($targetPiece !== null && $targetPiece->getColor() === $this->color){
            throw new OccupiedByAllyException;
        }
        
        // 4. on verifie que le chemin est libre (le cavalier il saute donc on check pas)
        if ($this->type !== PieceType::KNIGHT){
            if(!$board->isPathClear($this->position,$target)){
                throw new InvalidMoveException;
            }
        }

        return true;
    }
    
    /**
     * methode abstraite : chaque piece definit sa propre forme de deplacement
     * ex: le fou bouge en diagonale, la tour en ligne droite, etc.
     * 
     * @param Position $target la case ou on veut aller
     * @return bool true si la forme du deplacement est valide pour cette piece
     */
    abstract protected function isValidMovementShape(Position $target): bool;
    
    /**
     * verifie si il y a une piece sur la case cible (pour les captures)
     * utilise par le pion qui peut capturer que en diagonale
     * 
     * @param Board $board le plateau de jeu
     * @param Position $target la case qu'on veut verifier
     * @return bool true si il y a une piece sur la case
     */
    protected function canCapture(Board $board, Position $target): bool {
        return $board->hasPieceAt($target);
    }
    
}