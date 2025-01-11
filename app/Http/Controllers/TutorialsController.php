<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Symfony\Component\Routing\Attribute\Route;

class TutorialsController extends Controller
{
    public function tenzies()
	{
		return Inertia::render('Tutorials/Index');
	}

	public function word()
	{
		return Inertia::render('Tutorials/Word');
	}

	public function wordGenerate()
	{

		// generate 5 character common word
		// recursively generate a domain word until it is 5 characters long exactly
		$word = fake()->domainWord();
		while (strlen($word) !== 5) {
			$word = fake()->domainWord();
		}

		$word = str($word)
			->upper()
			->replaceMatches('/[^[:alpha:] ]/', '')
			->reverse()
			->match('/[A-Z]{5}/')
			->whenEmpty(function () {
				return substr(fake()->domainWord(),0,5);
			});

		return response()->json([
			'word' => $word
		]);
	}


	public function tosty()
	{
		return Inertia::render('Tutorials/Tosty');
	}
}
