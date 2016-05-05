<?php

namespace PHPMD\Renderer;

use PHPMD\AbstractRenderer;
use PHPMD\Report;
use PHPMD\Category\Category;
use PHPMD\Fingerprint;

class JSONRenderer extends AbstractRenderer
{
    public function renderReport(Report $report)
    {
        $writer = $this->getWriter();

        foreach ($report->getRuleViolations() as $violation) {
            $rule = $violation->getRule();
            $checkName = preg_replace("/^PHPMD\/Rule\//", "", str_replace("\\", "/", get_class($rule)));
            $path = preg_replace("/^\/code\//", "", $violation->getFileName());

            $category = Category::categoryFor($checkName);

            $metric = $violation->getMetric();
            $points = Category::pointsFor($checkName, $metric);
            $content = Category::documentationFor($checkName);

            $issue = array(
                "type" => "issue",
                "check_name" => $checkName,
                "description" => $violation->getDescription(),
                "categories" => array($category),
                "remediation_points" => $points,
                "location" => array(
                    "path" => $path,
                    "lines" => array(
                        "begin" => $violation->getBeginLine(),
                        "end" => $violation->getEndLine()
                    )
                ),
            );

            if ($content) {
                $issue["content"] = array(
                    "body" => $content
                );
            }

            $name = $violation->getMethodName() ?: $violation->getFunctionName() ?: $violation->getClassName();
            $fingerprintObject = new Fingerprint($path, $checkName, $name);
            $fingerprint = $fingerprintObject->compute();

            if ($fingerprint) {
                $issue["fingerprint"] = $fingerprint;
            }

            $json = json_encode($issue, JSON_UNESCAPED_SLASHES, JSON_UNESCAPED_UNICODE);
            $writer->write($json);
            $writer->write(chr(0));
        }
    }
}
