<?php
declare(strict_types = 1);

namespace JackMD\Charm\Command\Defaults;

use JackMD\Charm\Charm;
use JackMD\Charm\Command\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use function is_null;

class Compass extends BaseCommand{

	/**
	 * Compass constructor.
	 *
	 * @param Charm $plugin
	 */
	public function __construct(Charm $plugin){
		parent::__construct(
			$plugin,

			"compass",
			"charm.command.compass.use",
			"Get the direction in which you are headed.",
			"/compass"
		);
	}

	/**
	 * @param CommandSender $sender
	 * @param string        $label
	 * @param array         $args
	 */
	public function onCommand(CommandSender $sender, string $label, array $args): void{
		if(!$sender instanceof Player){
			$this->sendError($sender, "This command only works in-game.");

			return;
		}

		$direction = $sender->getDirection();

		if(is_null($direction)){
			$this->sendError($sender, "Oops, there was an error while getting your face direction.");

			return;
		}

		switch($sender->getDirection()){
			case 0:
				$direction = "South";
			break;

			case 1:
				$direction = "West";
			break;

			case 2:
				$direction = "North";
			break;

			case 3:
				$direction = "East";
			break;

			default:
				$direction = "unknown";
			break;
		}

		$this->sendMessage($sender, "You are facing §e$direction");
	}
}