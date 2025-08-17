@extends('front.index')
@section('title', Helper::getSiteTitle('Magazines Articles'))

@section('css-lib')
    
@endsection

@section('content')

	<main>
   <!--================Blog Area =================-->
   <section class="blog_area single-post-area section-padding">
      <div class="container">
         <div class="row">
            <div class="col-lg-8 posts-list">
               <div class="trending-area fix ">
                  <div class="trending-main">
                     <div class="row">
                        @php $articles = DB::table('articles')->where('status', 1)->orderBy('id', 'desc')->paginate(10); @endphp
                        @if (isset($articles) && !empty($articles))
                        @foreach ($articles as $article)
                           <div class="col-md-6">
                                   
                               <div class="trending-top mb-30">
                                   <div class="trend-top-img">
                                       @php $images = DB::table('images')->where('article_id', $article->id)->where('featured_image', 1)->first(); @endphp
                                       @if(!empty($images->image)) <img src="{{ asset('storage/'. $images->image) }}" alt="" style="height: 300px !important;"> @endif
                                       <div class="trend-top-cap trend-top-cap2">
                                           @php $magazine_id = DB::table('mag_articles')->where('article_id', $article->id)->pluck('magazine_id'); $magazines = DB::table('magazines')->whereIn('id', $magazine_id)->get(); @endphp
                                           @foreach ($magazines as $magazine)
                                               <span class="bgb">{{ $magazine->name }}</span>
                                           @endforeach
                                         
                                           <h2><a href="{{ route('article.detail', $article->slug) }}">{{ substr($article->title, 0, 40) }}</a></h2>
                                           @php $users = DB::table('users')->where('id', $article->user_id)->first(); @endphp
                                           <p>{{ "by ".strtoupper($users->username) . " - ".  date('d F Y', strtotime($article->created_at)) }}</p>
                                       </div>
                                   </div>
                               </div>
                           </div>        
                        @endforeach
                        @endif
                        
                     </div>
                  </div>
               </div>
               <div>
                  {{ $articles->links('vendor.pagination.bootstrap-5') }}
               </div>
            </div>
            <div class="col-lg-4">
               <div class="blog_right_sidebar">
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