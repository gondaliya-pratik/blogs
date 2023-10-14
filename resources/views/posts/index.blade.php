@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>My Post Listing<a href="{{ route('post.create') }}" class="btn btn-primary float-right">Add Post</a></h1>
                <table class="table table-bordered data-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Title</th>
                            <th>Publish Date</th>
                            <th>Publish Status</th>
                            <th>Total Like</th>
                            <th>Total Comment</th>
                            <th width="130px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" data-backdrop="false" id="deletePost" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Delete Post</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p>Are you sure you want to delete this post?</p>
          </div>
          <input type="hidden" name="postid" id="postid" value="">
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="delete-post btn btn-primary">Yes</button>
          </div>
        </div>
      </div>
    </div>

    <script>
        $(document).ready(function($) {
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('post.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'title', name: 'title'},
                    {data: 'publish_date', name: 'publish_date'},
                    {data: 'is_published', name: 'is_published'},
                    {data: 'total_like', name: 'total_like'},
                    {data: 'total_comment', name: 'total_comment'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });

            $('.delete-post').click(function(){
                var post_id = $('#postid').val();
                console.log(post_id);
                $.ajax({ 
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route('post.delete') }}',
                    data: {"post_id": post_id},
                    type: 'post',
                    success: function(result) {
                        if(result.status === 200) {
                            $('#deletePost').modal('hide');
                            table.ajax.reload(); 
                        }
                    }
                });
            
            });

        });

        function getPostId(postid) {
            $('#postid').val(postid);
        }
        </script>
@endsection