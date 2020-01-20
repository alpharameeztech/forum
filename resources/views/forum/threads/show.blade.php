@extends('layouts.forum')
@section('title', $thread->title . ' | ' . $thread->channel->slug )
@section('page-title') @yield('title')
@endsection

@section('page-content')
    <!-- =============== training-body =============== -->

    <thread-component inline-template :initial-replies-count="{{$thread->replies_count}}" :thread="{{$thread}}">
        <div>
            <div class="row">


                <div class="col-sm-12">
                    <div class="card thread">


                        <div class="card-body">

                            <div class="channelType">
                                <a v-bind:style="[primaryButtonOverrideStyles]"
                                   href="/forum/threads/{{$thread->channel->slug}}"
                                   class="badge badge-primary">{{$thread->channel->slug}}</a>
                            </div>

                            <div class=" row">


                                @if( $thread->creator->avatar != '' && strpos($thread->creator->avatar , 'http') !== false)
                                    <img src="{{ $thread->creator->avatar }}" class="rounded-circle img-fluid"
                                         width="100px"/>
                                @elseif( $thread->creator->avatar != '')

                                    <img src="{{Storage::disk("s3")->url($thread->creator->avatar)}}"
                                         class="rounded-circle img-fluid" width="100px"/>
                                @else
                                    <img src="/img/user-placeholder.jpg" class="rounded-circle img-fluid"
                                         width="100px"/>
                                @endif


                                <div class="col-sm-10">
                                    <h4 class="threadCreatorHeading">{{ ucfirst($thread->creator->name) }} .
                                        <span>{{$thread->created_at->diffForHumans()}}</span></h4>
                                </div>
                            </div>

                        {{--  ======================= viewing the thread ===================================  --}}
                        <!-- row ended -->
                            <div class="row" v-if="! editing" v-cloak>
                                <div class="col-sm-1"></div>

                                <div class="col-sm-11">
                                    <h5 class="card-title alert alert-primary"
                                        v-bind:style="[headingOverrideStyles]">{{$thread->title}}</h5>

                                    <p class="card-text" v-html="form.body"
                                       v-bind:style="[paragraphOverrideStyles]"></p>


                                    <i class="material-icons outline-comment icon-image-preview">
                                        comment
                                    </i>@{{repliesCount}}

                                    <span class="visits"> <i class="material-icons outline-comment icon-image-preview">
                                      visibility
                                      </i> {{ $thread->visits()->count() }}
                                    </span>

                                @auth
                                        <subscribe-thread-component
                                            :active="{{ json_encode($thread->isSubscribedTo) }}"></subscribe-thread-component>
                                    @endauth

                                    {{--  {{$thread->replyCount}}   --}}

                                    @can('update', $thread)
                                        <div class="flex">
                                            {{--  <form action="/forum{{$thread->path()}}" method="PATCH">  --}}
                                            {{--  @csrf {{method_field('DELETE')}}  --}}
                                            <button type="submit" class="editThreadCta btn btn-outline-info" @click=" editing = true">
                                                Edit

                                            </button>
                                            {{--  </form>  --}}
                                        </div>
                                    @endcan


                                    @can('delete', $thread)
                                        <div class="flex">
                                            <form action="/forum{{$thread->path()}}" method="POST">
                                                @csrf {{method_field('DELETE')}}
                                                <button type="submit" class="btn btn-outline-danger">
                                                    Delete

                                                </button>
                                            </form>
                                        </div>
                                    @endcan

                                </div>

                            </div>
                            <!-- row ended -->
                        {{--  ======================= viewing the thread ended ===================================  --}}

                        {{--  ======================= Editing the thread =========================================  --}}
                        <!-- row ended -->
                            <div class="row" v-else v-cloak>
                                <div class="col-sm-1"></div>

                                <div class="col-sm-11">
                                    <h5 class="card-title alert alert-primary">{{$thread->title}}</h5>

                                    <div class="form-group">

                                        <wysiwyg-component name="body" v-model="form.body"
                                                           ></wysiwyg-component>

                                    </div>


                                    {{--  {{$thread->replyCount}}   --}}

                                    @can('update', $thread)
                                        <div class="flex">
                                            {{--  <form action="/forum{{$thread->path()}}" method="PATCH">  --}}
                                            {{--  @csrf {{method_field('DELETE')}}  --}}
                                            <button type="button" class="btn btn-primary" @click="update">
                                                Save

                                            </button>

                                            <button type="submit" class="btn btn-outline-info" @click="cancel">
                                                Cancel

                                            </button>
                                            {{--  </form>  --}}
                                        </div>
                                    @endcan


                                    @can('delete', $thread)
                                        <div class="flex">
                                            <form action="/forum{{$thread->path()}}" method="POST">
                                                @csrf {{method_field('DELETE')}}
                                                <button type="submit" class="btn btn-outline-danger">
                                                    Delete

                                                </button>
                                            </form>
                                        </div>
                                    @endcan

                                </div>

                            </div>
                            <!-- row ended -->
                            {{--  ======================= Editing the thread ended ===================================  --}}

                        </div>
                        <!-- card-body ended -->


                    </div>
                    <!-- card thread ended -->


                </div>

                {{-- =================== thread replies ======================== --}}


                <replies-component
                    {{--  :data="{{ $thread->replies }}"  --}}
                    @removed="repliesCount--"
                    @added="repliesCount++"></replies-component>


            </div>
            <!-- row ended -->

            <div class="row">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif


            </div>


        </div>

    </thread-component>


    <!-- =============== training-body ended=============== -->
@endsection
