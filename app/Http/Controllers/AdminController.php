<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Models\Ticket;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = DB::table('users')->where('owner', 0)->get();
        return response()->json([
            'users' => $users,
            'token' => csrf_token()
        ]);
    }

    public function getUserTickets($id)
    {
        $userTickets = DB::table('tickets')
            ->join('users', 'users.id', '=', 'tickets.user_id')
            ->select(
                     'tickets.id',
                     'tickets.user_id',
                     'users.name',
                     'tickets.theme',
                     'users.email',
                     'tickets.file_path',
                     'tickets.message', 
                     'tickets.response',
                     'tickets.created_at'
                    )->where('tickets.user_id', $id)->paginate(5);
        
        return response()->json([
          'userTickets' => $userTickets
        ]);
    }
    
    public function downloadFile($user_id, $ticket_id) {
        $db_file_path = DB::table('tickets')
            ->select('file_path')
            ->where('id', $ticket_id)
            ->where('user_id', $user_id)
            ->first();
        
        $fileName = 'file_'.$ticket_id.'.pdf';    
        $filePath = public_path('/storage/'.$db_file_path->file_path.'/'.$fileName);
        return response()->file($filePath);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'ticket_id' => 'required',
            'user_id' => 'required',
            'response' => 'required',
        ]);
        $user_id = $request->user_id;
        $ticket_id = $request->ticket_id;
        $response = $request->response;
        $tickets = DB::table('tickets')
              ->where('id', $id)
              ->where('user_id', $user_id)
              ->update(['response' => $response]);
        return response()->json([
            'ticket_id' => $ticket_id,
            'user_id' => $user_id,
            'response' => $tickets
        ]);
    }
}
