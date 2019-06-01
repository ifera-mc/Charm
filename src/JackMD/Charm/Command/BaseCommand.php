<?php
declare(strict_types = 1);

namespace JackMD\Charm\Command;

use JackMD\Charm\Charm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\Server;

abstract class BaseCommand extends Command{

	/** @var Charm */
	private $plugin;

	/**
	 * BaseCommand constructor.
	 *
	 * @param Charm $plugin
	 * @param string     $commandName
	 * @param string     $permission
	 * @param string     $description
	 * @param string     $usageMessage
	 * @param array      $aliases
	 */
	public function __construct(Charm $plugin, string $commandName, string $permission, string $description = "", string $usageMessage = "", array $aliases = []){
		parent::__construct($commandName, $description, $usageMessage, $aliases);

		$this->setPermission($permission);

		$this->plugin = $plugin;
	}

	/**
	 * @param CommandSender $sender
	 * @param string        $label
	 * @param array         $args
	 */
	public function execute(CommandSender $sender, string $label, array $args): void{
		if((!$sender instanceof ConsoleCommandSender) && !$this->testPermissionSilent($sender)){
			$this->sendMessage($sender,"You don't have permission to use this command.");

			return;
		}

		$this->onCommand($sender, $label, $args);
	}

	/**
	 * @param CommandSender $sender
	 * @param string        $label
	 * @param array         $args
	 */
	public abstract function onCommand(CommandSender $sender, string $label, array $args): void;

	/**
	 * @param CommandSender $sender
	 * @param string        $permission
	 * @return bool
	 */
	public final function hasPermission(CommandSender $sender, string $permission): bool{
		if($sender->hasPermission($permission)){
			return true;
		}

		$this->sendMessage($sender,"You don't have permission to use this command.");

		return false;
	}

	/**
	 * @return Charm
	 */
	public function getPlugin(): Charm{
		return $this->plugin;
	}

	/**
	 * @return Server
	 */
	public function getServer(): Server{
		return $this->plugin->getServer();
	}

	/**
	 * @param CommandSender $sender
	 * @param string        $error
	 */
	public function sendError(CommandSender $sender, string $error): void{
		$sender->sendMessage(Charm::PREFIX . "§c$error");
	}

	/**
	 * @param CommandSender $sender
	 * @param string        $message
	 */
	public function sendMessage(CommandSender $sender, string $message): void{
		$sender->sendMessage(Charm::PREFIX . "§a$message");
	}
}