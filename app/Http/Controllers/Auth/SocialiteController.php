<?php

namespace App\Http\Controllers\Auth;

use Inertia\Inertia;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{

	// redirect
	public function redirect($provider)
	{
		return Inertia::location(
			Socialite::driver($provider)
				// ->scopes(['read:user', 'public_repo'])
				->redirect(),
		);
	}

	// callback
	public function callback($provider)
	{
		if (!$provider) {
			abort(404,'Callback provider not found');
		}

		$githubUser = Socialite::driver($provider)->user();

		$user = User::query()->updateOrCreate(['email' => $githubUser->getEmail()], [
			'name'              => $githubUser->getName(),
			'email'             => $githubUser->getEmail(),
			'socialite'         => $provider,
			'password'          => Hash::make(config('services.socialite.salt')),
			'remember_token'    => str()->random(60),
		]);

		Auth::login($user);

		return redirect('/dashboard');
	}

}
