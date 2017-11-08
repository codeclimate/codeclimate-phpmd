<?php
namespace PHPMD\Category;

const BUG_RISK = "Bug Risk";
const CLARITY = "Clarity";
const COMPATIBILITY = "Compatibility";
const COMPLEXITY = "Complexity";
const SECURITY = "Security";
const STYLE = "Style";

class Category
{
    public static $checks = array(
        "CleanCode/BooleanArgumentFlag" => [CLARITY, 300000],
        "CleanCode/ElseExpression" => [CLARITY, 200000],
        "CleanCode/StaticAccess" => [CLARITY, 200000],
        "Controversial/CamelCaseClassName" => [STYLE, 500000],
        "Controversial/CamelCaseMethodName" => [STYLE, 1000],
        "Controversial/CamelCaseParameterName" => [STYLE, 500000],
        "Controversial/CamelCasePropertyName" => [STYLE, 500000],
        "Controversial/CamelCaseVariableName" => [STYLE, 25000],
        "Controversial/Superglobals" => [SECURITY, 100000],
        "CyclomaticComplexity" => [COMPLEXITY, 100000],
        "Design/CouplingBetweenObjects" => [CLARITY, 400000],
        "Design/DepthOfInheritance" => [CLARITY, 500000],
        "Design/DevelopmentCodeFragment" => [SECURITY, 100000],
        "Design/EvalExpression" => [SECURITY, 300000],
        "Design/ExitExpression" => [BUG_RISK, 200000],
        "Design/GotoStatement" => [CLARITY, 200000],
        "Design/LongClass" => [COMPLEXITY, 200000],
        "Design/LongMethod" => [COMPLEXITY, 200000],
        "Design/LongParameterList" => [COMPLEXITY, 200000],
        "Design/NpathComplexity" => [COMPLEXITY, 200000],
        "Design/NumberOfChildren" => [CLARITY, 1000000],
        "Design/TooManyFields" => [COMPLEXITY, 900000],
        "Design/TooManyMethods" => [COMPLEXITY, 2000000],
        "Design/TooManyPublicMethods" => [COMPLEXITY, 2000000],
        "Design/WeightedMethodCount" => [COMPLEXITY, 2000000],
        "ExcessivePublicCount" => [COMPLEXITY, 700000],
        "Naming/BooleanGetMethodName" => [STYLE, 200000],
        "Naming/ConstantNamingConventions" => [STYLE, 100000],
        "Naming/ConstructorWithNameAsEnclosingClass" => [COMPATIBILITY, 400000],
        "Naming/LongVariable" => [STYLE, 1000000],
        "Naming/ShortMethodName" => [STYLE, 800000],
        "Naming/ShortVariable" => [STYLE, 500000],
        "UnusedFormalParameter" => [BUG_RISK, 200000],
        "UnusedLocalVariable" => [BUG_RISK, 200000],
        "UnusedPrivateField" => [BUG_RISK, 200000],
        "UnusedPrivateMethod" => [BUG_RISK, 200000],
    );

    public static function pointsFor($checkName, $metric)
    {
        $points = self::$checks[$checkName][1];

        if ($points && $metric) {
            $points *= $metric;
        }

        return $points;
    }

    public static function categoryFor($checkName)
    {
        $category = self::$checks[$checkName][0];

        if ($category == null) {
            $category = "Style";
        }

        return $category;
    }

    public static function documentationFor($checkName)
    {
        $checkNameParts = explode("/", $checkName);
        end($checkNameParts);
        $rule = array_pop($checkNameParts);

        $filePath = dirname(__FILE__) . "/content/" . strtolower($rule) . ".txt";

        if (file_exists($filePath)) {
            return file_get_contents($filePath);
        }
    }
}
