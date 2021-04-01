<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        //Creation of the admin user
        $admin = new User();
        $admin->setUsername('admin');
        $admin->setEmail('admin@admin.com');
        $admin->setPassword($this->encoder->encodePassword($admin, "password"));
        $admin->setRoles(array('ROLE_ADMIN'));

        $manager->persist($admin);

        //Creation of the anonymous user
        $anonymous = new User();
        $anonymous->setUsername('anonymous');
        $anonymous->setEmail('anonymous@anonymous.com');
        $anonymous->setPassword($this->encoder->encodePassword($admin, "password"));
        $anonymous->setRoles(array('ROLE_USER'));

        $manager->persist($anonymous);

        $users = array();
        
        //Creation of 5 users
        for($i = 0; $i < 5; $i++){
            $user = new User();
            $user->setUsername($faker->userName());
            $user->setPassword($this->encoder->encodePassword($user, $user->getUsername()));
            $user->setEmail($user->getUsername() . '@' . $faker->safeEmailDomain());
            $user->setRoles(array('ROLE_USER'));
            $users[] = $user;

            $manager->persist($user);
        }
        
        //Creation of a test task
        $task = new Task();
        $task->setTitle('test task');
        $task->setContent($faker->paragraph());
        $task->setCreatedAt($faker->dateTimeBetween('-2 months', '-1 months'));
        $task->setUser($users[mt_rand(0, count($users)-1)]);
        $task->isDone(0);
            
        $manager->persist($task);

        //Creation of 10 tasks
        for($i = 0; $i < 10; $i++){
            $task = new Task();
            $task->setTitle($faker->sentence(6));
            $task->setContent($faker->paragraph());
            $task->setCreatedAt($faker->dateTimeBetween('-2 months', '-1 months'));
            $task->setUser($users[mt_rand(0, count($users)-1)]);
            $task->isDone(0);
            
            $manager->persist($task);
        }

        $manager->flush();
    }
}
