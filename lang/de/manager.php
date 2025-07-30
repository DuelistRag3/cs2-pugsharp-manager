<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Manager Language Lines
    |--------------------------------------------------------------------------
    |
    | This file contains all language strings used throughout the whole manager.
    | You can change them to anything you want to customize your views to better match your application.
    | I sweeped through the whole codebase to find all strings used in the manager.
    | If you find any missing strings, please open an issue on GitHub.
    | THIS IS NOT SORTED AT ALL, sorry for that.
    |
    */

    'dashboard' => 'Dashboard',
    'tournaments' => 'Turniere',
    'tournament' => 'Turnier',
    'maps' => 'Karten',
    'server' => 'Server',
    'home' => 'Startseite',
    'teams' => 'Teams',
    'menu' => 'Menü',
    'name' => 'Name',
    'view' => 'Ansehen',

    'create_tournament' => 'Turnier erstellen',
    'description' => 'Beschreibung',
    'cancel_tournament' => 'Turnier abbrechen',
    'optional' => 'Optional',
    'generate_bracket_matchplan' => 'K.O.-Spielplan erstellen',
    'generate_round_robin_matchplan' => 'Jeder-gegen-jeden-Spielplan erstellen',
    'reset_matchplan' => 'Spielplan zurücksetzen',
    'start_tournament' => 'Turnier starten',
    'scramble_teams' => 'Teams mischen',
    'empty_matchplan' => 'Spielplan leeren',
    'edit' => 'Bearbeiten',
    'yes' => 'Ja',
    'no' => 'Nein',
    'add' => 'Hinzufügen',
    'cancel' => 'Abbrechen',
    'remove' => 'Entfernen',
    'remove_all_maps' => 'Alle Karten entfernen',
    'add_active_mappool' => 'Aktiven Kartenpool hinzufügen',
    'active_mappool_added' => 'Aktiver Kartenpool erfolgreich hinzugefügt.',
    'no_maps_found' => 'Keine Karten gefunden.',
    'no_maps_found_text' => 'Es wurden keine Karten in der Datenbank gefunden. Bitte füge zuerst Karten hinzu.',
    'add_map' => 'Karte hinzufügen',
    'map_name' => 'Kartenname',
    'map_code' => 'Karten-Code',
    'map_code_placeholder' => 'z.B. de_dust2',
    'map_code_help' => 'Der Karten-Code wird verwendet, um die Karte im Spiel zu identifizieren. Er sollte mit dem Kartenordnernamen auf dem Server übereinstimmen.',
    'map_thumbnail' => 'Karten-Vorschaubild',
    'map_thumbnail_help' => 'Das Vorschaubild wird im Manager angezeigt. Wenn leer, wird der Karten-Code verwendet, um es zu finden.',
    'delete_map' => 'Karte löschen?',
    'delete_map_text' => 'Bist du sicher, dass du diese Karte aus dem Pool entfernen möchtest? Diese Aktion kann nicht rückgängig gemacht werden.',
    'delete_all_maps' => 'Alle Karten löschen?',
    'delete_all_maps_text' => 'Bist du sicher, dass du alle Karten aus dem Pool entfernen möchtest? Diese Aktion kann nicht rückgängig gemacht werden.',
    'map_deleted' => 'Karte erfolgreich gelöscht.',
    'maps_deleted' => 'Alle Karten erfolgreich gelöscht.',

    'delete' => 'Löschen',

    'details' => 'Details',
    'registration_deadline' => 'Anmeldeschluss',
    'registration_deadline_help' => 'Wenn nicht festgelegt, endet die Anmeldung mit dem Turnierstart.',
    'start_date' => 'Startdatum',
    'end_date' => 'Enddatum',
    'tournament_not_finished' => 'Turnier nicht beendet',
    'max_teams' => 'Max. Teams',
    'team_size' => 'Teamgröße',
    'gametype' => 'Spieltyp',
    'final' => 'Finale',
    'status' => 'Status',

    'add_server' => 'Server hinzufügen',

    'ip_address' => 'IP-Adresse',
    'hostname' => 'Hostname',
    'port' => 'Port',
    'state' => 'Status',
    'server_name' => 'Servername',
    'player' => 'Spieler',
    'max_player' => 'Max. Spieler',
    'actions' => 'Aktionen',

    'online' => 'Online',
    'offline' => 'Offline',
    'reset' => 'Zurücksetzen',
    'server_reset' => 'Server erfolgreich zurückgesetzt.',
    'server_added' => 'Server erfolgreich hinzugefügt.',
    'server_deleted' => 'Server erfolgreich gelöscht.',
    'server_not_found' => 'Server nicht gefunden.',

    'no_servers_found' => 'Keine Server gefunden.',

    'maps' => 'Karten',
    'tournament_plan' => 'Turnierplan',
    'no_teams_registered' => 'Keine Teams für dieses Turnier registriert.',

    'tournament_messages' => [
        'not_found' => 'Turnier nicht gefunden.',
        'already_started' => 'Das Turnier hat bereits begonnen oder ist beendet.',
        'invalid_state' => 'Ungültiger Turnierstatus.',

        'not_enough_teams' => 'Nicht genug Teams für das Turnier.',
        'no_maps_selected' => 'Keine Karten für das Turnier ausgewählt.',
        'no_maps_selected_text' => 'Bitte wähle mindestens eine Karte für das Turnier aus.',

        'not_full' => 'Das Turnier ist nicht voll.',
        'not_full_text' => 'Das Turnier ist nicht voll, bist du sicher, dass du es starten möchtest?',

        'tournament_started' => 'Turnier erfolgreich gestartet.',

        'cancel_tournament' => 'Turnier abbrechen?',
        'cancel_tournament_text' => 'Bist du sicher, dass du dieses Turnier abbrechen möchtest? Diese Aktion kann nicht rückgängig gemacht werden.',
        'tournament_cancelled' => 'Turnier erfolgreich abgebrochen.',

        'matchplan_generated' => 'Spielplan erfolgreich erstellt.',
        'matchplan_not_generated' => 'Es gibt noch keinen Spielplan, bitte erstelle einen.',

        'teams_already_assigned' => 'Teams sind bereits den Spielen zugewiesen.',
        'teams_already_assigned_text' => 'Teams sind bereits den Spielen zugewiesen, möchtest du sie neu zuweisen?',
        'teams_assigned' => 'Teams erfolgreich zugewiesen.',

        'remove_all_teams' => 'Alle Teams aus dem Spielplan entfernen?',
        'remove_all_teams_text' => 'Dadurch werden alle Teams aus dem Spielplan entfernt und der Spielplan wird zurückgesetzt. Bist du sicher, dass du fortfahren möchtest?',

        'teams_removed' => 'Alle Teams erfolgreich aus dem Spielplan entfernt.',

        'reset_matchplan' => 'Spielplan zurücksetzen?',
        'reset_matchplan_text' => 'Bist du sicher, dass du den Spielplan zurücksetzen möchtest? Diese Aktion kann nicht rückgängig gemacht werden.',
        'matchplan_reset' => 'Spielplan erfolgreich zurückgesetzt.',

        'no_api_key' => 'Kein API-Token gesetzt.',

        'no_free_server' => 'Kein freier Server verfügbar.',

        'match_not_running' => 'Das Spiel läuft nicht.',
        'match_paused' => 'Das Spiel ist pausiert.',

        'added_map' => 'Karte erfolgreich hinzugefügt.',
        'removed_map' => 'Karte erfolgreich entfernt.',
    ],

    'status_types' => [
        'scheduled' => 'Geplant',
        'ongoing' => 'Laufend',
        'completed' => 'Beendet',
        'cancelled' => 'Abgebrochen',
        'unknown' => 'Unbekannt'
    ],

    'round_names' => [
        'round_1' => 'Runde 1',
        'round_2' => 'Runde 2',
        'round_3' => 'Runde 3',
        'round_4' => 'Runde 4',
        'round_5' => 'Runde 5',
        'round_6' => 'Runde 6',
        'round_7' => 'Runde 7',
        'best_of_64' => 'Zweiunddreißigstelfinale',
        'best_of_32' => 'Sechzehntelfinale',
        'best_of_16' => 'Achtelfinale',
        'quarter_final' => 'Viertelfinale',
        'semi_final' => 'Halbfinale',
        'final' => 'Finale'
    ],

    'rounds_per_map' => 'Runden pro Karte',
    'rounds_per_map_help' => 'Der Standard für CS2 sind 24 Runden.',
    'overtime_rounds' => 'Overtime-Runden',
    'overtime_rounds_help' => 'Der Standard für CS2 sind 6 Runden.',
    'overtime' => 'Overtime',

    'show' => 'Anzeigen',
];
