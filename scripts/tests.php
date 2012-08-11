<?php

/**
 * Locate the appropriate vendor/autoload.php file and uses it as --bootstrap for PHPUnit
 */
$arguments = $argv;

$target = RunnerHelper::getTargetPath($arguments);
$bootstrap = RunnerHelper::findBootstrapFor($target);

exec(sprintf(
	'phpunit --bootstrap=%s %s',
	$bootstrap,
	implode(' ', $arguments)
));

class RunnerHelper
{
	/**
	 *	Get the absolute path of the target
	 */
	public static function getTargetPath($argv)
	{
		$currentDir = $_SERVER['PWD'];
		$target = self::getTarget($argv);

		return self::isAbsolute($target) ? $target : $currentDir . '/' . $target;
	}

	/**
	 * Scan all the parent folders until it finds vendor/autoload.php
	 */
	public static function findBootstrapFor($target)
	{
		$folder = $target;
		do
		{
			$folder = self::parentFolder($folder);
			$bootstrap = self::findAutoloadFile($folder);

			if( $bootstrap !== null )
			{
				return $bootstrap;
			}
		}
		while($folder && $folder !== '/');

		exit("Could not find autoloader for $target \n");
	}

	private static function getTarget(&$argv)
	{
		foreach( $argv as $i => $arg )
		{
			if( $arg === '--target' )
			{
				$target = $argv[$i+1];
				unset($argv[$i]);
				unset($argv[$i+1]);

				return $target;
			}
		}

		exit("Missing argument --target \n");
	}

	private static function isAbsolute($target)
	{
		return substr($target, 0, 1) === '/';
	}

	private static function parentFolder($folder)
	{
		return substr($folder, 0, strrpos($folder, '/'));
	}

	private static function findAutoloadFile($folder)
	{
		$autoloader = $folder . '/vendor/autoload.php';
		if(file_exists($autoloader))
		{
			return $autoloader;
		}

		return null;
	}
}