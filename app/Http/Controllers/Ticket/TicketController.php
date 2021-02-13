<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Http\Requests\Ticket\TicketRequest;
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
        return response()->json([
            'tickets' => $this->getTicketByUserId()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TicketRequest $request)
    {
        $user = Auth::user();
        if($this->notAdmin()) {
            if($this->lastRecordTimeExists()) {
                if($this->isSendTime()) {
                    $isProcessed = $this->processRequest($request, $user);
                    if($isProcessed) {
                        return response()->json([
                            'success' => 'Запрос отправлен.',
                            'status' => 'success'
                        ]);
                    }
                }else {
                    $remainingTime = 24 - $this->getTimeDifference();
                    return response()->json([
                        'alert' => 'До следующей отправки '.$remainingTime.' час.',
                        'status' => 'pending'
                    ]);
                }
            }else {
                $isProcessed = $this->processRequest($request, $user);
                if ($isProcessed) {
                    return response()->json([
                        'success' => 'Запрос отправлен.',
                        'status' => 'success'
                    ]);
                }
            }
        }
    }
    
    public function processRequest($request, $user) 
    {
        $ticket = new Ticket;
        $ticket->theme = $request->theme;
        $ticket->message = $request->message;
        $ticket->user_id = $user->id;    
        
        $ticket->save();
        
        if($request->file('attachedFile')) {
            $this->processFile($request->file('attachedFile'), $ticket, $user->id);
        }
        
        if($ticket) {
            return true;    
        }
        
        return false;        
    }
    
    public function notAdmin() 
    {
        return Auth::user()->owner == 0;
    }

    public function lastRecordTimeExists()
    {   
        return $this->getLastRecordTime() != null;
    }

    public function getLastRecordTime() 
    {
        return $this->getLastRecord()
            ? Ticket::select('created_at')->latest()->first()->created_at 
            : null;
    }

    public function getLastRecord()
    {
        return Ticket::select('created_at')->latest()->first();
    } 

    public function getTimeDifference() 
    {
        return now()->diffInHours($this->getLastRecordTime());
    }

    public function isSendTime() {
        return $this->getTimeDifference() > 24;
    }

    public function getTicketByUserId() 
    {
        return Ticket::where('user_id', Auth::user()->id)->paginate(5);
    }



    public function processFile($file, $ticket, $id) 
    {
        $path = 'public/attachedFiles/user/' . $id . '/ticket/' . $ticket->id;
        $dbPath = '/attachedFiles/user/' . $id . '/ticket/' . $ticket->id;
        Storage::putFileAs($path, 
                           $file,
                           'file_' . $ticket->id . '.' . $file->getClientOriginalExtension(), 
                           'public');
        $tickets = Ticket::where('id', $ticket->id)->where('user_id', $id)->update(['file_path' => $dbPath]);
    }
}


