<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketAnswer;
use App\Models\TicketCategory;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tickets = Ticket::with('user', 'assignee', 'category', 'resolver')->get();

        $response = [];

        foreach($tickets as $ticket) {
            $entry = [
                'Id' => $ticket->Id,
                'UserId' => $ticket->UserId,
                'User' => $ticket->user->Name,
                'AssigneeId' => $ticket->AssigneeId,
                'AssignedRank' => $ticket->AssignedRank,
                'CategoryId' => $ticket->CategoryId,
                'Category' => $ticket->category->Title,
                'Title' => $ticket->Title,
                'State' => $ticket->State,
                'ResolvedBy' => $ticket->ResolvedBy,
                'LastResponseAt' => $ticket->LastResponseAt,
                'CreatedAt' => $ticket->CreatedAt,
                'ResolvedAt' => $ticket->ResolvedAt,
            ];

            if($ticket->assignee) {
                $entry['Assignee'] = $ticket->assignee->Name;
            }

            if($ticket->resolver) {
                $entry['Resolver'] = $ticket->assignee->Name;
            }


            array_push($response, (object)$entry);
        }

        return $response;
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
        $ticket = new Ticket();
        $ticket->UserId = auth()->user()->Id;
        $ticket->CategoryId = $request->get('category');
        $ticket->Title = $request->get('title');
        $ticket->State = 'Open';
        $ticket->save();

        $fields = $request->get('fields');

        if(!empty($fields)) {
            $category = TicketCategory::with('fields')->find($ticket->CategoryId);

            $text = [];

            foreach($fields as $key => $value) {
                foreach($category->fields as $field) {
                    if('field' . $field->Id === $key) {
                        array_push($text, $field->Name . ': ' . $value);
                        break;
                    }
                }
            }

            if(!empty($text)) {
                $answer = new TicketAnswer();
                $answer->TicketId = $ticket->Id;
                $answer->UserId = auth()->user()->Id;
                $answer->MessageType = 1;
                $answer->Message = implode(chr(0x0A), $text);
                $answer->save();
            }
        }


        $answer = new TicketAnswer();
        $answer->TicketId = $ticket->Id;
        $answer->UserId = auth()->user()->Id;
        $answer->MessageType = 0;
        $answer->Message = $request->get('message');
        $answer->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket $ticket)
    {
        $entry = [
            'Id' => $ticket->Id,
            'UserId' => $ticket->UserId,
            'User' => $ticket->user->Name,
            'AssigneeId' => $ticket->AssigneeId,
            'AssignedRank' => $ticket->AssignedRank,
            'CategoryId' => $ticket->CategoryId,
            'Category' => $ticket->category->Title,
            'Title' => $ticket->Title,
            'State' => $ticket->State,
            'ResolvedBy' => $ticket->ResolvedBy,
            'LastResponseAt' => $ticket->LastResponseAt,
            'CreatedAt' => $ticket->CreatedAt,
            'ResolvedAt' => $ticket->ResolvedAt,
        ];

        if($ticket->assignee) {
            $entry['Assignee'] = $ticket->assignee->Name;
        }

        if($ticket->resolver) {
            $entry['Resolver'] = $ticket->assignee->Name;
        }

        $entry['answers'] = [];
        $answers = $ticket->answers()->with('user')->get();

        foreach($answers as $answer) {
            array_push($entry['answers'], [
                'Id' => $answer->Id,
                'UserId' => $answer->UserId,
                'User' => $answer->user->Name,
                'MessageType' => $answer->MessageType,
                'Message' => $answer->Message,
                'CreatedAt' => $answer->CreatedAt,
            ]);
        }

        return $entry;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function edit(Ticket $ticket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ticket $ticket)
    {
        $type = $request->get('type');

        switch($type)
        {
            case 'addMessage':
                $answer = new TicketAnswer();
                $answer->TicketId = $ticket->Id;
                $answer->UserId = auth()->user()->Id;
                $answer->MessageType = 0;
                $answer->Message = $request->get('message');
                $answer->save();
                break;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket $ticket)
    {
        //
    }
}
