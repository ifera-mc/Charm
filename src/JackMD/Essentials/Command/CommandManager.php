<?php
declare(strict_types = 1);

namespace JackMD\Essentials\Command;

use JackMD\Essentials\Command\Defaults\Gamemode;
use JackMD\Essentials\Essentials;
use pocketmine\command\Command;
use function in_array;
use function is_null;

class CommandManager{

	/** @var Essentials */
	private $plugin;

	/** @var array */
	private $overrideableCommands = [
		"gamemode"
	];

	/**
	 * CommandManager constructor.
	 *
	 * @param Essentials $plugin
	 */
	public function __construct(Essentials $plugin){
		$this->plugin = $plugin;

		$this->registerAll();
	}

	/**
	 * Registers all the commands.
	 */
	private function registerAll(): void{
		$plugin = $this->plugin;

		/** @var BaseCommand[] $commands */
		$commands = [
			new Gamemode($plugin),
		];

		foreach($commands as $command){
			$this->registerCommand($command);
		}
	}

	/**
	 * @param Command $command
	 */
	public function registerCommand(Command $command): void{
		$plugin = $this->plugin;
		$commandName = $command->getName();

		if((bool) !$plugin->getConfig()->getNested("commands.$commandName")){
			return;
		}

		if(in_array($commandName, $this->overrideableCommands)){
			$this->unregisterCommand($commandName);
		}

		$plugin->getServer()->getCommandMap()->register("essentials", $command);
		$plugin->getLogger()->debug(Essentials::PREFIX . "§7Registered Command: §6$commandName");
	}

	/**
	 * @param string $commandName
	 */
	public function unregisterCommand(string $commandName): void{
		$commandMap = $this->plugin->getServer()->getCommandMap();

		if(!is_null($command = $commandMap->getCommand($commandName))){
			$commandMap->unregister($command);
		}
	}
}