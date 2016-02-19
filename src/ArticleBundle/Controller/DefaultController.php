<?php

namespace ArticleBundle\Controller;


use ArticleBundle\Entity\Article;
use ArticleBundle\Entity\Comment;
use ArticleBundle\Form\ArticleType;
use ArticleBundle\Repository\ArticleRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

use JMS\SecurityExtraBundle\Annotation\Secure;



class DefaultController extends Controller{

    /**
     * @return ArticleRepository
     */
    protected function getRepository(){
    return $this
        ->getDoctrine()
        ->getRepository("ArticleBundle:Article");
    }

    //Exercice jour2:
    public function listAction(Request $request){

        $articleRepo = $this->getRepository();

//        var_dump($articles);die;
        $articles = $articleRepo->findAllWithComments();

        return $this->render('ArticleBundle:Default:list-articles.html.twig',[
            "articles" => $articles

        ]);

//        $articles = $this
//            ->getDoctrine()
//            ->getRepository("ArticleBundle:Article")
//            ->findAll();
//        $description = $request->query->get("description");
//        $em = $this->getDoctrine()->getManager();
//
//        if (null !== $description){
//            //requête DQL qu'on mettra plutot dans ArticleRepository
//            $query = $em->createQuery("SELECT art
//            FROM ArticleBundle:Article art
//            WHERE art.description LIKE :description_parameter
//            ORDER BY art.createdAt DESC
//        ")
//                ->setParameter("description_parameter", "%$description%");
//        }else{
//            $query = $em->createQuery("SELECT art
//            FROM ArticleBundle:Article art
//            ORDER BY art.createdAt DESC
//        ");
//        }
//
//        dump($query);//debogeur de symfony pour voir la requête
//        $articles = $query->getResult();
//        return $this->render('ArticleBundle:Default:list-articles.html.twig',[
//            "articles" => $articles
//        ]);


//        return $this->render('ArticleBundle:Default:list-articles.html.twig',[
//          "articles" => [
//              [
//                  "id" => 1,
//                  "title" => "article 1",
//                  "description" => "blabla article 1",
//                  "created_at" => new \DateTime(),
//                  "bg" => "rédacteur 1",
//              ],
//              [
//                  "id" => 2,
//                  "title" => "article 2",
//                  "description" => "blabla article 2",
//                  "created_at" => new \DateTime(),
//                  "bg" => "rédacteur 2",
//              ],
//              [
//                  "id" => 3,
//                  "title" => "article 3",
//                  "description" => "blabla article 3",
//                  "created_at" => new \DateTime(),
//                  "bg" => "rédacteur 3",
//              ],
//              [
//                  "id" => 4,
//                  "title" => "article 4",
//                  "description" => "blabla article 4",
//                  "created_at" => new \DateTime(),
//                  "bg" => "rédacteur 4",
//              ],
//          ]
//        ]);



    }

    public function addImageAction($id){

        $image = new \ArticleBundle\Entity\Image();

        $article = $this->getRepository()->find($id);

        $image
            ->setUrl("https://i.ytimg.com/vi/1v6M41Divso/maxresdefault.jpg")
            ->setAlt("Panda qui fait le con");

        $article->setImage($image);

        $em = $this->getDoctrine()->getManager();
        $em->persist($article);
        $em->flush();

        return new Response("Image ajoutée avec succès sur l'article $id");



    }

    public function indexAction(){
        //utilisation d'un service: la méthode store ici enregistre un fichier en local
        $service1 = $this->container->get('article.local_file');
        $service1->store(["data" => "data_tostore"]);


//        public function indexAction(Request $request){//
//        var_dump($request);
//        $urlFromPhp = isset($_GET['url']) ? $_GET['url'] : null;
//        $urlFromSym->query->get("url", "url-par-defaut");

//        //$_GET
//        $query = $request->query;
//        // $_POST
//        $post = $request->request;
//
//        if ($request->getMethod() == "POST"){
//
//        }elseif ($request->isXmlHttpRequest()){
//
//        }
// ***************
//        return $this->forward("");

//        $url_for_article1 = $this->generateUrl("article_detail", [
//            "slug" => "article-1",
//            "id" => 1
//        ]);

//        //redirection vers l'url qui vient d'être générée:
//        return $this->redirect($url_for_article1);

            //notre controleur passe un tab associatif à notre vue:
        return $this->render('ArticleBundle:Default:index.html.twig', [
//            "url_for_article1" => $url_for_article1,
            "valeurNeg" => -50,
            "dateDuJour" => new \DateTime(),
            "contenuHtml" => "<a href='test'>Test</a>",
            "items" => ["item 1", "item 2", "item 3"],
            "categories" => [
                ["name" => "catégorie 1", "description" => "Description de la catégorie 1"],
                ["name" => "catégorie 2", "description" => "Description de la catégorie 2"],
            ],
        ]);
    }



