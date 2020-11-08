<?php

declare(strict_types=1);

namespace App\Tests\Unitaire\Helper;

use App\Helper\ParamsInServices;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class ParamsInServicesTest extends WebTestCase
{

    protected static $paramInServices;

    public static function setUpBeforeClass()
    {
        self::$paramInServices = new ParamsInServices(new ParameterBag([ParamsInServices::ES_APP_NAME=>'test']));
    }

    
    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Ce paramètre est inconnu
     */
    public function testParamsNotGood()
    {
        $this->assertSame(self::$paramInServices->get('notfound'), 'test');
    }

    /**
     * @dataProvider params
     */
    public function testParamsGood($value): void
    {
        $this->assertSame(self::$paramInServices->get(ParamsInServices::ES_APP_NAME),'test');
    }
    

}
