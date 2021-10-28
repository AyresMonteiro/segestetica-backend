<?php

namespace App\Http\Handlers;

use Illuminate\Support\Facades\Storage;

class FileHandler
{
  public static function save($file, string $path)
  {
    return Storage::putFile($path, $file);
  }

  public static function saveAs($file, string $path, string $name)
  {
    return Storage::putFileAs($path, $file, $name);
  }

  public static function delete($filepath)
  {
    return Storage::delete($filepath);
  }
}
