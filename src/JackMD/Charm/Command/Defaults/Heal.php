<?php
declare(strict_types = 1);

namespace JackMD\Charm\Command\Defaults;

use JackMD\Charm\Charm;
use JackMD\Charm\Command\BaseCommand;
use JackMD\Charm\Utils\PlayerUtils;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\event\entity\EntityRegainHealthEvent;
use pocketmine\level\particle\HeartParticle;
use function strtolower;

class Heal extends BaseCommand{

	/**
	 * Heal constructor.
	 *
	 * @param Charm $plugin
	 */
	public function __construct(Charm $plugin){
		parent::__construct(
			$plugin,

			"heal",
			"charm.command.heal.use",
			"Heal yourself or another player.",
			"/heal [string:player]"
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
			if(!$this->hasPermission($sender, "charm.command.heal.self")){
				return;
			}
		}else{
			if(!$this->hasPermission($sender, "charm.command.heal.other")){
				return;
			}

			$this->sendMessage($sender, "Player §2{$target->getName()} §ahas been healed.");
		}

		$target->heal(new EntityRegainHealthEvent($target, $target->getMaxHealth() - $target->getHealth(), EntityRegainHealthEvent::CAUSE_CUSTOM));
		$target->getLevel()->addParticle(new HeartParticle($target->add(0, 2), 4));

		$this->sendMessage($target, "You have been healed.");
	}
}