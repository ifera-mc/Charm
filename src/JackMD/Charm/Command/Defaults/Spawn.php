<?php
declare(strict_types = 1);

namespace JackMD\Charm\Command\Defaults;

use JackMD\Charm\Charm;
use JackMD\Charm\Command\BaseCommand;
use JackMD\Charm\Utils\PlayerUtils;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\level\Location;
use function strtolower;

class Spawn extends BaseCommand{

	/**
	 * Spawn constructor.
	 *
	 * @param Charm $plugin
	 */
	public function __construct(Charm $plugin){
		parent::__construct(
			$plugin,

			"spawn",
			"charm.command.spawn.use",
			"Teleport to spawn. Or teleport a player to spawn.",
			"/spawn [string:player]"
		);
	}

	/**
	 * @param CommandSender $sender
	 * @param string        $label
	 * @param array         $args
	 */
	public function onCommand(CommandSender $sender, string $label, array $args): void{
		$target = $sender;

		if(isset($args[0])){
			$targetName = strtolower($args[0]);
			$target = $this->getServer()->getPlayer($targetName);

			if(!PlayerUtils::isOnline($target)){
				$this->sendError($sender, "Player §4$targetName §cis not online or doesn't exist.");

				return;
			}
		}

		if($target instanceof ConsoleCommandSender){
			$this->sendError($sender, "Please provide a player to send to spawn.");
			$this->sendError($sender, "Usage: " . $this->getUsage());

			return;
		}

		if($target === $sender){
			if(!$this->hasPermission($sender, "charm.command.spawn.self")){
				return;
			}
		}else{
			if(!$this->hasPermission($sender, "charm.command.spawn.other")){
				return;
			}

			$this->sendMessage($sender, "Player §2{$target->getName()} §ahas been sent to spawn.");
		}

		$target->teleport(Location::fromObject($this->getServer()->getDefaultLevel()->getSpawnLocation(), $this->getServer()->getDefaultLevel()));

		$this->sendMessage($target, "You have been teleported to spawn.");
	}
}