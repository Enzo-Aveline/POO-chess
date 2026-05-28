<?php

/**
 * Le plateau de jeu
 * gere uniquement l'etat du plateau : placer, enlever, deplacer les pieces
 * il fait aucune verification metier (c'est Game qui fait ca)
 * implemente Renderable pour s'afficher en string
 */
class Board implements Renderable {

    /** @var array<string, Piece> tableau associatif : cle = "row:col", valeur = objet Piece */
    private array $pieces = [];


    /**
     * place une piece sur le plateau a sa position actuelle
     * 
     * @param Piece $piece la piece a placer
     */
    public function placePiece(Piece $piece): void
    {
        $position = $piece->getPosition();
        $this->pieces[$position->toKey()] = $piece;
    }

    /**
     * retourne la piece a une position donnee, ou null si la case est vide
     * 
     * @param Position $position la case qu'on veut regarder
     * @return Piece|null la piece sur la case, ou null si c'est vide
     */
    public function getPieceAt(Position $position): ?Piece{
        $key = $position->toKey();
        if ($this->hasPieceAt($position)) {
            return $this->pieces[$key];
        }
        return null;
    }

    /**
     * verifie si une case est occupee par une piece
     * 
     * @param Position $position la case a verifier
     * @return bool true si il y a une piece dessus
     */
    public function hasPieceAt(Position $position): bool
    {
        return isset($this->pieces[$position->toKey()]);
    }

    /**
     * enleve la piece d'une case
     * 
     * @param Position $position la case a vider
     */
    public function removePieceAt(Position $position): void
    {
        unset($this->pieces[$position->toKey()]);
    }

    /**
     * deplace une piece de la case "from" vers la case "to"
     * gere aussi le roque : si c'est un roi qui bouge de 2 cases,
     * on deplace automatiquement la tour associee
     * 
     * @param Position $from la case de depart
     * @param Position $to la case d'arrivee
     * @throws NoPieceException si il y a pas de piece sur la case de depart
     */
    public function movePiece(Position $from, Position $to): void{
        $piece = $this->getPieceAt($from);

        if ($piece === null) {
            throw new NoPieceException;
        }

        // on enleve la piece de depart et la piece sur la case d'arrivee (si capture)
        $this->removePieceAt($from);
        $this->removePieceAt($to);
        $piece->setPosition($to);
        $this->placePiece($piece);

        // roque : si c'est un roi qui bouge de 2 cases on deplace la tour aussi
        if ($piece->getType() === PieceType::KING && abs($to->getColumn() - $from->getColumn()) === 2) {
            // on cherche la tour selon la direction
            $rookCol = ($to->getColumn() === 6) ? 7 : 0;
            $rookPosition = new Position($to->getRow(), $rookCol);
            // on calcule ou la tour doit atterrir (a cote du roi)
            $newRookPosition = new Position($to->getRow(), ($to->getColumn() === 6) ? 5 : 3);
            // on deplace la tour recursivement
            $this->movePiece($rookPosition, $newRookPosition);
        }
    }

    /**
     * verifie que le chemin entre deux cases est libre (pas de piece entre les deux)
     * on check que les cases intermediaires, pas la case de depart ni d'arrivee
     * utilise le spaceship operator (<=>) pour calculer la direction du deplacement
     * 
     * @param Position $from la case de depart
     * @param Position $to la case d'arrivee
     * @return bool true si le chemin est libre
     */
    public function isPathClear(Position $from, Position $to): bool{
        // direction : -1, 0 ou 1 pour chaque axe
        $rowDir = ($to->getRow() - $from->getRow()) <=> 0;
        $colDir = ($to->getColumn() - $from->getColumn()) <=> 0;

        // on part de la case juste apres "from" et on avance case par case
        $currentRow = $from->getRow() + $rowDir;
        $currentCol = $from->getColumn() + $colDir;

        // tant qu'on est pas arrive a "to", on verifie que chaque case est vide
        while ($currentRow !== $to->getRow() || $currentCol !== $to->getColumn()) {
            if ($this->hasPieceAt(new Position($currentRow, $currentCol))) {
                return false;
            }
            $currentRow += $rowDir;
            $currentCol += $colDir;
        }
        return true;
    }

    /**
     * retourne toutes les pieces du plateau
     * 
     * @return array<string, Piece> toutes les pieces indexees par leur position "row:col"
     */
    public function getPieces(): array{
        return $this->pieces;
    }

    /**
     * cherche la position du roi d'une couleur donnee sur le plateau
     * 
     * @param PieceColor $color la couleur du roi qu'on cherche
     * @return Position|null la position du roi, ou null si il est pas trouvé
     */
    public function getKingPosition(PieceColor $color): ?Position
    {
        $pieces = $this->getPieces();
        foreach ($pieces as $piece) {
            if ($piece->getColor() === $color && $piece->getType() === PieceType::KING) {
                return $piece->getPosition();
            }
        }
        return null;
    }

    /**
     * affiche le plateau en string
     * ligne 0 en haut (noir), ligne 7 en bas (blanc)
     * majuscule = blanc, minuscule = noir, point = case vide
     * 
     * @return string le plateau formaté en string
     */
    public function render(): string
    {
        $result = "";

        for ($row = 0; $row <= 7; $row++) {

            $result .= $row . " | ";

            for ($col = 0; $col <= 7; $col++) {
                $pos = new Position($row, $col);
                $piece = $this->getPieceAt($pos);

                if ($piece !== null) {
                    $result .= $piece->render() . " ";
                } else {
                    $result .= ". ";
                }
            }
            $result .= "\n";
        }

        $result .= "    ----------------\n";
        $result .= "     0 1 2 3 4 5 6 7\n";

        return $result;
    }

}
