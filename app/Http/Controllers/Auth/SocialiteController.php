<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
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
			'password'          => Hash::make(env('SOCIALITE_SALT')),
			'remember_token'    => str()->random(60),
			'email_verified_at' => Carbon::now()->timestamp,
		]);

		Auth::login($user);

		return redirect('/dashboard');
	}

}
