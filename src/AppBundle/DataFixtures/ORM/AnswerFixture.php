<?php
/**
 * Created by PhpStorm.
 * User: seb
 * Date: 31/08/2017
 * Time: 14:34
 */

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Answer;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Provider\DateTime;
use Symfony\Component\Validator\Constraints\Date;

class AnswerFixture extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $postRepository = $manager->getRepository("AppBundle:Post");
        $nbPosts = count($postRepository->findAll());

        $faker = Factory::create("fr_FR");

        for($i=1; $i <= $nbPosts; $i++){
            $nbAnswers = mt_rand(3, 10);

            for($k=1; $k <= $nbAnswers; $k++){
                $post = $this->getReference("post_{$i}");

                $entity = new Answer();
                $entity->setText($faker->text(mt_rand(30,200)))
                        ->setAuthor($faker->email)
                        ->setCreatedAt(
                            $faker->dateTimeBetween($post->getCreatedAt()->format('Y-m-d H:i:s'),
                            "now"
                            )
                        )
                        ->setPost($post);

                $manager->persist($entity);
            }

            $manager->flush();
        }
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 15;
    }
}