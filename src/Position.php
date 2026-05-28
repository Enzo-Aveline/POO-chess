<?php 

/**
 * Value Object qui represente une position sur le plateau (row, column)
 * immutable : une fois créé on peut pas changer les valeurs
 */
class Position {

    private int $row;
    private int $column;

    /**
     * on cree une position avec une ligne et une colonne
     * si les valeurs sont pas entre 0 et 7 on throw une exception
     * 
     * @param int $row la ligne (0 = haut, 7 = bas)
     * @param int $column la colonne (0 = gauche, 7 = droite)
     * @throws \InvalidArgumentException si les valeurs sont hors du plateau
     */
    public function __construct(int $row, int $column){
        if ($row <= 7 && $row >=0 && $column <= 7 && $column >=0){
            $this->row = $row;
            $this->column = $column;
        }else{
            throw new \InvalidArgumentException("Positions must be between 0 and 7.");
        }
    }

    /** @return int la ligne de la position */
    public function getRow(): int {
        return $this->row;
    }

    /** @return int la colonne de la position */
    public function getColumn(): int{
        return $this->column;
    }

    /**
     * compare deux positions pour savoir si c'est la meme case
     * 
     * @param Position $other la position a comparer
     * @return bool true si c'est la meme case
     */
    public function equals(Position $other): bool {
        return $other->column === $this->column && $other->row === $this->row;
    }

    /**
     * transforme la position en cle string genre "3:4" pour stocker dans un tableau associatif
     * 
     * @return string la cle au format "row:col"
     */
    public function toKey(): string {
        return sprintf("%d:%d", $this->row, $this->column);
    }

    /**
     * fait l'inverse de toKey : on prend une string "3:4" et on recree une Position
     * 
     * @param string $key la cle au format "row:col"
     * @return Position la position recréée
     */
    public static function fromKey(string $key): Position{
        $parts = explode(":", $key);
        $column = (int) $parts[1]; 
        $row = (int) $parts[0];
        return new Position($row,$column);
    }

}