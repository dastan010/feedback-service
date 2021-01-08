
@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <h1>{{$user->name}}</h1>
  </div>
</div>
@if ($user->owner == 1)
  <div id="admin"></div>
@endif
@if ($user->owner == 0)  
  <div id="userContainer"></div>    
@endif  
@endsection
