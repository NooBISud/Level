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

namespace ItsZodiaX\Level\provider;

use ItsZodiaX\Level\Level;
use pocketmine\Player;
use pocketmine\utils\Config;

class YamlProvider implements ProviderInterface{
	
	public function prepare(): void{
		if(!is_dir(Level::getInstance()->getDataFolder() . "data/")){
			mkdir(Level::getInstance()->getDataFolder() . "data/");
		}
	}
	
	/**
	 * @param Player $player
	 */
	public function registerPlayer(Player $player): void{
		$config = new Config(Level::getInstance()->getDataFolder() . "data/" . $player->getLowerCaseName() . ".yml", Config::YAML);
		if(!$config->exists("level")){
			$config->setAll(["level" => 0]);
			$config->save();
		}
	}
	
	/**
	 * @param Player $player
	 * @param int    $points
	 */
	public function addLevelPoints(Player $player, int $points = 1): void{
		$config = new Config(Level::getInstance()->getDataFolder() . "data/" . $player->getLowerCaseName() . ".yml", Config::YAML);
		$config->set("level", $this->getPlayerLevelPoints($player) + $points);
		$config->save();
	}
	
	/**
	 * @param Player $player
	 * @return bool
	 */
	public function playerExists(Player $player): bool{
		$config = new Config(Level::getInstance()->getDataFolder() . "data/" . $player->getLowerCaseName() . ".yml", Config::YAML);
		return ($config->exists("level")) ? true : false;
	}
	
	/**
	 * @param Player $player
	 * @return int
	 */
	public function getPlayerLevelPoints(Player $player): int{
		$config = new Config(Level::getInstance()->getDataFolder() . "data/" . $player->getLowerCaseName() . ".yml", Config::YAML);
		return (int) $config->get("level");
	}
	
	public function close(): void{
		//useless in this case...
	}
}

