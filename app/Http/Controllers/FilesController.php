<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\File;


class FilesController extends Controller{
	public function __construct(){
		$this->middleware('auth');
	}
	public function imageUpload(Request $request){
	  try {
      if(\Entrust::can('manage_content')) {
        if ($request->has('upload') and $request->has('mimetype')) {
          $data = $request->upload;
          $mime = $request->mimetype;
          if (preg_match('/data:image\/(gif|jpeg|png);base64,(.*)/i', $data, $matches)) {
            try {
              $extension = $matches[1];
            } catch (\Exception $e) {
              return "exception:extension";
            }
            try {
              $imageData = base64_decode($matches[2]);
            } catch (\Exception $e) {
              return "exception:decode";
            }
            /*try {
              $image = imagecreatefromstring($imageData);
            } catch (\Exception $e) {
              return "exception:image";
            }*/
            $filename = time() . '.' . $extension;
            try {
              Storage::disk('images')->put($filename, $imageData);
            } catch (\Exception $e) {
              Log::info("".$e);
              return "exception:store";
            }
            $url = '/storage/images/' . $filename;
            if (File::where('path', $url)->count() == 0) {
              $file = new File();
              $file->category = 'image';
              $file->filename = $filename;
              $file->extension = $extension;
              $file->path = $url;
              $file->mimetype = $mime;
              $file->save();
            }
            return $url;
          }
          return 'not an picture';
        }
        return 'arguments not valid';
      }else{
	      return "exception:security";
      }
    }catch (\Exception $e){
	    return "exception";
    }
	}
	public function imagesPage($sz){
	  return File::where('category','image')->orderBy('created_at','desc')->paginate($sz);
  }

}