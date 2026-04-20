<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'failed' => 'Ces identifiants ne correspondent pas à nos enregistrements.',
    'password' => 'Le mot de passe fourni est incorrect.',
    'throttle' => 'Trop de tentatives de connexion. Veuillez réessayer dans :seconds secondes.',

    'login' => [
        'cover_title' => 'Connectez-vous pour organiser, vendre et suivre vos événements.',
        'cover_subtitle' => 'Un seul compte pour créer des événements, consulter les inscriptions, suivre les certificats et exploiter le tableau de bord sans changer de contexte.',
        'benefit_1' => 'Flux d\'achat, certificats et gestion dans le même produit.',
        'benefit_2' => 'Expérience cohérente sur ordinateur et mobile.',
        'benefit_3' => 'Accès rapide à ce que vous devez faire maintenant.',
        'welcome_back' => 'Bon retour',
        'title' => 'Se connecter',
        'description' => 'Accédez à votre compte pour continuer à gérer vos événements, inscriptions et paiements en un seul endroit.',
        'email' => 'E-mail',
        'password' => 'Mot de passe',
        'remember' => 'Se souvenir de moi',
        'forgot_password' => 'Mot de passe oublié ?',
        'not_registered' => 'Pas encore inscrit ?',
        'register_link' => 'S\'inscrire',
    ],

    'register' => [
        'title' => 'S\'inscrire',
        'name' => 'Nom complet',
        'name_placeholder' => 'Nom',
        'email' => 'E-mail',
        'phone' => 'Téléphone',
        'password' => 'Mot de passe',
        'password_confirmation' => 'Confirmer le mot de passe',
        'agree_terms' => 'J\'accepte les :terms et la :privacy',
        'terms' => 'conditions d\'utilisation',
        'privacy' => 'politique de confidentialité',
        'already_registered' => 'Déjà inscrit ?',
        'login_link' => 'Se connecter',
    ],

    'forgot_password_page' => [
        'title' => 'Mot de passe oublié ?',
        'description' => 'Mot de passe oublié ? Aucun problème. Communiquez-nous simplement votre adresse e-mail et nous vous enverrons un lien de réinitialisation qui vous permettra d\'en choisir un nouveau.',
        'email_label' => 'Entrez votre e-mail',
        'submit_button' => 'Envoyer l\'e-mail',
    ],

    'verify_email' => [
        'title' => 'Vérifiez votre e-mail',
        'description' => 'Merci d\'avoir rejoint Ticket DZ6 ! Avant de commencer, pourriez-vous vérifier votre adresse e-mail en cliquant sur le lien que nous venons de vous envoyer ? Si vous n\'avez pas reçu l\'e-mail, nous vous en enverrons un autre avec plaisir.',
        'sent' => 'Un nouveau lien de vérification a été envoyé à l\'adresse e-mail fournie lors de l\'inscription.',
        'resend_button' => 'Renvoyer l\'e-mail',
        'logout_button' => 'Se déconnecter',
    ],

];
