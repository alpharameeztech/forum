@extends('admin.layouts.app')
@section('title', 'Threads')

@section('page-title')
@yield('title')
@endsection

@section('content')

<div class="">

  <div class="my-2">

    <admin-header :user="{{Auth::user()}}"></admin-header>

  </div>

</div>

@endsection
