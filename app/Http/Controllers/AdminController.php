<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Models\Ticket;
use Illuminate\Support\Facades\Storage;
use Response;

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
                     'tickets.message', 
                     'tickets.response',
                     'tickets.created_at'
                    )->where('tickets.user_id', $id)->get();
        
        return response()->json([
          'userTickets' => $userTickets
        ]);
    }
    public function downloadFile() {
        $file = public_path().'/storage/attachedFiles/user/3/ticket/49/file_49.pdf';
        $headers = array(
            'Content-Type: application/pdf'
        );

        return response()->download($file,'file_39.pdf', $headers);
        // return response()->json([
        //     'path' => public_path()
        // ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
