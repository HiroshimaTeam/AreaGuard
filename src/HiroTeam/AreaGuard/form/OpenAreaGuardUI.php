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
use HiroTeam\AreaGuard\form\base\BaseSimpleForm;
use HiroTeam\AreaGuard\libs\forms\SimpleForm;
use pocketmine\player\Player;

class OpenAreaGuardUI extends BaseSimpleForm {
	protected function makeUI(SimpleForm $form, Player $player) : void {
		$langManager = AreaGuardMain::getInstance()->getLangManager();
		$form->addButton($langManager->getTranslateReference('UI_MANAGE_AREAS_BUTTON'));
		$form->addButton($langManager->getTranslateReference('UI_MANAGE_LANG_BUTTON'));
		$form->addButton($langManager->getTranslateReference('UI_QUIT_BUTTON'));
	}

	protected function handler(Player $player, $data) : void {
		switch ($data) {
			case 0:
				new ManageAreaUI($player);
				break;

			case 1:
				new ChangeLangUI($player);
				break;
		}
	}
}
