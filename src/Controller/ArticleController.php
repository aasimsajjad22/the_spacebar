<?php


namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Service\MarkdownHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{

    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage(ArticleRepository $articleRepository)
    {
        $articles = $articleRepository->findAllPublishedOrderedByNewest();
        return $this->render('article/home.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/news/{slug}", name="article_show")
     */
    public function show(Article $article, MarkdownHelper $markdownHelper)
    {
        $article->setContent($markdownHelper->parse($article->getContent()));

        return $this->render('article/show.html.twig', [
            'title' => 'simple title',
            'article'=> $article,
        ]);
    }
}