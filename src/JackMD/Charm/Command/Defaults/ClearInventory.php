<?php
declare(strict_types = 1);

namespace JackMD\Charm\Command\Defaults;

use JackMD\Charm\Charm;
use JackMD\Charm\Command\BaseCommand;
use JackMD\Charm\Utils\PlayerUtils;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use function strtolower;

class ClearInventory extends BaseCommand{

	/**
	 * Top constructor.
	 *
	 * @param Charm $plugin
	 */
	public function __construct(Charm $plugin){
		parent::__construct(
			$plugin,

			"clearinventory",
			"charm.command.clearinventory.use",
			"Clear your oor another players inventory.",
			"/ci [string:player]",
			[
				"ci"
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
			$this->sendError($sender, "Please provide a player to clear inventory.");
			$this->sendError($sender, "Usage: " . $this->getUsage());

			return;
		}

		if($target === $sender){
			if(!$this->hasPermission($sender, "charm.command.clearinventory.self")){
				return;
			}
		}else{
			if(!$this->hasPermission($sender, "charm.command.clearinventory.other")){
				return;
			}

			$this->sendMessage($sender, "Player §2{$target->getName()} §ainventory has been cleared.");
		}

		$target->getInventory()->clearAll();
		$target->getArmorInventory()->clearAll();
		$target->getCursorInventory()->clearAll();

		$this->sendMessage($target, "Your inventory was cleared.");
	}
}