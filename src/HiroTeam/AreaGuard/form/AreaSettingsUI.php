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


use HiroTeam\AreaGuard\area\AreaManager;
use HiroTeam\AreaGuard\AreaGuardMain;
use HiroTeam\AreaGuard\form\base\BaseCustomForm;
use HiroTeam\AreaGuard\lang\LangManager;
use HiroTeam\AreaGuard\libs\forms\CustomForm;
use pocketmine\player\Player;

/**
 * @property string areaName
 * @property AreaManager areaManager
 * @property LangManager langManager
 */
class AreaSettingsUI extends BaseCustomForm
{
    public function __construct(Player $player, string $areaName)
    {
        $this->areaName = $areaName;
        $main = AreaGuardMain::getInstance();
        $this->areaManager = $main->getAreaManager();
        $this->langManager = $main->getLangManager();
        parent::__construct($player);
    }

    protected function makeUI(CustomForm $form, Player $player): void
    {
        $area = $this->areaManager->getAreaByName($this->areaName);
        $langManager = $this->langManager;
        foreach (array_keys(AreaManager::DEFAULT_AREA_SETTINGS) as $setting) {
            if ($setting === 'priority') continue;
            $form->addToggle($langManager->getTranslateReference("SETTINGS.$setting"), $area->{$setting}, $setting);
        }
        $form->addInput($langManager->getTranslateReference('SETTINGS.priority'), '0, 1, 2,...', $area->priority, 'priority');
        $form->addToggle($langManager->getTranslateReference('UI_REDEFINE_AREA_TOGGLE'), false, 'redefine');
        $form->addToggle($langManager->getTranslateReference('UI_DELETE_AREA_TOGGLE'), false, 'delete');
    }

    protected function handler(Player $player, array $data): void
    {
        $areaManager = $this->areaManager;
        $langManager = $this->langManager;

        if ($data['delete']) {
            $areaManager->deleteArea($this->areaName);
            $player->sendMessage($langManager->getTranslateReference('DELETE_AREA_MESSAGE', [
                'area' => $this->areaName
            ]));
            return;
        }
        if ($data['redefine']) {
            $area = $areaManager->getAreaByName($this->areaName);
            $areaManager->onNewAreaCreation($player, $this->areaName, false, $areaManager->getSettingsFromArea($area));
            $player->sendMessage($this->langManager->getTranslateReference('SELECT_TWO_POINT_AREA', [
                'area' => $this->areaName
            ]));
            return;
        }
        $area = $this->areaManager->getAreaByName($this->areaName);
        $modifiedSettings = '';
        foreach (array_keys(AreaManager::DEFAULT_AREA_SETTINGS) as $setting) {
            if ($area->{$setting} !== $data[$setting]) {
                if ($setting === 'priority') {
                    if (!is_numeric($data[$setting]) or (int)$area->{$setting} === (int)$data[$setting]) continue;
                    $settingText = $data[$setting];
                    $area->{$setting} = intval($data[$setting]);
                } else {
                    $settingText = $data[$setting] ? 'true' : 'false';
                    $area->{$setting} = $data[$setting];
                }
                $modifiedSettings .= $langManager->getTranslateReference("SETTINGS.$setting") . " -> " . $settingText . "\n";
            }
        }
        if ($modifiedSettings) {
            $player->sendMessage($langManager->getTranslateReference('MODIFIE_SETTING_MESSAGE', [
                'area' => $this->areaName,
                'setting' => $modifiedSettings
            ]));
        }
    }
}