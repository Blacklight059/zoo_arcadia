<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AccessTest extends WebTestCase
{
    public function testHomepageAccess(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();

    }

    public function testAdminRouteRedirectsForUnauthenticatedUser(): void
    {
        $client = static::createClient();
        $client->request('GET', '/admin/dashboard');
        $this->assertResponseStatusCodeSame(302); // Redirection
        $this->assertResponseRedirects('/'); // Vérifie la redirection vers /login
    }

    public function testAdminRouteAccessForAdminUser(): void
    {
        $client = static::createClient();

        // Simule un utilisateur avec le rôle ROLE_ADMIN
        $user = self::getContainer()->get('doctrine')->getRepository(User::class)->findOneBy(['email' => 'admin@admin.com']);
        $client->loginUser($user);
    
        // Effectuer la requête
        $client->request('GET', '/admin/dashboard');
        $this->assertResponseIsSuccessful(); // Vérifie que la réponse est HTTP 200
    
    }

    public function testAdminAccessDeniedForVeterinarian(): void
    {
        $client = static::createClient();
    
        // Simuler une connexion vétérinaire
        $client->request('POST', '/login', [
            'email' => 'vet1@example.com',
            'password' => 'test1'
        ]);
        $client->followRedirect();
    
        $client->request('GET', '/admin');
        $this->assertResponseStatusCodeSame(403); // Accès refusé
    }

    public function testLoginForm(): void
    {
        $client = static::createClient();
    
        // Accéder à la page de connexion
        $crawler = $client->request('GET', '/login');
    
        // Vérifiez que le formulaire existe
        $this->assertCount(1, $crawler->filter('form'));
    
        // Soumettre le formulaire
        $form = $crawler->selectButton('Connexion')->form([
            'email' => 'admin@admin.com',
            'password' => 'admin',
        ]);
        $client->submit($form);
    
        $client->followRedirect();
        $this->assertResponseIsSuccessful(); // Vérifie la redirection après connexion
    }
    

    public function testServiceCreationForm(): void
    {
        $client = static::createClient();

        // Simuler la connexion d'un administrateur
        $client->request('POST', '/login', [
            'email' => 'admin@admin.com',
            'password' => 'adminadmin'
        ]);
        $client->followRedirect();

        // Accéder au formulaire de création
        $crawler = $client->request('GET', '/admin/service/create');
        $form = $crawler->selectButton('Créer')->form([
            'service[name]' => 'Nouveau Service',
            'service[description]' => 'Description du service',
        ]);
        $client->submit($form);
        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.success-message', 'Service créé avec succès');
    }

    
}
