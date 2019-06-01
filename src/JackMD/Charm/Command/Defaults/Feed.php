<?php
declare(strict_types = 1);

namespace JackMD\Charm\Command\Defaults;

use JackMD\Charm\Charm;
use JackMD\Charm\Command\BaseCommand;
use JackMD\Charm\Utils\PlayerUtils;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\level\particle\HappyVillagerParticle;
use function strtolower;

class Feed extends BaseCommand{

	/**
	 * Feed constructor.
	 *
	 * @param Charm $plugin
	 */
	public function __construct(Charm $plugin){
		parent::__construct(
			$plugin,

			"feed",
			"charm.command.feed.use",
			"Feed yourself or another player.",
			"/feed [string:player]"
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
			$this->sendError($sender, "Please provide a player to heal.");
			$this->sendError($sender, "Usage: " . $this->getUsage());

			return;
		}

		if($target === $sender){
			if(!$this->hasPermission($sender, "charm.command.feed.self")){
				return;
			}
		}else{
			if(!$this->hasPermission($sender, "charm.command.feed.other")){
				return;
			}

			$this->sendMessage($sender, "Player §2{$target->getName()} §ahas been fed.");
		}

		$target->setFood(20);
		$target->getLevel()->addParticle(new HappyVillagerParticle($target->add(0, 2)));

		$this->sendMessage($target, "You have been fed.");
	}
}