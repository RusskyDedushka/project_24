<?php

namespace Ticktacktoe;

include_once "PlayerInterface.php";
include_once "AI.php";

class AIPlayer implements PlayerInterface
{

    private $side = 'x';
    private $ai = null;
    private $oldState = null;

    public function __construct($side, AI $ai)
    {
        $this->side = $side;
        $this->ai = $ai;
    }

    public function getSide()
    {
        return $this->side;
    }

    public function makeStep(Game $game)
    {
        //получаем список доступных ходов
        $free = $game->getFree();
        
        //случайным образом решаем, является ли текущий ход 
        //зондирующим (случайным) или жадным (максимально выгодным)
        if (($r = rand(0, 100)) < 10) {
            //случайный ход
            echo 'random step'."\n";
            $step = $free[array_rand($free)];
            $game->set($step, $this->side);
            $this->oldState = $game->getState($this->side);
            return;
        }

        //жадный ход
        $rewards = [];
        foreach ($free as $step) {
            //для каждого доступного хода оцениваем состояние игры после него
            $newGame = clone($game);
            $newGame->set($step, $this->side);
            $rewards[$step] = $this->ai->getReward($newGame->getState($this->side));
        }

        //выясняем, какое вознаграждение оказалось максимальным
        $maxReward = 0;
        foreach ($rewards as $step => $reward) {
            if ($reward > $maxReward) {
                $maxReward = $reward;
            }
        }

        //находим все шаги с максимальным вознаграждением
        $steps = [];
        foreach ($rewards as $step => $reward) {
            if ($maxReward > $reward - 0.01 && $maxReward < $reward + 0.01) {
                $steps[] = $step;
            }
        }

        //корректируем оценку прошлого состояния
        //с учетом ценности нового состояния
        if (isset($this->oldState)) {
            $this->ai->correct($this->oldState, $maxReward);
        }

        //выбираем ход из ходов с максимальный вознаграждением
        $step = $steps[array_rand($steps)];
        $game->set($step, $this->side);

        //сохраняем текущее состояние для того, 
        //чтобы откорректировать её ценность на следующем ходе
        $this->oldState = $game->getState($this->side);
    }

    public function loose()
    {
        //корректируем ценность предыдущего состояния при проигрыше
        if (isset($this->oldState)) {
            $this->ai->correct($this->oldState, 0);
        }
    }

    public function win()
    {
        //корректируем ценность предыдущего состояния при выигрыше
        if (isset($this->oldState)) {
            $this->ai->correct($this->oldState, 1);
        }
    }
    
    public function draw()
    {
        //корректируем ценность предыдущего состояния при ничьей
        if (isset($this->oldState)) {
            $this->ai->correct($this->oldState, 0.5);
        }
    }

}
