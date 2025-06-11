<?php

// 💣 Forçage de l'environnement test en toute priorité
putenv('APP_ENV=test');
$_ENV['APP_ENV'] = 'test';
$_SERVER['APP_ENV'] = 'test';

putenv('APP_DEBUG=0');
$_ENV['APP_DEBUG'] = '0';
$_SERVER['APP_DEBUG'] = '0';

use DoctrineMigrations\Version20250608111703;
use App\Kernel;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Filesystem\Filesystem;

require dirname(__DIR__).'/vendor/autoload.php';

// 👇 Ce chargement est OK car il ne remplace pas les variables déjà fixées
(new Dotenv())->bootEnv(dirname(__DIR__).'/.env.test');

// Clean up from previous runs
(new Filesystem())->remove([__DIR__ . '/../var/cache/test']);
(new Filesystem())->remove([__DIR__ . '/../var/sessions/test']);

$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
$output = new ConsoleOutput();
$application = new Application($kernel);
$application->setAutoExit(false);
$application->setCatchExceptions(false);

$runCommand = static function (string $name, array $options = []) use ($application): void {
    $input = new ArrayInput(array_merge(['command' => $name, '--env' => 'test'], $options));
    $input->setInteractive(false);
    $application->run($input);
};

echo "\n[ENV] Symfony utilise : " . ($_ENV['APP_ENV'] ?? '??') . "\n";


$runCommand('doctrine:database:create', [
    '--if-not-exists' => true,
]);
$runCommand('doctrine:schema:drop', [
    '--force' => true,
    '--full-database' => true,
]);
// $runCommand('doctrine:schema:create');
// $runCommand('doctrine:migrations:execute', [
//     'versions' => [Version20250608111703::class],
// ]);
$runCommand('doctrine:migrations:migrate', [
    '--no-interaction' => true,
]);

$runCommand('doctrine:fixtures:load', [
    '--group' => ['CodeceptionFixtures'],
    '--env' => 'test',
]);

$kernel->shutdown();
