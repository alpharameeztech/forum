@extends('layouts.forum')
@section('title', 'Create a Discussion Thread')

@section('page-title')
  @yield('title')
@endsection
@section('page-content')
           <!-- =============== create thread =============== -->
           <script src='https://www.google.com/recaptcha/api.js'></script>

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
      
                <form class="col-lg-12" action="/forum/threads" method="POST" >
                  @csrf
                  @method('post')

                    <div class="form-group">
                      <label for="title">Title</label>
                      <input type="text" class="form-control"  placeholder="Add a title" name="title" required>
                    
                    </div>
                    <div class="form-group">
                        <label for="body">Discuss</label>
                        {{--  <textarea required class="form-control" id="exampleFormControlTextarea1" rows="8" placeholder="What's on your mind?" name="body"></textarea>
                        
                        --}}

                        <wysiwyg-component name="body"></wysiwyg-component>
                        
                    </div>

                    <div class="form-group">
                     
                      <select class="form-control form-control-lg" name="channel" required>
                        <option value="">Select a channel</option>
                          @foreach ($channels as $channel)
                            <option value="{{$channel->id}}">{{$channel->slug}}</option>
                          @endforeach
                       
                      </select>
                    </div>
                    
                    <div class="g-recaptcha" data-sitekey="6Lc6Np4UAAAAAFh30asYcWo68c-uUNAmwSaPVgZz">


                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>

            </div>
          <!-- =============== create thread ended=============== -->
@endsection
