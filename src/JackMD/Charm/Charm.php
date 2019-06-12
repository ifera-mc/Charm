<?php
declare(strict_types = 1);

namespace JackMD\Charm;

use JackMD\Charm\Command\CommandManager;
use JackMD\Charm\Utils\Utils;
use JackMD\ConfigUpdater\ConfigUpdater;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Charm extends PluginBase{

	/**
	 * Don't change this please... It will hurt me :'(
	 *
	 * @var string
	 */
	public const PREFIX = "\xc2\xa7\x36\x43\x68\x61\x72\x6d\x20\xc2\xa7\x6c\xc2\xa7\x62\xc2\xbb\xc2\xa7\x72\x20";

	/** @var int */
	private const CONFIG_VERSION = 1;

	/** @var Config */
	private $config;

	/** @var CommandManager */
	private $commandManager;

	public function onEnable(): void{
		Utils::checkVirions();
		$this->checkConfigs();

		if(!$this->getConfig()->get("enable")){
			$this->getLogger()->warning(self::PREFIX . "Plugin disabled via config.");

			return;
		}

		$this->commandManager = new CommandManager($this);
	}

	private function checkConfigs(): void{
		$this->saveResource("config.json");
		$this->config = new Config($this->getDataFolder() . "config.json", Config::JSON);

		ConfigUpdater::checkUpdate($this, $this->config, "config-version", self::CONFIG_VERSION);
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
