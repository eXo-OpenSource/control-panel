@extends('layouts.app')

@section('content')
    <div class="d-flex flex-row align-items-center h-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="clearfix">
                        <h1 class="float-left display-3 mr-4">500</h1>
                        <h4 class="pt-3">Interner Server Fehler</h4>
                        <p class="text-muted">Es ist ein unvorhersehbarer Fehler aufgetreten.</p>
                        @if(app()->bound('sentry') && app('sentry')->getLastEventId())
                            <p class="text-muted">Error ID: {{ app('sentry')->getLastEventId() }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @if(app()->bound('sentry') && app('sentry')->getLastEventId())
        @if(env('SENTRY_JS_DSN') && !env('APP_DEBUG'))
            <script>
                Sentry.showReportDialog({
                    eventId: '{{ app('sentry')->getLastEventId() }}',
                    lang: '{{ app()->getLocale() }}',
                    user: {
                        name: '@if(auth()->user()){{ auth()->user()->Name }}@endif'
                    }
                });
            </script>
        @endif
    @endif
@endsection
