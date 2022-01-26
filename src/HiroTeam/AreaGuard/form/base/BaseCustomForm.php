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
use HiroTeam\AreaGuard\libs\forms\CustomForm;
use pocketmine\player\Player;

abstract class BaseCustomForm extends CustomForm
{
    public function __construct(Player $player)
    {
        parent::__construct();
        $this->send($player);
    }

    private function send(Player $player)
    {
        $langManager = AreaGuardMain::getInstance()->getLangManager();
        $this->setTitle($langManager->getTranslateReference('UI_AREAGUARD_TITLE'));
        $this->makeUI($this, $player);
        $this->setCallable($this->makeHandler());
        $this->sendToPlayer($player);
    }

    abstract protected function makeUI(CustomForm $form, Player $player): void;

    private function makeHandler(): ?callable
    {
        return function (Player $player, ?array $data = null): void {
            if (!empty($data)) {
                $this->handler($player, $data);
            }
        };
    }

    abstract protected function handler(Player $player, array $data): void;
}