<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Wsmallnews\Order\Contracts\BuyerInterface;
use Wsmallnews\Order\Traits\Buyerable;
use Wsmallnews\Pay\Contracts\PayerInterface;
use Wsmallnews\Pay\Traits\UserPayerable;
use Wsmallnews\Support\Traits\Morphable;
use Wsmallnews\User\Contracts\UserInterface as SnUserInterface;
use Wsmallnews\User\Traits\Userable;

class User extends Authenticatable implements FilamentUser, SnUserInterface, PayerInterface, BuyerInterface
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use Userable;
    use UserPayerable;
    use Buyerable;
    use Morphable;

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


    public function canAccessPanel(Panel $panel): bool
    {
        return str_ends_with($this->email, '@smallnews.top') && $this->hasVerifiedEmail();
    }
}
