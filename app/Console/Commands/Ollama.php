<?php

namespace App\Console\Commands;

use App\Console\Tools\Models;
use App\Console\Tools\SearchTool;
use App\Models\Messages;
use App\Models\User;
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

	protected string $model = Models::Phi4->value;

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
		$prism = Prism::text()
			// ->withTools([new SearchTool()])
			// ->withMaxSteps(5)
			->withSystemPrompt("You are an expert doctor named Dr.AI, who can diagnose patients and prescribe medicine")
			->withClientOptions(['timeout' => 120])
			->using(Provider::Ollama, $this->model);

		while (true) {
			$this->chat($prism);
		}
	}

	public function chat(PendingRequest $prism): void
	{
		$txt = textarea("Ask Ollama " . strtoupper($this->model), 'Type your question here', rows: 10);

		$this->previousMessages();
		$this->messages->push(new UserMessage($txt));

		$answer = $prism->withMessages($this->messages->toArray())->generate();
		$this->messages->merge($answer->responseMessages);

		$this->box('Response', wordwrap($answer->text), color: 'magenta');
	}


	/**
	 * @param User $user
	 * @return Collection<UserMessage[]>
	 */
	public function previousMessages(): Collection
	{
		$user = User::query()->where('email', 'ucha19871@gmail.com')->first();
		$this->messages->push(new UserMessage("Name: $user->name | Email: $user->email"));

		// TODO: pass messages to the chat history
		return Messages::query()->where('user_id', $user->id)
			->take(50)
			->get(['response', 'text'])
			->flatMap(function ($item) {
				return [
					[
						'role'    => 'user',
						'content' => str($item->text)->lower()->trim()->value(),
					],
					[
						'role'    => 'assistant',
						'content' => str($item->response)->lower()->trim()->value(),
					],
				];
			});
	}

}
