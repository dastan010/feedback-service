<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\Ticket as TicketResource;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Auth;

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
            
            if ($diff > 24) {
                $request->validate([
                    'theme' => 'required',
                    'message' => 'required'
                ]);
                $ticket = new Ticket([
                    'theme' => $request->theme,
                    'message' => $request->message,
                    'user_id' => Auth::user()->id
                ]);
                $ticket->save();
                
                if ($request->file('attachedFile')) {
                    $path = 'public/attachedFiles/user/' . Auth::user()->id . '/ticket/' . $ticket->id;
                    $dbPath = '/attachedFiles/user/' . Auth::user()->id . '/ticket/' . $ticket->id;
                    Storage::putFileAs($path, 
                                       $request->file('attachedFile'),
                                       'file_'.$ticket->id.'.'.$request->file('attachedFile')->getClientOriginalExtension(), 'public');
                    $tickets = DB::table('tickets')
                        ->where('id', $ticket->id)
                        ->where('user_id', Auth::user()->id)
                        ->update(['file_path' => $dbPath]);
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
