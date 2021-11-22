<?php

namespace App\Helper;

use Closure;
use Image;
use Intervention\Image\Constraint;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageHelper {

    /**
     * Save the uploaded image.
     *
     * @param UploadedFile $file     Uploaded file.
     * @param int          $maxWidth
     * @param string       $path
     * @param Closure      $callback Custom file naming method.
     *
     * @return string File name.
     */
    public static function saveImage(
        UploadedFile $file, 
        $path = null, 
        $maxWidth = null, 
        $quality = 60, 
        Closure $callback = null
    ) {
        if (!$path) {
            $path = config('filesystems.uploads.url_photo');
        }

        if ($callback) {
            $fileName = $callback();
        } else {
            $fileName = self::getFileName($file);
        }

        $img = self::makeImage($file);
        if($maxWidth != null) {
            $img = self::resizeImage($img, $maxWidth);
        }

        self::uploadImage($img, $fileName, $path, $quality);

        return $fileName;
    }

    public static function deletePhoto($fileName, $path)
    {
        if (!$path) {
            $path = config('filesystems.uploads.url_photo');
        }
        return \File::delete(public_path($path.$fileName));
    }

    /**
     * Get uploaded file's name.
     *
     * @param UploadedFile $file
     *
     * @return null|string
     */
    protected static function getFileName(UploadedFile $file)
    {
        $filename = $file->getClientOriginalName();
        $filename = date('Ymd_His') . '_' . strtolower(pathinfo($filename, PATHINFO_FILENAME)) . '.' . pathinfo($filename, PATHINFO_EXTENSION);

        return $filename;
    }

    /**
     * Create the image from upload file.
     *
     * @param UploadedFile $file
     *
     * @return \Intervention\Image\Image
     */
    protected static function makeImage(UploadedFile $file)
    {
        return Image::make($file);
    }

    /**
     * Resize image to the configured size.
     *
     * @param \Intervention\Image\Image $img
     * @param int                       $maxWidth
     *
     * @return \Intervention\Image\Image
     */
    protected static function resizeImage(\Intervention\Image\Image $img, $maxWidth = 150)
    {
        $img->resize($maxWidth, null, function (Constraint $constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        return $img;
    }

    /**
     * Save the uploaded image to the file system.
     *
     * @param \Intervention\Image\Image $img
     * @param string                    $fileName
     * @param string                    $path
     */
    protected static function uploadImage($img, $fileName, $path, $quality)
    {
        if(!file_exists(public_path($path))) {
            mkdir(public_path($path), 755, true);
        }

        $img->save(public_path($path . $fileName), $quality);
    }

}