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


use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageByBlockEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityExplodeEvent;
use pocketmine\event\entity\ExplosionPrimeEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerToggleFlightEvent;
use pocketmine\player\Player;

class AreaListener implements Listener
{
    /**
     * @var AreaManager
     */
    private AreaManager $areaManager;

    public function __construct(AreaManager $areaManager)
    {
        $this->areaManager = $areaManager;
    }

    /**
     * @param PlayerExhaustEvent $event
     * @priority LOW
     */
    public function onExhaust(PlayerExhaustEvent $event){
        $player = $event->getPlayer();
        $area = $this->areaManager->getAreaByPosition($player->getPosition());
        if (!$area) return;
        if ($area->hunger){
            $event->cancel();
        }
    }

    /**
     * @param BlockPlaceEvent $event
     * @priority LOW
     */
    public function onPlace(BlockPlaceEvent $event)
    {

        $area = $this->areaManager->getAreaByPosition($event->getBlock()->getPosition());
        if (!$area) return;
        $player = $event->getPlayer();
        if ($area->hasPermission($player, 'place')) return;
        if (!$area->place) {
            $event->cancel();
        }
    }

    /**
     * @param BlockBreakEvent $event
     * @priority LOW
     */
    public function onBreak(BlockBreakEvent $event)
    {
        $area = $this->areaManager->getAreaByPosition($event->getBlock()->getPosition());
        if (!$area) return;
        $player = $event->getPlayer();
        if ($area->hasPermission($player, 'break')) return;
        if (!$area->break) {
            $event->cancel();
        }
    }

    /**
     * @param PlayerInteractEvent $event
     * @priority LOW
     */
    public function onUse(PlayerInteractEvent $event)
    {
        $area = $this->areaManager->getAreaByPosition($event->getBlock()->getPosition());
        if (!$area) return;
        if ($event->getItem()->getBlock()->getId() !== 0) return;
        $player = $event->getPlayer();
        if ($area->hasPermission($player, 'use')) return;
        if (!$area->use) {
            $event->cancel();
        }
    }

    /**
     * @param EntityDamageEvent $event
     * @priority LOW
     */
    public function onDamage(EntityDamageEvent $event)
    {
        $player = $event->getEntity();
        if (!($player instanceof Player)) return;
        $area = $this->areaManager->getAreaByPosition($player->getPosition());
        if (!$area) return;
        $cancel = false;
        if ($event instanceof EntityDamageByEntityEvent) {
            $damager = $event->getDamager();
            if ($damager instanceof Player) {
                foreach ([$area, $this->areaManager->getAreaByPosition($damager->getPosition())] as $playerArea) {
                    if ($playerArea) {
                        if (!$playerArea->pvp) {
                            $cancel = true;
                        }
                    }
                }
            }
        } elseif (!$area->damage) {
            $cancel = true;
        }
        if ($cancel) {
            $event->cancel();
        }
    }

    /**
     * @param EntityDamageByBlockEvent $event
     * @priority LOW
     */
    public function blockExplode(EntityDamageByBlockEvent $event)
    {
        $player = $event->getEntity();
        if (!($player instanceof Player)) return;
        $area = $this->areaManager->getAreaByPosition($player->getPosition());
        if (!$area) return;
        if (!$area->damage) {
            $event->cancel();
        }
    }

    /**
     * @param EntityDamageByEntityEvent $event
     * @priority LOW
     */
    public function damageByEntity(EntityDamageByEntityEvent $event)
    {
        $player = $event->getEntity();
        if (!($player instanceof Player)) return;
        if ($event->getDamager() instanceof Player) return;
        $area = $this->areaManager->getAreaByPosition($player->getPosition());
        if (!$area) return;
        if (!$area->damage) {
            $event->cancel();
        }
    }

    /**
     * @param PlayerDropItemEvent $event
     * @priority LOW
     */
    public function onDrop(PlayerDropItemEvent $event)
    {
        $player = $event->getPlayer();
        $area = $this->areaManager->getAreaByPosition($player->getPosition());
        if (!$area) return;
        if ($area->hasPermission($player, 'drop')) return;
        if (!$area->drop) {
            $event->cancel();
        }
    }

    /**
     * @param ExplosionPrimeEvent $event
     * @priority LOW
     */
    public function onExplosion(ExplosionPrimeEvent $event)
    {
        $area = $this->areaManager->getAreaByPosition($event->getEntity()->getPosition());
        if (!$area) return;
        if (!$area->explosion) {
            $event->cancel();
        }
    }

    /**
     * @param EntityExplodeEvent $event
     * @priority LOW
     */
    public function onExplosionBlock(EntityExplodeEvent $event)
    {
        $finalBlocks = [];
        foreach ($event->getBlockList() as $block) {
            $area = $this->areaManager->getAreaByPosition($block->getPosition());
            $blockCanBeRemove = true;
            if ($area) {
                if (!$area->explosion) {
                    $blockCanBeRemove = false;
                }
            }
            if ($blockCanBeRemove) {
                $finalBlocks[] = $block;
            }
        }
        $event->setBlockList($finalBlocks);
    }

    /**
     * @param PlayerToggleFlightEvent $event
     * @priority LOW
     */
    public function onFly(PlayerToggleFlightEvent $event)
    {
        $player = $event->getPlayer();
        $area = $this->areaManager->getAreaByPosition($player->getPosition());
        if (!$area) return;
        if ($area->hasPermission($player, 'fly')) return;
        if (!$area->fly) {
            if ($event->isFlying()) {
                $event->cancel();
            }
        }
    }
}
