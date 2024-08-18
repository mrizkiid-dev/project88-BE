<?php
namespace App\Utils;
class CheckerHelper {
    public static function isDigit($id): void{
        $pattern = '/^[0-9]+$/';
        if($id && !preg_match($pattern,$id)){
            throw new HttpException(404, 'Not Found');
        }
    }
}