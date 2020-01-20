@component('forum.activities.activity')

  @slot('heading')

    <h5 class="card-title">

        @if( !empty( $user->alias ) )
            {{ ucfirst($user->alias) }}
        @else
            {{ ucfirst($user->name) }}
        @endif

        published

      <a href="/forum{{$activity->subject->path()}}">{{ $activity->subject->title }} </a>

    </h5>

  @endslot

  @slot('body')

    <p class="card-text">{!! $activity->subject->body !!}</p>


    <footer class="blockquote-footer">{{$activity->created_at->diffForHumans()}}</footer>

  @endslot

  @endcomponent()
