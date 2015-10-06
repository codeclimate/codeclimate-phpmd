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
        if(isset($this->config['include_paths'])) {
            $this->queueWithIncludePaths();
        } else {
            $this->queuePaths($dir, $prefix, $this->config['exclude_paths']);
        }

        $this->server->process_work(false);
    }

    public function queueWithIncludePaths() {
        foreach ($this->config['include_paths'] as $f) {
            if ($f !== '.' and $f !== '..') {

                if (is_dir("/code$f")) {
                    $this->queuePaths("/code$f", "$f/");
                    continue;
                }

                $this->server->addwork(array("/code/$f"));
            }
        }
    }

    public function queuePaths($dir, $prefix = '', $exclusions = []) {
        $dir = rtrim($dir, '\\/');

        foreach (scandir($dir) as $f) {
            if (in_array("$prefix$f", $exclusions)) {
                continue;
            }

            if ($f !== '.' and $f !== '..') {
                if (is_dir("$dir/$f")) {
                    $this->queuePaths("$dir/$f", "$prefix$f/", $exclusions);
                    continue;
                }

                $prefix = ltrim($prefix, "\\/");
                $this->server->addwork(array("/code/$prefix$f"));
            }
        }
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
