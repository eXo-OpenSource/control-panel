<?php

namespace App\Http\Controllers\Api;

use App\Services\ForumService;
use App\Services\MTAService;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Ticket;
use App\Models\TicketAnswer;
use Illuminate\Http\Request;
use App\Models\TicketCategory;
use App\Http\Controllers\Controller;

class TicketController extends Controller
{
    private $forumService;

    public function __construct(ForumService $forumService)
    {
        $this->forumService = $forumService;
    }


    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function index()
    {
        $state = request()->get('state');
        $search = request()->get('search');
        $limit = 30;

        if(!in_array($state, ['open', 'both', 'closed'])) {
            $state = 'open';
        }

        $tickets = auth()->user()->tickets()->with('user', 'assignee', 'category', 'resolver');

        if(auth()->user()->Rank >= 1) {
            $tickets = Ticket::with('user', 'assignee', 'category', 'resolver');
            $tickets->where('AssignedRank', '<=', auth()->user()->Rank);
            $tickets->orWhere('AssignedRank', '=', null);
        }

        switch($state)
        {
            case 'open':
                $tickets->where('State', Ticket::TICKET_STATE_OPEN);
                break;
            case 'closed':
                $tickets->where('State', Ticket::TICKET_STATE_CLOSED);
                break;
        }

        if($search && $search != '') {
            $categoryIds = TicketCategory::query()->where('Title', 'LIKE', '%' . $search . '%')->get()->pluck('Id');
            $userIds = User::query()->where('Name', 'LIKE', '%' . $search . '%')->get()->pluck('Id');
            $tickets->where('Title', 'LIKE', '%' . $search . '%');
            $tickets->orWhereIn('UserId', $userIds);
            $tickets->orWhereIn('AssigneeId', $userIds);
            $tickets->orWhereIn('CategoryId', $categoryIds);
        }

        $tickets->orderBy('Id', 'DESC');

        $tickets = $tickets->paginate($limit);

        $responseData = [];

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
                'StateText' => $ticket->State === Ticket::TICKET_STATE_OPEN ? 'Offen' : 'Geschlossen',
                'ResolvedBy' => $ticket->ResolvedBy,
                'AnswerCount' => $ticket->answers()->where('MessageType', 0)->count(),
                'LastResponseAt' => $ticket->LastResponseAt->format('d.m.Y H:i:s'),
                'CreatedAt' => $ticket->CreatedAt->format('d.m.Y H:i:s'),
                'ResolvedAt' => $ticket->ResolvedAt ? $ticket->ResolvedAt->format('d.m.Y H:i:s') : null,
            ];

            if($ticket->assignee) {
                $entry['Assignee'] = $ticket->assignee->Name;
            }

            if($ticket->resolver) {
                $entry['Resolver'] = $ticket->assignee->Name;
            }


