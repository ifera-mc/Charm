<?php
declare(strict_types = 1);

namespace JackMD\Charm\Utils;

use JackMD\ConfigUpdater\ConfigUpdater;
use JackMD\UpdateNotifier\UpdateNotifier;
use RuntimeException;
use function class_exists;

class Utils{

	/**
	 * Checks if the required virions/libraries are present before enabling the plugin.
	 */
	public static function checkVirions(): void{
		$requiredVirions = [
			UpdateNotifier::class,
			ConfigUpdater::class
		];

		foreach($requiredVirions as $class){
			if(!class_exists($class)){
				throw new RuntimeException("Charm plugin will only work if you use the plugin phar from Poggit.");
			}
		}
	}
}