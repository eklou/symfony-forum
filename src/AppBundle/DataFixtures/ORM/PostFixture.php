<?php
/**
 * Created by PhpStorm.
 * User: seb
 * Date: 31/08/2017
 * Time: 14:12
 */

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Post;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class PostFixture extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $postData = [
            ["title" => "SQL et les ORM", "theme" => "theme_SQL"],
            ["title" => "Les agrégats SQL", "theme" => "theme_SQL"],
            ["title" => "Oracle la Rolls des SGBDR", "theme" => "theme_SQL"],
            ["title" => "SQL et les relations", "theme" => "theme_SQL"],
            ["title" => "Les procédures stockées", "theme" => "theme_SQL"],

            ["title" => "Twig un super moteur de template", "theme" => "theme_Symfony"],
            ["title" => "Prendre le contrôle du contrôleur", "theme" => "theme_Symfony"],
            ["title" => "Entité du troisième type", "theme" => "theme_Symfony"],
            ["title" => "Doctrine et les associations", "theme" => "theme_Symfony"],

            ["title" => "Les nouveautés de PHP7", "theme" => "theme_PHP"],
            ["title" => "Un petit framework MVC en PHP", "theme" => "theme_PHP"],
            ["title" => "Le pattern DAO", "theme" => "theme_PHP"],

            ["title" => "Les nouveautés de ES 7", "theme" => "theme_Javascript"],
            ["title" => "La gestion des promesses", "theme" => "theme_Javascript"],
            ["title" => "Créer son premier plugin jQuery", "theme" => "theme_Javascript"],

            ["title" => "Ionic 3", "theme" => "theme_Ionic"],
            ["title" => "Une todo list avec Ionic 3", "theme" => "theme_Ionic"],
            ["title" => "Ionic Native", "theme" => "theme_Ionic"],
            ["title" => "Ionic et AngularJS", "theme" => "theme_Ionic"],

        ];

        $faker = Factory::create("fr_FR");

        $postNumber = 1;

        foreach ($postData as $item){
            $entity = new Post();
            $author_reference = "auteur_".mt_rand(1,3);
            $entity->setTitle($item["title"])
                ->setTheme($this->getReference($item["theme"]))
                ->setCreatedAt($faker->dateTimeThisYear())
                ->setAuthor($this->getReference($author_reference))
                ->setText($faker->text(800));

            $this->addReference("post_". $postNumber++, $entity);

            $manager->persist($entity);
        }

        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 10;
    }
}