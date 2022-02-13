<?php
/**
 * ██╗░░██╗██╗██████╗░░█████╗░████████╗███████╗░█████╗░███╗░░░███╗
 * ██║░░██║██║██╔══██╗██╔══██╗╚══██╔══╝██╔════╝██╔══██╗████╗░████║
 * ███████║██║██████╔╝██║░░██║░░░██║░░░█████╗░░███████║██╔████╔██║
 * ██╔══██║██║██╔══██╗██║░░██║░░░██║░░░██╔══╝░░██╔══██║██║╚██╔╝██║
 * ██║░░██║██║██║░░██║╚█████╔╝░░░██║░░░███████╗██║░░██║██║░╚═╝░██║
 * ╚═╝░░╚═╝╚═╝╚═╝░░╚═╝░╚════╝░░░░╚═╝░░░╚══════╝╚═╝░░╚═╝╚═╝░░░░░╚═╝
 * AreaGuard-HiroTeam By WillyDuGang
 *
 * GitHub: https://github.com/HiroshimaTeam/AreaGuard
 */

namespace HiroTeam\AreaGuard\command;

use HiroTeam\AreaGuard\AreaGuardMain;
use HiroTeam\AreaGuard\form\OpenAreaGuardUI;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\PluginOwned;

class AreaGuardCommand extends Command implements PluginOwned
{
    public function __construct()
    {
        parent::__construct('areaguard', 'AreaGuard command to open ui to create area protection', '/areaguard', ['ag']);
        $this->setPermission('areaguard.use');
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if(!($sender instanceof Player)) return true;
        if(!$this->testPermission($sender)){
            return true;
        }
        new OpenAreaGuardUI($sender);
        return true;
    }
    
    public function getOwningPlugin(): AreaGuardMain
    {
       return AreaGuardMain::getInstance();
    }
}
