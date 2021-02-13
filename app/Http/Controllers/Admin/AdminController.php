<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Http\Requests\FeedbackResponse\FeedbackResponseRequest;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'users' => User::where('owner', 0)->get(),
            'token' => csrf_token()
        ]);
    }

    public function getUserTickets($id)
    {
        return response()->json([
          'userTickets' => $this->processQuery($id)
        ]);
    }

    public function processQuery($id)
    {
        return DB::table('tickets')->join('users', 'users.id', '=', 'tickets.user_id')
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
    }
    
    public function downloadFile($user_id, $ticket_id) 
    {
        $fileName = 'file_'.$ticket_id.'.pdf';    
        $filePath = public_path('/storage/'.$this->getFilePath($user_id, $ticket_id).'/'.$fileName);
        return response()->file($filePath);
    }
    
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FeedbackResponseRequest $request, $id)
    {
        $user_id = $request->user_id;
        $ticket_id = $request->ticket_id;
        $response = $request->response;
        
        return response()->json([
            'ticket_id' => $ticket_id,
            'user_id' => $user_id,
            'response' => $this->processUpdateQuery($id, $user_id, $response)
            ]);
    }
    
    public function getFilePath($user_id, $ticket_id) 
    {
        return Ticket::
            select('file_path')
            ->where('id', $ticket_id)
            ->where('user_id', $user_id)
            ->first()->file_path;
    }

    public function processUpdateQuery($id, $user_id, $response) 
    {
        return Ticket::
            where('id', $id)
            ->where('user_id', $user_id)
            ->update(['response' => $response]);
    }
}
