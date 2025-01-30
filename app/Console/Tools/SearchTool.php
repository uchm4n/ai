<?php

namespace App\Console\Tools;

use App\Models\Drug;
use EchoLabs\Prism\Tool;

class SearchTool extends Tool
{

	public function __construct()
	{
		$this
			->as('search')
			->for('search for specific drugs and medicine')
			->withStringParameter('query', 'Detailed search query. Best to search one topic at a time.')
			->using($this);
	}

	public function __invoke(string $query)
	{
		$result = Drug::where('name', 'like', "%$query%")
			->orWhere('description', 'like', "%$query%")
			->take(4)
			->map(function ($item) {
				return [
					'title'       => $item->title_ka . '|' . $item->title_en,
					'substance'   => $item->substance,
					'description' => $item->all,
				];
			});

		return view('prompts.search', compact('result'))->render();
	}
}