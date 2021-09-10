<?php

namespace PHPMD\Tests;

use PHPMD\Category\Category;

class CategoryTest extends \PHPUnit\Framework\TestCase
{

    public function testCategoryFor()
    {
        $category = Category::categoryFor("Design/EvalExpression");
        $this->assertEquals("Security", $category);
    }
}
