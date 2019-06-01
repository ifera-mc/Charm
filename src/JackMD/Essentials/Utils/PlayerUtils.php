<?php
declare(strict_types = 1);

namespace JackMD\Essentials\Utils;

use pocketmine\Player;
use function is_null;

class PlayerUtils{

	/**
	 * @param Player|null $player
	 * @return bool
	 */
	public static function isOnline(?Player $player): bool{
		return (!is_null($player)) && ($player instanceof Player) && ($player->isOnline());
	}
}