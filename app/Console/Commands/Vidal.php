<?php

namespace App\Console\Commands;

use App\Models\Drug;
use App\Models\Embedding;
use App\Models\Substance;
use App\Models\User;
use EchoLabs\Prism\Enums\Provider;
use EchoLabs\Prism\Prism;
use Illuminate\Console\Command;

use Illuminate\Support\Facades\Storage;

use function Laravel\Prompts\textarea;

class Vidal extends Command
{

	protected $description = 'Vidal scraping';

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'apps:vidal';

	/**
	 * Execute the console command.
	 */
	public function handle()
	{
		// TODO
	}

}
