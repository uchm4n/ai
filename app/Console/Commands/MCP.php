<?php

namespace App\Console\Commands;

use App\Console\Tools\Models;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Prism\Prism\Enums\Provider;
use Prism\Prism\Prism;
use Prism\Prism\Text\PendingRequest;
use Prism\Relay\Exceptions\RelayException;
use Prism\Relay\Exceptions\ServerConfigurationException;
use Prism\Relay\Exceptions\ToolCallException;
use Prism\Relay\Exceptions\ToolDefinitionException;
use Prism\Relay\Exceptions\TransportException;
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
		$txt = textarea('Prompt');
		// $txt = "use tool add_numbers. what is addition of two numbers 5 and 2, return the result.";
		// $txt = "Add two numbers together, 5 and 2, return the result.";
		// $txt = "Add two numbers together, 5 and 2, return the result.";
		// $txt = "Give me an Application configuration settings";
		$response = $this->agent($txt)->asStream();

		foreach ($response as $chunk) {
			// Write each chunk directly to output without buffering
			info($chunk->text);
		}
	}


	protected function agent(string $prompt): PendingRequest
	{
		$tools = [];
		try {
			$tools = [
				...Relay::tools('mcp'),
				...Relay::tools('github'),
				...Relay::tools('puppeteer'),
			];
			// Use the tools...
		} catch (ServerConfigurationException $e) {
			// Handle configuration errors (missing server, invalid settings)
			Log::error('MCP Server configuration error: ' . $e->getMessage());
		} catch (ToolDefinitionException $e) {
			// Handle issues with tool definitions from the MCP server
			Log::error('MCP Tool definition error: ' . $e->getMessage());
		} catch (TransportException $e) {
			// Handle communication errors with the MCP server
			Log::error('MCP Transport error: ' . $e->getMessage());
		} catch (ToolCallException $e) {
			// Handle errors when calling a specific tool
			Log::error('MCP Tool call error: ' . $e->getMessage());
		} catch (RelayException $e) {
			// Handle any other MCP-related errors
			Log::error('Relay general error: ' . $e->getMessage());
		}

		return Prism::text()
			->using(Provider::Gemini, Models::Gemini2_0->value)
			->withSystemPrompt($this->systemMessage)
			->withPrompt($prompt)
			->withTools($tools)
			->usingTopP(1)
			->withMaxSteps(50)
			->withMaxTokens(1000);
	}
}
