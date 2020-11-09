<?php

declare(strict_types=1);

namespace App\Tests\Unitaire\Entity;

use Faker;
use App\Entity\Process;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProcessTest extends KernelTestCase
{
    private $nameGood = 'abcdef';
    private $refGood = '';

    public function createEntity(): Process
    {
        return (new Process());
    }

    public function assertHasErros(Process $mp, $number = 0, $message)
    {
        self::bootKernel();
        $error = self::$container->get('validator')->validate($mp);
        $this->assertCount($number, $error, $message);
    }

    public function testCreate()
    {
        $mp = $this->createEntity();
        $this->assertHasErros($mp, 1, 'test de creation');
        $this->assertInstanceOf(Process::class, $mp);
    }

    public function testNoError()
    {
        $this->assertHasErros(
            $this->createEntity()
                ->setName($this->nameGood)
                ->setRef($this->refGood),
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
                ->setName($value)
                ->setRef($this->refGood),
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
     * @dataProvider refs
     */
    public function testRefsBad($value, $number, $message)
    {
        $this->assertHasErros(
            $this->createEntity()
                ->setName($this->nameGood)
                ->setRef($value),
            $number,
            $message . ' : ' . $value
        );
    }

    public function refs()
    {
        $faker = Faker\Factory::create();
        return [
            ['', 0, 'empty'],
            ['a', 0, '1 char'],
            ['aa', 0, 'just 2 char'],
            ['abc', 0, 'just 3 char '],
            ['abcc', 0, 'just 4 char '],
            ['abccc', 0, 'just 5 char '],
            ['abcccc', 0, 'just 6 char '],
            [$faker->realText(25), 0, 'more than 25 char'],
            [$faker->realText(35), 1, 'more than 35 char']
        ];
    }



    public function testInitialisation()
    {
        $user = $this->createEntity();

        $this->assertNull($user->getName(), 'Name');
        $this->assertNull($user->getRef(), 'Réference');
        $this->assertSame(true, $user->getIsEnable(), 'Enable');
    }
}
