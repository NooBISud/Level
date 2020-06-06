<?php
declare(strict_types = 1);

/**
 *   _   _____________
 *  | | / /  _  \ ___ \
 *  | |/ /| | | | |_/ /
 *  |    \| | | |    /
 *  | |\  \ |/ /| |\ \
 *  \_| \_/___/ \_| \_|
 *
 * KDR, a Kill Death Ratio plugin for PocketMine-MP
 * Copyright (c) 2018 JackMD  < https://github.com/JackMD >
 *
 * Discord: JackMD#3717
 * Twitter: JackMTaylor_
 *
 * This software is distributed under "GNU General Public License v3.0".
 * This license allows you to use it and/or modify it but you are not at
 * all allowed to sell this plugin at any cost. If found doing so the
 * necessary action required would be taken.
 *
 * KDR is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License v3.0 for more details.
 *
 * You should have received a copy of the GNU General Public License v3.0
 * along with this program. If not, see
 * <https://opensource.org/licenses/GPL-3.0>.
 * ------------------------------------------------------------------------
 */

namespace ItsZodiaX\Level;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\math\Vector3;
use pocketmine\block\Block;

class EventListener implements Listener{

	/** @var KDR */
	public $plugin;
	
	/**
	 * EventListener constructor.
	 *
	 * @param KDR $plugin
	 */
	public function __construct(Level $plugin){
		$this->plugin = $plugin;
	}
	
	/**
	 * @param PlayerJoinEvent $event
	 */
	public function onJoin(PlayerJoinEvent $event){
		$player = $event->getPlayer();
		$player->setHealth(20);
		$player->removeAllEffects();
		$xp = $this->plugin->getProvider()->getPlayerLevelPoints($player)/200;
		$player->setXpLevel((int)$xp);
		if(!$this->plugin->getProvider()->playerExists($player)){
			$this->plugin->getProvider()->registerPlayer($player);
		}
		foreach(glob($this->plugin->getServer()->getDataPath() . "worlds/*") as $world) {
			$world = str_replace($this->plugin->getServer()->getDataPath() . "worlds/", "", $world);
				if($this->plugin->getServer()->isLevelLoaded($world)){
					continue;
				}
			$this->plugin->getServer()->loadLevel($world);
			$level = $this->plugin->getServer()->getLevelByName($world);
			$level->setTime(0);
			$level->stopTime();
		}
		foreach(glob($this->plugin->getServer()->getDataPath() . "worlds/*") as $world) {
			$world = str_replace($this->plugin->getServer()->getDataPath() . "worlds/", "", $world);
			$level = $this->plugin->getServer()->getLevelByName($world);
			$level->setTime(0);
			$level->stopTime();
		}
	}

	public function onItemHeld(PlayerItemHeldEvent $event){
		$player = $event->getPlayer();
		if($player->getLevel()->getFolderName() == "Lobby"){
			if($player->getInventory()->getItemInHand()->getId() === 46){
				$player->getInventory()->clearAll();
			}				
		}
	}

    public function onDrop(PlayerDropItemEvent $event){
        if($event->getPlayer()->getLevel()->getFolderName() == "Lobby"){
            $event->setCancelled();              
        }  
    }

	/**
	 * @param PlayerDeathEvent $event
	 */
	public function onPlayerKill(PlayerDeathEvent $event){
		$player = $event->getPlayer();
		$cause = $player->getLastDamageCause();
		if($cause instanceof EntityDamageByEntityEvent){
			$damager = $cause->getDamager();
			if($damager instanceof Player){
				$this->plugin->getProvider()->addLevelPoints($damager, (int) $this->plugin->getConfig()->get("level-points"));
			}
		}
	}

	public function levelChange(EntityLevelChangeEvent $event) {
        $entity = $event->getEntity();
        if ($entity instanceof Player) {
            $level = $event->getTarget()->getName();							
			if($level == 'Lobby'){
				$entity->removeAllEffects();
				$xp = $this->plugin->getProvider()->getPlayerLevelPoints($entity)/200;
				$entity->setXpLevel((int)$xp);
			}
        }
	}
	
    public function Booster(PlayerMoveEvent $event){
		$player = $event->getPlayer();
        if($player->getLevel()->getFolderName() == "Lobby"){         
			$block = $player->getLevel()->getBlock(new Vector3($player->x, $player->y - 0.5, $player->z));
			if ($block->getId() === Block::REDSTONE_BLOCK) {
			    $effect = Effect::getEffect(1);
			    $player->addEffect(new EffectInstance($effect, 10, 6, false));								
		    }     
		}  
    }
}
