<?php

namespace CodeClimate\PHPMD;

use PHPMD\PHPMD;
use PHPMD\RuleSetFactory;
use PHPMD\Writer\StreamWriter;
use PHPMD\Renderer\JSONRenderer;

class Runner
{
    private $config;
    private $server;

    public function __construct($config, $server)
    {
        $this->config = $config;
        $this->server = $server;
    }

    public function queueDirectory($dir, $prefix = '')
    {
        $dir = rtrim($dir, '\\/');

        foreach (scandir($dir) as $f) {
            if (in_array("$prefix$f", $this->config["exclude_paths"])) {
                continue;
            }

            if ($f !== '.' and $f !== '..') {
                if (is_dir("$dir/$f")) {
                    $this->queueDirectory("$dir/$f", "$prefix$f/");
                    continue;
                }

                $this->server->addwork(array("/code/$prefix$f"));
            }
        }

        $this->server->process_work(false);
    }

    public function run($files)
    {
        $resultFile = tempnam(sys_get_temp_dir(), 'phpmd');

        $renderer = new JSONRenderer();
        $renderer->setWriter(new StreamWriter($resultFile));

        $ruleSetFactory = new RuleSetFactory();

        $phpmd = new PHPMD();

        if (isset($this->config['config']['file_extensions'])) {
            $phpmd->setFileExtensions(explode(',', $this->config['config']['file_extensions']));
        }

        $rulesets = "cleancode,codesize,controversial,design,naming,unusedcode";

        if (isset($this->config['config']['rulesets'])) {
            $rulesets = $this->config['config']['rulesets'];
        }

        $phpmd->processFiles(
            implode(",", $files),
            $rulesets,
            array($renderer),
            $ruleSetFactory
        );

        return $resultFile;
    }
}
