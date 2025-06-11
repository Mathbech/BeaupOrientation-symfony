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
        $I->fillField('email', 'prof1@ecole.com'); // adapte l'email si besoin
        $I->fillField('password', 'Test123#');
        $I->click('Sign In');
        $I->wait(2); // Attendre un peu pour que la redirection se fasse
        $I->see('Courses'); // À adapter selon ce qui s'affiche après connexion réussie
    }

    public function loginWithInvalidCredentials(AcceptanceTester $I)
    {
        $I->amOnPage('/login');
        $I->fillField('email', 'prof1@ecole.com');
        $I->fillField('password', 'mauvais_motdepasse');
        $I->click('Sign In');
        $I->wait(2); // Attendre un peu pour que le message d'erreur s'affiche
        $I->see('Identifiants invalides'); // À adapter selon le message exact affiché
    }
}
