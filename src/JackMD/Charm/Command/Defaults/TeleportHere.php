<?php
declare(strict_types = 1);

namespace JackMD\Charm\Command\Defaults;

use JackMD\Charm\Charm;
use JackMD\Charm\Command\BaseCommand;
use JackMD\Charm\Utils\PlayerUtils;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use function strtolower;

class TeleportHere extends BaseCommand{

	/**
	 * TeleportHere constructor.
	 *
	 * @param Charm $plugin
	 */
	public function __construct(Charm $plugin){
		parent::__construct(
			$plugin,

			"tphere",
			"charm.command.teleporthere.use",
			"Teleport an online player to yourself or to another player.",
			"/tphere <string:player> <string:target>",
			[
				"teleporthere"
			]
		);
	}

	/**
	 * @param CommandSender $sender
	 * @param string        $label
	 * @param array         $args
	 */
	public function onCommand(CommandSender $sender, string $label, array $args): void{
		if(!isset($args[0])){
			$this->sendError($sender, "Please mention the player to teleport.");
			$this->sendError($sender, "Usage: " . $this->getUsage());

			return;
		}

		$playerName = strtolower($args[0]);
		$player = $this->getServer()->getPlayer($playerName);

		if(!PlayerUtils::isOnline($player)){
			$this->sendError($sender, "Player §4$playerName §cis not online or doesn't exist.");

			return;
		}

		$target = $sender;

		if(isset($args[1])){
			$targetName = strtolower($args[1]);
			$target = $this->getServer()->getPlayer($targetName);

			if(!PlayerUtils::isOnline($target)){
				$this->sendError($sender, "Player §4$targetName §cis not online or doesn't exist.");

				return;
			}
		}

		if($target instanceof ConsoleCommandSender){
			$this->sendError($sender, "Usage: " . $this->getUsage());

			return;
		}

		$player->teleport($target, $target->getYaw(), $target->getPitch());
		$this->sendMessage($player, "You were teleported to §6{$target->getName()}");

		$this->sendMessage($target, "Player {$player->getName()} has been teleported to your position.");
	}
}