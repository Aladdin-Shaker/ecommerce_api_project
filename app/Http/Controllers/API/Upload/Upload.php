<?php

namespace App\Http\Controllers;

use App\Model\File;
use App\Model\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Upload extends Controller
{
    /*
        TWO WAY TO UPLOAD :
        1- single upload => upload the file without store it in the file model
        2- linked upload
    */

    // file, path, new_name, delete_file, upload_type

    public static function upload($data = [])
    {
        if (in_array('new_name', $data)) {
            $new_name = $data['new_name'] === null ? time() : $data['new_name'];
        }
        if (request()->hasFile($data['file']) && 'single' == $data['upload_type']) {
            in_array($data['delete_file'], $data) && !empty($data['delete_file']) ? Storage::delete($data['delete_file']) : '';
            return request()->file($data['file'])->store($data['path']);
        } elseif (request()->hasFile($data['file']) && 'files' == $data['upload_type']) {

            $file = request()->file($data['file']); // get the file from the request
            $name = $file->getClientOriginalName(); // get name before upload
            $hashedName = $file->hashName(); // get name after upload
            $size = $file->getSize(); // get file size
            $mime_type = $file->getMimeType(); // get mime type

            $file->store($data['path']); // save
            $add = File::create([
                'name' =>  $name,
                'size' => $size,
                'file' => $hashedName,
                'path' => $data['path'], // get path from controller
                'full_file' => $data['path'] . '/' . $hashedName,
                'mime_type' => $mime_type,
                'file_type' => $data['file_type'], // get file_type from controller
                'relation_id' => $data['relation_id'], // get relation_id from controller
            ]);

            return $add->id;
        }
    }

    // delete single file from File table
    public function delete($id)
    {
        $file = File::find($id);
        if (!empty($file)) {
            $file->delete();
            Storage::delete($file->full_file);
        }
    }

    // delete multiple files from File table
    public function delete_files($product_id)
    {
        $files = File::where('file_type', 'product')->where('relation_id', $product_id)->get();
        if (count($files) > 0) {
            foreach ($files as $file) {
                $this->delete($file->id);
                Storage::deleteDirectory($file->path);
            }
        }
    }
}
