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
class ChangeLangUI extends BaseCustomForm
{
    public function __construct(Player $player)
    {
        $this->langManager = AreaGuardMain::getInstance()->getLangManager();
        parent::__construct($player);
    }

    protected function makeUI(CustomForm $form, Player $player): void
    {
        $langManager = $this->langManager;
        $form->addDropdown(
            $langManager->getTranslateReference('UI_SELECT_LANG_DROPDOWN'),
            $langManager->getAllLangs(),
            $langManager->getSelectedLangIndex()
        );
    }

    protected function handler(Player $player, array $data): void
    {
        $langManager = $this->langManager;
        $newLang = $langManager->getAllLangs()[$data[0]];
        if ($newLang === $langManager->getSelectedLang()) {
            $player->sendMessage($langManager->getTranslateReference('FAIL_CHANGE_LANG_MESSAGE', [
                'lang' => $newLang
            ]));
        } else {
            $langManager->setLang($newLang);
            $player->sendMessage($langManager->getTranslateReference('SUCCESS_CHANGE_LANG_MESSAGE', [
                'lang' => $newLang
            ]));
        }
    }
}