@extends('dashboard.index')
@section('title', Helper::getSiteTitle('Add Articles'))

@section('css-lib')
    {{-- <link href="{{ asset('assets/libs/quill/quill.core.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/quill/quill.snow.css') }}" rel="stylesheet" type="text/css" /> --}}
    <link href="{{ asset('assets/libs/dropify/css/dropify.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/dropzone/min/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/mohithg-switchery/switchery.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/summernote/summernote-lite.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('articles') }}">Articles</a></li>
                        <li class="breadcrumb-item active">Add Articles</li>
                    </ol>
                </div>
                <h4 class="page-title">Add Articles</h4>
            </div>
        </div>
    </div>
	
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <form id="article_form" class="w-100 p-0 m-0 " @if(isset(request()->id)) action="{{ route('articles.edit', ['id' => request()->id, 'type' => 'dashboard']) }}" @else action="{{ route('articles.add', 'dashboard') }}" @endif method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="alert alert-success ajax_response_success d-none"></div>
                        <div class="alert alert-danger ajax_response_error d-none"></div>
                        
                        <div class="row">
                            <div class="col-sm-12 d-flex justify-content-between">
                                <h3>ADD ARTICLE</h3>
                                <div >
                                    @php $index = 'status' @endphp
                                    <label for="{{ $index }}" class="form-label h4">Publish :</label>
                                    <input type="checkbox" id="{{ $index }}" name="{{ $index }}" value="1" @if(isset($articles->status)) {{$articles->status === 1 ? 'checked' : '' }} @endif data-plugin="switchery" data-color="#1bb99a" />
                                </div>
                            </div>
                        </div>
                    
                        <div class="row mt-2">
                            <div class="col-sm-6">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            @php $index = 'title'; @endphp
                                            <label for="{{ $index }}" class="form-label">Title :</label>
                                            <input type="text" class="form-control" id="{{ $index }}" name="{{ $index }}" @if(isset($articles->title)) value="{{ $articles->title }}" @endif placeholder="Add article title ..."/>
                                            <span class="text-danger">
                                                @error($index)
                                                {{ $message }}
                                                @enderror
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            @php $index = 'slug'; @endphp
                                            <label for="{{ $index }}" class="form-label">Slug :</label>
                                            <input type="text" class="form-control" id="{{ $index }}" name="{{ $index }}" @if(isset($articles->slug)) value="{{ $articles->slug }}" @endif placeholder="Add article slug ..."/>
                                            <span class="text-danger">
                                                @error($index)
                                                {{ $message }}
                                                @enderror
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            @php $index = 'magazine_id[]'; @endphp
                                            <label for="{{ $index }}" class="form-label">Magazines :</label>
                                            <select class="form-control select2-multiple" id="{{ $index }}" name="{{ $index }}" data-toggle="select2" data-width="100%" multiple="multiple" data-placeholder="Choose ...">
                                                <option></option>
                                                @if (isset($magazines) && !empty($magazines))
                                                    @foreach ($magazines as $magazine)
                                                        <option value="{{ $magazine->id }}" @if(isset($magazine_id)) {{ $magazine_id->contains($magazine->id) ? 'selected' : ''}} @endif>{{ $magazine->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <span class="text-danger">
                                                @error($index)
                                                {{ $message }}
                                                @enderror
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            @php $index = 'content'; @endphp
                                            <label for="{{ $index }}" class="form-label">Content :</label>
                                            <textarea id="textarea" class="summernote" name="{{ $index }}" placeholder="Add content here ....">@if(isset($articles->content)) {!! $articles->content !!} @endif</textarea>
                                            <span class="text-danger">
                                                @error($index)
                                                {{ $message }}
                                                @enderror
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            
                            <div class="col-sm-6">
                                <div>
                                    <h4 class="bg-light p-1">Featured Image</h4>
                                    @php $index = 'featured_image'; @endphp
                                    <input type="file" id="{{ $index }}" name="{{ $index }}" @if(isset($featured_image)) data-default-file="{{ asset('storage/'.$featured_image) }}" @endif  data-plugins="dropify" data-height="150" />
                                    <span class="text-danger">
                                        @error($index)
                                        {{ $message }}
                                        @enderror
                                    </span>
                                    
                                </div>
                                <div>
                                    <h4 class="bg-light p-1">Featured Video</h4>
                                    @php $index = 'featured_video'; @endphp
                                    <input type="file" id="{{ $index }}" name="{{ $index }}" @if(isset($featured_video)) data-default-file="{{ asset('storage/'.$featured_video) }}" @endif data-plugins="dropify" data-height="150" />
                                    <span class="text-danger">
                                        @error($index)
                                        {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                                <div>
                                    <h4 class="bg-light p-1">Multiple Images</h4>
                                    @php $index = 'images[]'; @endphp
                                    <input type="file" id="{{ $index }}" name="{{ $index }}" multiple data-plugins="dropify" data-height="150" />
                                    <span class="text-danger">
                                        @error($index)
                                        {{ $message }}
                                        @enderror
                                    </span>
                                    @if (isset($images) && !empty($images))
                                        @foreach($images as $path)
                                        
                                        <div class="card product-box d-inline-block p-0 m-0" style="height:90px; width: 120px;">
                                            <div class="card-body">
                                                <div class="product-action">
                                                    <a data-url="{{ route('image_remove', ['id' => $path->id, 'type' => 'image']) }}" class="btn btn-danger btn-xs waves-effect waves-light show_alert"><i class="mdi mdi-close"></i></a>
                                                </div>
            
                                                <div class="bg-light">
                                                
                                                <img src="{{ asset('storage/'. $path->image) }}" class="rounded img-fluid mx-auto d-block" style="height:60px; width: 100px; border: 2px solid red;" alt="product-pic"/>
                                                </div>
                                                
                                            </div>
                                        </div> 
                                        
                                        @endforeach
                                    @endif
                                </div>
                                <div>
                                    <h4 class="bg-light p-1">Multiple Videos</h4>
                                    @php $index = 'videos[]';  @endphp
                                    <input type="file" id="{{ $index }}" name="{{ $index }}" multiple data-plugins="dropify" data-height="150" />
                                    <span class="text-danger">
                                        @error($index)
                                        {{ $message }}
                                        @enderror
                                    </span>
                                    @if (isset($videos) && !empty($videos))
                                        @foreach($videos as $path)
                                        
                                        <div class="card product-box d-inline-block p-0 m-0" style="height:90px; width: 120px;">
                                            <div class="card-body">
                                                <div class="product-action">
                                                    <a data-url="{{ route('image_remove', ['id' => $path->id, 'type' => 'video']) }}" class="btn btn-danger btn-xs waves-effect waves-light show_alert"><i class="mdi mdi-close"></i></a>
                                                </div>
            
                                                <div class="bg-light">
                                                
                                                <video src="{{ asset('storage/'. $path->video) }}" class="rounded img-fluid mx-auto d-block" style="height:60px; width: 100px; border: 2px solid red;" alt="product-video"></video>
                                                </div>
                                                
                                            </div>
                                        </div> 
                                        
                                        @endforeach
                                    @endif
                                </div>
                                
                            </div>
                        
                        </div>
                        <div class="mt-3">
                            <button id="submit_btn" data-form_id="article_form" type="submit" class="btn btn-info waves-effect waves-light mx-auto d-block w-25">Save</button>
                        </div>
                    </form>
                </div>
            </div> 
        </div> 
    </div>

@endsection
@section('js-lib')
    
    <script src="{{ asset('assets/libs/summernote/summernote-lite.min.js') }}"></script>
    {{-- <script src="{{ asset('assets/libs/quill/quill.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form-quilljs.init.js') }}"></script> --}}
    <script src="{{ asset('assets/libs/dropify/js/dropify.min.js') }}"></script>
    <script src="{{ asset('assets/libs/dropzone/min/dropzone.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form-fileuploads.init.js') }}"></script>
    <script src="{{ asset('assets/libs/mohithg-switchery/switchery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form-advanced.init.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.summernote').summernote({
                placeholder: 'Add content here ...',
                tabsize: 2,
                height: 300
              });
        });
    </script>
@endsection
