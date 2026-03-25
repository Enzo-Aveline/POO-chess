<?php 

class Position {

    private int $row;
    private int $column;

    public function __construct(int $row, int $column){
        if ($row <= 7 && $row >=0 && $column <= 7 && $column >=0){
            $this->row = $row;
            $this->column = $column;
        }else{
            throw new \InvalidArgumentException("Positions must be between 0 and 7.");
        }
    }

    public function getRow(): int {
        return $this->row;
    }

    public function getColumn(): int{
        return $this->column;
    }

    public function equals(Position $other): bool {
        return $other->column === $this->column && $other->row === $this->row;
    }

    public function toKey(): string {
        return sprintf("%d:%d", $this->row, $this->column);
    }

    public static function fromKey(string $key): Position{
        $parts = explode(":", $key);
        $column = (int) $parts[1]; 
        $row = (int) $parts[0];
        return new Position($row,$column);
    }

}