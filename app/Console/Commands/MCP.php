<?php

namespace App\Console\Commands;

use App\Console\Tools\Models;
use Illuminate\Console\Command;
use Prism\Prism\Enums\Provider;
use Prism\Prism\Prism;
use Prism\Prism\Text\PendingRequest;
use Prism\Relay\Facades\Relay;
use function Laravel\Prompts\note;
use function Laravel\Prompts\info;
use function Laravel\Prompts\textarea;

class MCP extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'app:mcp';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'A MCP (Model Context Protocol) client tool';
	protected string $systemMessage = "You are an expert doctor named Dr.AI, Who can diagnose patients and prescribe medicine. 
										ALWAYS answer in Georgian language and
										ALWAYS answer in MARKDOWN format and 
										ALWAYS provide a diagnosis or prescription and 
										ALWAYS provide a link to the source of the information.";

	/**
	 * Execute the console command.
	 */
	public function handle()
	{
		$response = $this->agent(textarea('Prompt'))->asStream();

		foreach ($response as $chunk) {
			// Write each chunk directly to output without buffering
			info($chunk->text);
		}


	}


	protected function agent(string $prompt): PendingRequest
	{
		return Prism::text()
			->using(Provider::Gemini, Models::Gemini2_0->value)
			->withSystemPrompt($this->systemMessage)
			->withPrompt($prompt)
			->withTools([
				...Relay::tools('db'),
			])
			->usingTopP(1)
			->withMaxSteps(50)
			->withMaxTokens(8192);
	}
}
