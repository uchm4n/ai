<?php

namespace App\Console\Tools;

enum Models: string
{
	case Qwen = "hf.co/bartowski/Qwen2.5-7B-Instruct-1M-GGUF:Q8_0";
	case Phi4 = "phi4";
	case Deepseek = "deepseek-r1:32b";

}
