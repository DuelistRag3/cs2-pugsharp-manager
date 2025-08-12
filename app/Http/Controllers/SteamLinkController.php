<?php

namespace App\Http\Controllers;

use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;
use GuzzleHttp\Psr7\Uri;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Ilzrv\LaravelSteamAuth\Exceptions\Authentication\SteamResponseNotValidAuthenticationException;
use Ilzrv\LaravelSteamAuth\Exceptions\Validation\ValidationException;
use Ilzrv\LaravelSteamAuth\SteamAuthenticator;

class SteamLinkController extends Controller
{
    public function __invoke(
        Request $request,
        Redirector $redirector,
        Client $client,
        HttpFactory $httpFactory,
    ): RedirectResponse {
        $steamAuthenticator = new SteamAuthenticator(
            new Uri($request->getUri()),
            $client,
            $httpFactory,
        );

        try {
            $steamAuthenticator->auth();
        } catch (ValidationException|SteamResponseNotValidAuthenticationException) {
            return $redirector->to(
                $steamAuthenticator->buildAuthUrl()
            );
        }

        $steamUser = $steamAuthenticator->getSteamUser();

        $exists = User::where('steam_id', $steamUser->getSteamId())->first();

        if($exists)
        {
            session()->flash('steam_already_linked', true);
            return $redirector->to('/profile');
        }

        $user = auth()->user();

        $user->steam_id = $steamUser->getSteamId();
        $user->steam_name = $steamUser->getPersonaName();
        $user->steam_avatar = $steamUser->getAvatarFull();
        $user->steam_url = $steamUser->getProfileUrl();

        $user->save();

        session()->flash('steam_linked', true);

        return $redirector->to('/profile/' . $user->id);
    }
}
