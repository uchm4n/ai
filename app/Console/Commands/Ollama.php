<?php

namespace App\Console\Commands;

use EchoLabs\Prism\Enums\Provider;
use EchoLabs\Prism\Prism;
use EchoLabs\Prism\Text\PendingRequest;
use EchoLabs\Prism\ValueObjects\Messages\UserMessage;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Laravel\Prompts\Concerns\Colors;
use Laravel\Prompts\Themes\Default\Concerns\DrawsBoxes;

use function Laravel\Prompts\textarea;

class Ollama extends Command
{
	use DrawsBoxes;
	use Colors;

	protected $description = 'Chat with Ollama';

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'app:ollama';


	protected string $model = 'phi4';
	protected Collection $messages;

	public function __construct()
	{
		parent::__construct();
		$this->messages = new Collection();
	}


	/**
	 * Execute the console command.
	 */
	public function handle()
	{
		$prism = Prism::text()->using(Provider::Ollama, $this->model);

		while (true) {
			$this->chat($prism);
		}
	}

	public function chat(PendingRequest $prism): void
	{
		$txt = textarea("Ask Ollama " . strtoupper($this->model), 'Type your question here', rows: 10);

		$this->messages->push(new UserMessage($txt));

		$answer = $prism->withMessages($this->messages->toArray())->generate();
		$this->messages->merge($answer->responseMessages);

		$this->box('Response', wordwrap($answer->text), color: 'magenta');
	}

}
