<?php

namespace App\Tests\Entity;

use App\Entity\Mission;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

/**
 * Class MissionTest
 *
 * @TODO: more unit tests
 * @package App\Entity
 */
class MissionTest extends TestCase
{


    public function testGetClient()
    {
        $user = new  User();
        $user->setUsername('Ronald');
        $mission = new Mission();
        $mission->setClient($user);

        $this->assertInstanceOf(User::class, $mission->getClient());
        $this->assertEquals($user->getUsername(), $mission->getClient()->getUsername());
    }

    public function testGetServiceDate()
    {
        $date = new \DateTime();

        $mission = new Mission();
        $mission->setServiceDate($date);
        $this->assertInstanceOf(\DateTimeInterface::class, $mission->getServiceDate());
        $this->assertEquals($date->format('Y-m-d'), $mission->getServiceDate()->format('Y-m-d'));
    }

    public function testSetQuantity()
    {
        $mission = new Mission();
        $mission->setQuantity(3);
        $this->assertEquals(3, $mission->getQuantity());
    }

    public function testGetDestinationCountry()
    {
        $mission = new Mission();
        $mission->setDestinationCountry('CN');
        $this->assertEquals('CN', $mission->getDestinationCountry());
    }

    public function testGetProductName()
    {
        $mission = new Mission();
        $mission->setProductName('my product');
        $this->assertEquals('my product', $mission->getProductName());
    }
}
