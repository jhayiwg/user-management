<?php

namespace App\Services\Photo;

use Intervention\Image\Facades\Image;

class Upload {

    const AVATAR_WIDTH = 430;
    const AVATAR_HEIGHT = 430;
    
    public static function handle($file) {
        return $file->store('public/images');
    }
    public static function Crop($file, $points = []) {

        $image = Image::make($file);
        if ($points) {
			$size   = floor($points['x2']-$points['x1']);
			$image->crop($size, $size, (int) $points['x1'], (int) $points['y1']);
		} else {
            $image->fit(self::AVATAR_WIDTH, self::AVATAR_HEIGHT);
		}

        return $image->resize(self::AVATAR_WIDTH, self::AVATAR_HEIGHT)->save();
    }
}
