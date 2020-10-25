<?php

namespace Ticktacktoe;

class AI
{

    private $table = [];

    public function __construct()
    {
        if (file_exists('rewards.json')) {
            $this->table = json_decode(file_get_contents('rewards.json'), true);
        }
    }

    public function getReward($state)
    {
        $game = new Game($state);
        //если победитель - мы, то оценка состояния игры "1"
        if ($game->isWin($state, 'x')) {
            return 1;
        }

        //если победиль - соперник, то оценка состояния игры "0"
        if ($game->isWin($state, 'o')) {
            return 0;
        }

        //смотрим ценность по таблице
        if (isset($this->table[$state])) {
            return $this->table[$state];
        }

        //если в таблице нет, то считаем начальной ценностью "0.5"
        return 0.5;
    }

    public function correct($state, $newReward)
    {
        $oldReward = $this->getReward($state);
        $this->table[$state] = $oldReward + 0.1 * ($newReward - $oldReward);
        echo 'correct ' . $state . ' => ' . $this->table[$state] . "\n";
    }

    public function save()
    {
        file_put_contents('rewards.json', json_encode($this->table, JSON_UNESCAPED_UNICODE));
    }

}
