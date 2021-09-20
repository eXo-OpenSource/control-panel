<?php

namespace App\Http\Controllers\Api;

use App\Models\TicketBan;
use App\Services\ForumService;
use App\Services\MTAService;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Ticket;
use App\Models\TicketAnswer;
use Exo\TeamSpeak\Responses\TeamSpeakResponse;
use Exo\TeamSpeak\Services\TeamSpeakService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Models\TicketCategory;
use App\Http\Controllers\Controller;

class TicketController extends Controller
{
    protected $forumService;

    protected $teamSpeak;

    public function __construct(ForumService $forumService, TeamSpeakService $teamSpeak)
    {
        $this->forumService = $forumService;
        $this->teamSpeak = $teamSpeak;
    }


    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function index()
    {
        $state = request()->get('state');
        $assignee = request()->get('assignee');
        $categories = request()->get('categories') ?? null;
        $search = request()->get('search');
        $limit = 30;

        if($categories && auth()->user()->Rank >= 1) {
            $categories = explode(',', $categories);
        } else {
            $categories = null;
        }


        if(!in_array($state, ['open', 'both', 'closed'])) {
            $state = 'open';
        }

        if(!in_array($assignee, ['all', 'unassigned', 'assigned', 'me'])) {
            $assignee = 'all';
        }

        $tickets = auth()->user()->tickets()->with('user', 'assignee', 'category', 'resolver');

        if(auth()->user()->Rank >= 1) {
            $tickets = Ticket::with('user', 'assignee', 'category', 'resolver');
            $tickets->where(function (Builder $query) {
                $query->where('AssignedRank', '<=', auth()->user()->Rank)->orWhere('AssignedRank', '=', null);
            });
        } else {
            $tickets->where('IsAdmin', 0);
            $assignee = 'all';
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

        switch($assignee)
        {
            case 'all':
                break;
            case 'unassigned':
                $tickets->where('AssigneeId', null);
                break;
            case 'assigned':
                $tickets->where('AssigneeId', '<>', null);
                break;
            case 'me':
                $tickets->where('AssigneeId', auth()->user()->Id);
                break;
        }

        if($categories) {
            $tickets->whereIn('CategoryId', $categories);
        }

        if($search && $search != '') {
            $categoryIds = TicketCategory::query()->where('Title', 'LIKE', '%' . $search . '%')->get()->pluck('Id');
            $userIds = User::query()->where('Name', 'LIKE', '%' . $search . '%')->get()->pluck('Id');

            $tickets->where(function (Builder $query) use ($search, $userIds, $categoryIds) {
                $query->where('Title', 'LIKE', '%' . $search . '%');
                $query->orWhereIn('UserId', $userIds);
                $query->orWhereIn('AssigneeId', $userIds);
                $query->orWhereIn('CategoryId', $categoryIds);
            });
        }

        $tickets->orderBy('Id', 'DESC');

        $tickets = $tickets->paginate($limit);

        $responseData = [];

        foreach($tickets as $ticket) {
            $entry = [
                'Id' => $ticket->Id,
                'UserId' => $ticket->UserId,
                'User' => $ticket->user ? $ticket->user->Name : __('Unbekannt'),
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
                $entry['Resolver'] = $ticket->resolver->Name;
            }


            array_push($responseData, (object)$entry);
        }

        return [
            'items' => $responseData,
            'perPage' => $tickets->perPage(),
            'currentPage' => $tickets->currentPage(),
            'lastPage' => $tickets->lastPage(),
            'settings' => [
                'display' => auth()->user()->TicketDisplay
            ]
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

        if(!$category) {
            return response()->json(['Status' => 'Failed', 'Message' => __('Bitte wähle eine Kategorie aus!')])->setStatusCode(400);
        }

        if(auth()->user()->isBanned() !== false) {
            if($category->IsAllowedForBannedUsers !== 1) {
                return response()->json(['Status' => 'Failed', 'Message' => __('Aufgrund deiner Sperre kannst du kein Ticket von dieser Kategorie erstellen!')])->setStatusCode(400);
            }

            if (Ticket::where('UserId', auth()->user()->Id)->where('CreatedAt', '>=', Carbon::now()->subDay())->count() >= 1) {
                return response()->json(['Status' => 'Failed', 'Message' => __('Aufgrund deiner Sperre kannst du nur ein Ticket innerhalb von 24h erstellen!')])->setStatusCode(400);
            }
        }

        $ticketBan = TicketBan::where('UserId', auth()->user()->Id)->where('BannedUntil', '>=', Carbon::now())->orderBy('BannedUntil', 'DESC')->first();
        if ($ticketBan !== null) {
            return response()->json(['Status' => 'Failed', 'Message' => __('Du wurdest bis zum :date mit dem Grund ":reason" aus dem Ticketsystem ausgeschlossen!', ['date' => $ticketBan->BannedUntil->format('d.m.Y H:m:s'), 'reason' => $ticketBan->Reason])])->setStatusCode(400);
        }

        $fields = $request->get('fields');

        if(empty($request->get('title')) || !is_string($request->get('title'))
                || $request->get('title') === '' || str_replace(' ', '', $request->get('title')) === '') {
            return response()->json(['Status' => 'Failed', 'Message' => __('Bitte gib eine Titel ein!')])->setStatusCode(400);
        }

        $text = [];
        $textInternal = [];

        $addUsers = [];
        $addUserIds = [];
        $addAdminIds = [];

        foreach($category->fields()->orderBy('Order', 'ASC')->get() as $field) {
            $found = false;
            foreach($fields as $key => $value) {
                if('field' . $field->Id === $key) {
                    if($field->Required === 1 && $value === '') {
                        return response()->json(['Status' => 'Failed', 'Message' => __('Das Feld ":name" muss ausgefüllt sein!', ['name' => $field->Name])])->setStatusCode(400);
                    }
                    if($field->Type === 'internal') {
                        if(strlen($value) > $field->MaxLength) {
                            return response()->json(['Status' => 'Failed', 'Message' => __('Das Feld ":name" ist zu lang! Maximal sind :maxLength Zeichen zulässig!', ['name' => $field->Name, 'maxLength' => $field->MaxLength])])->setStatusCode(400);
                        }
                        array_push($textInternal, $field->Name . ': ' . $value);
                    } elseif($field->Type === 'checkbox') {
                        $data = json_decode($field->Data);
                        if ($value === 'on') {
                            if ($data && isset($data[0])) {
                                array_push($text, $field->Name . ': ' . __($data[0]));
                            } else {
                                array_push($text, $field->Name . ': ' . __('Ja'));
                            }
                        } else {
                            if ($data && isset($data[1])) {
                                array_push($text, $field->Name . ': ' . __($data[1]));
                            } else {
                                array_push($text, $field->Name . ': ' . __('Nein'));
                            }
                        }
                    } elseif($field->Type === 'uuid') {
                        $client = $this->teamSpeak->getDatabaseIdFromUniqueId($value);
                        if($client->status !== TeamSpeakResponse::RESPONSE_SUCCESS) {
                            return response()->json(['Status' => 'Failed', 'Message' => __('Diese eindeutige ID ist dem TeamSpeak Server nicht bekannt. Du musst zuerst einmalig auf dem TeamSpeak verbinden.')])->setStatusCode(400);
                        }
                        array_push($text, $field->Name . ': ' . $value);
                    } elseif($field->Type === 'user') {
                        $user = User::find($value);

                        if ($user == null && $field->Required) {
                            return response()->json(['Status' => 'Failed', 'Message' => __('Das Feld ":name" muss ausgefüllt sein!', ['name' => $field->Name])])->setStatusCode(400);
                        }

                        if (!in_array($user->Id, $addUserIds)) {
                            array_push($text, $field->Name . ': ' . $user->Name);
                            array_push($addUsers, $user);
                            array_push($addUserIds, $user->Id);
                        }
                    } elseif($field->Type === 'users') {
                        $maxUsers = intval($field->Data) ?? -1;
                        $users = 0;
                        $names = [];
                        foreach($value as $userId) {
                            $user = User::find($userId);

                            if ($user == null && $field->Required) {
                                return response()->json(['Status' => 'Failed', 'Message' => __('Das Feld ":name" muss ausgefüllt sein!', ['name' => $field->Name])])->setStatusCode(400);
                            }

                            if (!in_array($user->Id, $addUserIds)) {
                                $users++;
                                array_push($names, $user->Name);
                                array_push($addUsers, $user);
                                array_push($addUserIds, $user->Id);
                            }
                        }

                        if($users > $maxUsers && $maxUsers !== -1) {
                            return response()->json(['Status' => 'Failed', 'Message' => __('Beim Feld ":name" können maximal :count Benutzer hinzugefügt werden!', ['name' => $field->Name, 'count' => $maxUsers])])->setStatusCode(400);
                        }

                        array_push($text, $field->Name . ': ' . implode(', ', $names));
                    } elseif($field->Type === 'admins') {
                        $maxUsers = intval($field->Data) ?? -1;
                        $users = 0;
                        $names = [];
                        foreach($value as $userId) {
                            $user = User::find($userId);

                            if ($user == null && $field->Required) {
                                return response()->json(['Status' => 'Failed', 'Message' => __('Das Feld ":name" muss ausgefüllt sein!', ['name' => $field->Name])])->setStatusCode(400);
                            }
                            if (!in_array($user->Id, $addUserIds)) {
                                $users++;
                                array_push($names, $user->Name);
                                array_push($addUsers, $user);
                                array_push($addUserIds, $user->Id);
                                array_push($addAdminIds, $user->Id);
                            }
                        }

                        if($users > $maxUsers && $maxUsers !== -1) {
                            return response()->json(['Status' => 'Failed', 'Message' => __('Beim Feld ":name" können maximal :count Benutzer hinzugefügt werden!', ['name' => $field->Name, 'count' => $maxUsers])])->setStatusCode(400);
                        }

                        array_push($text, $field->Name . ': ' . implode(', ', $names));
                    } else {
                        if(strlen($value) > $field->MaxLength) {
                            return response()->json(['Status' => 'Failed', 'Message' => __('Das Feld ":name" ist zu lang! Maximal sind :maxLength Zeichen zulässig!', ['name' => $field->Name, 'maxLength' => $field->MaxLength])])->setStatusCode(400);
                        }
                        array_push($text, $field->Name . ': ' . $value);
                    }
                    $found = true;
                    break;
                }
            }

            if(!$found) {
                if($field->Required === 1) {
                    return response()->json(['Status' => 'Failed', 'Message' => __('Das Feld ":name" muss ausgefüllt sein!', ['name' => $field->Name])])->setStatusCode(400);
                } else {
                    if($field->Type === 'checkbox') {
                        $data = json_decode($field->Data);
                        if($data && isset($data[1])) {
                            array_push($text, $field->Name . ': ' . __($data[1]));
                        } else {
                            array_push($text, $field->Name . ': ' . __('Nein'));
                        }
                    }
                }
            }
        }

        if(count($text) === 0 && (empty($request->get('message')) || !is_string($request->get('message'))
                || $request->get('message') === '' || str_replace(' ', '', $request->get('message')) === '')) {
            return response()->json(['Status' => 'Failed', 'Message' => __('Bitte gib eine Nachricht ein!')])->setStatusCode(400);
        }

        $userId = auth()->user()->Id;
        $user = auth()->user();
        $createdFor = false;

        if (!empty($request->get('createFor'))) {
            if (auth()->user()->Rank >= 1) {
                $user = User::find($request->get('createFor'));
                if ($user === null) {
                    return response()->json(['Status' => 'Failed', 'Message' => __('Der Benutzer existiert nicht!')])->setStatusCode(400);
                }
                $userId = $user->Id;
                $createdFor = true;

                if (!in_array(auth()->user()->Id, $addUserIds)) {
                    array_push($addUsers, auth()->user());
                }
                array_push($addAdminIds, auth()->user()->Id);

                array_unshift($text, __(':name hat dieses Ticket für :target erstellt.', ['name' => auth()->user()->Name, 'target' => $user->Name]));
            }
        }

        $ticket = new Ticket();
        $ticket->UserId = $userId;
        $ticket->CategoryId = $category->Id;
        $ticket->AssignedRank = $category->AdminRank;
        $ticket->Title = $request->get('title');
        $ticket->State = Ticket::TICKET_STATE_OPEN;

        if ($createdFor) {
            $ticket->AssigneeId = auth()->user()->Id;

            if ($request->get('closeTicket')) {
                $ticket->ResolvedBy = auth()->user()->Id;
                $ticket->ResolvedAt = Carbon::now();
                $ticket->State = Ticket::TICKET_STATE_CLOSED;
            }
        }

        $ticket->save();

        $ticket->users()->attach($user, ['JoinedAt' => new Carbon(), 'IsAdmin' => 0]);

        if(count($text) > 0) {
            $answer = new TicketAnswer();
            $answer->TicketId = $ticket->Id;
            $answer->UserId = auth()->user()->Id;
            $answer->MessageType = 1;
            $answer->Message = implode(chr(0x0A), $text);
            $answer->save();
        }

        if (count($textInternal) > 0)
        {
            $answer = new TicketAnswer();
            $answer->TicketId = $ticket->Id;
            $answer->UserId = auth()->user()->Id;
            $answer->MessageType = 2;
            $answer->Message = implode(chr(0x0A), $textInternal);
            $answer->save();
        }

        $addedNames = [];
        foreach($addUsers as $user) {
            if (!$ticket->users->contains($user)) {
                if ($user->Id !== auth()->user()->Id) {
                    array_push($addedNames, $user->Name);
                }
                $isAdmin = in_array($user->Id, $addAdminIds) ? 1 : 0;
                $ticket->users()->attach($user, ['JoinedAt' => new Carbon(), 'IsAdmin' => $isAdmin]);

                if($user->Rank === 0 && !$createdFor) {
                    $user->sendMessage('[TICKET] ' . auth()->user()->Name . ' hat dich zu dem Ticket #' . $ticket->Id . ' hinzugefügt!', ['r' => 255, 'g' => 50, 'b' => 0], route('tickets.index') . '/' . $ticket->Id);
                }
            }
        }

        if(count($addedNames) > 0) {
            $answer = new TicketAnswer();
            $answer->TicketId = $ticket->Id;
            $answer->UserId = auth()->user()->Id;
            $answer->MessageType = 1;
            $answer->Message = sprintf("%s wurde(n) zum Ticket hinzugefügt von %s.", implode(', ', $addedNames), auth()->user()->Name);
            $answer->save();
        }

        if ($createdFor) {
            $answer = new TicketAnswer();
            $answer->TicketId = $ticket->Id;
            $answer->UserId = auth()->user()->Id;
            $answer->MessageType = 1;
            $answer->Message = __(":name hat sich das Ticket selbst zugewiesen.", ['name' => auth()->user()->Name]);
            $answer->save();
        }

        if(!empty($request->get('message'))) {
            $answer = new TicketAnswer();
            $answer->TicketId = $ticket->Id;
            $answer->UserId = auth()->user()->Id;
            $answer->MessageType = 0;
            $answer->Message = $request->get('message');
            $answer->save();
        }

        if ($createdFor) {
            if ($request->get('closeTicket')) {
                $answer = new TicketAnswer();
                $answer->TicketId = $ticket->Id;
                $answer->UserId = auth()->user()->Id;
                $answer->MessageType = 1;
                $answer->Message = sprintf("Das Ticket wurde von %s geschlossen", auth()->user()->Name);
                $answer->save();
            }
        }

        if (!$createdFor) {
            event(new \App\Events\TicketCreated($ticket));
            $mtaService = new MTAService();
            $mtaService->sendMessage('admin', null, '[TICKET] Es wurde ein neues Ticket von ' . $ticket->user->Name . ' (' . $ticket->category->Title .') erstellt!', ['r' => 255, 'g' => 50, 'b' => 0, 'minRank' => $ticket->AssignedRank]);
        }

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
     * @return array|\Illuminate\Http\JsonResponse|object
     */
    public function update(Request $request, Ticket $ticket)
    {
        abort_unless(auth()->user()->can('update', $ticket), 403);

        $mtaService = new MTAService();

        $type = $request->get('type');
        $userId = auth()->user()->Id;
        $name = auth()->user()->Name;

        if($ticket->State === Ticket::TICKET_STATE_CLOSED && $type !== 'delete' && $type !== 'open') {
            return response()->json(['Status' => 'Failed', 'Message' => __('Das Ticket ist geschlossen!')])->setStatusCode(400);
        }

        switch($type)
        {
            case 'addMessage':
                if (empty($request->get('message')) || !is_string($request->get('message'))
                    || $request->get('message') === '' || str_replace(' ', '', $request->get('message')) === '') {
                    return response()->json(['Status' => 'Failed', 'Message' => __('Bitte gib eine Nachricht ein!')])->setStatusCode(400);
                }

                if (!$ticket->users->contains($userId)) {
                    $ticket->users()->attach($userId, ['JoinedAt' => new Carbon(), 'IsAdmin' => auth()->user()->Rank > 0 ? 1 : 0]);
                    $ticket->save();

                    $answer = new TicketAnswer();
                    $answer->TicketId = $ticket->Id;
                    $answer->UserId = $userId;
                    $answer->MessageType = 1;
                    $answer->Message = sprintf("%s ist dem Ticket beigetreten.", auth()->user()->Name);
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
                    return response()->json(['Status' => 'Failed', 'Message' => __('Du bist dazu nicht berechtigt!')])->setStatusCode(400);
                }

                $ticket->State = Ticket::TICKET_STATE_CLOSED;
                $ticket->ResolvedBy = auth()->user()->Id;
                $ticket->ResolvedAt = Carbon::now();
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
            case 'open':
                if (auth()->user()->Rank < 3) {
                    return response()->json(['Status' => 'Failed', 'Message' => __('Du bist dazu nicht berechtigt!')])->setStatusCode(400);
                }

                $ticket->State = Ticket::TICKET_STATE_OPEN;
                $ticket->ResolvedBy = null;
                $ticket->ResolvedAt = null;
                $ticket->save();

                $answer = new TicketAnswer();
                $answer->TicketId = $ticket->Id;
                $answer->UserId = $userId;
                $answer->MessageType = 1;
                $answer->Message = sprintf("Das Ticket wurde von %s geöffnet", auth()->user()->Name);
                $answer->save();
                event(new \App\Events\TicketUpdated($ticket));
                $message = '[TICKET] Das Ticket #' . $ticket->Id . ' wurde von ' . $name . ' geöffnet!';
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
                    $data = $ticket->users()->find($request->get('newUserId'));

                    if($data->pivot->LeftAt !== null) {
                        $ticket->users()->updateExistingPivot($request->get('newUserId'), ['LeftAt' => null]);
                        $ticket->save();

                        $addUser = User::find($request->get('newUserId'));

                        $answer = new TicketAnswer();
                        $answer->TicketId = $ticket->Id;
                        $answer->UserId = $userId;
                        $answer->MessageType = 1;
                        if(auth()->user()->Id === $addUser->Id) {
                            $answer->Message = sprintf("%s ist dem Ticket wieder beigetreten.", auth()->user()->Name);
                        } else {
                            $answer->Message = sprintf("%s hat %s wieder zum Ticket hinzugefügt.", $name, $addUser->Name);
                        }
                        $answer->save();
                        event(new \App\Events\TicketUpdated($ticket));

                        if($addUser->Rank === 0)
                        {
                            $addUser->sendMessage('[TICKET] ' . $name . ' hat dich zu dem Ticket #' . $ticket->Id . ' hinzugefügt!', ['r' => 255, 'g' => 50, 'b' => 0], route('tickets.index') . '/' . $ticket->Id);
                        }
                    }
                    else
                    {
                        return response()->json(['Status' => 'Failed', 'Message' => __('Dieser Benutzer ist bereits im Ticket!')])->setStatusCode(400);
                    }
                }

                if (auth()->user()->Rank < 1) {
                    return response()->json(['Status' => 'Failed', 'Message' => __('Du bist dazu nicht berechtigt!')])->setStatusCode(400);
                }

                $addUser = User::find($request->get('newUserId'));

                if (!$addUser) {
                    return response()->json(['Status' => 'Failed', 'Message' => __('Benutzer existiert nicht!')])->setStatusCode(400);
                }

                $ticket->users()->attach($addUser->Id, ['JoinedAt' => new Carbon(), 'IsAdmin' => $addUser->Rank > 0 ? 1 : 0]);
                $ticket->save();

                $answer = new TicketAnswer();
                $answer->TicketId = $ticket->Id;
                $answer->UserId = $userId;
                $answer->MessageType = 1;
                if(auth()->user()->Id === $addUser->Id) {
                    $answer->Message = sprintf("%s ist dem Ticket beigetreten.", auth()->user()->Name);
                } else {
                    $answer->Message = sprintf("%s hat %s zum Ticket hinzugefügt.", $name, $addUser->Name);
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
                    return response()->json(['Status' => 'Failed', 'Message' => __('Dieser Benutzer ist befindet sich nicht im Ticket!')])->setStatusCode(400);
                }

                if (auth()->user()->Rank < 3) {
                    return response()->json(['Status' => 'Failed', 'Message' => __('Du bist dazu nicht berechtigt!')])->setStatusCode(400);
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
                if (auth()->user()->Rank < 1) {
                    return response()->json(['Status' => 'Failed', 'Message' => __('Du bist dazu nicht berechtigt!')])->setStatusCode(400);
                }

                $assignUser = User::find($request->get('assignUserId'));
                $joinMessage = false;

                if (!$assignUser) {
                    return response()->json(['Status' => 'Failed', 'Message' => __('Benutzer existiert nicht!')])->setStatusCode(400);
                }

                if (!$ticket->users->contains($assignUser->Id)) {
                    $ticket->users()->attach($assignUser->Id, ['JoinedAt' => new Carbon(), 'IsAdmin' => $assignUser->Rank > 0 ? 1 : 0]);
                    $ticket->save();

                    $joinMessage = true;
                }

                $answer = new TicketAnswer();
                $answer->TicketId = $ticket->Id;
                $answer->UserId = auth()->user()->Id;
                $answer->MessageType = 1;
                if(auth()->user()->Id === $assignUser->Id) {
                    if($joinMessage) {
                        $answer->Message = __(":name ist dem Ticket beigetreten und hat sich das Ticket selbst zugewiesen.", ['name' => auth()->user()->Name]);
                    } else {
                        $answer->Message = __(":name hat sich das Ticket selbst zugewiesen.", ['name' => auth()->user()->Name]);
                    }
                } else {
                    if($joinMessage) {
                        $answer->Message = __(":name2 wurde von :name dem Ticket hinzugefügt und zugewiesen.", ['name' => auth()->user()->Name, 'name2' => $assignUser->Name]);
                    } else {
                        $answer->Message = __(":name hat das Ticket :name2 zugewiesen.", ['name' => auth()->user()->Name, 'name2' => $assignUser->Name]);
                    }
                }
                $answer->save();

                $ticket->AssigneeId = $assignUser->Id;
                $ticket->save();
                event(new \App\Events\TicketUpdated($ticket));
                break;
            case 'assignToRank':
                if (!$ticket->users->contains($request->get('removeUserId'))) {
                    return response()->json(['Status' => 'Failed', 'Message' => __('Dieser Benutzer ist befindet sich nicht im Ticket!')])->setStatusCode(400);
                }

                if (auth()->user()->Rank < 3) {
                    return response()->json(['Status' => 'Failed', 'Message' => __('Du bist dazu nicht berechtigt!')])->setStatusCode(400);
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
            case 'delete':
                if (auth()->user()->Rank < 7) {
                    return response()->json(['Status' => 'Failed', 'Message' => __('Du bist dazu nicht berechtigt!')])->setStatusCode(400);
                }

                $ticket->delete();
                return '';
                break;

            default:
                return response()->json(['Status' => 'Failed', 'Message' => __('Unbekannte Funktion!')])->setStatusCode(400);
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
