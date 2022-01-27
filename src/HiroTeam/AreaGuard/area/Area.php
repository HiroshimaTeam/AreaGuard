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

namespace HiroTeam\AreaGuard\area;


use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\world\Position;

/**
 * @property Position position1
 * @property Position position2
 * @property string areaName
 */
class Area
{

    public function __construct(string $areaName, Position $position1, Position $position2, array $settings)
    {
        $this->areaName = $areaName;
        $this->position1 = $position1;
        $this->position2 = $position2;
        foreach ($settings as $setting => $value) {
            $this->{$setting} = $value;
        }
    }

    public function setSettings(array $settings)
    {
        foreach ($settings as $setting => $value) {
            $this->{$setting} = $value;
        }
    }

    public function positionIsInArea(Position $position): bool
    {
        [$targetX, $targetY, $targetZ] = [$position->getFloorX(), $position->getFloorY(), $position->getFloorZ()];
        [$x, $y, $z] = $this->getAsortPositions();
        $world = $this->position1->getWorld();
        if ($world->getFolderName() === $position->getWorld()->getFolderName()) {
            if ($x[0] <= $targetX and $targetX <= $x[1]) {
                if ($y[0] <= $targetY and $targetY <= $y[1]) {
                    if ($z[0] <= $targetZ and $targetZ <= $z[1]) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    private function getAsortPositions(): array
    {
        $x = [$this->position1->getFloorX(), $this->position2->getFloorX()];
        $y = [$this->position1->getFloorY(), $this->position2->getFloorY()];
        $z = [$this->position1->getFloorZ(), $this->position2->getFloorZ()];
        asort($x);
        asort($y);
        asort($z);
        return [array_values($x), array_values($y), array_values($z)];
    }

    public function hasPermission(Player $player, string $action): bool
    {
        return $player->hasPermission($action . '.' . $this->areaName) or
            $player->hasPermission($action . '.' . '*') or
            Server::getInstance()->isOp($player->getName());
    }
}