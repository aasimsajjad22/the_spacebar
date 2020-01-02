<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class TagFixtures extends Fixture
{

    private $faker;

    public function load(ObjectManager $manager)
    {
        $this->faker = Factory::create();
        $user = new User();
        $user->setEmail('admin0@gmail.com');
        for($i = 0; $i< 10; $i++) {
            $article = new Article();
            $article->setTitle($this->faker->realText(50))
                ->setSlug($this->faker->slug(3))
                ->setContent($this->faker->realText())
                ->setPublishedAt($this->faker->dateTimeThisYear());
            for($k = 0; $k < rand(2, 5); $k++) {
                $tag = new Tag();
                $tag->setName($this->faker->realText(20));
                $tag->setSlug($this->faker->slug(3));
                $manager->persist($tag);
                $article->addTag($tag);
            }

            for($j = 0; $j < rand(3, 10); $j ++) {
                $comment1 = new Comment();
                $comment1->setAuthorName($this->faker->name())
                    ->setContent($this->faker->realText())
                    ->setArticle($article);

                $comment2 = new Comment();
                $comment2->setAuthorName($this->faker->name())
                    ->setContent($this->faker->realText())
                    ->setArticle($article);

                $manager->persist($comment1);
                $manager->persist($comment2);
            }
            $manager->persist($article);
        }

        for($i = 0; $i < 10; $i ++) {
            $tag = new Tag();
            $tag->setName($this->faker->realText(20));
            $tag->setSlug($this->faker->slug(3));
            $manager->persist($tag);
        }

        $manager->flush();
    }
}
