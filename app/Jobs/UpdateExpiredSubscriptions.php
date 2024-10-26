<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\UserSubscription;
use Carbon\Carbon;

class UpdateExpiredSubscriptions implements ShouldQueue
{
    use Queueable;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * Execute the job.
     */
    public function handle(): void
    {
        echo "El job UpdateExpiredSubscriptions se está ejecutando.\n";
        // Obtén las suscripciones que han expirado
        $expiredSubscriptions = UserSubscription::where('is_active', 1)
        ->where('ends_at', '<', Carbon::now())
            ->get();
        // Cambia el estado de las suscripciones expiradas
        foreach ($expiredSubscriptions as $subscription) {
            $subscription->update([
                'is_active' => 0,
                'canceled_at' => Carbon::now(), // Actualiza la fecha de cancelación
            ]);
        }
    }
}
