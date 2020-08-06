<?php

namespace GodWeedZao\EasyRepairItems;

use pocketmine\scheduler\Task;

class Cooldown extends Task
{

    public function __construct($plugin)
    {
        $this->plugin = $plugin;
    }

    public function onRun($tick)
    {
        foreach ($this->plugin->goldencarrotCooldown as $player) {
            if ($this->plugin->goldencarrotCooldownTime[$player] <= 0) {
                unset($this->plugin->goldencarrotCooldown[$player]);
                unset($this->plugin->goldencarrotCooldownTime[$player]);
            } else {
                $this->plugin->goldencarrotCooldownTime[$player]--;
            }
        }
    }
}