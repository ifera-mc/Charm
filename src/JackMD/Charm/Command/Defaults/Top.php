<?php
declare(strict_types = 1);

namespace JackMD\Charm\Command\Defaults;

use JackMD\Charm\Charm;
use JackMD\Charm\Command\BaseCommand;
use JackMD\Charm\Utils\PlayerUtils;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\math\Vector3;
use function strtolower;

class Top extends BaseCommand{

	/**
	 * Top constructor.
	 *
	 * @param Charm $plugin
	 */
	public function __construct(Charm $plugin){
		parent::__construct(
			$plugin,

			"top",
			"charm.command.top.use",
			"Teleport to the highest block above you. Or teleport another player.",
			"/top [string:player]"
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
			$this->sendError($sender, "Please provide a player to send to top.");
			$this->sendError($sender, "Usage: " . $this->getUsage());

			return;
		}

		if($target === $sender){
			if(!$this->hasPermission($sender, "charm.command.top.self")){
				return;
			}
		}else{
			if(!$this->hasPermission($sender, "charm.command.top.other")){
				return;
			}

			$this->sendMessage($sender, "Player §2{$target->getName()} §ahas been teleported to the highest block.");
		}

		$target->teleport(new Vector3($target->getX(), $target->getLevel()->getHighestBlockAt($target->getFloorX(), $target->getFloorZ()) + 1, $target->getZ()));

		$this->sendMessage($target, "You have been teleported to the highest block above you.");
	}
}