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

namespace HiroTeam\AreaGuard\form\base;

use HiroTeam\AreaGuard\AreaGuardMain;
use HiroTeam\AreaGuard\libs\forms\SimpleForm;
use pocketmine\player\Player;

abstract class BaseSimpleForm extends SimpleForm {
	public function __construct(Player $player) {
		parent::__construct();
		$this->send($player);
	}

	private function send(Player $player) : void {
		$langManager = AreaGuardMain::getInstance()->getLangManager();
		$this->setTitle($langManager->getTranslateReference('UI_AREAGUARD_TITLE'));
		$this->makeUI($this, $player);
		$this->setCallable($this->makeHandler());
		$this->sendToPlayer($player);
	}

	abstract protected function makeUI(SimpleForm $form, Player $player) : void;

	private function makeHandler() : ?callable {
		return function (Player $player, $data = null) : void {
			if ($data !== null) {
				$this->handler($player, $data);
			}
		};
	}

	abstract protected function handler(Player $player, $data) : void;
}
