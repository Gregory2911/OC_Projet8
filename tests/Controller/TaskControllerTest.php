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
        
        $task = $this->loadTask('titre test');
        $this->assertEquals($user->getId(), $task->getUser()->getId());
    }

    public function testEditTask()
    {
        $client = static::createClient();

        $task = $this->loadTask('titre test');
        //récupérer le user de la tache
        $idUserTask = $task->getUser()->getId();
        //connexion d'un utilisateur différent
        $user = $this->createUser('anonymous');
        $this->login($client, $user);

        $crawler = $client->request('GET', '/tasks/' . $task->getId() . '/edit');
        $form = $crawler->selectButton('Modifier')->form([
            'task[title]' => 'titre test modifié',
            'task[content]' => 'contenu test modifié'
        ]);
        $client->submit($form);
        $client->followRedirect();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        //controler que la tache modifié est bien passée en paramètre de la page
        $this->assertStringContainsString('titre test modifié', $client->getResponse()->getContent());
        $this->assertStringContainsString('contenu test modifié', $client->getResponse()->getContent());
        //controler que le user de la tache modifiée est toujours le même et non pas celui connecté
        $taskEdit = $this->loadTask('titre test modifié');
        $this->assertEquals($idUserTask, $taskEdit->getUser()->getId());
    }

    public function testToggleTask()
    {
        $client = static::createClient();

        $task = $this->loadTask('titre test modifié');

        $client->request('GET', '/tasks/' . $task->getId() . '/toggle');
        $client->followRedirect();
        $this->assertEquals(1, $task->isDone());
    }
    
    public function testDeleteTaskWithBadUser()
    {
        $client = static::createClient();

        $user = $this->createUser('anonymous');
        $this->login($client, $user);
        $task = $this->loadTask('titre test modifié');

        $client->request('GET', '/tasks/' . $task->getId() . '/delete');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testDeleteAnonymousTaskWithAdminUser()
    {
        $client = static::createClient();

        $user = $this->createUser('anonymous');
        $this->login($client, $user);
        
        $crawler = $client->request('GET', '/tasks/create');
        $form = $crawler->selectButton('Ajouter')->form([
            'task[title]' => 'titre test anonyme',
            'task[content]' => 'contenu test anonyme'
        ]);
        $client->submit($form);

        $task = $this->loadTask('titre test anonyme');
        
        //on tente de supprimer la tache avec l'admin user
        $user = $this->createUser('admin');
        $this->login($client, $user);
        $client->request('GET', '/tasks/' . $task->getId() . '/delete');        
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        //on contrôle que le tache n'existe plus
        $client->request('GET', '/tasks/' . $task->getId());
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testAddTaskWithNotAuthenticateUser()
    {
        $client = static::createClient();
        $client->request('GET', '/tasks/create');
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }

    public function testEditTaskWithNotAuthenticateUser()
    {
        $client = static::createClient();
        $task = $this->loadTask('test task');
        $client->request('GET', '/tasks/' . $task->getId() . '/edit');
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }

    public function testDeleteTaskWithNotAuthenticateUser()
    {
        $client = static::createClient();
        $task = $this->loadTask('test task');
        $client->request('GET', '/tasks/' . $task->getId() . '/delete');
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }
}