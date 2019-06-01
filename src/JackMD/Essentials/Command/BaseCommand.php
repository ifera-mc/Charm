<?php
declare(strict_types = 1);

namespace JackMD\Essentials\Command;

use JackMD\Essentials\Essentials;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\Server;

abstract class BaseCommand extends Command{

	/** @var Essentials */
	private $plugin;

	/**
	 * BaseCommand constructor.
	 *
	 * @param Essentials $plugin
	 * @param string     $commandName
	 * @param string     $permission
	 * @param string     $description
	 * @param string     $usageMessage
	 * @param array      $aliases
	 */
	public function __construct(Essentials $plugin, string $commandName, string $permission, string $description = "", string $usageMessage = "", array $aliases = []){
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
	 * @return Essentials
	 */
	public function getPlugin(): Essentials{
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
		$sender->sendMessage(Essentials::PREFIX . "§c$error");
	}

	/**
	 * @param CommandSender $sender
	 * @param string        $message
	 */
	public function sendMessage(CommandSender $sender, string $message): void{
		$sender->sendMessage(Essentials::PREFIX . "§a$message");
	}
}