<?php

namespace App\Tests\Entity;


use PHPUnit\Framework\TestCase;

/**
 * Class MissionTest
 *
 * @TODO: more unit tests
 * @package App\Entity
 */
class UserTest extends TestCase
{

    public function testGetUsername()
    {
        $user = new User();
        $user->setUsername('James');
        $this->assertEquals('James', $user->getUsername());
    }

    public function testOwnsMission()
    {
        $mission = new Mission();
        $mission->setProductName('product');

        $mission2 = new Mission();
        $mission2->setProductName('product 2');

        $user = new User();
        $user->addMission($mission);
        $this->assertEquals(true, $user->ownsMission($mission));
        $this->assertEquals(false, $user->ownsMission($mission2));
    }

    public function testAddMission()
    {
        $mission = new Mission();
        $mission->setProductName('name');

        $user = new User();
        $user->addMission($mission);
        $this->assertEquals($mission, $user->getMissions()->first());
    }

    public function testGetRoles()
    {
        $user = new User();
        $user->setRoles([User::ROLE_ADMIN]);
        $this->assertEquals(User::ROLE_ADMIN, $user->getRoles()[0]);
    }
}
