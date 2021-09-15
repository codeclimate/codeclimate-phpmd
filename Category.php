<?php
namespace PHPMD\Category;

class Category
{
    const BUG_RISK = "Bug Risk";
    const CLARITY = "Clarity";
    const COMPATIBILITY = "Compatibility";
    const COMPLEXITY = "Complexity";
    const SECURITY = "Security";
    const STYLE = "Style";

    public static $checks = array(
        "CleanCode/BooleanArgumentFlag" => [self::CLARITY, 300000],
        "CleanCode/ElseExpression" => [self::CLARITY, 200000],
        "CleanCode/StaticAccess" => [self::CLARITY, 200000],
        "CleanCode/IfStatementAssignment" => [self::CLARITY, 200000],
        "CleanCode/DuplicatedArrayKey" => [self::CLARITY, 300000],
        "CleanCode/MissingImport" => [self::CLARITY, 300000],
        "CleanCode/UndefinedVariable" => [self::CLARITY, 700000],
        "CleanCode/ErrorControlOperator" => [self::CLARITY, 300000],
        "Controversial/CamelCaseClassName" => [self::STYLE, 500000],
        "Controversial/CamelCaseMethodName" => [self::STYLE, 1000],
        "Controversial/CamelCaseParameterName" => [self::STYLE, 500000],
        "Controversial/CamelCasePropertyName" => [self::STYLE, 500000],
        "Controversial/CamelCaseVariableName" => [self::STYLE, 25000],
        "Controversial/Superglobals" => [self::SECURITY, 100000],
        "CyclomaticComplexity" => [self::COMPLEXITY, 100000],
        "Design/CouplingBetweenObjects" => [self::CLARITY, 400000],
        "Design/CountInLoopExpression" => [self::BUG_RISK, 100000],
        "Design/DepthOfInheritance" => [self::CLARITY, 500000],
        "Design/DevelopmentCodeFragment" => [self::SECURITY, 100000],
        "Design/EmptyCatchBlock" => [self::BUG_RISK, 200000],
        "Design/EvalExpression" => [self::SECURITY, 300000],
        "Design/ExitExpression" => [self::BUG_RISK, 200000],
        "Design/GotoStatement" => [self::CLARITY, 200000],
        "Design/LongClass" => [self::COMPLEXITY, 200000],
        "Design/LongMethod" => [self::COMPLEXITY, 200000],
        "Design/LongParameterList" => [self::COMPLEXITY, 200000],
        "Design/NpathComplexity" => [self::COMPLEXITY, 200000],
        "Design/NumberOfChildren" => [self::CLARITY, 1000000],
        "Design/TooManyFields" => [self::COMPLEXITY, 900000],
        "Design/TooManyMethods" => [self::COMPLEXITY, 2000000],
        "Design/TooManyPublicMethods" => [self::COMPLEXITY, 2000000],
        "Design/WeightedMethodCount" => [self::COMPLEXITY, 2000000],
        "ExcessivePublicCount" => [self::COMPLEXITY, 700000],
        "Naming/BooleanGetMethodName" => [self::STYLE, 200000],
        "Naming/ConstantNamingConventions" => [self::STYLE, 100000],
        "Naming/ConstructorWithNameAsEnclosingClass" => [self::COMPATIBILITY, 400000],
        "Naming/LongClassName" => [self::STYLE, 1000000],
        "Naming/LongVariable" => [self::STYLE, 1000000],
        "Naming/ShortClassName" => [self::STYLE, 500000],
        "Naming/ShortMethodName" => [self::STYLE, 800000],
        "Naming/ShortVariable" => [self::STYLE, 500000],
        "UnusedFormalParameter" => [self::BUG_RISK, 200000],
        "UnusedLocalVariable" => [self::BUG_RISK, 200000],
        "UnusedPrivateField" => [self::BUG_RISK, 200000],
        "UnusedPrivateMethod" => [self::BUG_RISK, 200000],
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
