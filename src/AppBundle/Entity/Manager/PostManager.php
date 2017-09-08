<?php


namespace AppBundle\Entity\Manager;


use AppBundle\Entity\Post;
use Doctrine\ORM\EntityManager;

class PostManager
{
    /**
     * @var EntityManager
     */
    private $manager;

    /**
     * @var Post
     */
    private $post;

    /**
     * PostManager constructor.
     * @param EntityManager $manager
     */
    public function __construct(EntityManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @return Post
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @param Post $post
     * @return PostManager
     */
    public function setPost($post)
    {
        $this->post = $post;
        return $this;
    }

    public function save($autoFlush = true){
        $this->manager->persist($this->post);
        if($autoFlush){
            $this->manager->flush();
        }
    }

    public function delete(){
        $this->manager->remove($this->post);
        $this->manager->flush();
    }


}