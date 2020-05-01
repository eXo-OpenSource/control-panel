@section('title', __('Schulungen') . ' - '. $user->Name)
@can('trainings', $user)
    @php
        $trainings = \App\Models\Training\TrainingUser::query()->where('UserId', $user->Id)->where('Role', 0)->with('training')->with('training.user')->orderBy('CreatedAt', 'DESC')->get();


        $targets = auth()->user()->character->getTrainingTargets();
    @endphp
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            {{ __('Schulungen') }}
                        </div>
                        <div class="card-body">
                            <table class="table table-sm table-responsive-sm">
                                <thead>
                                <tr>
                                    <th scope="col">{{ __('Name') }}</th>
                                    <th scope="col">{{ __('Ausbilder') }}</th>
                                    <th scope="col">{{ __('Datum') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($trainings as $training)
                                    @if(
                                        auth()->user()->Rank >= 3 ||
                                        ($training->training->ElementType === 2 && $training->training->ElementId === $user->character->FactionId && in_array('faction', $targets)) ||
                                        ($training->training->ElementType === 3 && $training->training->ElementId === $user->character->CompanyId && in_array('company', $targets))
                                    )
                                    <tr>
                                        <td><a href="/trainings/{{ $training->training->Id }}">{{ $training->training->Name }}</a></td>
                                        <td>{{ $training->training->user->Name }}</td>
                                        <td>{{ $training->training->CreatedAt }}</td>
                                    </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endcan
