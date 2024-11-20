<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class AcceuilController extends AbstractController
{
    #[Route('/', name: 'app_acceuil')]
    public function index(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        // $user = $this->createUser($entityManager, $passwordHasher);
    
        return $this->render('Index/index.html.twig', [
            'controller_name' => 'AcceuilController',
            // 'message' => 'Nouvel utilisateur créé avec l\'ID : ' . $user->getId(),
        ]);
    }

    private function createUser(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Utilisateur 
{
    $user = new Utilisateur();
    $user->setMatricule('12346');
    $user->setNom('Doe');
    $user->setRoles(['ROLE_USER']);

    // Hachage du mot de passe
    $hashedPassword = $passwordHasher->hashPassword(
        $user,
        'motdepasse123'
    );
    $user->setPassword($hashedPassword);

    // Persistance de l'utilisateur dans la base de données
    $entityManager->persist($user);
    $entityManager->flush();

    return $user;
}

}