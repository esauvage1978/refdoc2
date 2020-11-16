<?php

declare(strict_types=1);

namespace App\Tests\Unitaire\Helper;

use App\Workflow\WorkflowNames;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class WorkflowNamesTest extends WebTestCase
{

    protected static $workflowNames;

    public static function setUpBeforeClass()
    {
        self::$workflowNames = new WorkflowNames();
    }

    
    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Ce workflow est inconnu
     */
    public function testParamsNotGood()
    {
        $this->assertSame(self::$workflowNames->check('notfound'), 'test');
    }

    public function testParamsGood(): void
    {
        $this->assertSame(self::$workflowNames->check(workflowNames::WORKFLOW_ALL), workflowNames::WORKFLOW_ALL);
    }
    

}
