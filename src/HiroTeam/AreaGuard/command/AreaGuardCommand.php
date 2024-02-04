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

use HiroTeam\AreaGuard\form\OpenAreaGuardUI;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class AreaGuardCommand extends Command {
	public function __construct() {
		parent::__construct('areaguard', 'AreaGuard command to open ui to create area protection', '/areaguard', ['ag']);
		$this->setPermission('areaguard.use');
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args) : void {
		if (!($sender instanceof Player)) {
			return;
		}
		if (!$this->testPermission($sender)) {
			return;
		}
		new OpenAreaGuardUI($sender);
	}
}
