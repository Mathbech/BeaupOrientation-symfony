<?php

use App\Tests\Support\AcceptanceTester;

class LoginTestCest
{
    public function _before(AcceptanceTester $I)
    {
        // Avant chaque test
    }

    public function loginWithValidCredentials(AcceptanceTester $I)
    {
        $I->amOnPage('/login');
        $I->fillField('email', 'utilisateur_test@example.com'); // adapte l'email si besoin
        $I->fillField('password', 'motdepasse_test');
        $I->click('Sign In');
        $I->see('Bienvenue'); // À adapter selon ce qui s'affiche après connexion réussie
    }

    public function loginWithInvalidCredentials(AcceptanceTester $I)
    {
        $I->amOnPage('/login');
        $I->fillField('email', 'utilisateur_test@example.com');
        $I->fillField('password', 'mauvais_motdepasse');
        $I->click('Sign In');
        $I->wait(2); // Attendre un peu pour que le message d'erreur s'affiche
        $I->see('Identifiants invalides'); // À adapter selon le message exact affiché
    }
}
