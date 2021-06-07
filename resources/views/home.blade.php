@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Home</div>

                <div class="card-body">
                    <div class="d-flex">
                    </div>

                    <div>
                        <h3>Questions</h4>

                            @if(count($questions) == 0)
                            <p>No questions yet</p>
                            @endif

                            <ul>
                                @for($i = count($questions) - 1; $i >= 0; $i--)
                                <li><a href="/question/{{ $questions[$i]->id }}">{{ $questions[$i]->title }}</a> @ {{ $questions[$i]->created_at }}</li>
                                @endfor
                            </ul>
                    </div>

                    <div>
                        <h3>Users</h4>

                            @if(count($users) == 0)
                            <p>No users yet</p>
                            @endif

                            <ul>
                                @for($i = count($users) - 1; $i >= 0; $i--)
                                <li><a href="/profile/{{ $users[$i]->id }}">{{ $users[$i]->name }}</a></li>
                                @endfor
                            </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
