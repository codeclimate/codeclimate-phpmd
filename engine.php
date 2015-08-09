<?php

declare(ticks=1);

namespace PHPMD\TextUI;

use CodeClimate\PHPMD\Runner;

error_reporting(E_ERROR | E_PARSE | E_NOTICE);
date_default_timezone_set('UTC');
ini_set('memory_limit', -1);

require_once __DIR__.'/vendor/autoload.php';
require_once "JSONRenderer.php";
require_once "Runner.php";

use PHPMD\PHPMD;
use PHPMD\RuleSetFactory;
use PHPMD\Writer\StreamWriter;
use PHPMD\Renderer\JSONRenderer;

$runner = new Runner();

// setup forking daemon
$server = new \fork_daemon();
$server->max_children_set(20);
$server->max_work_per_child_set(50);
$server->store_result_set(true);
$response = $server->register_child_run(array($runner, "run"));

$config = json_decode(file_get_contents('/config.json'), true);

$runner->setConfig($config);
$runner->setServer($server);
$runner->queueDirectory("/code");

$server->process_work(true);

foreach ($server->get_all_results() as $result_file) {
    echo file_get_contents($result_file);
    unlink($result_file);
}
