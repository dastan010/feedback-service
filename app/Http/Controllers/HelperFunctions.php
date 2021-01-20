<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class HelperFunctions {
  static function processFile($file, $ticket, $id) {
      $path = 'public/attachedFiles/user/' . $id . '/ticket/' . $ticket->id;
      $dbPath = '/attachedFiles/user/' . $id . '/ticket/' . $ticket->id;
      Storage::putFileAs($path, 
                        $file,
                        'file_'.$ticket->id.'.'.$file->getClientOriginalExtension(), 
                        'public');
      $tickets = DB::table('tickets')
          ->where('id', $ticket->id)
          ->where('user_id', $id)
          ->update(['file_path' => $dbPath]);
  }
}