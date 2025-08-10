<?php

namespace App\Console\Tools;

use App\Models\Drug;
use Prism\Prism\Exceptions\PrismException;
use Prism\Prism\Tool;

class SearchTool extends Tool
{
    public function __construct()
    {
        $this
            ->as('search')
            ->for('Search for specific drugs and medicine. Search for pharmacy related subjects.')
            ->withStringParameter('query', 'Detailed search query. Best to search one topic at a time.')
            ->using(fn (): static => $this);
    }

    public function search(string $drug): string
    {
        try {
            $drug = strtolower($drug);
            $results = Drug::query()
                ->where('title_en', 'like', "%$drug%")
                ->orWhere('title_ka', 'like', "%$drug%")
                ->orWhere('all', 'like', "%$drug%")
                ->take(2)
                ->get()
                ->toArray();

            return view('prompts.search', ['results' => $results])->render();
        } catch (\Exception $e) {
            // Handle the exception
            throw new PrismException('Tool Exception: '.$e->getMessage());
        }
    }
}
