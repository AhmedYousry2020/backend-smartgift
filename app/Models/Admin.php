<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guard = 'admin';

    protected $fillable = [
        'first_name', 'last_name','email', 'password','image'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

     //Accessors
     public function GetFirstNameAttribute($value){
        return ucfirst($value);
        }
        public function GetLastNameAttribute($value){
            return ucfirst($value);

        }
       public function getImagePathAttribute(){

            if (!$this->image) {
                return asset('uploads/user_images/default.png'); // Return a default image
            }
            return asset('uploads/user_images/'.$this->image);

       }
}
