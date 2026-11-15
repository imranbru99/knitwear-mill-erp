<?php

namespace App\Console\Commands;

use App\Models\ProductionOrder;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendTnaDigest extends Command
{
    protected $signature = 'factory:tna-digest';

    protected $description = 'Email a short ship-date risk digest. Safe to run twice in a day.';

    public function handle(): int
    {
        $risks = ProductionOrder::query()->whereIn('risk', ['amber', 'red'])->orderBy('number')->get();
        $lines = $risks->map(fn (ProductionOrder $order) => $order->number.' '.$order->risk.' qty '.$order->quantity)->implode("\n");
        $body = $lines !== '' ? $lines : 'No red or amber jobs today.';
        $recipients = User::query()->where('sees_all_branches', true)->pluck('email');
        foreach ($recipients as $email) {
            Mail::raw($body, fn ($message) => $message->to($email)->subject('TNA risk digest'));
        }
        $this->info('Digest sent to '.$recipients->count().' people.');

        return self::SUCCESS;
    }
}
