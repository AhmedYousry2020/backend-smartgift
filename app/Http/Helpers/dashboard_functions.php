<?php


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


if(!function_exists('uploadImage')){

    function uploadImage($request, $model = '' ){
        $model        = Str::plural($model);
        $model        = Str::ucfirst($model);
//        $originalName =  $request->getClientOriginalName(); // Get file Original Name
        $originalName =  uniqid().'.'.$request->getClientOriginalExtension(); // Get file Original Name
        $imageName    = str_replace([ '(', ')', ' '],'','profile-' . time() . $originalName);  // Set Image name
        $contents     = file_get_contents( $request );

        if(Storage::disk(config('filesystems.default'))->put( $model . '/' . $imageName , $contents))
            return $model . '/' . $imageName;

        return false;
    }
}


if(!function_exists('deleteImage')){

    function deleteImage($imageName, $model){
        $model = Str::plural($model);
        $model = Str::ucfirst($model);

        if ($imageName != 'default.png'){
            $path = "/Images/" . $model . '/' .$imageName;
            Storage::disk(config('filesystems.default'))->delete($path);
        }
    }
}


if(!function_exists('getImagePath')){
    function getImagePath( $imageName = null , $defaultImage = 'default.svg'  ,$url=''): string
    {



        if ( is_null( $imageName )) // check if the image is null or the image doesn't exist
            return asset('placeholder_images/' . $defaultImage);
        else{
            if($url=='dashboard'){
                return config('filesystems.app_files_url').'/storage/'.$imageName;
            }else{
                return Storage::disk(config('filesystems.default'))->url( '/' . $imageName );
            }

        }

    }
}


// if(!function_exists('settings')){

//     function settings(): AppSetting
//     {
//         return new AppSetting();
//     }

// }


// if(!function_exists('currency')){

//     function currency() : string
//     {
//         return __( settings()->get('currency') );
//     }

// }


if(!function_exists('getAllLanguages')){

    function getAllLanguages()
    {
        return collect([
            [
                "id" => 1,
                "name" => "العربية",
                "code" => "ar",
                "flag" => "ar.png",
                "is_default" => 1,
                "is_available" => 1,
                "direction" => "rtl"
            ],
            [
                "id" => 2,
                "name" => "English",
                "code" => "en",
                "flag" => "en.png",
                "is_default" => 0,
                "is_available" => 1,
                "direction" => "ltr",
            ]
        ]);
    }

}

if(!function_exists('getDefaultLanguage')){

    function getDefaultLanguage()
    {
        return getAllLanguages()->firstWhere('is_default','=',1);
    }
}
if(!function_exists('getDefaultLanguageCode')){

    function getDefaultLanguageCode(): string
    {
        $defaultLang = getDefaultLanguage();

        return $defaultLang ? $defaultLang['code'] : 'ar';
    }

}

if(!function_exists('randomNumber')){
    function randomNumber($length=8,$type='number') {
        $random = "";
        $data = "123456123456789071234567890890";
        if($type=='char'){
            $data = "zxcvbnmasdfghjklqwertyuiop";
        }elseif($type=='both'){
            $data = "123456123456789071234567890890zxcvbnmasdfghjklqwertyuiop";
        }
        for ($i = 0; $i < $length; $i++) {
                $random .= substr($data, (rand() % (strlen($data))), 1);
        }
        return $random;

    }
}

if(!function_exists('minuteBetweenTwoDate')){
    function minuteBetweenTwoDate($from_date,$to_date='') {
        $from_date=strtotime($from_date);
        if($to_date==''){
            $today=strtotime(date('Y-m-d H:i:s'));
        }else{
            $today=strtotime($to_date);
        }

        $minute=round(abs($from_date - $today) / 60,2);
        return $minute;

    }
}








