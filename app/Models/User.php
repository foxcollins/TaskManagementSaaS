<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;

    const ROLE_USER = 'user';
    const ROLE_ADMIN = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(UserSubscription::class);
    }



    ///metods
    /**
     * Obtiene el plan de suscripción actual del usuario junto con las limitaciones.
     *
     * @return array|null
     */
    public function currentSubscription()
    {
        // Obtén la suscripción actual del usuario
        $subscription = $this->subscriptions()->latest()->first(); // Asumiendo que tienes una relación definida

        if (!$subscription) {
            return null; // Si no hay suscripción, devuelve null
        }
        
        // Obtén los detalles del plan
        $subscriptionPlan = SubscriptionPlan::find($subscription->plan_id);
        // Retorna un array con el nombre del plan y las limitaciones
        $isExpired = Carbon::now()->greaterThan($subscription->ends_at);
        return [
            'plan_id' => $subscription->plan_id,
            'plan_name' => $subscriptionPlan->name,
            'description' => $subscriptionPlan->description,
            'price' => $subscriptionPlan->price,
            'duration' => $subscriptionPlan->duration,
            'currency' => $subscriptionPlan->currency,
            'is_active' => $subscriptionPlan->is_active,
            'task_limit' => $subscriptionPlan->task_limit,
            'starts_at'  => $subscription->starts_at,
            'ends_at' => $subscription->ends_at,
            'is_expired' => $isExpired
        ];
    }
}
