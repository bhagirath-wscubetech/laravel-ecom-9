<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

trait UploadImage
{
    /**
     * Upload image.
     */
    private function uploadImage($file, $path = null, $compression = null, $product = null)
    {
        $image_uploaded = false;

        // File system
        $file_system = config('filesystems.default');
        //------------

        // Generate image name and get extension
        $extension = $file->getClientOriginalExtension();
        if ($product == '') {
            $file_name        = $this->generateImageName($extension);
        } else {
            $file_name        = $product . '.' . $extension;
        }

        $destination_path = $path . $file_name;
        //--------------------------------------
        // Upload image
        try {
            if (!is_null($compression)) {
                // Compress image
                $image = Image::make($file)->encode($extension, $compression);
                //---------------

                // Upload image
                $image_uploaded = Storage::disk($file_system)->put($destination_path, (string) $image, 'public');
                //-------------
            } else {
                // Upload image
                $image = Storage::disk($file_system)->putFileAs(rtrim($path, '/'), $file, $file_name);
                //-------------
                if (!is_null($image)) {

                    $image_uploaded = true;
                }
            }
        } catch (\Exception $e) {
        }
        //-------------

        // Set data
        if ($image_uploaded) {
            $data = [
                '_status'  => true,
                '_message' => "File Uploaded",
                '_name'    => $file_name
            ];
        } else {
            $data = [
                '_status'  => false,
                '_message' => "Unable to upload the file",
                '_name'    => null
            ];
        }
        //---------

        return $data;
    }

    /**
     * Generate image name.
     *
     * @param  string  $extension
     * @return string
     */
    private function generateImageName($extension)
    {
        return Str::uuid() . '-' . time() . '.' . $extension;
    }
}
