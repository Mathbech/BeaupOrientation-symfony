<?php

use App\Kernel;
use Symfony\Component\Dotenv\Dotenv;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

// Forcer le chargement du .env si les variables ne sont pas déjà là
if (!isset($_SERVER['APP_ENV']) && !isset($_ENV['APP_ENV'])) {
    (new Dotenv())->loadEnv(dirname(__DIR__).'/.env');
}

return function (array $context) {
    // Valeur fallback si pas définie
    $env = $_ENV['APP_ENV'] ?? $_SERVER['APP_ENV'] ?? 'dev';
    $debug = (bool) ($_ENV['APP_DEBUG'] ?? $_SERVER['APP_DEBUG'] ?? ('prod' !== $env));

    return new Kernel($env, $debug);
};
