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

use pocketmine\Player;

interface ProviderInterface{
	
	/**
	 * Prepare initial steps required to get the database running.
	 */
	public function prepare(): void;
	
	/**
	 * Register a player into the database.
	 *
	 * @param Player $player
	 */
	public function registerPlayer(Player $player): void;
	
	/**
	 * Add death points to the player.
	 *
	 * @param Player $player
	 * @param int    $points
	 */

	public function addLevelPoints(Player $player, int $points = 1): void;
	
	/**
	 * Check if a player is already registered.
	 *
	 * @param Player $player
	 * @return bool
	 */
	public function playerExists(Player $player): bool;
	
	/**
	 * Returns the kill points of a player.
	 *
	 * @param Player $player
	 * @return int
	 */
	public function getPlayerLevelPoints(Player $player): int;	
	
	/**
	 * Close the database.
	 */
	public function close(): void;
}

