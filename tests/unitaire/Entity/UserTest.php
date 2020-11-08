<?php

declare(strict_types=1);

namespace App\Tests\Unitaire\Entity;

use Faker;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CategoryTest extends KernelTestCase
{
    /**
     * @var Category
     */
    private $user;

    private $nameGood='abcdef';
    private $mailGood= 'abcdef@live.fr';

    public function createEntity(): User
    {
        return (new User());
    }

    public function assertHasErros(User $user, $number = 0,$message)
    {
        self::bootKernel();
        $error = self::$container->get('validator')->validate($user);
        $this->assertCount($number, $error,$message);
    }

    public function testCreate()
    {
        $user = $this->createEntity();
        $this->assertHasErros($user, 2, 'test de creation');
        $this->assertInstanceOf(User::class, $user);
    }

    public function testNoError()
    {
        $this->assertHasErros(
            $this->createEntity()
            ->setName($this->nameGood)
            ->setEmail($this->mailGood)
            , 0,'Test no error');
    }

    /**
     * @dataProvider names
     */
    public function testNames($value,$number,$message)
    {
        $this->assertHasErros(
            $this->createEntity()
            ->setName($value)
            ->setEmail($this->mailGood),
            $number, $message . ' : '. $value);
    }

    public function names()
    {
        $faker = Faker\Factory::create();
        return [
            ['', 2, 'empty'],
            ['a',1,'just 1 char'],
            ['ab',1, 'just 2 char'],
            ['abc',1, 'just 3 char'],
            [$faker->realText(110),1, 'more than 110 char'],
            ['abc4',0, 'just 4 char'],
            ['Emmanuel SAUVAGE',0, 'long name'],
            [$faker->realText(20), 0,'more than 20 char']
        ];
    }

    /**
     * @dataProvider mails
     */
    public function testMailsBad($value,$number, $message)
    {
        $this->assertHasErros(
            $this->createEntity()
            ->setName($this->nameGood)
            ->setEmail($value),
            $number,
            $message . ' : ' . $value
        );
    }

    public function mails()
    {
        $faker = Faker\Factory::create();
        return [
            ['', 2, 'empty'],
            ['a',2, 'without @'],
            ['a@',2, 'just 2 char with @'],
            ['a@c',2, 'just 3 char with @'],
            ['a@l.fr', 1, 'good mail but short'],
            ['123456789', 1, 'bad mail good longueur'],
            [$faker->realText(300). '@live.fr',2, 'more than 250 char with @live.fr'],
            ['emmanuel.sauvage@live.fr',0, 'good mail'],
            ['sdfghjkljhgfjklmjhgjkljhgfjklmjhgjklmdsffdsdfgdsf@live.fr',0,  'more than 50  @live.fr']
        ];
    }


    /**
     * @dataProvider plainPassword
     */
    public function testPlainPassword($value, $number, $message)
    {
        $this->assertHasErros(
            $this->createEntity()
                ->setName($this->nameGood)
                ->setEmail($this->mailGood)
                ->setPlainPassword($value),
            $number,
            $message . ' : ' . $value
        );
    }

    public function plainPassword()
    {
        return [
            ['a', 2, 'just 1 char'],
            ['aaaaaaa', 2, 'just 7 char'],
            ['aa1aaaa', 2, 'just 7 char with number and char'],
            ['aaaaaaaaaaaaa', 1, 'more than 8 char'],
            ['aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 2, 'more than 20 char'],
            ['1aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 1, 'more than 20 char with char and number'],
            ['Aaaaaaaaaaaaa', 1, 'more than 8 char without number'],
            ['1111111111111', 1, 'more than 8 char without char '],
            ['a111111111111', 0, 'Good password '],
        ];
    }

    /**
     * @dataProvider plainPasswordConfirmation
     */
    public function testPlainPasswordConfirmation($value1, $value2, $number, $message)
    {
        $this->assertHasErros(
            $this->createEntity()
                ->setName($this->nameGood)
                ->setEmail($this->mailGood)
                ->setPlainPassword($value1)
                ->setPlainPasswordConfirmation($value2),
            $number,
            $message . ' : ' . $value1 .'-'. $value2
        );
    }

    public function plainPasswordConfirmation()
    {
        return [
            ['a111111111111', 'a111111111112', 1, 'différent but good '],
            ['a111111111111', 'a111111111111', 0, 'différent but good '],
        ];
    }


    /**
     * @dataProvider phones
     */
    public function testPhone($value, $number, $message)
    {
        $this->assertHasErros(
            $this->createEntity()
                ->setName($this->nameGood)
                ->setEmail($this->mailGood)
                ->setPhone($value),
            $number,
            $message . ' : ' . $value
        );
    }

    public function phones()
    {
        return [
            ['', 0, 'chaine vide'],
            [null, 0, 'chaine null'],
            ['00.00.00.00.00', 0, 'phone valide '],
            ['00.00.00.00.001234567', 1, 'trop long (21 char) '],
        ];
    }

    public function testInitialisation()
    {
        $user = $this->createEntity();

        $this->assertNull($user->getName(),'Name');
        $this->assertNull($user->getEmail(), 'Mail');
        $this->assertNull($user->getContent(),'Content');
        $this->assertSame(true, $user->getIsEnable(),'Enable');
    }
}
