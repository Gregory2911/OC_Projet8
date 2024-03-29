<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TaskTest extends KernelTestCase
{

    public function getEntity()
    {
        $user = new User();
        return (new Task())
            ->setTitle("Ceci est un titre test")
            ->setContent('Ceci est un contenu de test')
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUser($user);
    }

    public function assertHasErrors(Task $task, int $number = 0)
    {
        self::bootKernel();
        $errors = self::$container->get('validator')->validate($task);
        $messages = [];
        /**@var ConstraintViolation $error */
        foreach ($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
        }
        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    public function testValidEntity()
    {
        $this->assertHasErrors($this->getEntity(), 0);
    }

    public function testInvalidBlankTitle()
    {
        $this->assertHasErrors($this->getEntity()->setTitle(""), 1);
    }

    public function testInvalidBlankContent()
    {
        $this->assertHasErrors($this->getEntity()->setContent(""), 1);
    }

    public function testInvalidUser()
    {
        $this->assertHasErrors($this->getEntity()->setUser(null), 0);
    }
}