<?php

declare(ticks=1);

namespace PHPMD\TextUI;

use CodeClimate\PHPMD\Runner;

error_reporting(E_ERROR | E_PARSE | E_NOTICE);
date_default_timezone_set('UTC');
ini_set('memory_limit', -1);

require_once __DIR__.'/vendor/autoload.php';
require_once "Fingerprint.php";
require_once "JSONRenderer.php";
require_once "Runner.php";
require_once "Category.php";

use PHPMD\PHPMD;
use PHPMD\RuleSetFactory;
use PHPMD\Writer\StreamWriter;
use PHPMD\Renderer\JSONRenderer;

// obtain the config
$config = json_decode(file_get_contents('/config.json'), true);

// setup forking daemon
$server = new \fork_daemon();
$server->max_children_set(3);
$server->max_work_per_child_set(50);
$server->store_result_set(true);
$runner = new Runner($config, $server);
$server->register_child_run(array($runner, "run"));

$runner->queueDirectory("/code");

$server->process_work(true);

$results = $server->get_all_results();

// If there is no output from the runner, an exception must have occurred
if (count($results) == 0) {
    exit(1);
}

foreach ($results as $result_file) {
    if (is_a($result_file, "Exception")) {
        exit(1);
    }

    echo file_get_contents($result_file);
    unlink($result_file);
}
