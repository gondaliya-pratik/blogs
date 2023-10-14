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
                    <p class="card-text">{{ $item->description }}</p>
                    <p class="card-text">
                        <small class="text-muted">{{date("d M, Y", strtotime($item->publish_date))}}</small>
                        <button data-post-id="{{$item->id}}" type="button" class="likes float-right btn btn-primary">Like <span id="likes_{{$item->id}}" class="badge bg-secondary">{{$item->likes_count}}</span>
                        </button>
                    </p>
                    
                    <div class="row">
                        <div class="col-md-12 comment-section">
                    @foreach($item->comments as $comment)
                            <div class="media">
                                <img width="50px" class="mr-3 rounded-circle" alt="Bootstrap Media Preview" src="https://images.unsplash.com/photo-1529665253569-6d01c0eaf7b6?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NHx8cHJvZmlsZXxlbnwwfHwwfHx8MA%3D%3D&w=1000&q=80" />
                                <div class="media-body">
                                    <div class="row">
                                         <div class="col-8 d-flex">
                                            <h5>Maria Smantha</h5>
                                            <span>- 2 hours ago</span>
                                         </div>
                                         <div class="col-4">
                                         <div class="pull-right reply">
                                            <a href="#"><span><i class="fa fa-reply"></i> reply</span></a>
                                         </div>
                                     </div>
                                    </div>      
                                    
                                    {{$comment->comment}}                                    
                                    
                                </div>
                            </div>
                    @endforeach
                        </div>
                    </div>
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
                            var html = '<div class="media"><img width="50px" class="mr-3 rounded-circle" alt="Bootstrap Media Preview" src="https://images.unsplash.com/photo-1529665253569-6d01c0eaf7b6?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NHx8cHJvZmlsZXxlbnwwfHwwfHx8MA%3D%3D&w=1000&q=80" /><div class="media-body"><div class="row"><div class="col-8 d-flex"><h5>Maria Smantha</h5><span>- 2 hours ago</span></div><div class="col-4"><div class="pull-right reply"><a href="#"><span><i class="fa fa-reply"></i> reply</span></a></div></div></div>'+result.data.comment+'</div></div>'
                            $(".comment-section").append(html);
                        }
                    }
                });
            }
        });
    });
</script>
@endsection
