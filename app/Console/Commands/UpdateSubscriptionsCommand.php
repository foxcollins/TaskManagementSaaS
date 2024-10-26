<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\UpdateExpiredSubscriptions;
use App\Models\UserSubscription;
use Carbon\Carbon;

class UpdateSubscriptionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:update-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualizar suscripciones expiradas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando la actualización de suscripciones expiradas...');

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

        $this->info('El job UpdateExpiredSubscriptions ha sido despachado.');
    }
}