    public function detailAction($slug, $id, Request $request){// ordre des paramètres n'est pas important

//        var_dump($id);
//        var_dump($slug);
//        var_dump($request);die;
        $id = (int) $id;
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository("ArticleBundle:Article")->find($id);


        $comments = $article->getComments();

        if($article == null){
            return $this->redirectToRoute("article_list");
            throw $this->createNotFoundException();
        }

        $comment1 = new Comment();
        $comment1->setContent("commentaire 1 sur article ". $article->getId())
                 ->setArticle($article);

        $comment2 = new Comment();
        $comment2->setContent("commentaire 2 sur article ". $article->getId())
                 ->setArticle($article);


        $comments[] = $comment1;
        $comments[] = $comment2;

        $em->persist($article);
        $em->flush();

        return $this->render('ArticleBundle:Default:detail.html.twig', [
            "article" => $article
        ]);


//        return $this->render('ArticleBundle:Default:detail.html.twig', [
//            "slug" => $slug,
//            "id" => $id
//        ]);

    }

    /**
     * Ajout d'un article  [GET ou POST]
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function addAction(Request $request)
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->add("Ajouter", "submit");
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();
            $flashMessage = "L'article a bien été ajouté !";
            /** @var Session $session */
            $session = $request->getSession();
            $session->getFlashBag()->add('info', $flashMessage);
            return $this->redirectToRoute("article_detail", [
                "id"   => $article->getId(),
                "slug" => $article->getSlug()
            ]);
        }
        return $this->render("ArticleBundle:Default:add.html.twig", [
            "formulaire" => $form->createView()
        ]);
    }

//    public function addAction(Request $request){
//        $article = new Article();
//
//        $article
//            ->setTitle("titre 3")
//            ->setDescription("description de l'article 3")
//            ->setContent("contenu 3")
//            ->setCreatedBy("auteur 3");
//
//        $em = $this->getDoctrine()->getManager();//récup entityManager dans var $em
//
//        try{
//            $em->persist($article);
//            $em->flush();//exécute les requêtes sur la BDD
//            $flashMessage = "L'article a bien été ajouté";
//
//        }catch(\Exception $e){
//            $flashMessage = "L'article n'a pas pu être ajouté";
//        }
//
//        /** @var Session $session */
//        $session = $request->getSession();
//        $session->getFlashBag()->add('ajoutArticle', $flashMessage);
//
//        return $this->redirectToRoute("article_detail", [
//            "id" => $article->getId(),
//            "slug" => $article->getSlug()//getSlug pour formater le titre et enlever les caractères spéciaux
//        ]);

//    $article = new Article();
//        $form = $this->createForm(new ArticleType(), $article);
//        $form->handleRequest($request);
//
//        if($form->isValid()){
//            return $this->redirectToRoute("article_list");
//        }
//        return $this->render("ArticleBundle:Default:add.html.twig",[
//            "formulaire" => $form->createView()
//        ]);
//    }


    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Secure(roles="ROLE_ADMIN")
     */
    public function deleteAction($id){
        $em = $this->getDoctrine()->getManager();

        $repoArticle = $em->getRepository("ArticleBundle:Article");


        if( null === $article = $repoArticle->find($id)){
            throw $this->createNotFoundException();
        }
        $em->remove($article);
        $em->flush();
        return $this->redirectToRoute("article_list");

    }

    public function createFormArticle(Article $article){

        $formBuilder = $this->createFormBuilder($article);
        $formBuilder
            ->add("title", "text")
            ->add("description", "textarea")
            ->add("content", "textarea")
            ->add("createdAt", "datetime")
            ->add("Ajouter", "submit")
            ->add("Reset", "reset")
            ->setAction($this->generateUrl("article_update_post", ["id" => $article->getId()]));
        return $formBuilder->getForm();
    }


    //accessible seulement en POST:
    public function updatePostAction($id, Request $request){
        $em = $this->getDoctrine()->getManager();

        $article = $em->getRepository("ArticleBundle:Article")->find($id);
        $form = $this->createForm($article);

        $form->handleRequest($request);

        if($form->isValid()){
            $articleUpdated = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($articleUpdated);
            $em->flush();
            return $this->redirectToRoute("article_detail",[
                "id" => $article->getId(),
                "slug" => $article->getSlug()
            ]);
        }
//        var_dump($form->getData());
        return new Response("updatePostAction");
    }


    //fonction accessible uniquement en GET:
    public function updateAction($id){
        $em = $this->getDoctrine()->getManager();

        $article = $em->getRepository("ArticleBundle:Article")->find($id);

        $form = $this->createFormArticle($article);
        return $this->render("ArticleBundle:Default:update.html.twig", [
            "formulaire" => $form->createView()
        ]);
//        $formBuilder = $this->createFormBuilder($article);
//        $formBuilder
//            ->add("title", "text")
//            ->add("description", "textarea")
//            ->add("content", "textarea")
//            ->add("createdAt", "datetime")
//            ->add("Ajouter", "submit")
//            ->add("Reset", "reset")
//            ->setAction($this->generateUrl("article_update_post", ["id" => $article->getId()]));
//
//        /** @var Form $form */
//        $form = $formBuilder->getForm();//récupération du formulaire
//
//        return $this->render("ArticleBundle:Default:update.html.twig", [
//            "formulaire" => $form->createView()
//        ]);


    }


}
