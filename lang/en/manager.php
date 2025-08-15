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
    'tournaments' => 'Tournaments',
    'tournament' => 'Tournament',
    'maps' => 'Maps',
    'server' => 'Server',
    'home' => 'Home',
    'teams' => 'Teams',
    'menu' => 'Menu',
    'name' => 'Name',
    'view' => 'View',

    'create_tournament' => 'Create Tournament',
    'description' => 'Description',
    'cancel_tournament' => 'Cancel Tournament',
    'optional' => 'Optional',
    'generate_bracket_matchplan' => 'Generate Bracket Matchplan',
    'generate_round_robin_matchplan' => 'Generate Round Robin Matchplan',
    'reset_matchplan' => 'Reset Matchplan',
    'start_tournament' => 'Start Tournament',
    'scramble_teams' => 'Scramble Teams',
    'empty_matchplan' => 'Empty Matchplan',
    'edit' => 'Edit',
    'yes' => 'Yes',
    'no' => 'No',
    'add' => 'Add',
    'cancel' => 'Cancel',
    'remove' => 'Remove',
    'remove_all_maps' => 'Remove All Maps',
    'add_active_mappool' => 'Add Active Mappool',
    'active_mappool_added' => 'Active Mappool added successfully.',
    'no_maps_found' => 'No maps found.',
    'no_maps_found_text' => 'No maps found in the database. Please add some maps first.',
    'add_map' => 'Add Map',
    'map_name' => 'Map Name',
    'map_code' => 'Map Code',
    'map_code_placeholder' => 'e.g. de_dust2',
    'map_code_help' => 'The map code is used to identify the map in the game. It should be the same as the map folder name in on the server.',
    'map_thumbnail' => 'Map Thumbnail',
    'map_thumbnail_help' => 'The map thumbnail is used to display the map in the manager. If empty, the mapcode is used to find it in this',
    'delete_map' => 'Delete Map?',
    'delete_map_text' => 'Are you sure you want to delete this map from the available pool? This action cannot be undone.',
    'delete_all_maps' => 'Delete All Maps?',
    'delete_all_maps_text' => 'Are you sure you want to delete all maps from the available pool? This action cannot be undone.',
    'map_deleted' => 'Map deleted successfully.',
    'maps_deleted' => 'All maps deleted successfully.',

    'delete' => 'Delete',

    'details' => 'Details',
    'registration_deadline' => 'Registration Deadline',
    'registration_deadline_help' => 'If not set, the registration will end with the tournament start.',
    'start_date' => 'Start Date',
    'end_date' => 'End Date',
    'tournament_not_finished' => 'Tournament not finished',
    'max_teams' => 'Max Teams',
    'team_size' => 'Team Size',
    'gametype' => 'Gametype',
    'final' => 'Final',
    'status' => 'Status',

    'add_server' => 'Add Server',

    'ip_address' => 'IP-Address',
    'hostname' => 'Hostname',
    'port' => 'Port',
    'state' => 'State',
    'server_name' => 'Server Name',
    'player' => 'Player',
    'max_player' => 'Max Player',
    'actions' => 'Actions',

    'online' => 'Online',
    'offline' => 'Offline',
    'reset' => 'Reset',
    'server_reset' => 'Server reset successfully.',
    'server_added' => 'Server added successfully.',
    'server_deleted' => 'Server deleted successfully.',
    'server_not_found' => 'Server not found.',

    'no_servers_found' => 'No servers found.',

    'maps' => 'Maps',
    'tournament_plan' => 'Tournament Plan',
    'no_teams_registered' => 'No teams registered for this tournament.',

    'tournament_messages' => [
        'not_found' => 'Tournament not found.',
        'already_started' => 'Tournament has already started or is finished.',
        'invalid_state' => 'Invalid tournament state.',

        'not_enough_teams' => 'Not enough teams for the tournament.',
        'no_maps_selected' => 'No maps selected for the tournament.',
        'no_maps_selected_text' => 'Please select at least one map for the tournament.',

        'not_full' => 'Tournament is not full.',
        'not_full_text' => 'The tournament is not full, are you sure you want to start it?',

        'tournament_started' => 'Tournament started successfully.',

        'cancel_tournament' => 'Cancel Tournament?',
        'cancel_tournament_text' => 'Are you sure you want to cancel this tournament? This action cannot be undone.',
        'tournament_cancelled' => 'Tournament cancelled successfully.',

        'matchplan_generated' => 'Matchplan generated successfully.',
        'matchplan_not_generated' => 'There is no Matchplan yet, please generate one.',

        'teams_already_assigned' => 'Teams are already assigned to matches.',
        'teams_already_assigned_text' => 'Teams are already assigned to matches, do you want to reassign them?',
        'teams_assigned' => 'Teams assigned successfully.',

        'remove_all_teams' => 'Remove all teams from the matchplan?',
        'remove_all_teams_text' => 'This will remove all teams from the matchplan and reset the matchplan. Are you sure you want to continue?',

        'teams_removed' => 'All teams removed from the matchplan successfully.',

        'reset_matchplan' => 'Reset Matchplan?',
        'reset_matchplan_text' => 'Are you sure you want to reset the matchplan? This action cannot be undone.',
        'matchplan_reset' => 'Matchplan reset successfully.',

        'no_api_key' => 'No API token set.',

        'no_free_server' => 'No free server available.',

        'match_not_running' => 'Match is not running.',
        'match_paused' => 'Match is paused.',

        'added_map' => 'Map added successfully.',
        'removed_map' => 'Map removed successfully.',
    ],

    'status_types' => [
        'scheduled' => 'Scheduled',
        'ongoing' => 'Ongoing',
        'completed' => 'Finished',
        'cancelled' => 'Cancelled',
        'unknown' => 'Unknown'
    ],

    'round_names' => [
        'round_1' => 'Round 1',
        'round_2' => 'Round 2',
        'round_3' => 'Round 3',
        'round_4' => 'Round 4',
        'round_5' => 'Round 5',
        'round_6' => 'Round 6',
        'round_7' => 'Round 7',
        'best_of_64' => 'Best of 64',
        'best_of_32' => 'Best of 32',
        'best_of_16' => 'Best of 16',
        'quarter_final' => 'Quarter Final',
        'semi_final' => 'Semi Final',
        'final' => 'Final'
    ],

    'rounds_per_map' => 'Rounds per Map',
    'rounds_per_map_help' => 'The default for CS2 is 24 rounds.',
    'overtime_rounds' => 'Overtime Rounds',
    'overtime_rounds_help' => 'The default for CS2 is 6 rounds.',
    'overtime' => 'Overtime',

    'show' => 'Show',

    'register_team' => 'Register Team',
    'team_name' => 'Team Name',
    'team_tag' => 'Team Tag',
    'steam_ids' => 'Steam IDs',
    'steam_ids_help' => 'Please enter the Steam64 IDs of your team members. These are required to identify the players.',
    'steam_id_player' => 'Steam ID player :number',
    'steam_id_required' => 'Each Steam ID is required.',
    'steam_id_distinct' => 'Each Steam ID must be unique.',
    'steam_id_regex' => 'A Steam ID must contain 17 digits.',

    'matches' => 'Matches',
    'match' => 'Match',
    'upcoming_matches' => 'Upcoming Matches',
    'running_matches' => 'Running Matches',
    'finished_matches' => 'Finished Matches',

    'steam' => [
        'unlink_confirmation_title' => 'Unlink Steam Account?',
        'unlink_confirmation_text' => 'Are you sure you want to unlink your Steam account? This action cannot be undone.',
        'unlink' => 'Unlink'
    ],

    'matchhistory_title' => 'Match History',

    'stats' => 'Stats',
    'captain' => 'Captain',
    'players' => 'Players',
    'player' => 'Player',
    'team' => 'Team',
    'create_team' => 'Create Team',
    'edit_team' => 'Edit Team',
    'delete_team' => 'Delete Team',
    'delete_team_confirmation' => 'Delete Team?',
    'delete_team_confirmation_text' => 'Are you sure you want to delete this team? This action cannot be undone.',
    'teamname' => 'Team Name',
    'teamtag' => 'Team Tag',
    'teamlogo' => 'Team Logo',
    'team_created' => 'Team created successfully.',
    'team_updated' => 'Team updated successfully.',
    'team_deleted' => 'Team deleted successfully.',
    'team_not_found' => 'Team not found.',
    'no_teams_found' => 'No teams found.',
    'no_available_teams' => 'No available teams found.',



    'notifications' => 'Notifications',
    'invites' => 'Invites',

    'accept_invite' => 'Accept',
    'decline_invite' => 'Decline',

    'invite_player' => 'Invite Player',
    'pending_invites' => 'Pending Invites',

    'invite_player_accepted' => 'Invite accepted successfully.',
    'invite_player_declined' => 'Invite declined successfully.',

    'invite_player_confirm_text' => 'Are you sure you want to invite :player ?',
    'invite_player_success' => ':player invited successfully.',
    'invite_player_error' => 'Failed to invite player.',

    'cancel_invite' => 'Cancel Invite',
    'cancel_invite_confirm_text' => 'Are you sure you want to cancel the invite for :player ?',
    'cancel_invite_success' => ':player invite canceled successfully.',

    'search' => 'Search Players',
    'search_placeholder' => 'Search by name, Steam name, Steam ID or email',

    'search_teams' => 'Search Teams',

    'override_num_maps' => 'Override number of maps',

    'tournament_default' => 'Tournament Default',
    'best_of_1' => 'Best of 1',
    'best_of_3' => 'Best of 3',
    'best_of_5' => 'Best of 5',

    'start_match' => 'Start Match',
    'show_config' => 'Show Config',
    'view_match' => 'View Match',
    'pause_match' => 'Pause Match',
    'resume_match' => 'Resume Match',
    'abort_match' => 'Abort Match',

    'num_maps_overridden' => 'Number of Maps Overridden',

    'close' => 'Close',

    'select_players' => 'Select Players',
    'num_players_exceeded' => 'You can only select up to :max player/s.',

    'team_registered' => 'Team registered successfully.'
];
