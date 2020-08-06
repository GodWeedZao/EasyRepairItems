<?php

namespace GodWeedZao\EasyRepairItems\RepairCommands;

use GodWeedZao\EasyRepairItems\Main;
use GodWeedZao\EasyRepairItems\RepairCommands\RepairArmor;
use GodWeedZao\EasyRepairItems\RepairCommands\RepairItems;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;

class RepairCommand
{

    private $plugin;

    /**
     * RepairCommand constructor.
     * @param Main $plugin
     */

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
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
        {
            if (!$player instanceof Player) {
                $player->sendMessage("§l§4Use This Command In Game.");
                return true;
            }
            if ($cmd->getName() === "repair") {
                if (!isset($args[0])) {
                    $player->sendMessage("§l§cUsage: §4/repair item");
                    if (Main::Config("MONEY") === true) {
                        $player->sendMessage("§l§c>§aPaymentMode§2: §eMoney");
                        $EconomyAPI = Server::getInstance()->getPluginManager()->getPlugin("EconomyAPI");
                        $money = $EconomyAPI->myMoney($player);
                        $player->sendMessage("§l§c>§aYour Money§2: §6{$money}");
                    }
                    if (Main::Config("XP") === true) {
                        $player->sendMessage("§l§c>§aPaymentMode§2: §eXp");
                        $xp = $player->getXpLevel();
                        $player->sendMessage("§l§c>§aYour Xp§2: §6{$xp}");
                    }
                }
                if ((isset($args[0])) && ($args[0] === "item")) {
                    $slot = $player->getInventory()->getHeldItemIndex();
                    $Item = $player->getInventory()->getItem($slot);
                    if ($this->plugin->isItem($Item) || $this->plugin->isArmor($Item)) {
                        $repairItem = new RepairItems ($this->plugin->getInstance());
                        $repairItem->RepairItem($player);
                    } else {
                        $player->sendMessage("§l§4Item Held Item is not Tool/Armor, Repair Failed.");
                    }
                }
            }
        }
        return true;
    }
}
    