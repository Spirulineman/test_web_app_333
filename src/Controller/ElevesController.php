<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
/* ************************************************************************** */

use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/* ************************************************************************** */
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Eleve;
use Doctrine\ORM\EntityManagerInterface;

class ElevesController extends AbstractController
{
    /**
     * @Route("/", name="controller_eleves")
     */
    public function index(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Eleve::class);
        $eleves = $repository->findAll();

        return $this->render('controller_eleves/index.html.twig', [
            'controller_name' => 'ElevesController',
            'eleves' => $eleves
        ]);
    }
    /**
     * @Route("eleve/create", name="create_eleve")
     * @Route("eleve/update/{id}", name="update_eleve")
     * 
     */
    public function new(Request $request, EntityManagerInterface $manager, Eleve $eleve = null): Response
    {
        // creates a task object and initializes some data for this example
        if (!$eleve) {
            $eleve = new Eleve();
        }

        // formulaire de création d'un nouvel élève :
        $form = $this->createFormBuilder($eleve)
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('dateNaissance', BirthdayType::class)
            ->add('moyenne', NumberType::class)
            ->add('save', SubmitType::class, ['label' => 'Créer un nouvel élève'])
            ->getForm();

        // requête de récupération des données de création de l'élève :
        $form->handleRequest($request);

        //requête vers la bdd
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($eleve);
            $manager->flush();
            return $this->redirectToRoute('controller_eleves');
        }

        //affiche le formulaire (rendu) 
        return $this->render('controller_eleves/nouvelEleve.html.twig', [
            'eleve' => $eleve,
            'form' => $form->createView()
        ]);
    }
}
