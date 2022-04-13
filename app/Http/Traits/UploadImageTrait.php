<?php

namespace App\Http\Traits;


trait UploadImageTrait
{
    public function uploadImage(string $directory, $file)
    {
        if ($file) {
            $ext = $file->getClientOriginalExtension();
            $imageName = "assets/images/$directory/" .  uniqid() . ".$ext";
            $file->move(public_path("assets/images/$directory"), $imageName);
        } else {
            $imageName = "assets/images/noImageYet.jpg";
        }
        return $imageName;
    }
}
