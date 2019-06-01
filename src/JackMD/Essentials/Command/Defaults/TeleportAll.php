<?php
declare(strict_types = 1);

namespace JackMD\Essentials\Command\Defaults;

use JackMD\Essentials\Command\BaseCommand;
use JackMD\Essentials\Essentials;
use JackMD\Essentials\Utils\PlayerUtils;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use function count;
use function strtolower;

class TeleportAll extends BaseCommand{

	/**
	 * TeleportAll constructor.
	 *
	 * @param Essentials $plugin
	 */
	public function __construct(Essentials $plugin){
		parent::__construct(
			$plugin,

			"tpall",
			"essentials.command.teleportall.use",
			"Teleport every online player to your position or to some other player.",
			"/tpall [string:player]",
			[
				"teleportall"
			]
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
			$this->sendError($sender, "Usage: " . $this->getUsage());

			return;
		}

		$players = $this->getServer()->getOnlinePlayers();

		foreach($players as $player){
			if($player === $target){
				continue;
			}

			$player->teleport($target, $target->getYaw(), $target->getPitch());
			$this->sendMessage($player, "You were teleported to §6{$target->getName()}");
		}

		$this->sendMessage($target, "Players (§dx" . count($players) . "§a) were teleported to your position.");
	}
}