<?php

namespace PHPMD\Renderer;

use PHPMD\AbstractRenderer;
use PHPMD\Report;

class JSONRenderer extends AbstractRenderer
{
    private $ruleCategories = array(
      "CleanCode/BooleanArgumentFlag" => "Clarity",
      "CleanCode/ElseExpression" => "Clarity",
      "CleanCode/StaticAccess" => "Clarity",
      "Controversial/CamelCaseClassName" => "Style",
      "Controversial/CamelCaseMethodName" => "Style",
      "Controversial/CamelCaseParameterName" => "Style",
      "Controversial/CamelCasePropertyName" => "Style",
      "Controversial/CamelCaseVariableName" => "Style",
      "Controversial/Superglobals" => "Security",
      "CyclomaticComplexity" => "Complexity",
      "Design/CouplingBetweenObjects" => "Clarity",
      "Design/DepthOfInheritance" => "Clarity",
      "Design/EvalExpression" => "Security",
      "Design/ExitExpression" => "Bug Risk",
      "Design/GotoStatement" => "Clarity",
      "Design/LongClass" => "Complexity",
      "Design/LongMethod" => "Complexity",
      "Design/LongParameterList" => "Complexity",
      "Design/NpathComplexity" => "Complexity",
      "Design/NumberOfChildren" => "Clarity",
      "Design/TooManyFields" => "Complexity",
      "Design/TooManyMethods" => "Complexity",
      "Design/WeightedMethodCount" => "Complexity",
      "ExcessivePublicCount" => "Complexity",
      "Naming/BooleanGetMethodName" => "Style",
      "Naming/ConstantNamingConventions" => "Style",
      "Naming/ConstructorWithNameAsEnclosingClass" => "Compatability",
      "Naming/LongVariable" => "Style",
      "Naming/ShortMethodName" => "Style",
      "Naming/ShortVariable" => "Style",
      "UnusedFormalParameter" => "Bug Risk",
      "UnusedLocalVariable" => "Bug Risk",
      "UnusedPrivateField" => "Bug Risk",
      "UnusedPrivateMethod" => "Bug Risk"
    );

    public function renderReport(Report $report)
    {
        $writer = $this->getWriter();

        foreach ($report->getRuleViolations() as $violation) {
            $rule = $violation->getRule();
            $checkName = preg_replace("/^PHPMD\/Rule\//", "", str_replace("\\", "/", get_class($rule)));

            $path = preg_replace("/^\/code\//", "", $violation->getFileName());
            $category = $this->ruleCategories[$checkName];

            if ($category == null) {
                $category = "Style";
            }

            $issue = array(
                "type" => "issue",
                "check_name" => $checkName,
                "description" => $violation->getDescription(),
                "categories" => array($category),
                "location" => array(
                    "path" => $path,
                    "lines" => array(
                        "begin" => $violation->getBeginLine(),
                        "end" => $violation->getEndLine()
                    )
                )
            );

            $json = json_encode($issue, JSON_UNESCAPED_SLASHES, JSON_UNESCAPED_UNICODE);
            $writer->write($json);
            $writer->write(chr(0));
        }
    }
}
