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

namespace HiroTeam\AreaGuard;

use HiroTeam\AreaGuard\area\AreaListener;
use HiroTeam\AreaGuard\area\AreaManager;
use HiroTeam\AreaGuard\area\SetAreaPositionListener;
use HiroTeam\AreaGuard\command\AreaGuardCommand;
use HiroTeam\AreaGuard\data\AreaDataManager;
use HiroTeam\AreaGuard\lang\LangManager;
use pocketmine\plugin\PluginBase;

class AreaGuardMain extends PluginBase
{

    public const DEV_MODE = false;

    private static AreaGuardMain $main;

    private LangManager $langManager;
    private AreaDataManager $dataManager;
    private AreaManager $areaManager;

    /**
     * @return AreaGuardMain
     */
    public static function getInstance(): AreaGuardMain
    {
        return self::$main;
    }

    /**
     * @return LangManager
     */
    public function getLangManager(): LangManager
    {
        return $this->langManager;
    }

    /**
     * @return AreaDataManager
     */
    public function getDataManager(): AreaDataManager
    {
        return $this->dataManager;
    }

    /**
     * @return AreaManager
     */
    public function getAreaManager(): AreaManager
    {
        return $this->areaManager;
    }

    protected function onEnable(): void
    {
        self::$main = $this;
        if (self::DEV_MODE) {
            $this->saveResource('config.yml', true);
        }
        $this->saveDefaultConfig();
        $this->getServer()->getCommandMap()->register('AreaGuard', new AreaGuardCommand());
        $this->dataManager = new AreaDataManager($this);
        $this->areaManager = new AreaManager($this);
        $this->langManager = new LangManager($this, $this->getFile() . 'resources/lang');
        $this->getServer()->getPluginManager()->registerEvents(new SetAreaPositionListener($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new AreaListener($this->areaManager), $this);

    }

    protected function onDisable(): void
    {
        $this->areaManager->save();
    }
}