<?php
namespace PHPMD\Category;

class Category
{
    public static $checks = array(
        "CleanCode/BooleanArgumentFlag" => ["Clarity", 300000],
        "CleanCode/ElseExpression" => ["Clarity", 200000],
        "CleanCode/StaticAccess" => ["Clarity", 200000],
        "Controversial/CamelCaseClassName" => ["Style", 500000],
        "Controversial/CamelCaseMethodName" => ["Style", 1000],
        "Controversial/CamelCaseParameterName" => ["Style", 500000],
        "Controversial/CamelCasePropertyName" => ["Style", 500000],
        "Controversial/CamelCaseVariableName" => ["Style", 25000],
        "Controversial/Superglobals" => ["Security", 100000],
        "CyclomaticComplexity" => ["Complexity", 100000],
        "Design/CouplingBetweenObjects" => ["Clarity", 400000],
        "Design/DepthOfInheritance" => ["Clarity", 500000],
        "Design/DevelopmentCodeFragment" => ["Security", 100000],
        "Design/EvalExpression" => ["Security", 300000],
        "Design/ExitExpression" => ["Bug Risk", 200000],
        "Design/GotoStatement" => ["Clarity", 200000],
        "Design/LongClass" => ["Complexity", 200000],
        "Design/LongMethod" => ["Complexity", 200000],
        "Design/LongParameterList" => ["Complexity", 200000],
        "Design/NpathComplexity" => ["Complexity", 200000],
        "Design/NumberOfChildren" => ["Clarity", 1000000],
        "Design/TooManyFields" => ["Complexity", 900000],
        "Design/TooManyMethods" => ["Complexity", 2000000],
        "Design/TooManyPublicMethods" => ["Complexity", 2000000],
        "Design/WeightedMethodCount" => ["Complexity", 2000000],
        "ExcessivePublicCount" => ["Complexity", 700000],
        "Naming/BooleanGetMethodName" => ["Style", 200000],
        "Naming/ConstantNamingConventions" => ["Style", 100000],
        "Naming/ConstructorWithNameAsEnclosingClass" => ["Compatibility", 400000],
        "Naming/LongVariable" => ["Style", 1000000],
        "Naming/ShortMethodName" => ["Style", 800000],
        "Naming/ShortVariable" => ["Style", 500000],
        "UnusedFormalParameter" => ["Bug Risk", 200000],
        "UnusedLocalVariable" => ["Bug Risk", 200000],
        "UnusedPrivateField" => ["Bug Risk", 200000],
        "UnusedPrivateMethod" => ["Bug Risk", 200000],
    );

    public static function pointsFor($checkName, $metric)
    {
        $points = self::$checks[$checkName][1];

        if ($points && $metric) {
            return $points *= $metric;
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
        $rule = array_pop(
            explode("/", $checkName)
        );

        $filePath = dirname(__FILE__) . "/content/" . strtolower($rule) . ".txt";

        if (file_exists($filePath)) {
            return file_get_contents($filePath);
        }
    }
}
