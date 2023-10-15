@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @foreach($posts as $item)
                <div class="card mb-3">
                  <img src="{{ url($item->image) }}" width="150px" height="200px" class="card-img-top" alt="{{ $item->title }}">
                  <div class="card-body">
                    <h5 class="card-title">{{ $item->title }}</h5>
                    <p class="card-text">{!! $item->description !!}</p>
                    <p class="card-text">
                        <small class="text-muted">{{date("d M, Y", strtotime($item->publish_date))}}</small>
                        <button data-post-id="{{$item->id}}" type="button" class="likes float-right btn btn-primary">Like <span id="likes_{{$item->id}}" class="badge bg-secondary">{{$item->likes_count}}</span>
                        </button>
                    </p>
                    
                    @include('commentsDisplay', ['comments' => $item->comments, 'post_id' => $item->id])
                    
                    <p class="card-text">Add comment</p>
                        <div class="form-group">
                            <input type="text" class="form-control" name="comment" id="comment_{{$item->id}}" placeholder="Enter comment">
                        </div>
                        <button data-post-id="{{$item->id}}" type="button" class="comments-btn float-right btn btn-primary">Submit</button>
                  </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
<script>

    $(document).ready(function () {
        $('.likes').click(function(){
            var post_id = $(this).data("post-id");
            $.ajax({ 
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('blog.like') }}',
                data: {"post_id": post_id},
                type: 'post',
                success: function(result) {
                    if(result.status === 200) {
                        $('#likes_'+post_id).text(result.data);
                    }
                }
            });
        });

        $('.comments-btn').click(function(){
            var post_id = $(this).data("post-id");
            var comment = $('#comment_'+post_id).val();
            if(comment != '') {
                $.ajax({ 
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route('blog.comment') }}',
                    data: {"post_id": post_id, "comment": comment},
                    type: 'post',
                    success: function(result) {
                        if(result.status === 200) {
                            $('#comment_'+post_id).val('');
                            var html = ''
                            $(".comment-section").append(html);
                        }
                    }
                });
            }
        });

        $('.replay-btn').click(function(){
            var parent_id = $(this).data("id");
            var formData = $("#reply-form-"+parent_id).serializeArray();
            if(formData[0].value != ''){    
                $.ajax({ 
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route('blog.comment') }}',
                    data: formData,
                    type: 'post',
                    success: function(result) {
                        if(result.status === 200) {
                            $('#comment-'+parent_id).val('');
                            
                        }
                    }
                });
            }
        });
        $(".forms").keypress(function(event) {
            if (event.which === 13) {
                event.preventDefault();
            }
        });
    });
</script>
@endsection
