@extends('front.index')
@section('title', Helper::getSiteTitle('Article Details'))

@section('css-lib')
    
@endsection

@section('content')

	<main>
   <!--================Blog Area =================-->
   <section class="blog_area single-post-area section-padding">
      <div class="container">
         <div class="row">
            <div class="col-lg-8 posts-list">
               <div class="single-post">
                  <div class="feature-img">
                    @php $images = DB::table('images')->where('article_id', $article->id)->where('featured_image', 1)->first(); @endphp
                    @if(!empty($images->image)) <img class="img-fluid w-100" src="{{ asset('storage/'. $images->image) }}" alt="" style="max-height: 500px !important;"> @endif
                     
                  </div>
                  <div class="blog_details">
                     <h2>
                        {{ $article->title }}
                     </h2>
                     <ul class="blog-info-link mt-3 mb-4">
                        <li><a href="#"><i class="fa fa-user"></i> Travel, Lifestyle</a></li>
                        <li><a href="#"><i class="fa fa-comments"></i> 03 Comments</a></li>
                     </ul>
                     <div class="row">
                        <div class="col-12">
                            <div class="excert">
                                {!! $article->content !!}
                            </div>
                        </div>
                    </div>

                    <div class="quote-wrapper">
                        <h3 class="mb-3">Download Section</h3>
                        <div class="quotes">
                           <div class="d-flex justify-content-between mt-3">
                              <h5>CS101 Handouts</h5>
                              <a href="{{ asset('front_assets/img/logo/logo.png') }}" class="button boxed-btn p-2" download>Download</a>
                           </div>
                           <div class="d-flex justify-content-between mt-3">
                              <h5>CS101 Mid Term MCQs</h5>
                              <button class="button boxed-btn p-2">Download</button>
                           </div>
                           <div class="d-flex justify-content-between mt-3">
                              <h5>CS101 Mid Term Subjective</h5>
                              <button class="button boxed-btn p-2">Download</button>
                           </div>
                        </div>
                     </div>
                     
                     <div class="weekly2-news-area">
                        <div class="container">
                            <div class="weekly2-wrapper">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="slider-wrapper">
                                            <!-- section Tittle -->
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="small-tittle mb-30">
                                                        <h4>More Images</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Slider -->
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="weekly2-news-active d-flex">
                                                        @php $images = DB::table('images')->where('article_id', $article->id)->where('featured_image', 0)->get(); @endphp
                                                        @foreach ($images as $key => $image)
                                                            <!-- Single -->
                                                            <div class="weekly2-single">
                                                                <div class="weekly2-img">
                                                                    
                                                                    @if(!empty($image->image)) <img src="{{ asset('storage/'. $image->image) }}" alt="" style="height: 170px !important;"> @endif
                                                                </div>
                                                                
                                                            </div> 
                                                        @endforeach
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                  </div>
               </div>

               <div class="navigation-top">
                  <div class="d-sm-flex justify-content-between text-center">
                     <p class="like-info"><span class="align-middle"><i class="fa fa-heart"></i></span> Lily and 4
                        people like this</p>
                     <div class="col-sm-4 text-center my-2 my-sm-0">
                        <!-- <p class="comment-count"><span class="align-middle"><i class="fa fa-comment"></i></span> 06 Comments</p> -->
                     </div>
                     <ul class="social-icons">
                        <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                        <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                        <li><a href="#"><i class="fab fa-dribbble"></i></a></li>
                        <li><a href="#"><i class="fab fa-behance"></i></a></li>
                     </ul>
                  </div>
                  <div class="navigation-area">
                     <div class="row">
                        <div
                           class="col-lg-6 col-md-6 col-12 nav-left flex-row d-flex justify-content-start align-items-center">
                           <div class="thumb">
                              <a href="#">
                                 <img class="img-fluid" src="assets/img/post/preview.png" alt="">
                              </a>
                           </div>
                           <div class="arrow">
                              <a href="#">
                                 <span class="lnr text-white ti-arrow-left"></span>
                              </a>
                           </div>
                           <div class="detials">
                              <p>Prev Post</p>
                              <a href="#">
                                 <h4>Space The Final Frontier</h4>
                              </a>
                           </div>
                        </div>
                        <div
                           class="col-lg-6 col-md-6 col-12 nav-right flex-row d-flex justify-content-end align-items-center">
                           <div class="detials">
                              <p>Next Post</p>
                              <a href="#">
                                 <h4>Telescopes 101</h4>
                              </a>
                           </div>
                           <div class="arrow">
                              <a href="#">
                                 <span class="lnr text-white ti-arrow-right"></span>
                              </a>
                           </div>
                           <div class="thumb">
                              <a href="#">
                                 <img class="img-fluid" src="assets/img/post/next.png" alt="">
                              </a>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="blog-author">
                @php $admin_article = DB::table('articles')->where('user_id', $user->id)->where('status', 1)->orderBy('id', 'desc')->first();@endphp
                  <div class="media align-items-center">
                    @if(!empty($user->image)) <img class="" src="{{ asset('storage/'. $user->image) }}"> @endif
                     <div class="media-body">
                        <a href="#">
                           <h4 class="text-capitalize">{{ $user->username }}</h4>
                           <p>{{ $admin_article->title }}</p>
                        </a>
                        
                     </div>
                  </div>
               </div>
               <div class="comments-area">
                  @php $this_article = DB::table('articles')->where('slug', request()->slug)->first(); $comments = DB::table('comments')->where('article_id', $this_article->id)->where('status', 1)->get(); $images = DB::table('images')->where('article_id', $this_article->id)->where('featured_image', 1)->first(); @endphp
                  <h4>{{ count($comments) }} Comments</h4>
                  <div class="comment-list">
                     <div class="single-comment justify-content-between d-flex">
                        <div class="user justify-content-between d-flex">
                           <div class="thumb">
                              <img src="{{ asset('storage/'. $images->image) }}" alt="">
                           </div>
                           @foreach ($comments as $comment)
                              <div class="desc">
                                 <p class="comment">
                                    {{ $comment->comment }}
                                 </p>
                                 <div class="d-flex justify-content-between">
                                    <div class="align-items-center">
                                       <h5>
                                          <a href="#">{{ $this_article->title }}</a>
                                       </h5>
                                       <p class="date">{{ date('F j, Y \a\t g:i a', strtotime($comment->created_at)) }} </p>
                                    </div>
                                    {{-- <div class="reply-btn">
                                       <a href="#" class="btn-reply text-uppercase">reply</a>
                                    </div> --}}
                                 </div>
                              </div>
                           @endforeach
                        </div>
                     </div>
                  </div>
                  
               </div>
               <div class="comment-form">
                  <h4>Leave a Reply</h4>
                  <form class="form-contact comment_form" action="{{ route('comment.add', request()->slug) }}" method="post">
                     @csrf
                     <div class="alert alert-success ajax_response_success d-none"></div>
                    <div class="alert alert-danger ajax_response_error d-none"></div>
                     <div class="row">
                        <div class="col-12">
                           <div class="form-group">
                              <textarea class="form-control w-100" name="comment" id="comment" cols="30" rows="9"
                                 placeholder="Write Comment"></textarea>
                                 <span class="text-danger _comment"></span>
                           </div>
                        </div>
                        <div class="col-sm-6">
                           <div class="form-group">
                              <input class="form-control" name="name" id="name" type="text" placeholder="Name">
                              <span class="text-danger _name"></span>
                           </div>
                        </div>
                        <div class="col-sm-6">
                           <div class="form-group">
                              <input class="form-control" name="email" id="email" type="email" placeholder="Email">
                              <span class="text-danger _email"></span>
                           </div>
                        </div>
                        <div class="col-12">
                           <div class="form-group">
                              <input class="form-control" name="website" id="website" type="text" placeholder="Website">
                           </div>
                        </div>
                     </div>
                     <div class="form-group">
                        <button type="submit" class="button button-contactForm btn_1 boxed-btn">Send Message</button>
                     </div>
                  </form>
               </div>
            </div>
            <div class="col-lg-4">
               <div class="blog_right_sidebar">
                    <aside class="single_sidebar_widget">
                        <div class="embed-responsive embed-responsive-16by9"> 
                            <video controls class="embed-responsive-item">
                                @php $videos = DB::table('videos')->where('article_id', $article->id)->where('featured_video', 1)->orderBy('id', 'desc')->first(); @endphp
                                @if(!empty($videos->video)) <source src="{{ asset('storage/'. $videos->video) }}" type="video/mp4"> @endif
                                
                            </video>
                        </div>
                    </aside>
                    <aside class="single_sidebar_widget">
                        
                        <div class="slider-active">
                            
                            @php $videos = DB::table('videos')->where('article_id', $article->id)->where('featured_video', 0)->orderBy('id', 'desc')->get(); @endphp
                            @if(!empty($videos)) 
                                @foreach ($videos as $video)
                                <div class="single-slider">
                                    <div class="trending-top mb-30">
                                        <div class="trend-top-img">
                                            <div class="embed-responsive embed-responsive-16by9" style="max-height: 400px;"> 
                                                <video controls class="embed-responsive-item" >
                                                    <source src="{{ asset('storage/'. $video->video) }}" type="video/mp4"> 
                                                </video>
                                            </div>  
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @endif
                        </div>
                    </aside>
                    
                  <aside class="single_sidebar_widget search_widget">
                     <form action="{{ route('search.keyword') }}" method="post">
                     @csrf
                        <div class="form-group">
                           <div class="input-group mb-3">
                              <input type="text" class="form-control" name="keyword" @if(isset($keyword)) value="{{ $keyword }}" @endif placeholder='Search Keyword'
                                 onfocus="this.placeholder = ''" onblur="this.placeholder = 'Search Keyword'">
                              <div class="input-group-append">
                                 <button class="btns" type="submit"><i class="ti-search"></i></button>
                              </div>
                           </div>
                        </div>
                        <button class="button rounded-0 primary-bg text-white w-100 btn_1 boxed-btn"
                           type="submit">Search</button>
                     </form>
                  </aside>
                  <aside class="single_sidebar_widget tag_cloud_widget">
                     <h4 class="widget_title">Magazines</h4>
                     <ul class="list">
                        @php $magazines = DB::table('magazines')->get(); @endphp
                        @foreach ($magazines as $magazine)
                            <li>
                                @php $articles = DB::table('mag_articles')->where('magazine_id', $magazine->id)->count();@endphp
                               <a href="{{ route('magazine.article', $magazine->slug) }}">{{ $magazine->name.'('.$articles.')' }}</a>
                            </li>
                        @endforeach
                        
                        </li>
                     </ul>
                  </aside>
                  
                  <aside class="single_sidebar_widget popular_post_widget">
                     <h3 class="widget_title">Recent Post</h3>
                    @php $articles = DB::table('articles')->where('status', 1)->orderBy('id', 'desc')->take(5)->get();@endphp
                    @foreach ($articles as $key => $article)
                         <div class="media post_item">
                            @php $images = DB::table('images')->where('article_id', $article->id)->where('featured_image', 1)->first(); @endphp
                            @if(!empty($images->image)) <img src="{{ asset('storage/'. $images->image) }}" alt="" style="max-height: 80px !important;"> @endif
                            
                            <div class="media-body">
                               <a href="single-blog.html">
                                  <a href="{{ route('article.detail', $article->slug) }}" class="text-muted">{{ substr($article->title, 0, 50).'...' }}</a>
                               </a>
                               <p>{{ date('F d, Y', strtotime($article->created_at)) }}</p>
                            </div>
                         </div>
                    @endforeach
                    <div class="d-flex justify-content-center mt-2">
                       <a href="{{ route('article.all') }}" class="text-primary text-center">View more -></a>
                    </div>
                  </aside>
                  
                  <aside class="single_sidebar_widget newsletter_widget">
                     <h4 class="widget_title">Newsletter</h4>
                     <form action="#">
                        <div class="form-group">
                           <input type="email" class="form-control" onfocus="this.placeholder = ''"
                              onblur="this.placeholder = 'Enter email'" placeholder='Enter email' required>
                        </div>
                        <button class="button rounded-0 primary-bg text-white w-100 btn_1 boxed-btn"
                           type="submit">Subscribe</button>
                     </form>
                  </aside>
               </div>
            </div>
         </div>
      </div>
   </section>
   <!--================ Blog Area end =================-->
</main>

@endsection
@section('js-lib')
    
@endsection