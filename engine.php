<?php

namespace PHPMD\TextUI;

error_reporting(E_ERROR | E_PARSE | E_NOTICE);
date_default_timezone_set('UTC');
ini_set('memory_limit', -1);

require_once __DIR__.'/vendor/autoload.php';
require_once "JSONRenderer.php";

use PHPMD\PHPMD;
use PHPMD\RuleSetFactory;
use PHPMD\Writer\StreamWriter;
use PHPMD\Renderer\JSONRenderer;

$config = json_decode(file_get_contents('/config.json'), true);

$renderer = new JSONRenderer();
$renderer->setWriter(new StreamWriter(STDOUT));

$ruleSetFactory = new RuleSetFactory();

$all_files = scandir_recursive("/code");

if ($config["exclude_paths"]) {
  $files = array();

  foreach ($all_files as $file) {
    if (!in_array($file, $config["exclude_paths"])) {
      $files[] = "/code/".$file;
    }
  }
} else {
  foreach ($all_files as $file) {
    if (!in_array($file, $ignorePatterns)) {
      $files[] = "/code/".$file;
    }
  }
}

$phpmd = new PHPMD();

// if ($extensions !== null) {
//     $phpmd->setFileExtensions(explode(',', $extensions));
// }

$phpmd->processFiles(
    implode(",", $files),
    "cleancode,codesize,controversial,design,naming,unusedcode",
    array($renderer),
    $ruleSetFactory
);

function scandir_recursive($dir, $prefix = '') {
  $dir = rtrim($dir, '\\/');
  $result = array();

  foreach (scandir($dir) as $f) {
    if ($f !== '.' and $f !== '..') {
      if (is_dir("$dir/$f")) {
        $result = array_merge($result, scandir_recursive("$dir/$f", "$prefix$f/"));
      } else {
        $result[] = $prefix.$f;
      }
    }
  }

  return $result;
}
