<?php

namespace App\Console\Tools;

enum Models: string
{
	case Qwen = "hf.co/bartowski/Qwen2.5-7B-Instruct-1M-GGUF:Q8_0";
	case Phi4 = "phi4";
	case Llama = "llama3.2";
	case Deepseek = "deepseek-r1:32b";
	case Mistral = "mistral:latest";
	case Mxbai = "mxbai-embed-large";
	case Nomic = "nomic-embed-text";
	case Gemini2_0 = "gemini-2.0-flash";
	case Gemini2_5 = "";

}
