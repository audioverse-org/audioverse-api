<?php

namespace App;

use App\Newsletter;
use App\Notifications\ResetPassword;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'userId';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    public function socialAuth() {
        return $this->hasOne('App\UserSocialAuth', 'userId');
    }

    public function newsletter() {
        return $this->hasOne('App\Newsletter','userId');
    }
    /**
     * Subscribe to newsletter
     *
     */
    public function subscribeNewsletter() {

        try {

            $newsletter = Newsletter::where(['userId' => $this->userId])->firstOrFail();
            $newsletter->email = $this->email;
            $newsletter->subscribed = 1;
            $newsletter->active = 1;
            $newsletter->save();

        } catch (ModelNotFoundException $e) {

            $newsletter = new Newsletter();
            $newsletter->userId = $this->userId;
            $newsletter->name = 'Subscriber';
            $newsletter->token = '';
            $newsletter->email = $this->email;
            $newsletter->subscribed = 1;
            $newsletter->active = 1;
            $newsletter->save();
        }

        return true;
    }

    public function unsubscribeNewsletter() {

        try {

            $newsletter = Newsletter::where(['userId' => $this->userId])->firstOrFail();
            $newsletter->email = $this->email;
            $newsletter->subscribed = 0;
            $newsletter->active = 1;
            $newsletter->save();

            return true;

        } catch (ModelNotFoundException $e) {

            return false;
        }
    }
    public function addSocialAuth() {

        $socialAuth = new UserSocialAuth();
        $socialAuth->userId = $this->userId;
        $socialAuth->socialId = 0;
        $socialAuth->socialServiceName = 'AudioVerse';
        $socialAuth->sessionToken = mt_rand(1000000000,9999999999);
        $socialAuth->lastLogin = date('Y-m-d H:i:s');
        $socialAuth->save();
    }

    /**
     * Deletes user verification request
     *
     */
    public function deleteVerificationRequest() {

        try {
            $request = UserRequest::where(['userId' => $this->userId])->firstOrFail();
            $request->delete();
            return true;
        } catch (ModelNotFoundException $e) {
            return false;
        }
    }
    /**
     * Gets verification token
     *
     * @return string
     */
    public function getVerificationRequest() {

        try {
            $request = UserRequest::where(['userId' => $this->userId])->firstOrFail();
            return MD5($request->requestId);
        } catch (ModelNotFoundException $e) {
            return $this->getNewVerificationRequest();
        }
    }

    /**
     * Generates verification token
     *
     * @return string
     */
    public function getNewVerificationRequest() {

        $request = new UserRequest();
        $request->userId = $this->userId;
        $request->type = 'createAccount';
        $request->pending = 1;
        $request->active = 1;
        $request->timestamps = false;
        $request->save();
        return MD5($request->requestId);
    }

    /**
     * Override to use custom send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token, $this->email));
    }
}
