<?php

namespace App\Support\Integrations;

use ImranDevBd\AiHub\AIHubManager;
use ImranDevBd\AiHub\Testing\AIHubFake;
use Throwable;

class AiClient
{
    public function text(string $prompt, string $system = ''): ?string
    {
        $manager = app(AIHubManager::class);
        if (! $manager instanceof AIHubFake && ! $this->hasProviderKey()) {
            return null;
        }

        try {
            $pending = $manager->prompt($prompt);
            if ($system !== '') {
                $pending = $pending->system($system);
            }

            $response = $pending->generate();

            return $response->success ? $response->content : null;
        } catch (Throwable) {
            return null;
        }
    }

    private function hasProviderKey(): bool
    {
        foreach (config('ai-hub.providers', []) as $name => $provider) {
            if ($name === 'ollama') {
                continue;
            }
            if (filled($provider['api_key'] ?? null)) {
                return true;
            }
        }

        return false;
    }
}
