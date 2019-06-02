<?php
declare(strict_types = 1);

namespace JackMD\Charm\Command\Defaults;

use JackMD\Charm\Charm;
use JackMD\Charm\Command\BaseCommand;
use pocketmine\command\CommandSender;
use function implode;

class Broadcast extends BaseCommand{

	/**
	 * Heal constructor.
	 *
	 * @param Charm $plugin
	 */
	public function __construct(Charm $plugin){
		parent::__construct(
			$plugin,

			"broadcast",
			"charm.command.broadcast.use",
			"Broadcast a message on the server.",
			"/broadcast [string:message]",
			[
				"bcast"
			]
		);
	}

	/**
	 * @param CommandSender $sender
	 * @param string        $label
	 * @param array         $args
	 */
	public function onCommand(CommandSender $sender, string $label, array $args): void{
		$this->getServer()->broadcastMessage("§c[§aBroadcast§c] §f" . implode(" ", $args));
	}
}