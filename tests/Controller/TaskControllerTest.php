<?php

namespace App\Tests\Controller;

use App\Tests\LoginForTest;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{

    use LoginForTest;

    public function testTasksPage () {
        $client = static::createClient();
        $client->request('GET', '/tasks');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testTasksCreatePage()
    {
        $client = static::createClient();
        $client->request('GET', '/tasks/create');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testTasksEditPage()
    {
        $client = static::createClient();
        $client->request('GET', '/tasks/11/edit');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testTasksTogglePage()
    {
        $client = static::createClient();
        $client->request('GET', '/tasks/11/toggle');
        $this->assertResponseRedirects('/tasks');
    }

    public function testTasksDeletePageWithBadUser()
    {
        $client = static::createClient();
        $client->request('GET', '/tasks/11/delete');
        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneByUsername('admin');
        $this->login($client, $user);
        $this->assertResponseRedirects('/tasks');
        $this->assertSelectorExists('.alert.alert-danger');
        //voir si dans la liste des taches la tache existe encore
    }
}