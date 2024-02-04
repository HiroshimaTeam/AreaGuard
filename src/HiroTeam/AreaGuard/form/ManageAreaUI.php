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

class ManageAreaUI extends BaseSimpleForm {
	protected function makeUI(SimpleForm $form, Player $player) : void {
		$main = AreaGuardMain::getInstance();
		$langManager = $main->getLangManager();
		foreach (array_keys($main->getAreaManager()->getAreas()) as $areaName) {
			$form->addButton($areaName, -1, '', $areaName);
		}
		$form->addButton($langManager->getTranslateReference('UI_NEW_AREA_BUTTON'), -1, '', 'new');
		$form->addButton($langManager->getTranslateReference('UI_RETURN_BUTTON'), -1, '', 'return');
		$form->addButton($langManager->getTranslateReference('UI_QUIT_BUTTON'), -1, '', 'quit');
	}

	protected function handler(Player $player, $data) : void {
		if ($data === 'quit') {
			return;
		}
		switch ($data) {
			case 'new':
				new CreateNewAreaUI($player);
				break;

			case 'return':
				new OpenAreaGuardUI($player);
				break;
			default:
				new AreaSettingsUI($player, $data);
				break;
		}
	}
}
