<?php

namespace Ticktacktoe;

include_once "PlayerInterface.php";

class UserPlayer implements PlayerInterface
{

    private $side = 'x';

    public function __construct($side)
    {
        $this->side = $side;
    }

    public function getSide()
    {
        return $this->side;
    }

    public function makeStep(Game $game)
    {
        $game->printField();
        
        $free = $game->getFree();

        $input = null;

        while (1) {
            $input = readline();
            if (in_array($input, $free)) {
                break;
            }
        }
        
        $game->set($input, $this->side);
    }

    public function loose()
    {
        echo 'you loose' . "\n";
    }

    public function win()
    {
        echo 'you win' . "\n";
    }

    public function draw()
    {
        echo 'draw' . "\n";
    }
}
