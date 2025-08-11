<?php

namespace Modules\User\Pipes;

use Closure;
use Illuminate\Support\Facades\Log;

class SendWelcomeEmailPipe
{
    public function handle(mixed $payload, Closure $next): mixed
    {
        // For demo purposes, avoid real mailing; just log the intent
        if (is_array($payload) && ! empty($payload['email'])) {
            Log::info('SendWelcomeEmailPipe: would send welcome email to '.$payload['email']);
        }

        return $next($payload);
    }
}
