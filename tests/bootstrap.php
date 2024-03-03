<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

// load environment variables if not in CI
if (getenv('CI') !== 'true') {
    // Load environment variables from .env
    $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
    $dotenv->load();
}
