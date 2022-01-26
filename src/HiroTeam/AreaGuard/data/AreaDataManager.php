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

namespace HiroTeam\AreaGuard\data;

use HiroTeam\AreaGuard\area\Area;
use HiroTeam\AreaGuard\AreaGuardMain;
use pocketmine\utils\Config;

class AreaDataManager
{
    private Config $data;
    private AreaGuardMain $main;

    public function __construct(AreaGuardMain $main)
    {
        $this->data = new Config($main->getDataFolder() . 'data.yml', Config::YAML);
        $this->main = $main;
    }

    public function getAll(): array
    {
        return $this->data->getAll();
    }

    public function saveAllArea(array $areas)
    {
        foreach ($areas as $area) {
            $this->saveArea($area);
        }
        $this->data->save();
    }

    private function saveArea(Area $area, bool $save = false)
    {
        $settings = $this->main->getAreaManager()->getSettingsFromArea($area);
        $pos1 = $area->position1;
        $pos2 = $area->position2;
        [$x1, $y1, $z1] = [$pos1->getFloorX(), $pos1->getFloorY(), $pos1->getFloorZ()];
        [$x2, $y2, $z2] = [$pos2->getFloorX(), $pos2->getFloorY(), $pos2->getFloorZ()];
        $this->data->set($area->areaName, [
            'x1' => $x1,
            'y1' => $y1,
            'z1' => $z1,
            'x2' => $x2,
            'y2' => $y2,
            'z2' => $z2,
            'world' => $pos1->getWorld()->getFolderName(),
            'settings' => $settings
        ]);
        if ($save) {
            $this->data->save();
        }
    }

    public function removeArea(string $areaName)
    {
        $this->data->remove($areaName);
        $this->data->save();
    }
}