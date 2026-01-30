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
    'home' => 'Start',
    'teams' => 'Teams',
    'menu' => 'Menü',
    'name' => 'Name',
    'view' => 'View',
    'settings' => [
        'title' => 'Einstellungen',

        'headings' => [
            'general' => 'Allgemeine Einstellungen',
        ],

        'options' => [
            'page_title' => 'Seitentitel',
            'theme' => 'Theme auswählen',
        ],

        'updated' => 'Die Einstellung :setting wurde aktualisiert'
    ],
    'save' => 'Speichern',

    'create_tournament' => 'Turnier erstellen',
    'update_tournament' => 'Turnier aktualisieren',
    'description' => 'Beschreibung',
    'cancel_tournament' => 'Turnier abbrechen',
    'tournament_updated' => 'Turnier erfolgreich aktualisiert.',
    'optional' => 'Optional',
    'generate_bracket_matchplan' => 'Bracket Matchplan generieren',
    'generate_swiss_matchplan' => 'Swiss Matchplan generieren (16 Teams)',
    'reset_matchplan' => 'Matchplan zurücksetzen',
    'start_tournament' => 'Turnier starten',
    'scramble_teams' => 'Teams mischen',
    'empty_matchplan' => 'Leeren Matchplan',
    'edit' => 'Bearbeiten',
    'yes' => 'Ja',
    'no' => 'Nein',
    'add' => 'Hinzufügen',
    'cancel' => 'Abbrechen',
    'remove' => 'Entfernen',
    'remove_all_maps' => 'Alle Karten entfernen',
    'add_active_mappool' => 'Aktiven Mappool hinzufügen',
    'active_mappool_added' => 'Aktiver Mappool erfolgreich hinzugefügt.',
    'no_maps_found' => 'Keine Karten gefunden.',
    'no_maps_found_text' => 'Keine Karten in der Datenbank gefunden. Bitte füge zuerst einige Karten hinzu.',
    'add_map' => 'Karte hinzufügen',
    'map_name' => 'Kartenname',
    'map_code' => 'Kartencode',
    'map_code_placeholder' => 'z.B. de_dust2',
    'map_code_help' => 'Der Mapcode wird verwendet, um die Karte im Spiel zu laden. Stelle sicher, dass der Mapcode korrekt ist.',
    'map_thumbnail' => 'Map Thumbnail',
    'map_thumbnail_help' => 'Das Map Thumbnail wird verwendet, um die Karte im Manager anzuzeigen. Falls leer, wird der Mapcode verwendet, um sie in diesem <a href="https://github.com/ghostcap-gaming/cs2-map-images/tree/main/cs2" target="_blank" class="text-blue-500 hover:underline">repo</a> zu finden.',
    'delete_map' => 'Karte löschen?',
    'delete_map_text' => 'Bist du sicher, dass du diese Karte aus dem verfügbaren Pool entfernen willst? Diese Aktion kann nicht rückgängig gemacht werden.',
    'delete_all_maps' => 'Alle Karten löschen?',
    'delete_all_maps_text' => 'Bist du sicher, dass du alle Karten aus dem verfügbaren Pool entfernen willst? Diese Aktion kann nicht rückgängig gemacht werden.',
    'map_deleted' => 'Karte erfolgreich gelöscht.',
    'maps_deleted' => 'Alle Karten erfolgreich gelöscht.',
    'no_matches' => 'Keine Spiele gefunden',

    'delete' => 'Löschen',

    'details' => 'Details',
    'registration_deadline' => 'Anmeldeschluss',
    'registration_deadline_help' => 'Wenn nicht gesetzt, endet die Anmeldung mit dem Turnierstart.',
    'start_date' => 'Start Datum',
    'end_date' => 'End Datum',
    'tournament_not_finished' => 'Turnier nicht beendet',
    'max_teams' => 'Max Teams',
    'team_size' => 'Team Größe',
    'gametype' => 'Spieltyp',
    'final' => 'Finale',
    'status' => 'Status',

    'add_server' => 'Server hinzufügen',

    'ip_address' => 'IP-Adresse',
    'hostname' => 'Hostname',
    'port' => 'Port',
    'state' => 'Status',
    'server_name' => 'Server Name',
    'player' => 'Spieler',
    'max_player' => 'Max Spieler',
    'actions' => 'Aktionen',

    'online' => 'Online',
    'offline' => 'Offline',
    'reset' => 'Zurücksetzen',
    'server_reset' => 'Server zurücksetzen erfolgreich.',
    'server_added' => 'Server erfolgreich hinzugefügt.',
    'server_deleted' => 'Server gelöscht.',
    'server_not_found' => 'Server nicht gefunden.',

    'no_servers_found' => 'Keine Server gefunden.',

    'tournament_plan' => 'Turnierplan',
    'no_teams_registered' => 'Keine Teams für dieses Turnier registriert.',
    'add_all_maps' => 'Alle Karten hinzufügen',

    'tournament_messages' => [
        'not_found' => 'Turnier nicht gefunden.',
        'already_started' => 'Turnier bereits gestartet oder beendet.',
        'invalid_state' => 'Ungültiger Turnierstatus.',

        'not_enough_teams' => 'Nicht genug Teams für das Turnier.',
        'no_maps_selected' => 'Keine Karten für das Turnier ausgewählt.',
        'no_maps_selected_text' => 'Bitte wählen Sie mindestens eine Karte für das Turnier aus.',

        'not_full' => 'Turnier ist nicht voll.',
        'not_full_text' => 'Das Turnier ist nicht voll, bist du sicher, dass du es starten willst?',

        'tournament_started' => 'Turnier erfolgreich gestartet.',

        'cancel_tournament' => 'Turnier abbrechen?',
        'cancel_tournament_text' => 'Bist du sicher, dass du dieses Turnier abbrechen willst? Diese Aktion kann nicht rückgängig gemacht werden.',
        'tournament_cancelled' => 'Turnier erfolgreich abgebrochen.',

        'matchplan_generated' => 'Matchplan generiert.',
        'matchplan_not_generated' => 'Es gibt noch keinen Matchplan, bitte generiere einen.',

        'teams_already_assigned' => 'Teams sind bereits den Spielen zugeordnet.',
        'teams_already_assigned_text' => 'Teams sind bereits den Spielen zugeordnet, möchtest du sie neu zuordnen?',
        'teams_assigned' => 'Teams erfolgreich zugeordnet.',

        'remove_all_teams' => 'Alle Teams vom Matchplan entfernen?',
        'remove_all_teams_text' => 'Dies wird alle Teams vom Matchplan entfernen und den Matchplan zurücksetzen. Bist du sicher, dass du fortfahren willst?',

        'teams_removed' => 'Alle Teams erfolgreich vom Matchplan entfernt.',

        'reset_matchplan' => 'Matchplan zurücksetzen?',
        'reset_matchplan_text' => 'Bist du sicher das du den Matchplan zurücksetzen willst? Diese Aktion kann nicht rückgängig gemacht werden.',
        'matchplan_reset' => 'Matchplan erfolgreich zurückgesetzt.',

        'no_api_key' => 'Kein API-Token gesetzt.',

        'no_free_server' => 'Kein freier Server verfügbar.',

        'match_not_running' => 'Match läuft nicht.',
        'match_paused' => 'Match pausiert.',

        'added_map' => 'Map erfolgreich hinzugefügt.',
        'removed_map' => 'Map erfolgreich entfernt.',
        'all_maps_added' => 'Alle Maps erfolgreich hinzugefügt.',
        'match_started_on' => 'Match gestartet auf :server',
    ],

    'status_types' => [
        'scheduled' => 'Geplant',
        'awaiting_start' => 'Warten auf Start',
        'paused' => 'Pausiert',
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
        'best_of_64' => 'Best of 64',
        'best_of_32' => 'Best of 32',
        'best_of_16' => 'Best of 16',
        'quarter_final' => 'Viertel Finale',
        'semi_final' => 'Halb Final',
        'final' => 'Finale'
    ],

    'rounds_per_map' => 'Runden pro Karte',
    'rounds_per_map_help' => 'Standart für CS2 sind 24 Runden.',
    'overtime_rounds' => 'Overtime Runden',
    'overtime_rounds_help' => 'Standart für CS2 sind 6 Runden.',
    'overtime' => 'Overtime',

    'show' => 'Zeigen',

    'register_team' => 'Team anmelden',
    'team_name' => 'Team Name',
    'team_tag' => 'Team Tag',
    'steam_ids' => 'Steam IDs',
    'steam_ids_help' => 'Bitte gib die Steam64 IDs deiner Teammitglieder ein. Diese sind notwendig um die Spieler zu identifizieren.',
    'steam_id_player' => 'Steam ID Spieler :number',
    'steam_id_required' => 'Jede Steam ID ist notwendig.',
    'steam_id_distinct' => 'Jede Steam ID muss einzigartig sein.',
    'steam_id_regex' => 'Eine Steam ID muss 17 Ziffern enthalten.',

    'matches' => 'Matches',
    'match' => 'Match',
    'upcoming_matches' => 'Kommende Matches',
    'running_matches' => 'Laufende Matches',
    'finished_matches' => 'Beendete Matches',

    'steam' => [
        'unlink_confirmation_title' => 'Steam Account entfernen?',
        'unlink_confirmation_text' => 'Bist du sicher dass du deinen Steam Account entfernen willst? Diese Aktion kann nicht rückgängig gemacht werden.',
        'unlink' => 'Entfernen',
    ],

    'matchhistory_title' => 'Match History',

    'stats' => 'Stats',
    'captain' => 'Captain',
    'players' => 'Spieler',
    'player' => 'Spieler',
    'team' => 'Team',
    'create_team' => 'Team erstellen',
    'edit_team' => 'Team bearbeiten',
    'delete_team' => 'Team löschen',
    'delete_team_confirmation' => 'Team löschen?',
    'delete_team_confirmation_text' => 'Bist du sicher dass du dieses Team löschen willst? Diese Aktion kann nicht rückgängig gemacht werden.',
    'teamname' => 'Team Name',
    'teamtag' => 'Team Tag',
    'teamlogo' => 'Team Logo',
    'team_created' => 'Team erfolgreich erstellt.',
    'team_updated' => 'Team erfolgreich aktualisiert.',
    'team_deleted' => 'Team erfolgreich gelöscht.',
    'team_not_found' => 'Team nicht gefunden.',
    'no_teams_found' => 'Keine Teams gefunden.',
    'no_available_teams' => 'Keine verfügbaren Teams gefunden.',



    'notifications' => 'Benachrichtigungen',
    'invites' => 'Einladungen',

    'accept_invite' => 'Akzeptieren',
    'decline_invite' => 'Ablehnen',

    'invite_player' => 'Spieler einladen',
    'pending_invites' => 'Ausstehende Einladungen',

    'invite_player_accepted' => 'Einladung erfolgreich akzeptiert.',
    'invite_player_declined' => 'Einladung erfolgreich abgelehnt.',

    'invite_player_confirm_text' => 'Bist du sicher dass du :player einladen willst?',
    'invite_player_success' => ':player erfolgreich eingeladen.',
    'invite_player_error' => 'Fehler beim Einladen des Spielers.',

    'cancel_invite' => 'Einladung abbrechen',
    'cancel_invite_confirm_text' => 'Bist du sicher dass du die Einladung für :player abbrechen willst?',
    'cancel_invite_success' => ':player Einladung erfolgreich abgebrochen.',

    'search' => 'Suche',
    'search_placeholder' => 'Suche nach Name, Steam Name, Steam ID oder E-Mail',

    'search_teams' => 'Teams suchen',

    'override_num_maps' => 'Anzahl der Karten überschreiben',

    'tournament_default' => 'Tournament Standart',
    'best_of_1' => 'Best of 1',
    'best_of_3' => 'Best of 3',
    'best_of_5' => 'Best of 5',

    'start_match' => 'Match starten',
    'show_config' => 'Zeige Config',
    'view_match' => 'Match ansehen',
    'pause_match' => 'Match pausieren',
    'resume_match' => 'Match fortsetzen',
    'abort_match' => 'Match abbrechen',

    'num_maps_overridden' => 'Anzahl der Karten überschrieben.',

    'close' => 'Schließen',

    'select_players' => 'Spieler auswählen',
    'num_players_exceeded' => 'Du kannst nur bis zu :max Spieler auswählen.',

    'team_registered' => 'Team erfolgreich angemeldet.',
    'cancel_registration' => 'Registrierung abbrechen',
    'cancel_registration_text' => 'Bist du sicher dass du die Registrierung für dieses Team abbrechen willst?',
    'registration_cancelled' => 'Registrierung erfolgreich abgebrochen.',

    'running_tournaments' => 'Laufende Turniere',
    'upcoming_tournaments' => 'Kommende Turniere',
    'finished_tournaments' => 'Beendete Turniere',
    'timeformat' => 't.m.Y H:i',

    'no_maps_yet' => 'Bislang keine Karten ausgewählt.',
    'guest_mode' => 'Gastmodus',
    'guest_mode_help' => 'Aktiviere den Gastmodus um Teams ohne Account für dieses Turnier zuzulassen.',
    'guest_mode_not_editable' => 'Der Gastmodus kann nach dem Erstellen des Turniers nicht mehr geändert werden.',
    'disabled' => 'Deaktiviert',
    'enabled' => 'Aktiviert',
    'tournament_created' => 'Turnier erfolgreich erstellt.',
    'winner' => 'Gewinner',

    'scoreboard' => 'Scoreboard',
    'scoreboard_disabled' => 'Das Scoreboard ist im Gastmodus nicht verfügbar.',
    'clear_server_ask' => 'Bist du sicher dass du diesen Server zurücksetzen willst? Dadurch werden laufende Matches gestoppt.',
    'delete_server_ask' => 'Bist du sicher dass du diesen Server löschen willst? Diese Aktion ist dauerhaft.',

    'no_tournaments' => 'Keine Turniere gefunden.',
    'date' => 'Datum',
    'result' => 'Ergebnis',
    'available_maps' => 'Verfügbare Karten',
    'selected_map_not_found' => 'Die ausgewählte Karte wurde nicht in den verfügbaren Karten gefunden.',
    'server_updated' => 'Server erfolgreich aktualisiert.',

    'occupation' => 'Besetzt?',
    'free' => 'Frei',
    'used_by' => 'Verwendet durch',

    'match_awaiting_start' => 'Spiel status ist Wartend auf Start. Das Spiel startet automatisch, sobald der Server bereit ist.',

    'api.logging' => 'API Protokollierung',
];
