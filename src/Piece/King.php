<?php

/**
 * Le Roi - piece la plus importante du jeu
 * bouge d'une case dans toutes les directions
 * peut faire le roque (petit et grand) si il a pas encore bouge
 */
class King extends Piece 
{

    /**
     * @param PieceColor $color la couleur du roi
     * @param Position $position sa position initiale
     */
    public function __construct(PieceColor $color, Position $position) {
        
        parent::__construct($color,$position);
        $this->type = PieceType::KING;

    }

    /**
     * forme du deplacement du roi :
     * - mouvement classique : 1 case max dans n'importe quelle direction
     * - roque : 2 cases horizontalement (seulement si il a pas bouge)
     * 
     * @param Position $target la case ou on veut aller
     * @return bool true si la forme est valide
     */
    protected function isValidMovementShape(Position $target):bool{
        $distanceRow = abs($this->position->getRow() - $target->getRow());
        $distanceCol = abs($this->position->getColumn() - $target->getColumn());
        if ($distanceRow == 0 && $distanceCol == 0){
            return false;
        }
        $classiqueMove = $distanceRow <= 1 && $distanceCol <= 1;
        $roqueMove = $distanceRow == 0 && $distanceCol == 2;
        
        if(!$this->hasMoved){
            //si le roi n'a pas encore bougé il peut bouger de 2 cases en ligne droite (roque) ou faire le mouvement classique
            return $classiqueMove || $roqueMove;    
        }else{
            // si la distance entre ma position actuel et celle ou je veux aller est inferieur ou = a 1 en row et column
            return $classiqueMove;
        }
    }

    /**
     * surcharge de canMove pour gerer le roque
     * si c'est un mouvement classique (1 case) on delegue au parent
     * si c'est un roque (2 cases) on verifie :
     * - que le parent valide les bases (chemin libre, forme valide)
     * - que la tour est bien la, c'est bien une tour, meme couleur, et elle a pas bouge
     * - pour le grand roque : que la case entre la tour et le roi (col 1) est vide aussi
     * 
     * @param Board $board le plateau de jeu
     * @param Position $target la case ou le roi veut aller
     * @return bool true si le deplacement est valide
     * @throws InvalidMoveException si le roque est pas possible
     */
    public function canMove(Board $board, Position $target): bool
    {
        $distanceCol = abs($this->position->getColumn() - $target->getColumn());

        // mouvement classique : on delegue au parent
        if ($distanceCol <= 1) {
            return parent::canMove($board, $target);
        }

        // tentative de roque
        if ($distanceCol === 2) {
            
            // on verifie les bases avec le parent (chemin libre, pas d'allie sur la cible)
            parent::canMove($board, $target);

            // on cherche la tour selon la direction du roque
            // petit roque : roi va en col 6, tour est en col 7
            // grand roque : roi va en col 2, tour est en col 0
            $rookCol = ($target->getColumn() === 6) ? 7 : 0;
            $rookPosition = new Position($this->position->getRow(), $rookCol);

            $rook = $board->getPieceAt($rookPosition);

            // on verifie que la tour est valide pour le roque
            if ($rook === null 
                || $rook->getType() !== PieceType::ROOK 
                || $rook->getColor() !== $this->color 
                || $rook->hasMoved()
            ) {
                throw new InvalidMoveException("Le roque est impossible : la tour n'est pas valide ou a déjà bougé");
            }

            // grand roque : on verifie que la case col 1 est libre aussi
            // (le parent a verifie les cases entre roi et cible, mais pas entre cible et tour)
            if ($rookCol === 0) {
                $pos1 = new Position($this->position->getRow(), 1);
                if ($board->hasPieceAt($pos1)) {
                    throw new InvalidMoveException("Le roque est impossible : des pièces bloquent le chemin");
                }
            }
            return true;
        }

        throw new InvalidMoveException();
    }
}
