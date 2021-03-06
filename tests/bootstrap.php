<?php declare(strict_types = 1);

require __DIR__ . '/../vendor/autoload.php';

Tester\Environment::setup();

function lockDatabase(): void {
	Tester\Environment::lock('database', __DIR__ . '/temp');
}

$configurator = new Nette\Configurator;
$configurator->setDebugMode(false);
$configurator->setTempDirectory(__DIR__ . '/temp');
$configurator->createRobotLoader()
	->addDirectory(__DIR__ . '/../app')
	->register();

$configurator->addConfig(__DIR__ . '/../app/config/config.neon');
if (file_exists(__DIR__ . '/config.local.neon')) {
	$configurator->addConfig(__DIR__ . '/config.local.neon');
}
return $configurator->createContainer();
