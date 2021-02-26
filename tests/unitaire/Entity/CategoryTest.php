<?php

declare(strict_types=1);

namespace App\Tests\Unitaire\Entity;

use Faker;
use App\Entity\Category;
use App\Workflow\WorkflowNames;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CategoryTest extends KernelTestCase
{
    private $nameGood = 'abcdef';
    private $refGood = '000';

    public function createEntity(): Category
    {
        return (new Category());
    }

    public function assertHasErros(Category $entity, $number = 0, $message)
    {
        self::bootKernel();
        $error = self::$container->get('validator')->validate($entity);
        $this->assertCount($number, $error, $message);
    }

    public function testCreate()
    {
        $entity = $this->createEntity();
        $this->assertHasErros($entity, 1, 'test de creation');
        $this->assertInstanceOf(Category::class, $entity);
    }

    public function testNoError()
    {
        $this->assertHasErros(
            $this->createEntity()
                ->setName($this->nameGood),
            0,
            'Test no error'
        );
    }

    /**
     * @dataProvider names
     */
    public function testNames($value, $number, $message)
    {
        $this->assertHasErros(
            $this->createEntity()
                ->setName($value),
            $number,
            $message . ' : ' . $value
        );
    }

    public function names()
    {
        $faker = Faker\Factory::create();
        return [
            ['', 2, 'empty'],
            ['a', 1, 'just 1 char'],
            ['ab', 1, 'just 2 char'],
            ['abc', 0, 'just 3 char'],
            [$faker->realText(300), 1, 'more than 300 char'],
            ['abc4', 0, 'just 4 char'],
            ['Dossier client', 0, 'long name'],
            [$faker->realText(20), 0, 'more than 20 char']
        ];
    }

    /**
     * @dataProvider icones
     */
    public function testIcones($value, $number, $message)
    {
        $this->assertHasErros(
            $this->createEntity()
                ->setName($this->nameGood)
                ->setIcone($value),
            $number,
            $message . ' : ' . $value
        );
    }

    public function icones()
    {
        $faker = Faker\Factory::create();
        return [
            ['', 2, 'empty'],
            ['a', 1, '1 char'],
            ['aa', 1, 'just 2 char'],
            ['abc', 0, 'just 3 char '],
            ['abcc', 0, 'just 4 char '],
            ['abccc', 0, 'just 5 char '],
            ['abcccc', 0, 'just 6 char '],
            [$faker->realText(55), 1, 'more than 30 char']
        ];
    }


    /**
     * @dataProvider bgColors
     */
    public function testbgColors($value, $number, $message)
    {
        $this->assertHasErros(
            $this->createEntity()
                ->setName($this->nameGood)
                ->setBgColor($value),
            $number,
            $message . ' : ' . $value
        );
    }

    public function bgColors()
    {
        $faker = Faker\Factory::create();
        return [
            ['', 2, 'empty'],
            ['a', 1, '1 char'],
            ['aa', 1, 'just 2 char'],
            ['abc', 1, 'just 3 char '],
            ['#fff', 0, 'just 4 char '],
            ['#ffff', 0, 'just 5 char '],
            ['#fffff', 0, 'just 6 char '],
            ['#ffffff', 0, 'just 7 char '],
            ['12345678', 1, 'more than 7 char']
        ];
    }



    /**
     * @dataProvider foreColors
     */
    public function testForeColors($value, $number, $message)
    {
        $this->assertHasErros(
            $this->createEntity()
                ->setName($this->nameGood)
                ->setForeColor($value),
            $number,
            $message . ' : ' . $value
        );
    }

    public function foreColors()
    {
        $faker = Faker\Factory::create();
        return [
            ['', 2, 'empty'],
            ['a', 1, '1 char'],
            ['aa', 1, 'just 2 char'],
            ['abc', 1, 'just 3 char '],
            ['#fff', 0, 'just 4 char '],
            ['#ffff', 0, 'just 5 char '],
            ['#fffff', 0, 'just 6 char '],
            ['#ffffff', 0, 'just 7 char '],
            ['12345678', 1, 'more than 7 char']
        ];
    }

    /**
     * @dataProvider workflowNames
     */
    public function testWorkflowNames($value, $number, $message)
    {
        $this->assertHasErros(
            $this->createEntity()
                ->setName($this->nameGood)
                ->setWorkflowName($value),
            $number,
            $message . ' : ' . $value
        );
    }

    public function workflowNames()
    {
        $faker = Faker\Factory::create();
        return [
            [WorkflowNames::WORKFLOW_ALL, 0, 'Data : ' . WorkflowNames::WORKFLOW_ALL],
            [WorkflowNames::WORKFLOW_WITHOUT_CONTROL, 0, 'data : '. WorkflowNames::WORKFLOW_WITHOUT_CONTROL],
            [WorkflowNames::WORKFLOW_WITHOUT_DOC, 0, 'data : ' . WorkflowNames::WORKFLOW_WITHOUT_DOC],
        ];
    }

    public function testInitialisation()
    {
        $entity = $this->createEntity();

        $this->assertNull($entity->getName(), 'Name');
        $this->assertSame(true, $entity->getIsEnable(), 'Enable');
    }
}
