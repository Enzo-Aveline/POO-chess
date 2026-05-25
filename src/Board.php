<?php

class Board implements Renderable {

    private array $pieces = [];

    public function placePiece(Piece $piece): void
    {
        $position = $piece->getPosition();
        $this->pieces[$position->toKey()] = $piece;
    }

    public function getPieceAt(Position $position): ?Piece {
        $key = $position->toKey();
        if ($this->hasPieceAt($position)) {
            return $this->pieces[$key];
        }
        return null;
    }

    public function hasPieceAt(Position $position): bool
    {
        return isset($this->pieces[$position->toKey()]);
    }

    public function removePieceAt(Position $position): void
    {
        unset($this->pieces[$position->toKey()]);
    }

    public function movePiece(Position $from, Position $to): void {
        $piece = $this->getPieceAt($from);

        // Supprimer la pièce de l'ancienne position
        $this->removePieceAt($from);

        // Supprimer une éventuelle pièce capturée sur la case cible
        if ($this->hasPieceAt($to)) {
            $this->removePieceAt($to);
        }

        // Mettre à jour la position de la pièce et la placer
        $piece->setPosition($to);
        $this->placePiece($piece);
    }

    public function isPathClear(Position $from, Position $to): bool {
        $rowDiff = $to->getRow() - $from->getRow();
        $colDiff = $to->getColumn() - $from->getColumn();

        // Déterminer la direction du déplacement
        $rowStep = ($rowDiff === 0) ? 0 : ($rowDiff > 0 ? 1 : -1);
        $colStep = ($colDiff === 0) ? 0 : ($colDiff > 0 ? 1 : -1);

        // Parcourir les cases intermédiaires (exclure from et to)
        $currentRow = $from->getRow() + $rowStep;
        $currentCol = $from->getColumn() + $colStep;

        while ($currentRow !== $to->getRow() || $currentCol !== $to->getColumn()) {
            $intermediatePos = new Position($currentRow, $currentCol);
            if ($this->hasPieceAt($intermediatePos)) {
                return false;
            }
            $currentRow += $rowStep;
            $currentCol += $colStep;
        }

        return true;
    }

    public function getPieces(): array {
        return $this->pieces;
    }

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

    public function render(): string
    {
        $result = "";

        // On commence par la ligne 0 (Haut) et on descend vers 7 (Bas)
        for ($row = 0; $row <= 7; $row++) {

            // Petit indicateur de numéro de ligne sur le côté
            $result .= $row . " | ";

            for ($col = 0; $col <= 7; $col++) {
                $pos = new Position($row, $col);
                $piece = $this->getPieceAt($pos);

                if ($piece !== null) {
                    $result .= $piece->render() . " ";
                } else {
                    // Case vide : on affiche un point
                    $result .= ". ";
                }
            }
            $result .= "\n"; // Saut de ligne après chaque rangée
        }

        // Affichage des lettres de colonnes en bas
        $result .= "    ----------------\n";
        $result .= "     0 1 2 3 4 5 6 7\n";

        return $result;
    }

}
