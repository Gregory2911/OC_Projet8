<?php

namespace App\Tests\Controller;

use App\Entity\Task;
use App\Entity\User;
use App\Tests\LoginForTest;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{

    use LoginForTest;

    public function createUser($username)
    {
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByUsername($username);
        return $testUser;
    }

    public function loadTask($title)
    {
        $taskRepository = static::$container->get(TaskRepository::class);
        $task = $taskRepository->findOneByTitle($title);
        return $task;
    }

    public function testTasksPage () {
        $client = static::createClient();
        $client->request('GET', '/tasks');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testCreateTask()
    {
        $client = static::createClient();

        $user = new User();
        $user = $this->createUser('admin');
        
        $this->login($client, $user);
        $crawler = $client->request('GET', '/tasks/create');
        $form = $crawler->selectButton('Ajouter')->form([
            'task[title]' => 'titre test',
            'task[content]' => 'contenu test'
        ]);
        $client->submit($form);
        $client->followRedirect();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertStringContainsString('titre test', $client->getResponse()->getContent());
        $this->assertStringContainsString('contenu test', $client->getResponse()->getContent());
    }

    public function testEditTask()
    {
        $client = static::createClient();

        $task = new Task();
        $task = $this->loadTask('titre test');
        
        $crawler = $client->request('GET', '/tasks/' . $task->getId() . '/edit');
        $form = $crawler->selectButton('Modifier')->form([
            'task[title]' => 'titre test modifié',
            'task[content]' => 'contenu test modifié'
        ]);
        $client->submit($form);
        $client->followRedirect();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertStringContainsString('titre test modifié', $client->getResponse()->getContent());
        $this->assertStringContainsString('contenu test modifié', $client->getResponse()->getContent());
    }

    public function testToggleTask()
    {
        $client = static::createClient();

        $task = new Task();
        $task = $this->loadTask('titre test modifié');

        $client->request('GET', '/tasks/' . $task->getId() . '/toggle');
        $client->followRedirect();
        // $this->assertContains("a bien été marquée", $client->getResponse()->getContent());
        $this->assertEquals(1, $task->isDone());
    }
    /*
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
    }*/
}