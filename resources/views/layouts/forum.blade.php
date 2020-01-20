<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">

    {!! $themeCssCode !!}

    <link rel="stylesheet" href="{{ mix('/css/vuetify.css') }}">

</head>


<body>
    <div id="app" class="{{  $themeCssClass }}">

        <main class="py-4">

            <div class="row topBar">
                <div class="col-sm-2 newDiscussionDiv">
                    <div class="container">
                        <div class="row">
                            <a v-bind:style="[primaryButtonOverrideStyles]" class="newDiscussionCta btn btn-primary" href="{{ route('new.discussion') }}" role="button">New Discussion</a>
                        </div>
                    </div>
                </div>

                <div class="col-sm-2">

                    <div class="btn-group">
                        <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Latest
                        </button>
                        <div class="dropdown-menu">
{{--                            <ul class="nav flex-column" >--}}

                                <a v-bind:style="[linkOverrideStyles]" class="nav-link " href="/forum/threads" tabindex="-1" >All Threads</a>
                                @auth

                                    <a v-bind:style="[linkOverrideStyles]" class="nav-link " href="/forum/profiles/{{Auth::user()->slug}}" tabindex="-1" >My Profile</a>

                                    <a v-bind:style="[linkOverrideStyles]" class="nav-link " href="/forum/threads?by={{Auth::user()->name}}" tabindex="-1" >My Threads</a>
                                @endauth

                                <a v-bind:style="[linkOverrideStyles]" class="nav-link " href="/forum/threads?fresh=1" tabindex="-1" >No Replies Yet</a>

                                <a v-bind:style="[linkOverrideStyles]" class="nav-link " href="/forum/threads?popular=1" tabindex="-1">Popular All Time</a>

{{--                            </ul>--}}
                        </div>
                    </div>
                    <div class="btn-group">
                        <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" >
                            All
                        </button>
                        <div class="dropdown-menu">
                            @if(!empty($channels))
                                <ul class="nav flex-column">
                                    @foreach ($channels as $channel)
                                        <li class="nav-item">
                                            <a v-bind:style="[linkOverrideStyles]" class="nav-link" href="/forum/threads/{{$channel->slug}}" tabindex="-1" > {{$channel->name}}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                        </div>
                    </div>


                </div>

                <div class="col-sm-6">
                    <div class="row">

{{--                        ========================== adding search component ============================--}}
                        @include("forum.threads.search")
{{--                        ========================== adding search component ============================--}}

                        <div class="col-sm-3">

                            @auth

                                <user-notification-component></user-notification-component>

                            @endauth

                            @auth
                                <div class="dropdown">
                                    <button class="btn userSetting dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {{ ucfirst(Auth::user()->name) }}
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </div>
                                </div>
                            @endauth
                        </div>

                    </div>
                </div>

                <div class="col-sm-2">

                </div>


            </div><!-- row ended -->

            <div class="row">

                <left-navigation inline-template>

                    <div class="col-2">

                    <div class="container">
                    <ul class="nav flex-column" >

                            <a v-bind:style="[linkOverrideStyles]" class="nav-link " href="/forum/threads" tabindex="-1"  >All Threads</a>
                            @auth

                                <a v-bind:style="[linkOverrideStyles]" class="nav-link " href="/forum/profiles/{{Auth::user()->slug}}" tabindex="-1" >My Profile</a>

                                <a v-bind:style="[linkOverrideStyles]" class="nav-link " href="/forum/threads?by={{Auth::user()->name}}" tabindex="-1" >My Threads</a>
                            @endauth

                            <a v-bind:style="[linkOverrideStyles]" class="nav-link " href="/forum/threads?fresh=1" tabindex="-1" >No Replies Yet</a>

                            <a v-bind:style="[linkOverrideStyles]" class="nav-link " href="/forum/threads?popular=1" tabindex="-1" >Popular All Time</a>

                        </ul>
                    </div>
                </div>

                </left-navigation>

                <div class="col-sm-8">

                    <v-app>
                        <flashing-component></flashing-component>
                    </v-app>

                    @yield('page-content')

                    <loading-component></loading-component>
                </div>

                <right-navigation inline-template>

                    <div class="col-sm-2">

                        @if( Auth::check() && ( Auth::user()->isAdmin() || Auth::user()->isPublisher()) )
                            <a v-bind:style="[primaryButtonOverrideStyles]" class="btn btn-primary" href="/admin" role="button">Admin Dashboard</a>
                        @endif

                        <br /><br />

{{--                        @auth--}}
{{--                            <div class="row">--}}
{{--                                <a v-bind:style="[primaryButtonOverrideStyles]" class="btn btn-primary" href="/forum/threads/create" role="button">New Discussion</a>--}}
{{--                            </div>--}}
{{--                        @endauth--}}

                        <br />

                        <ul class="nav flex-column">
                            <h3>Categories</h3>
                            @if(!empty($channels))
                                @foreach ($channels as $channel)

                                    <li class="nav-item">
                                        <a v-bind:style="[linkOverrideStyles]" class="nav-link" href="/forum/threads/{{$channel->slug}}" tabindex="-1" > {{$channel->name}}</a>
                                    </li>

                                @endforeach
                            @endif

                        </ul>

                        <br />

                    <div class="card" style="width: 100%">
                            <div class="card-header">
                                    Trending Threads
                            </div>
                            <ul class="list-group list-group-flush">

                                @foreach ($trending_threads as $thread)

                                    <li class="list-group-item"><a v-bind:style="[linkOverrideStyles]" href="/forum{{$thread->path }}"> {{$thread->title}} </a></li>

                                @endforeach

                            </ul>
                    </div>

                </div>

                </right-navigation>

            </div>
        </main>

        @guest
            <div>
                 <p class="text-center">Already a member? Please <a href="/login">Sign In</a> to participate in this conversation</p>
                    <p class="text-center">Not a member? Please <a href="/register">Sign Up</a> to join the powerful community</p>
            </div>
        @endguest

    </div>

    <script>

        window.App = {!! json_encode([
            'user' => Auth::user(),
            'signedInCheck' => Auth::check()
        ]) !!};

    </script>

    {!! $themeJsCode !!}

</body>
</html>
