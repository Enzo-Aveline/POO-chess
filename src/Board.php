<?php

class Board implements InterfaceBoard {

    private array $pieces = [];


    public function placePiece(Piece $piece): void
    {
        $position = $piece->getPosition();
        $this->pieces[$position->toKey()] = $piece;
    }

    public function getPieceAt(Position $position): ?Piece{
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

    public function movePiece(Position $from, Position $to): void{
        $piece = $this->getPieceAt($from);

        if ($piece === null) {
            throw new NoPieceException;
        }

        $this->removePieceAt($from);
        $this->removePieceAt($to);
        $piece->setPosition($to);
        $this->placePiece($piece);
    }

    public function isPathClear(Position $from, Position $to): bool{
        $rowDir = ($to->getRow() - $from->getRow()) <=> 0;
        $colDir = ($to->getColumn() - $from->getColumn()) <=> 0;

        $currentRow = $from->getRow() + $rowDir;
        $currentCol = $from->getColumn() + $colDir;

        while ($currentRow !== $to->getRow() || $currentCol !== $to->getColumn()) {
            if ($this->hasPieceAt(new Position($currentRow, $currentCol))) {
                return false;
            }
            $currentRow += $rowDir;
            $currentCol += $colDir;
        }
        return true;
    }

    public function getPieces(): array{
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
                    $result .= ". ";
                }
            }
            $result .= "\n";
        }

        // Affichage des lettres de colonnes en bas
        $result .= "    ----------------\n";
        $result .= "     0 1 2 3 4 5 6 7\n";

        return $result;
    }

}
