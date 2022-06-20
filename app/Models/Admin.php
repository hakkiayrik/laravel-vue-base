<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Admin extends Authenticatable implements JWTSubject
{
	use Notifiable, HasRoles, SoftDeletes;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'first_name', 'last_name',  'username', 'email', 'password', 'status', 'avatar_id'
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token',
	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'email_verified_at' => 'datetime',
	];

	public function getJWTIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get user attribute sets.
	 */

	public function getJWTCustomClaims()
	{
		return [];
	}

    /**
     * Get user posts sets.
     */
    public function posts() {
        return $this->hasMany(Post::class);
    }
}
