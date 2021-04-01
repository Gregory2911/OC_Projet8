<?php

namespace App\Tests\Controller;

use App\Entity\Task;
use App\Entity\User;
use App\Tests\LoginForTest;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{

    use LoginForTest;

    public function createUser($username)
    {
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByUsername($username);
        return $testUser;
    }

    public function testPageUsersWithNotRoleAdminUser()
    {
        $client = static::createClient();
        $user = $this->createUser('anonymous');
        $this->login($client, $user);
        $user->setRoles(array('ROLE_USER'));

        $client->request('GET', '/users');
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testPageCreateUserWithNotRoleAdminUser()
    {
        $client = static::createClient();
        $user = $this->createUser('anonymous');
        $this->login($client, $user);
        $user->setRoles(array('ROLE_USER'));

        $client->request('GET', '/users/create');
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testPageEditUserWithNotRoleAdminUser()
    {
        $client = static::createClient();
        $user = $this->createUser('anonymous');
        $this->login($client, $user);
        $user->setRoles(array('ROLE_USER'));

        $client->request('GET', '/users/' . $user->getId() . '/edit');
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }
}