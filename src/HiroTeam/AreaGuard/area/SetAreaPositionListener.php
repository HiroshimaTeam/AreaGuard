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
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerQuitEvent;

class SetAreaPositionListener implements Listener
{
    /**
     * @var AreaGuardMain
     */
    private AreaGuardMain $main;

    public function __construct(AreaGuardMain $main)
    {
        $this->main = $main;
    }

    public function onInteract(PlayerInteractEvent $event)
    {
        if($this->main->getAreaManager()->onCreateNewAreaInteraction($event->getPlayer(), $event->getBlock())){
            $event->cancel();
        }
    }

    public function onBreak(BlockBreakEvent $event)
    {
        if($this->main->getAreaManager()->onCreateNewAreaInteraction($event->getPlayer(), $event->getBlock())){
            $event->cancel();
        }
    }

    public function onQuit(PlayerQuitEvent $event){
        $this->main->getAreaManager()->onInterruptCreateArea($event->getPlayer());
    }
}