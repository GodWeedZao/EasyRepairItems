<?php

declare(strict_types=1);

namespace GodWeedZao\EasyRepairItems;

use GodWeedZao\EasyRepairItems\Cooldown;
use GodWeedZao\EasyRepairItems\RepairCommands\RepairCommand;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\item\Armor;
use pocketmine\item\Item;
use pocketmine\item\Tool;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

class Main extends PluginBase implements Listener
{

    public static $cmd;
    public static $instance;
    public $goldencarrotCooldown = [];
    public $goldencarrotCooldownTime = [];

    /**
     * @param $msg
     * @return mixed
     */

    public static function Config($msg)
    {
        return self::$instance->getConfig()->get($msg);
    }

    public function onEnable(): void
    {
        @mkdir($this->getDataFolder());
        $this->saveDefaultConfig();
        if (($this->getConfig()->get("XP") === true) && ($this->getConfig()->get("MONEY") === true) || ($this->getConfig()->get("XP") === false) && ($this->getConfig()->get("MONEY") === false)) {
            $this->getServer()->getPluginManager()->disablePlugin($this);
            $this->getServer()->getLogger()->info(TextFormat::DARK_RED . TextFormat::BOLD . "Sorry for that, You can just Select XP or MONEY for EasyRepair Plugin.");
            return;
        }
        if ($this->getConfig()->get("Cooldown") === true) {
            $this->getScheduler()->scheduleRepeatingTask(new Cooldown($this), 20);
        }
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        self::$cmd = new RepairCommand ($this);
        self::$instance = $this;
    }

    /**
     * @return mixed
     */

    public function getInstance()
    {
        return self::$instance;
    }

    /**
     * @param $xp
     * @param $price
     * @return mixed
     */

    public function MinesXp($xp, $price)
    {
        $final = $xp - $price;
        return $final;
    }

    /**
     * @param CommandSender $player
     * @param Command $cmd
     * @param string $label
     * @param array $args
     * @return bool
     */

    public function onCommand(CommandSender $player, Command $cmd, string $label, array $args): bool
    {
        if ($this->getConfig()->get("Cooldown") === true) {
            if (!isset($this->goldencarrotCooldown[$player->getName()])) {
                self::$cmd->onCommand($player, $cmd, $label, $args);
                $this->goldencarrotCooldown[$player->getName()] = $player->getName();
                $time = self::Config("Cooldown-Time");
                $this->goldencarrotCooldownTime[$player->getName()] = $time;
            } else {
                $player->sendPopup("§e§l-=§3Please Use After§c " . $this->goldencarrotCooldownTime[$player->getName()] . "§3 Seconds§e=-");
            }
        } else {
            self::$cmd->onCommand($player, $cmd, $label, $args);
        }
        return true;
    }

    /**
     * @param Item $item
     * @return bool
     */

    public function isItem(Item $item)
    {
        return $item instanceof Tool;
    }

    /**
     * @param Item $item
     * @return bool
     */

    public function isArmor(Item $item)
    {
        return $item instanceof Armor;
    }
}
