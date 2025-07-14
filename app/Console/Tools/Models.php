<?php

namespace App\Console\Tools;

enum Models: string
{
	case Qwen = "hf.co/bartowski/Qwen2.5-7B-Instruct-1M-GGUF:Q8_0";
	case Phi4 = "phi4";
	case Llama = "llama3.2";
	case Deepseek = "deepseek-r1:32b";
	case Mistral = "mistral:latest";
	case Mistral_Small = "mistral-small-latest";
	case Mistral_Large = "mistral-large-latest";
	case Mxbai = "mxbai-embed-large";
	case Nomic = "nomic-embed-text";
	case Gemini2_0 = "gemini-2.0-flash";
	// case Gemini2_5 = "gemini-2.5-pro-preview-03-25";
	case Gemini2_5 = "gemini-2.5-flash";
	case Gemini2_5_tts = "gemini-2.5-flash-preview-tts";
	case XAI_3 = "grok-3";
	case XAI_4 = "grok-4";
	case OpenAI = "gpt-4.1";
	case OpenAI_TTS = "tts-1";
	case OpenAI_Whisper = "whisper-1";

}
