<?php
declare(strict_types = 1);

namespace JackMD\Charm\Command\Defaults;

use JackMD\Charm\Charm;
use JackMD\Charm\Command\BaseCommand;
use pocketmine\command\CommandSender;
use function count;
use function implode;

class KickAll extends BaseCommand{

	/**
	 * KickAll constructor.
	 *
	 * @param Charm $plugin
	 */
	public function __construct(Charm $plugin){
		parent::__construct(
			$plugin,

			"kickall",
			"charm.command.kickall.use",
			"Kick every player online on the server.",
			"/kickall [string:reason]"
		);
	}

	/**
	 * @param CommandSender $sender
	 * @param string        $label
	 * @param array         $args
	 */
	public function onCommand(CommandSender $sender, string $label, array $args): void{
		$reason = "Kicked by admin.";

		if(!empty($args)){
			$reason = implode(" ", $args);
		}

		$players = $this->getServer()->getOnlinePlayers();

		foreach($players as $player){
			if($player === $sender){
				continue;
			}

			$player->kick($reason, false);
		}

		$this->sendMessage($sender, "Kicked §6" . (count($players) - 1) . " §aonline players.");
	}
}