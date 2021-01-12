
@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <h1>{{$user->name}}</h1>
  </div>
</div>
<div id="userContainer"></div>      
@endsection
