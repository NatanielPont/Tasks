<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Lab404\Impersonate\Models\Impersonate;
use Laravel\Passport\HasApiTokens;
use NotificationChannels\WebPush\HasPushSubscriptions;
use Spatie\Permission\Traits\HasRoles;
//use NotificationChannels\WebPush\HasPushSubscriptions;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasRoles,Notifiable, HasApiTokens,Impersonate,HasPushSubscriptions;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];
    const DEFAULT_PHOTO = 'default.png';
//    const PHOTOS_PATH = 'user_photos';
    const DEFAULT_PHOTO_PATH = 'app/photos/' . self::DEFAULT_PHOTO;
    const USERS_CACHE_KEY = 'tasks.natanielpont.scool.cat.user';


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function photo()
    {
        return $this->hasOne(Photo::class);
    }
    public function assignPhoto(Photo $photo)
    {
        $photo->user_id = $this->id;
        $photo->save();
        return $this;
    }
    public function avatars()
    {
        return $this->hasMany(Avatar::class);
    }
    public function addAvatar(Avatar $avatar)
    {
        $this->avatars()->save($avatar);
        return $this;
    }
    /**
     * @return bool
     */
    public function canImpersonate()
    {
        return $this->isSuperAdmin();
    }
    /**
     * @return bool
     */
    public function canBeImpersonated()
    {
        return !$this->isSuperAdmin();
    }
    public function impersonatedBy()
    {
        if ($this->isImpersonated()) return User::findOrFail(Session::get('impersonated_by'));
        return null;
    }
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
    public function addTask($task)
    {
        $this->tasks()->save($task);
    }
    public function addTasks($tasks)
    {
        $this->tasks()->saveMany($tasks);
    }
    /**
     * @return mixed
     */
    public function isSuperAdmin()
    {
        return $this->admin;
    }
    public function map()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'gravatar' => $this->gravatar,
            'admin' => (boolean) $this->admin,
            'roles' => $this->roles()->pluck('name')->unique()->toArray(),
            'permissions' => $this->getAllPermissions()->pluck('name')->unique()->toArray(),
            'hash_id' => $this->hash_id,
            'online' =>(boolean) $this->online


        ];
    }

    public function mapSimple()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'gravatar' => $this->gravatar,
            'admin' => (boolean) $this->admin,
            'hash_id' => $this->hash_id,
            'online' => $this->online

        ];
    }

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getGravatarAttribute()
    {
        return 'https://www.gravatar.com/avatar/' . md5($this->email);
    }
    public function scopeRegular($query)
    {
        return $query->where('admin',false);
    }
    public function scopeAdmin($query)
    {
        return $query->where('admin',true);
    }


    /**
     * Hashed key.
     * @return string
     */
    protected function hashedKey()
    {
        $hashids = new \Hashids\Hashids(config('tasks.salt'));
        return $hashids->encode($this->getKey());
    }
    /**
     * Get the photo path prefix.
     *
     * @param  string  $value
     * @return string
     */
    public function getHashIdAttribute($value)
    {
        return $this->hashedKey();
    }

    public function isOnline()
    {
        return Cache::has(User::USERS_CACHE_KEY . '-user-is-online-' . $this->id);
    }
    public function getOnlineAttribute()
    {
        return $this->isOnline();
    }
    public function channels()
    {
        return $this->belongsToMany(Channel::class);
    }





}
