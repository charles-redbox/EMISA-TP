<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request): Response
    {
        //dump($request); exit;
        return new Response(
            '<html><body>Bienvenue sur la page d\'accueil!</body></html>'
        );
    }

    /**
     * @Route("/blog", name="blog_list")
     */
    public function blogList(): Response
    {
        // Simuler une liste d'articles
        $articles = [
            [
                'title' => 'Premier article',
                'content' => 'Ceci est le contenu du premier article.',
                'slug' => 'slug-article'
            ],
            [
                'title' => 'Deuxième article',
                'content' => 'Voici le contenu du deuxième article.',
                'slug' => 'slug-article'
            ],
            [
                'title' => 'Troisième article',
                'content' => 'Contenu du troisième article ici.',
                'slug' => 'slug-article'
            ],
        ];

        // Passer les articles à la vue
        return $this->render('blog/list.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/blog/{slug}", name="article_show")
     */
    public function show(string $slug): Response
    {

        $article = [
            'title' => 'Premier article',
            'content' => 'Ceci est le contenu du premier article.'
        ];

        return $this->render('blog/article.html.twig', [
            'article' => $article,
        ]);
    }

}
?>