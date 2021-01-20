<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Auth;
use Debugbar;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $preserveKeys = true;
    public function index()
    {
        $tickets = DB::table('tickets')->where('user_id', Auth::user()->id)->get();
        
        return response()->json([
            'tickets' => $tickets 
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->owner == 0) {
            $test = DB::table('tickets')
                ->select('created_at')
                ->latest()->first();
            
            if ($test != null) {
                $diff = now()->diffInHours($test->created_at);
            }

            $sendTime = $diff > 24;

            if ($sendTime) {
                $request->validate([
                    'theme' => 'required',
                    'message' => 'required'
                ]);
                
                $ticket = new Ticket([
                    'theme'   => $request->theme,
                    'message' => $request->message,
                    'user_id' => $user->id
                ]);
                
                $ticket->save();
                
                if ($request->file('attachedFile')) {
                    HelperFunctions::processFile($request->file('attachedFile'), $ticket, $user->id);
                }
                
                return response()->json([
                    'success' => 'Запрос отправлен.',
                    'status' => 'success'
                ]);    
            } else {
                $remainingTime = 24 - $diff;
                return response()->json([
                    'alert' => 'До следующей отправки '.$remainingTime.' час.',
                    'status' => 'pending'
                ]);
            }
        }
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
        //
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
