@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>Edit Post</h1>

                <form id="post-form" action="{{ route('post.update',$post->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('patch')
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" id="title" placeholder="Enter title" value="{{ $post->title }}">
                        @error('title')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" class="form-control  @error('description') is-invalid @enderror" rows="10" placeholder="Enter description">{{ $post->description }}</textarea>
                        @error('description')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="image">Image</label>
                        <img src="{{ url($post->image) }}" width="150px" height="150px">
                        <input type="file" class="form-control  @error('image') is-invalid @enderror" name="image" id="image">
                        @error('image')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                          <input class="form-check-input @error('is_published') is-invalid @enderror" type="checkbox" value="1" name="is_published" id="is_published" {{ ($post->is_published) ? 'checked' : '' }}>
                          <label class="form-check-label" for="is_published">
                            Publish
                          </label>
                        </div>
                        @error('is_published')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('#description').summernote({
                height: 350
            });
            $("#post-form").validate({
                rules: {
                    title: {
                        required: true,
                    },
                    description: {
                        required: true,
                    },
                },
                messages: {
                    title: {
                        required: "The title is required.",
                    },
                    description: {
                        required: "The description is required.",
                    },
                },
                submitHandler: function (form) {
                    // Here, you can perform actions when the form is valid and submitted
                    form.submit(); // Submit the form
                }
            });
        });
    </script>
@endsection