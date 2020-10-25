<?php

namespace Ticktacktoe;

include_once "Game.php";

interface PlayerInterface
{

    public function getSide();

    public function makeStep(Game $game);

    public function loose();

    public function win();

    public function draw();
    
}
