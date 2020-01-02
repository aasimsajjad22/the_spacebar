<?php

namespace App\DataFixtures;

use App\Entity\ApiToken;
use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $faker;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->faker = Factory::create();
        for($i = 0; $i < 10; $i ++) {
            $user = new User();
            $user->setEmail(sprintf('admin%d@gmail.com', $i))
                ->setFirstName($this->faker->firstName)
                ->setPassword($this->passwordEncoder->encodePassword(
                    $user,
                    'password'
                ))
                ->setTwitterUsername($this->faker->userName);
            $user->agreeTerms();
            if($i % 2 == 0) {
                $user->setRoles(['ROLE_ADMIN']);
            }
            $apiToken1 = new ApiToken($user);
            $apiToken2 = new ApiToken($user);
            $manager->persist($apiToken1);
            $manager->persist($apiToken2);
            $manager->persist($user);
        }

        $manager->flush();
    }

    public function loadArticleFixtures()
    {
        $em = $this->getDoctrine()->getManager();
        for($i = 0; $i< 2; $i++) {
            $article = new Article();
            $article->setTitle('i am learning doctrine 123 title here ... : ' . $i)
                ->setSlug('i-am-learning-doctrine-'. $i)
                ->setContent('Hello world this is working content hello world tes contents ' . $i)
                ->setPublishedAt(new \DateTime());

            for($j = 0; $j < rand(3, 10); $j ++) {
                $comment1 = new Comment();
                $comment1->setAuthorName('Aasim')
                    ->setContent('Hello world test comment ' . $j)
                    ->setArticle($article);

                $comment2 = new Comment();
                $comment2->setAuthorName('Arshad')
                    ->setContent('Hello world test comment from arshad  ' . $j)
                    ->setArticle($article);

                $em->persist($comment1);
                $em->persist($comment2);
            }
            $em->persist($article);
        }
    }
}
