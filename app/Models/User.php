<?php

namespace App\Models;

use App\Enums\Common\Status;
use App\Notifications\ResetPassword;
use App\Traits\DefaultAccessors;
use App\Traits\ScopePerson;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, ScopePerson, DefaultAccessors;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'status' => Status::class,
    ];

    /**
     * Get the people that owns the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function people(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'person_id');
    }

    /**
     * The stores that belong to the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function stores(): BelongsToMany
    {
        return $this->belongsToMany(Store::class);
    }

    /**
     * Get the cashier associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function cashier(): HasOne
    {
        return $this->hasOne(Cashier::class);
    }

    /**
     * The rules that belong to the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function rules(): BelongsToMany
    {
        return $this->belongsToMany(Rule::class);
    }

    public function hasRule(string $name)
    {
        return DB::table('rules')
            ->join('role_rule', 'rules.id', '=', 'role_rule.rule_id')
            ->join('model_has_roles', 'model_has_roles.role_id', '=', 'role_rule.role_id')
            ->where('rules.name', $name)
            ->where('model_has_roles.model_id', $this->id)
            ->exists();
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }
}
