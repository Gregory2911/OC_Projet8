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
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testPageCreateUserWithNotRoleAdminUser()
    {
        $client = static::createClient();
        $user = $this->createUser('anonymous');
        $this->login($client, $user);
        $user->setRoles(array('ROLE_USER'));

        $client->request('GET', '/users/create');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testPageEditUserWithNotRoleAdminUser()
    {
        $client = static::createClient();
        $user = $this->createUser('anonymous');
        $this->login($client, $user);
        $user->setRoles(array('ROLE_USER'));

        $client->request('GET', '/users/' . $user->getId() . '/edit');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testCreateUser()
    {
        $client = static::createClient();

        $user = $this->createUser('admin');        
        $this->login($client, $user);

        $crawler = $client->request('GET', '/users/create');
        $form = $crawler->selectButton('Ajouter')->form([
            'user[username]' => 'UserTest',
            'user[password][first]' => 'password',
            'user[password][second]' => 'password',
            'user[email]' => 'test@test.com'
        ]);
        $client->submit($form);
        $client->followRedirect();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertStringContainsString('UserTest', $client->getResponse()->getContent());
    }

    public function testEditUser()
    {
        $client = static::createClient();

        $user = $this->createUser('admin');        
        $this->login($client, $user);        
        $crawler = $client->request('GET', '/users/' . $this->createUser('UserTest')->getId() . '/edit');
        $form = $crawler->selectButton('Modifier')->form([
            'user[username]' => 'UserTest2',
            'user[password][first]' => 'password',
            'user[password][second]' => 'password',
            'user[email]' => 'test@test.com'       
        ]);
        $client->submit($form);
        $client->followRedirect();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertStringContainsString('UserTest2', $client->getResponse()->getContent());
    }
}