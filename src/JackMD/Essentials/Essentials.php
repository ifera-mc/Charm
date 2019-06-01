<?php
declare(strict_types = 1);

namespace JackMD\Essentials;

use JackMD\Essentials\Command\CommandManager;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Essentials extends PluginBase{

	/**
	 * Don't change this please... It will hurt me :'(
	 *
	 * @var string
	 */
	public const PREFIX = "\xc2\xa7\x64\x45\x73\x73\x65\x6e\x74\x69\x61\x6c\x73\x20\xc2\xa7\x6c\xc2\xa7\x62\xc2\xbb\xc2\xa7\x72\x20";

	/** @var Config */
	private $config;

	/** @var CommandManager */
	private $commandManager;

	public function onLoad(): void{
		$this->saveResource("config.json");

		$this->config = new Config($this->getDataFolder() . "config.json", Config::JSON);
	}

	public function onEnable(): void{
		if((bool) !$this->getConfig()->get("enable")){
			$this->getLogger()->warning(self::PREFIX . "Plugin disabled via config.");

			return;
		}

		$this->commandManager = new CommandManager($this);
	}

	/**
	 * @return Config
	 */
	public function getConfig(): Config{
		return $this->config;
	}

	/**
	 * @return CommandManager
	 */
	public function getCommandManager(): CommandManager{
		return $this->commandManager;
	}
}