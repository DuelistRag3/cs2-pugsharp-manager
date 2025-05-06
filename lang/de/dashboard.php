<?php

return [

    'title' => 'Dashboard',

    'menu' => [
        'home' => 'Startseite',
        'settings' => 'Einstellungen',
        'profile' => 'Profil',
        'logout' => 'Abmelden',
    ],

    'headings' => [
        'platform' => 'Plattform',
    ],

    'settings' => [
        'description' => 'Verwalte hier Deine Einstellungen.',

        'save' => 'Speichern',

        'app' => [
            'title' => 'App-Einstellungen',
            'description' => 'Verwalte Deine App-Einstellungen.',
            'name' => 'App-Name',
            'language' => 'Sprache',
            'language_description' => 'Wähle die Hauptsprache für die App (Benutzer können immer noch ihre eigene Sprache wählen).',
            'languages' => [
                'en' => 'Englisch',
                'de' => 'Deutsch',
            ],
            'saved' => 'Einstellungen erfolgreich gespeichert.',
        ],

        'profile' => [
            'title' => 'Profil',
            'description' => 'Aktualisiere Deinen Namen und Deine E-Mail-Adresse.',
            'username' => 'Benutzername',
            'email' => 'E-Mail',
            'email_unverified' => 'Deine E-Mail-Adresse ist nicht verifiziert.',
            'email_verifie_link' => 'Klicke hier, um die Verifizierungs-E-Mail erneut zu senden.',
            'email_verifie_link_sent' => 'Ein neuer Verifizierungslink wurde an Deine E-Mail-Adresse gesendet.',
            'saved' => 'Profil erfolgreich aktualisiert.',
            'delete' => 'Konto löschen',
            'delete_description' => 'Lösche Dein Konto dauerhaft und alle zugehörigen Ressourcen.',
            'delete_button' => 'Konto löschen',
            'delete_confirmation' => 'Bist Du sicher, dass Du Dein Konto löschen möchtest?',
            'delete_confirmation_description' => 'Sobald Dein Konto gelöscht ist, werden alle zugehörigen Ressourcen und Daten dauerhaft gelöscht. Bitte gib Dein Passwort ein, um zu bestätigen, dass Du Dein Konto dauerhaft löschen möchtest.',
            'delete_password' => 'Passwort',
            'delete_cancel' => 'Abbrechen',
        ],

        'password' => [
            'title' => 'Passwort ändern',
            'description' => 'Stelle sicher, dass Dein Konto ein langes, zufälliges Passwort verwendet, um sicher zu bleiben.',
            'current_password' => 'Aktuelles Passwort',
            'new_password' => 'Neues Passwort',
            'confirm_password' => 'Neues Passwort bestätigen',
            'saved' => 'Passwort erfolgreich geändert.',
        ],

        'appearance' => [
            'title' => 'Erscheinungsbild',
            'description' => 'Passe das Aussehen und die Bedienung Deiner App an.',
            'light' => 'Hell',
            'dark' => 'Dunkel',
            'system' => 'System',
            'theme' => 'Thema',
            'language' => 'Sprache',
            'saved' => 'Einstellungen zum Erscheinungsbild erfolgreich gespeichert.',
        ],
    ],

];