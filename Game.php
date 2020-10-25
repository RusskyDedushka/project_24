<?php

namespace Ticktacktoe;

class Game {
    
    private $field = '';
    
    public function __construct($field = null)
    {
        if ($field) {
            $this->field = $field;
        } else {
            $this->start();
        }
    }
    
    public function start()
    {
        $this->field = str_repeat(' ', 9);
    }

    public function printField()
    {
        for ($i = 0; $i < strlen($this->field); $i ++) {
            $cell = $this->field[$i];
            echo '[';
            echo $cell <> ' ' ? $cell : ($i + 1);
            echo ']';
            if ($i % 3 === 2) {
                echo "\n";
            }
        }
    }
    
    public function set($position, $side)
    {
        $this->field[$position - 1] = $side;
    }

    public function getFree()
    {
        $free = [];
        for ($i = 0; $i < strlen($this->field); $i ++) {
            $cell = $this->field[$i];
            if ($cell === ' ') {
                $free[] = $i + 1;
            }
        }
        return $free;
    }
    
    public function isDraw()
    {
        $free = $this->getFree();
        return empty($free);
    }

    public function isWin($side)
    {
        for ($i = 0; $i < 3; $i ++) {
            $isWin = true;
            for ($j = 0; $j < 3; $j ++) {
                if ($this->field[$i * 3 + $j] != $side) {
                    $isWin = false;
                    break;
                }
            }
            if ($isWin) {
                return $isWin;
            }
        }

        for ($i = 0; $i < 3; $i ++) {
            $isWin = true;
            for ($j = 0; $j < 3; $j ++) {
                if ($this->field[$j * 3 + $i] != $side) {
                    $isWin = false;
                    break;
                }
            }
            if ($isWin) {
                return $isWin;
            }
        }

        $isWin = true;
        for ($i = 0; $i < 3; $i ++) {
            if ($this->field[$i * 3 + $i] != $side) {
                $isWin = false;
                break;
            }
        }
        if ($isWin) {
            return $isWin;
        }

        $isWin = true;
        for ($i = 0; $i < 3; $i ++) {
            if ($this->field[$i * 3 + 2 - $i] != $side) {
                $isWin = false;
                break;
            }
        }
        if ($isWin) {
            return $isWin;
        }

        return false;
    }

    public function getState($side)
    {
        if ($side === 'x') {
            return $this->field;
        }

        $newField = '';
        for ($i = 0; $i < strlen($this->field); $i++) {
            switch ($this->field[$i]) {
                case 'x': $newField .= 'o';
                    break;
                case 'o': $newField .= 'x';
                    break;
                default: $newField .= $this->field[$i];
                    break;
            }
        }
        return $newField;
    }

}
