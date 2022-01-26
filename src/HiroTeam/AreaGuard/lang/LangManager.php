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

namespace HiroTeam\AreaGuard\lang;

use HiroTeam\AreaGuard\AreaGuardMain;
use HiroTeam\AreaGuard\utils\MessageReplacer;
use pocketmine\utils\Config;

class LangManager
{
    private const DEFAULT_LANG = 'eng';

    /**
     * @var Config[]
     */
    private array $langs = [];

    private AreaGuardMain $main;

    public function __construct(AreaGuardMain $main, string $langDir)
    {
        $dataFolder = $main->getDataFolder();
        foreach (scandir($langDir) as $dir) {
            if (in_array($dir, ['..', '.'])) continue;
            $main->saveResource("lang/$dir", AreaGuardMain::DEV_MODE);
            $this->langs[substr($dir, 0, -4)] = new Config($dataFolder . "lang/$dir", Config::YAML);
        }
        $this->main = $main;
    }

    public function getTranslateReference(string $reference, array $vars = []): string
    {
        $selectedLang = $this->getSelectedLang();
        if (isset($this->langs[$selectedLang])) {
            $lang = $this->langs[$selectedLang];
        } else {
            $lang = $this->langs[self::DEFAULT_LANG];
        }
        $text = $lang->getNested($reference) ?? $this->langs[self::DEFAULT_LANG]->getNested($reference);
        if (!empty($vars)) {
            $replacer = new MessageReplacer();
            $text = $replacer->replace($text, $vars);
        }
        return $text;
    }

    public function getSelectedLang(): string
    {
        return $this->main->getConfig()->get('lang') ?? self::DEFAULT_LANG;
    }

    public function getSelectedLangIndex(): int
    {
        $selectedLang = $this->getSelectedLang();
        $i = 0;
        foreach ($this->getAllLangs() as $lang) {
            if ($selectedLang === $lang) break;
            $i++;
        }
        return $i;
    }

    public function getAllLangs(): array
    {
        return array_keys($this->langs);
    }

    public function setLang(string $lang)
    {
        $config = $this->main->getConfig();
        $config->set('lang', $lang);
        $config->save();
    }
}