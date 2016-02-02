<?php

namespace CodeClimate\PHPMD;

use PHPMD\PHPMD;
use PHPMD\RuleSetFactory;
use PHPMD\Writer\StreamWriter;
use PHPMD\Renderer\JSONRenderer;

class Runner
{
    const RULESETS = 'cleancode,codesize,controversial,design,naming,unusedcode';

    private $config;
    private $server;

    public function __construct($config, $server)
    {
        $this->config = $config;
        $this->server = $server;
    }

    public function queueDirectory($dir, $prefix = '')
    {
        if (isset($this->config['include_paths'])) {
            $this->queueWithIncludePaths();
        } else {
            $this->queuePaths($dir, $prefix, $this->config['exclude_paths']);
        }

        $this->server->process_work(false);
    }

    public function queueWithIncludePaths()
    {
        foreach ($this->config['include_paths'] as $f) {
            if ($f !== '.' and $f !== '..') {
                if (is_dir("/code/$f")) {
                    $this->queuePaths("/code/$f", "$f");
                    continue;
                }
                $this->server->addwork(array("/code/$f"));
            }
        }
    }

    public function queuePaths($dir, $prefix = '', $exclusions = [])
    {
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

    public function prefixCodeDirectory($configRulesets)
    {
        $officialPhpRulesets = explode(',', Runner::RULESETS);
        $configRulesets = explode(',', $configRulesets);

        foreach ($configRulesets as &$r) {
            if (!in_array($r, $officialPhpRulesets) and $r[0] != "/") {
                $r  = "/code/$r";
            }
        }

        return implode(',', $configRulesets);
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

        $rulesets = Runner::RULESETS;

        if (isset($this->config['config']['rulesets'])) {
            $rulesets = $this->prefixCodeDirectory(
                $this->config['config']['rulesets']
            );
        }

        foreach ($files as &$file) {
            $phpmd->processFiles(
                $file,
                $rulesets,
                array($renderer),
                $ruleSetFactory
            );
        }

        return $resultFile;
    }
}
