<?php

declare(strict_types=1);

namespace App\Tests\Unitaire\Entity;

use Faker;
use App\Entity\User;
use App\Entity\Backpack;
use App\Entity\MProcess;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BackpackTest extends KernelTestCase
{
    private $nameGood = 'abcdef';

    public function createEntity(): Backpack
    {
        return (new Backpack());
    }

    public function assertHasErros(Backpack $mp, $number = 0, $message)
    {
        self::bootKernel();
        $error = self::$container->get('validator')->validate($mp);
        $this->assertCount($number, $error, $message);
    }

    public function testCreate()
    {
        $mp = $this->createEntity();
        $this->assertHasErros($mp, 3, 'test de creation');
        $this->assertInstanceOf(Backpack::class, $mp);
    }

    public function testNoError()
    {
        $this->assertHasErros(
            $this->createEntity()
                ->setName($this->nameGood)
                ->setOwner(new User())
                ->setMProcess(new MProcess()),
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
                ->setOwner(new User())
                ->setMProcess(new MProcess()),
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
     * @dataProvider dirs
     */
    public function testDirs1($value, $number, $message)
    {
        $this->assertHasErros(
            $this->createEntity()
                ->setName($this->nameGood)
                ->setOwner(new User())
                ->setMProcess(new MProcess())
                ->setDir1($value),
            $number,
            $message . ' : ' . $value
        );
    }

    /**
     * @dataProvider dirs
     */
    public function testDirs2($value, $number, $message)
    {
        $this->assertHasErros(
            $this->createEntity()
                ->setName($this->nameGood)
                ->setOwner(new User())
                ->setMProcess(new MProcess())
                ->setDir2($value),
            $number,
            $message . ' : ' . $value
        );
    }

    /**
     * @dataProvider dirs
     */
    public function testDirs3($value, $number, $message)
    {
        $this->assertHasErros(
            $this->createEntity()
                ->setName($this->nameGood)
                ->setOwner(new User())
                ->setMProcess(new MProcess())
                ->setDir3($value),
            $number,
            $message . ' : ' . $value
        );
    }

    /**
     * @dataProvider dirs
     */
    public function testDirs4($value, $number, $message)
    {
        $this->assertHasErros(
            $this->createEntity()
                ->setName($this->nameGood)
                ->setOwner(new User())
                ->setMProcess(new MProcess())
                ->setDir4($value),
            $number,
            $message . ' : ' . $value
        );
    }

    /**
     * @dataProvider dirs
     */
    public function testDirs5($value, $number, $message)
    {
        $this->assertHasErros(
            $this->createEntity()
                ->setName($this->nameGood)
                ->setOwner(new User())
                ->setMProcess(new MProcess())
                ->setDir5($value),
            $number,
            $message . ' : ' . $value
        );
    }

    public function dirs()
    {
        $faker = Faker\Factory::create();
        return [
            ['', 1, 'empty'],
            [null, 0, 'empty'],
            ['a', 1, 'just 1 char'],
            ['ab', 0, 'just 2 char'],
            ['abc', 0, 'just 3 char'],
            [$faker->realText(150), 1, 'more than 150 char'],
            ['abc4', 0, 'just 4 char'],
            ['Dossier client', 0, 'long name'],
            [$faker->realText(20), 0, 'more than 20 char']
        ];
    }

    public function testInitialisation()
    {
        $user = $this->createEntity();

        $this->assertNull($user->getName(), 'Name');
    }
}
