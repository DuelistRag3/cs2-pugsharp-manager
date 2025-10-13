<h1 align="center" id="title">CS2 Pugsharp Manager</h1>

![Version - Beta 1.3.0](https://img.shields.io/badge/Version-Beta_1.3.0-2ea44f?style=for-the-badge)
![License - CC BY-NC 4.0](https://img.shields.io/badge/License-CC_BY--NC_4.0-42e6f5?style=for-the-badge)

## About CS2 Pugsharp Manager

The CS2 Pugsharp Manager is a webbased tool using laravel as base to organize and manage cs2 tournaments. It requires your own server(s) with the wonderful <a href="https://github.com/Lan2Play/PugSharp" target="_blank">Lan2Play Pugsharp</a> plugin installed. Manuel management is planed, but not yet implamented. It acts as an alternative to the <a href="https://github.com/Lan2Play/eventula-manager">Lan2Play Eventula-Manager</a> and is more lightweight approach and is designed just for the tournament management, not for event management, for this please refer to the eventula manager.

## Table of Contents
- [Features](#features)
- [Planed Features](#planed-features)
- [Known bugs](#known-bugs)
- [Server Requirements](#server-requirements)
- [Installation](#Installation)
- [Screenshots](#Screenshots)
- [Support](#support)
- [Contributing](#Contributing)
- [Authors](#Authors)
- [License](#License)

## Features
- Multi-Language support (in development | missing many language strings ^^).
- Tournament management.
  - Start date, End date.
  - Team size.
  - Max teams.
  - Round and Overtime Length.
  - Match type (Best of 1, etc...).
  - Define playable maps.
  - start, cancel and pause matches.
  - Guest mode.
  - and more.
- Register and connect with steam.
- Team management.
  - Create, Delete.
  - Invite and Kick players.
  - Register your team for tournaments.
- Tournament overview for guests.
- Matches.
  - Matchoverview for upcoming and running matches.
- Automatic team advancement.
- Adding servers.

## Planed features
- On site map voting.
- Player stat overview for each map and overall.
- Docker container
- and more.

## Known bugs:
- Missing language strings all over the place in both german and englisch.
- Live validation for steam id´s does not work at all.

## Server Requirements

- PHP >= 8.2
- Ctype PHP Extension
- cURL PHP Extension
- DOM PHP Extension
- Fileinfo PHP Extension
- Filter PHP Extension
- Hash PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PCRE PHP Extension
- PDO PHP Extension
- Session PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension

## Installation
Currently there is no installer (will follow Soon™), so you have to install the application manually

1. Clone this reposetory into your webserver.
   ```bash
   git clone https://github.com/DuelistRag3/cs2-pugsharp-manager.git
   cd cs2-pugsharp-manager
   ```
2. Install PHP requirements.
   ```bash
   composer install
   ```
3. Install Node requirements.
   ```bash
   npm install
   ```
4. Copy your .env file and fill all relevant informations (change the admin credentials and change APP_ENV to production and APP_DEBUG to false).
   ```bash
   cp .env.example .env
   ```
5. Generate application key.
   ```bash
   php artisan key:generate
   ```
6. Generate bearer token.
   ```bash
   php artisan bearer:generate
   ```
7. Execute the migrations and seed (be sure you changed the default credentials in the env).
   ```bash
   php artisan migrate --seed
   ```
8. Compile assets.
   ```bash
   npm run build
   ```

For a detailed guide on how to configure your webserver to support laravel applications check out their <a href="https://laravel.com/docs/12.x/deployment" target="_blank">deployment guide</a>

## Screenshots

<h3>Tournament List</h3>
<img src="https://raw.githubusercontent.com/DuelistRag3/cs2-pugsharp-manager/refs/heads/main/screenshots/tournament_list.png">

<h3>Tournament View</h3>
<img src="https://raw.githubusercontent.com/DuelistRag3/cs2-pugsharp-manager/refs/heads/main/screenshots/tournament_view.png">

<h3>Bracket View</h3>
<img src="https://raw.githubusercontent.com/DuelistRag3/cs2-pugsharp-manager/refs/heads/main/screenshots/bracket_view.png">

## Support
To receive proper support, either open a GitHub issue, check out the channel on the [Lan2Play Discord](https://discord.gg/aEGq33zcZK) or my [Dev Discord](https://discord.gg/UkWEXBDFM8)

## Contributing

If you want to contribute to this project, just fork it and make a pull request.

## Authors

- [@DuelistRag3](https://github.com/DuelistRag3)

## License

This work is licensed under a
[Creative Commons Attribution-NonCommercial 4.0 International License][cc-by-nc].

[![CC BY-NC 4.0][cc-by-nc-image]][cc-by-nc]

[cc-by-nc]: https://creativecommons.org/licenses/by-nc/4.0/
[cc-by-nc-image]: https://licensebuttons.net/l/by-nc/4.0/88x31.png
[cc-by-nc-shield]: https://img.shields.io/badge/License-CC%20BY--NC%204.0-lightgrey.svg