            array_push($responseData, (object)$entry);
        }

        return [
            'items' => $responseData,
            'perPage' => $tickets->perPage(),
            'currentPage' => $tickets->currentPage(),
            'lastPage' => $tickets->lastPage(),
        ];
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
     * @return string
     */
    public function store(Request $request)
    {
        $category = TicketCategory::with('fields')->find($request->get('category'));

        $ticket = new Ticket();
        $ticket->UserId = auth()->user()->Id;
        $ticket->CategoryId = $category->Id;
        $ticket->AssignedRank = $category->AdminRank;
        $ticket->Title = $request->get('title');
        $ticket->State = Ticket::TICKET_STATE_OPEN;
        $ticket->save();

        $ticket->users()->attach(auth()->user(), ['JoinedAt' => new Carbon()]);

        $fields = $request->get('fields');
        $hasFilledFields = 0;

        if(!empty($fields)) {
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
                $hasFilledFields++;
            }
        }

        if(!empty($request->get('message'))) {
            $answer = new TicketAnswer();
            $answer->TicketId = $ticket->Id;
            $answer->UserId = auth()->user()->Id;
            $answer->MessageType = 0;
            $answer->Message = $request->get('message');
            $answer->save();
        } else {
            if($hasFilledFields === 0)
            {
                return 'BRUHH'; // TODO: check the things before?
            }
        }

        event(new \App\Events\TicketCreated($ticket));
        $mtaService = new MTAService();
        $mtaService->sendMessage('admin', null, '[TICKET] Es wurde ein neues Ticket von ' . $ticket->user->Name . ' (' . $ticket->category->Title .') erstellt!', ['r' => 255, 'g' => 50, 'b' => 0, 'minRank' => $ticket->AssignedRank]);

        return '';
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return array
     */
    public function show(Ticket $ticket)
    {
        abort_unless(auth()->user()->can('show', $ticket), 403);

        return $ticket->getApiResponse();
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
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Ticket $ticket
     * @return array
     */
    public function update(Request $request, Ticket $ticket)
    {
        abort_unless(auth()->user()->can('update', $ticket), 403);

        $mtaService = new MTAService();

        $type = $request->get('type');
        $userId = auth()->user()->Id;
        $name = auth()->user()->Name;

        switch($type)
        {
            case 'addMessage':
                if($ticket->State === Ticket::TICKET_STATE_CLOSED) {
                    return;
                }
                if (!$ticket->users->contains($userId)) {
                    $ticket->users()->attach($userId, ['JoinedAt' => new Carbon()]);
                    $ticket->save();

                    $answer = new TicketAnswer();
                    $answer->TicketId = $ticket->Id;
                    $answer->UserId = $userId;
                    $answer->MessageType = 1;
                    $answer->Message = sprintf("%s ist dem Ticket beigetreten!", auth()->user()->Name);
                    $answer->save();
                }
                $answer = new TicketAnswer();
                $answer->TicketId = $ticket->Id;
                $answer->UserId = $userId;
                $answer->MessageType = 0;
                $answer->Message = $request->get('message');
                $answer->save();
                event(new \App\Events\TicketUpdated($ticket));
                $message = '[TICKET] ' . $name . ' hat auf das Ticket #' . $ticket->Id . ' geantwortet!';
                $mtaService->sendMessage('admin', null, $message, ['r' => 255, 'g' => 50, 'b' => 0, 'minRank' => $ticket->AssignedRank]);

                foreach($ticket->users as $user)
                {
                    if($user->Rank === 0 && $user->Id !== auth()->user()->Id)
                    {
                        $user->sendMessage($message, ['r' => 255, 'g' => 50, 'b' => 0], route('tickets.index') . '/' . $ticket->Id);
                    }
                }
                break;
            case 'close':
                if (auth()->user()->Rank < 1 && $ticket->UserId !== auth()->user()->Id) {
                    return ['Status' => 'Failed', 'Message' => __('Du bist dazu nicht berechtigt!')];
                }

                $ticket->State = Ticket::TICKET_STATE_CLOSED;
                $ticket->save();

                $answer = new TicketAnswer();
                $answer->TicketId = $ticket->Id;
                $answer->UserId = $userId;
                $answer->MessageType = 1;
                $answer->Message = sprintf("Das Ticket wurde von %s geschlossen", auth()->user()->Name);
                $answer->save();
                event(new \App\Events\TicketUpdated($ticket));
                $message = '[TICKET] Das Ticket #' . $ticket->Id . ' wurde von ' . $name . ' geschlossen!';
                $mtaService->sendMessage('admin', null, $message, ['r' => 255, 'g' => 50, 'b' => 0, 'minRank' => $ticket->AssignedRank]);

                foreach($ticket->users as $user)
                {
                    if($user->Rank === 0 && $user->Id !== auth()->user()->Id)
                    {
                        $user->sendMessage($message, ['r' => 255, 'g' => 50, 'b' => 0], route('tickets.index') . '/' . $ticket->Id);
                    }
                }
                break;
            case 'addUser':
                if ($ticket->users->contains($request->get('newUserId'))) {
                    return ['Status' => 'Failed', 'Message' => __('Dieser Benutzer ist bereits im Ticket!')];
                }

                if (auth()->user()->Rank < 3) {
                    return ['Status' => 'Failed', 'Message' => __('Du bist dazu nicht berechtigt!')];
                }

                $addUser = User::find($request->get('newUserId'));

                if (!$addUser) {
                    return ['Status' => 'Failed', 'Message' => __('Benutzer existiert nicht!')];
                }

                $ticket->users()->attach($addUser->Id, ['JoinedAt' => new Carbon()]);
                $ticket->save();

                $answer = new TicketAnswer();
                $answer->TicketId = $ticket->Id;
                $answer->UserId = $userId;
                $answer->MessageType = 1;
                if(auth()->user()->Id === $addUser->Id) {
                    $answer->Message = sprintf("%s ist dem Ticket beigetreten!", auth()->user()->Name);
                } else {
                    $answer->Message = sprintf("%s hat %s zum Ticket hinzugefügt!", $name, $addUser->Name);
                }
                $answer->save();
                event(new \App\Events\TicketUpdated($ticket));

                if($addUser->Rank === 0)
                {
                    $addUser->sendMessage('[TICKET] ' . $name . ' hat dich zu dem Ticket #' . $ticket->Id . ' hinzugefügt!', ['r' => 255, 'g' => 50, 'b' => 0], route('tickets.index') . '/' . $ticket->Id);
                }
                break;
            case 'removeUser':
                if (!$ticket->users->contains($request->get('removeUserId'))) {
                    return ['Status' => 'Failed', 'Message' => __('Dieser Benutzer ist befindet sich nicht im Ticket!')];
                }

                if (auth()->user()->Rank < 3) {
                    return ['Status' => 'Failed', 'Message' => __('Du bist dazu nicht berechtigt!')];
                }

                $ticket->users()->updateExistingPivot($request->get('removeUserId'), ['LeftAt' => new Carbon()]);
                $ticket->save();

                $answer = new TicketAnswer();
                $answer->TicketId = $ticket->Id;
                $answer->UserId = $userId;
                $answer->MessageType = 1;
                $answer->Message = sprintf("%s hat %s aus dem Ticket entfernt!", auth()->user()->Name, User::find($request->get('removeUserId'))->Name);
                $answer->save();
                event(new \App\Events\TicketUpdated($ticket));
                break;
            case 'assignToUser':
                if (auth()->user()->Rank < 3) {
                    return ['Status' => 'Failed', 'Message' => __('Du bist dazu nicht berechtigt!')];
                }

                $assignUser = User::find($request->get('assignUserId'));

                if (!$assignUser) {
                    return ['Status' => 'Failed', 'Message' => __('Benutzer existiert nicht!')];
                }

                if (!$ticket->users->contains($assignUser->Id)) {
                    $ticket->users()->attach($assignUser->Id, ['JoinedAt' => new Carbon()]);
                    $ticket->save();

                    $answer = new TicketAnswer();
                    $answer->TicketId = $ticket->Id;
                    $answer->UserId = $assignUser->Id;
                    $answer->MessageType = 1;
                    if(auth()->user()->Id === $assignUser->Id) {
                        $answer->Message = sprintf("%s ist dem Ticket beigetreten!", auth()->user()->Name);
                    } else {
                        $answer->Message = sprintf("%s hat %s zum Ticket hinzugefügt!", $name, $assignUser->Name);
                    }
                    $answer->save();
                }

                $ticket->AssigneeId = $assignUser->Id;
                $ticket->save();
                event(new \App\Events\TicketUpdated($ticket));
                break;
            case 'assignToRank':
                if (!$ticket->users->contains($request->get('removeUserId'))) {
                    return ['Status' => 'Failed', 'Message' => __('Dieser Benutzer ist befindet sich nicht im Ticket!')];
                }

                if (auth()->user()->Rank < 3) {
                    return ['Status' => 'Failed', 'Message' => __('Du bist dazu nicht berechtigt!')];
                }

                $ticket->users()->updateExistingPivot($request->get('removeUserId'), ['LeftAt' => new Carbon()]);
                $ticket->save();

                $answer = new TicketAnswer();
                $answer->TicketId = $ticket->Id;
                $answer->UserId = $userId;
                $answer->MessageType = 1;
                $answer->Message = sprintf("%s hat %s aus dem Ticket entfernt!", auth()->user()->Name, User::find($request->get('removeUserId'))->Name);
                $answer->save();
                event(new \App\Events\TicketUpdated($ticket));
                break;
        }

        return $ticket->getApiResponse();
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
