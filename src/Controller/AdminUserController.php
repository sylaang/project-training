<?php

// on stocke le controlleur dans l'espace de nom controller
// si on a besoin de cette class il faudra rappeller son chemin
namespace App\Controller;

// dans mon code je vais utiliser l'entité user notamment dans la cas de 
// la création d'un user
use App\Entity\User;
// par rapport au formulaire dans le cas ou je créé ou j'edite un user.
use App\Form\UserType;
// Le repository est créé au moment de l'entité.
// il sert à recuperer les données findall findby ...
// dans notre cas il sert à Lire les données mais pas pour lire une seul donnée.
// on s'en sert pour la suppression , modification
use App\Repository\UserRepository;
// Pour tout les classes de controlleur
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// Les requetes (GET POST ...), dans tout les cas 
// sauf la page avec toutes les données qui ne necessite pas de lire des données en GET
// Edit : POST les données du formulaire
// Nouveau user : Post les données du formulaire
// suppression : GET des données de l'URL 
use Symfony\Component\HttpFoundation\Request;
// L'objet qui retourne la twig. 
// Afficher tout les user / un seul user : Renvoie une Twig
// Nouvel utilisateur / Edit : Renvoie une Twig
// Suppresion d'un User : Redirection
use Symfony\Component\HttpFoundation\Response;
// Dans chaque fonction j'ai besoin d'une URL.
//  Du coup la classe route est appellé à chaque fonction
use Symfony\Component\Routing\Annotation\Route;


// génére une route utilisable pour l'admin
// c'est une route général qui sera le préfixe utilisé pour toutes 
// les fonction de la classe AdminUserController
#[Route('/admin/user')]
// La class add user controller fait appel à l’Abstractcontroller afin d’utiliser les fonctions de symfony (développeurs de synfony)
class AdminUserController extends AbstractController
{
    // cette route s'applique à la fonction index 
    // elle est la suite la route de la classe
    #[Route('/', name: 'app_admin_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        // Renvoie une
        //  réponse à la twig et dans les paramètres de la réponse 
        // on va afficher tous les utilisateurs 
        // grâce à la classe $userRepository->findAll sur la page index html twig.
        return $this->render('admin_user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    // Route en GET et POST
    // GET parce qu on recupere le chemin de lURL pour afficher la page
    // POST parce quon a un formulaire et on recupere les données en POST
    #[Route('/new', name: 'app_admin_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository): Response
    {
        // on créé une variable entité utilisateur
        // qui servira pour le formulaire
        // il sera utilisé pour injecté les données en B.D.
        // on pourra l'utiliser pour la validation
        $user = new User();
        // creation du form intégrant l'entité User
        $form = $this->createForm(UserType::class, $user);
        // on lit l'objet request pour travailler sur le cas
        // d'envoi du formulaire
        $form->handleRequest($request);

        // si le form est envoyé
        // et que les données sont validé
        if ($form->isSubmitted() && $form->isValid()) {
            // on utilise le repository pour sauvegarder les données
            // en Base de donnée
            $userRepository->save($user, true);

            // redirection vers la page sur toutes les users
            return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
        }

        // le cas ou on a une page du formulaire non validé.
        return $this->renderForm('admin_user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    // la route qui succede à celle de la classe général : Admin/user/1
    // qui va etre interprété par l'entité directement ici le user
    // c'est le param converter qui réalise cette opération
    #[Route('/{id}', name: 'app_admin_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        // on renvoie la twig avec le user correspondant
        return $this->render('admin_user/show.html.twig', [
            'user' => $user,
        ]);
    }
    // la route qui succede à celle de la classe général : Admin/user/1
    // qui va etre interprété par l'entité directement ici le user
    // c'est le param converter qui réalise cette opération
    // en rajoutant edit
    #[Route('/{id}/edit', name: 'app_admin_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        // creation du form intégrant l'entité User
        $form = $this->createForm(UserType::class, $user);
        // on lit l'objet request pour travailler sur le cas
        // d'envoi du formulaire
        $form->handleRequest($request);

        // cas ou le formulaire est posté et validé !
        if ($form->isSubmitted() && $form->isValid()) {
            // on sauvegarde via la métode du repository
            $userRepository->save($user, true);

            // on redirige vers la page des users
            return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
        }

        // cas ou on pas encore posté on affiche le formulaire
        return $this->renderForm('admin_user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    // la route qui succede à celle de la classe général : Admin/user/1
    // qui va etre interprété par l'entité directement ici le user
    // c'est le param converter qui réalise cette opération
    #[Route('/{id}', name: 'app_admin_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        // on verifie si le token de securité est bien présent
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            // on utilise les metode du repository pour supprimer l'enregistrement en question
            $userRepository->remove($user, true);
        }
        // on redirige vers la page des users
        return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
    }
}