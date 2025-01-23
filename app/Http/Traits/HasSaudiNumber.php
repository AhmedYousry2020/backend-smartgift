<?php
namespace App\Http\Traits;

use App\Models\Country;
use Illuminate\Support\Str;

trait HasSaudiNumber{
    public function trimCodeFromPhone($phone,$countryID=1){
        $countryCode = Country::where('id',$countryID)->first();
        // $countryCode = $countryCode->code;
        $countryCode = $countryCode ? $countryCode->code : '+966';
        $phoneWithoutCode = substr($this->phone, strlen($countryCode), strlen($phone));
        $phone = $countryCode.$phoneWithoutCode;
        return $phone;
    }
}