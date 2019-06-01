<?php
declare(strict_types = 1);

namespace JackMD\Essentials\Command\Defaults;

use JackMD\Essentials\Command\BaseCommand;
use JackMD\Essentials\Essentials;
use JackMD\Essentials\Utils\PlayerUtils;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\Server;
use function strtolower;

class Gamemode extends BaseCommand{

	public function __construct(Essentials $plugin){
		parent::__construct(
			$plugin,

			"gamemode",
			"essentials.command.gamemode.use",
			"Change your gamemode.",
			"/gamemode <type:gamemode> [string:player]",
			[
				"survival",
				"suv",
				"s",
				"creative",
				"cre",
				"c",
				"spectator",
				"sp",
				"adventure",
				"adv",
				"gms",
				"gmc",
				"gmsp",
				"gma"
			]
		);
	}

	public function onCommand(CommandSender $sender, string $label, array $args): void{
		$label = strtolower($label);

		if(!isset($args[0]) && $label !== "gamemode"){
			switch($label){
				case "survival":
				case "suv":
				case "s":
				case "gms":
					$args[0] = "survival";
				break;

				case "creative":
				case "cre":
				case "c":
				case "gmc":
					$args[0] = "creative";
				break;

				case "spectator":
				case "sp":
				case "gmsp":
					$args[0] = "spectator";
				break;

				case "adventure":
				case "adv":
				case "gma":
					$args[0] = "adventure";
				break;
			}
		}

		if(!isset($args[0])){
			$this->sendError($sender, "Please supply a valid gamemode.");

			return;
		}

		$gamemode = Server::getGamemodeFromString((string) $args[0]);

		if($gamemode === -1){
			$this->sendError($sender, "Gamemode §5{$args[0]} §cnot found.");

			return;
		}

		$target = $sender;

		if(isset($args[1])){
			$targetName = strtolower($args[1]);

			$target = $this->getPlugin()->getServer()->getPlayer($targetName);

			if(!PlayerUtils::isOnline($target)){
				$this->sendError($sender, "Player with the name §4$targetName §cis not online or doesn't exist.");

				return;
			}
		}

		if($target instanceof ConsoleCommandSender){
			$this->sendError($sender, "You cannot use this command.");

			return;
		}

		if($target->getGamemode() === $gamemode){
			$this->sendError($sender, "Target player is already in " . Server::getGamemodeName($gamemode) . " mode.");

			return;
		}

		if($target === $sender){
			if(!$this->hasPermission($sender, "essentials.command.gamemode.self")){
				return;
			}
		}else{
			if(!$this->hasPermission($sender, "essentials.command.gamemode.target")){
				return;
			}

			$this->sendMessage($sender, "Successfully changed §2" . $target->getName() . "'s §agamemode to §6" . Server::getGamemodeName($target->getGamemode()));
		}

		$target->setGamemode($gamemode);

		$this->sendMessage($target, "Your gamemode has been changed to §6" . Server::getGamemodeName($target->getGamemode()));
	}
}