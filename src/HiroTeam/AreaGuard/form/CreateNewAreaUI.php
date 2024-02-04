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

namespace HiroTeam\AreaGuard\form;

use HiroTeam\AreaGuard\AreaGuardMain;
use HiroTeam\AreaGuard\form\base\BaseCustomForm;
use HiroTeam\AreaGuard\lang\LangManager;
use HiroTeam\AreaGuard\libs\forms\CustomForm;
use pocketmine\player\Player;

/**
 * @property LangManager langManager
 */
class CreateNewAreaUI extends BaseCustomForm {
	private LangManager $langManager;

	public function __construct(Player $player) {
		$this->langManager = AreaGuardMain::getInstance()->getLangManager();
		parent::__construct($player);
	}

	protected function makeUI(CustomForm $form, Player $player) : void {
		$langManager = $this->langManager;
		$form->addInput($langManager->getTranslateReference('UI_SET_AREA_NAME_INPUT'), '', null, 'areaName');
		$form->addToggle($langManager->getTranslateReference('UI_EXPAND_VERTICALLY_TOGGLE'), false, 'expand');
	}

	protected function handler(Player $player, array $data) : void {
		if ($data['areaName']) {
			AreaGuardMain::getInstance()->getAreaManager()->onNewAreaCreation($player, $data['areaName'], $data['expand']);
			$player->sendMessage($this->langManager->getTranslateReference('SELECT_TWO_POINT_AREA', [
				'area' => $data['areaName']
			]));
		} else {
			$player->sendMessage($this->langManager->getTranslateReference('AREA_NAME_INVALID_MESSAGE'));
		}
	}
}
