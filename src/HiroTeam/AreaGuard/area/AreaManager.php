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

use HiroTeam\AreaGuard\AreaGuardMain;
use pocketmine\block\Block;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\world\Position;

class AreaManager
{
    public const DEFAULT_AREA_SETTINGS = [
        'priority' => 0,
        'place' => false,
        'break' => false,
        'use' => true,
        'pvp' => false,
        'damage' => false,
        'drop' => true,
        'explosion' => false,
        'fly' => false
    ];

    private AreaGuardMain $main;

    /**
     * @var Area[]
     */
    private array $area = [];

    private $processCreationNewArea = [];

    public function __construct(AreaGuardMain $main)
    {
        $this->main = $main;
        $this->initAreas();
    }

    private function initAreas()
    {
        foreach ($this->main->getDataManager()->getAll() as $areaName => $data) {
            $worldManager = Server::getInstance()->getWorldManager();
            $worldManager->loadWorld($data['world']);
            $world = $worldManager->getWorldByName($data['world']);
            if (!$world) continue;
            $this->area[$areaName] = new Area(
                $areaName,
                new Position($data['x1'], $data['y1'], $data['z1'], $world),
                new Position($data['x2'], $data['y2'], $data['z2'], $world),
                $data['settings']
            );
            foreach (self::DEFAULT_AREA_SETTINGS as $setting => $value) {
                if (!isset($this->area[$areaName]->{$setting})) {
                    $this->area[$areaName]->{$setting} = $value;
                }
            }
        }
    }

    public function onNewAreaCreation(Player $player, string $areaName, bool $expandVertically, array $settings = [])
    {
        $this->processCreationNewArea[$player->getName()] = [
            'areaName' => $areaName,
            'expand' => $expandVertically,
            'world' => null,
            'pos1' => null,
            'settings' => $settings
        ];
    }

    public function onCreateNewAreaInteraction(Player $player, Block $block): bool
    {
        $playerName = $player->getName();
        if (!isset($this->processCreationNewArea[$playerName])) return false;
        $areaData = $this->processCreationNewArea[$playerName];
        $blockPosition = $block->getPosition();
        $xyz = [$blockPosition->getFloorX(), $blockPosition->getFloorY(), $blockPosition->getFloorZ()];
        $pos = implode(':', $xyz);
        $langManager = $this->main->getLangManager();
        if (!$areaData['pos1']) {
            $this->processCreationNewArea[$playerName]['world'] = $blockPosition->getWorld();
            $this->processCreationNewArea[$playerName]['pos1'] = $pos;
            $player->sendMessage($langManager->getTranslateReference('SET_FIRST_POSITION_MESSAGE', [
                'x' => $xyz[0],
                'y' => $xyz[1],
                'z' => $xyz[2],
                'world' => $blockPosition->getWorld()->getFolderName()
            ]));
        } else {
            $blockWorld = $blockPosition->getWorld();
            if ($this->processCreationNewArea[$playerName]['world']->getFolderName() === $blockWorld->getFolderName()) {
                $xyz1 = explode(':', $areaData['pos1']);
                $pos1 = new Position((int)$xyz1[0], (int)$xyz1[1], (int)$xyz1[2], $blockWorld);
                $pos2 = new Position($blockPosition->getFloorX(), $blockPosition->getFloorY(), $blockPosition->getFloorZ(), $blockWorld);
                if ($areaData['expand']) {
                    $pos1->y = 0;
                    $pos2->y = 256;
                }
                $this->createNewArea($areaData['areaName'], $pos1, $pos2, $areaData['settings']);
                $player->sendMessage($langManager->getTranslateReference('SET_SECOND_POSITION_MESSAGE', [
                    'x' => $xyz[0],
                    'y' => $xyz[1],
                    'z' => $xyz[2],
                    'world' => $blockPosition->getWorld()->getFolderName()
                ]));
                if (empty($areaData['settings'])){
                    $player->sendMessage($langManager->getTranslateReference('SUCCESS_CREATE_NEW_AREA', [
                        'area' => $areaData['areaName'],
                        'world' => $blockPosition->getWorld()->getFolderName()
                    ]));
                } else {
                    $player->sendMessage($langManager->getTranslateReference('SUCCESS_REDEFINE_AREA', [
                        'area' => $areaData['areaName'],
                    ]));
                }
                unset($this->processCreationNewArea[$playerName]);
            } else {
                $player->sendMessage($langManager->getTranslateReference('WORLD_MUST_BE_THE_SAME'));
            }
        }
        return true;
    }

    private function createNewArea(string $areaName, Position $position1, Position $position2, array $settings = [])
    {
        $this->area[$areaName] = new Area($areaName, $position1, $position2, empty($settings) ? self::DEFAULT_AREA_SETTINGS : $settings);
    }

    public function getAreaByName(string $areaName): ?Area
    {
        return isset($this->area[$areaName]) ? $this->area[$areaName] : null;
    }

    /**
     * @return Area[]
     */
    public function getAreas(): array
    {
        return $this->area;
    }

    public function getAreaByPosition(Position $position): ?Area
    {
        $matchedAreas = [];
        foreach ($this->area as $area) {
            if ($area->positionIsInArea($position)) {
                $matchedAreas[] = $area;
            }
        }

        if (empty($matchedAreas)) return null;
        if (count($matchedAreas) === 1) return $matchedAreas[0];
        $highestPriorityArea = array_shift($matchedAreas);
        foreach ($matchedAreas as $area) {
            if ($area->priority > $highestPriorityArea->priority) {
                $highestPriorityArea = $area;
            }
        }
        return $highestPriorityArea;
    }

    public function save()
    {
        $this->main->getDataManager()->saveAllArea($this->area);
    }

    public function deleteArea(string $areaName)
    {
        unset($this->area[$areaName]);
        $this->main->getDataManager()->removeArea($areaName);
    }

    public function getSettingsFromArea(Area $area): array{
        $settings = [];
        foreach (array_keys(AreaManager::DEFAULT_AREA_SETTINGS) as $settingRef){
            $settings[$settingRef] = $area->{$settingRef};
        }
        return $settings;
    }

}