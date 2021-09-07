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

    /* Skip until we fix ci

    public function testDocumentationFor()
    {
        $documentation = Category::documentationFor("Design/EvalExpression");
        $this->assertContains("An eval-expression is untestable", $documentation);
    }

     */
}
