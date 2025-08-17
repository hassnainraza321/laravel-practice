@extends('front.index')
@section('title', Helper::getSiteTitle('Magazines'))

@section('css-lib')
    
@endsection

@section('content')

	<main>
    <!-- Trending Area Start -->
    <div class="trending-area fix pt-25 gray-bg">
        <div class="container">
            <div class="trending-main">
                <div class="row">
                    <div class="col-lg-8">
                        <!-- Trending Top -->
                        <div class="slider-active">
                            <!-- Single -->
                            @php $articles = DB::table('articles')->where('user_id', $user->id)->where('status', 1)->get();@endphp
                            @if (isset($articles) && !empty($articles))
                                @foreach ($articles as $article)
                                    <div class="single-slider">
                                        <div class="trending-top mb-30">
                                            <div class="trend-top-img">
                                                @php $images = DB::table('images')->where('article_id', $article->id)->where('featured_image', 1)->first(); @endphp
                                                @if(!empty($images->image)) <img src="{{ asset('storage/'. $images->image) }}" alt="" class="img-fluid w-100" style="height: 650px !important;"> @endif
                                                <div class="trend-top-cap">
                                                    @php $magazine_id = DB::table('mag_articles')->where('article_id', $article->id)->pluck('magazine_id'); $magazines = DB::table('magazines')->whereIn('id', $magazine_id)->take(2)->get(); @endphp
                                                    @foreach ($magazines as $magazine)
                                                        <span class="bgr" data-animation="fadeInUp" data-delay=".2s" data-duration="1000ms">{{ $magazine->name }}</span>
                                                    @endforeach
                                                    <h2><a href="{{ route('article.detail', $article->slug) }}" data-animation="fadeInUp" data-delay=".4s" data-duration="1000ms">{{ substr($article->title, 0, 70) }}</a></h2>
                                                    @php $users = DB::table('users')->where('id', $article->user_id)->first(); @endphp
                                                    <p data-animation="fadeInUp" data-delay=".6s" data-duration="1000ms">{{ "by ".strtoupper($users->username). " - ".  date('d F Y', strtotime($article->created_at)) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                            
                        </div>
                    </div>
                    <!-- Right content -->
                    <div class="col-lg-4">
                            <!-- Trending Top -->
                        <div class="row">
                            @php $articles = DB::table('articles')->where('user_id', $user->id)->where('status', 1)->orderBy('id', 'desc')->take(2)->get();@endphp
                            @if (isset($articles) && !empty($articles))
                                @foreach ($articles as $article)
                                    <div class="col-lg-12 col-md-6 col-sm-6">
                                        <div class="trending-top mb-30">
                                            <div class="trend-top-img">
                                                @php $images = DB::table('images')->where('article_id', $article->id)->where('featured_image', 1)->first(); @endphp
                                                @if(!empty($images->image)) <img src="{{ asset('storage/'. $images->image) }}" alt="" style="height: 300px !important;"> @endif
                                                <div class="trend-top-cap trend-top-cap2">
                                                    @php $magazine_id = DB::table('mag_articles')->where('article_id', $article->id)->pluck('magazine_id'); $magazines = DB::table('magazines')->whereIn('id', $magazine_id)->take(2)->get(); @endphp
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
            </div>
        </div>
    </div>
    <!-- Trending Area End -->
    <!-- Whats New Start -->
    <section class="whats-news-area pt-50 pb-20 gray-bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                <div class="whats-news-wrapper">
                    <!-- Heading & Nav Button -->
                    <div class="row justify-content-between align-items-end mb-15">
                        <div class="col-xl-4">
                            <div class="section-tittle mb-30">
                                <h3>Whats New</h3>
                            </div>
                        </div>
                        <div class="col-xl-8 col-md-9">
                            <div class="properties__button">
                                <!--Nav Button  -->                                            
                                <nav>                                                 
                                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                        @php $magazines = DB::table('magazines')->get(); @endphp
                                        @foreach ($magazines as $key => $magazine)
                                            <a class="nav-item nav-link {{ $key === 0 ? 'active' : '' }}" id="nav-{{ $key }}-tab" data-toggle="tab" href="#nav-{{ $key }}" role="tab" aria-controls="nav-{{ $key }}" aria-selected="true">{{ $magazine->name }}</a>

                                        @if ($key === 4)
                                            @break
                                        @endif
                                            
                                        @endforeach
                                        @if ($key > 4)
                                            <div class="dropdown">
                                                <button class="btn btn-primary dropdown-toggle p-3" type="button" id="dropdownMenuButton" data-mdb-toggle="dropdown" aria-expanded="false">
                                                    More
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    @foreach ($magazines as $key => $magazine)
                                                    @if ($key > 4)
                                                        <li><a class="dropdown-item" href="{{ route('magazine.article', $magazine->slug) }}">{{ $magazine->name }}</a></li>
                                                    @endif
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                        
                                    </div>
                                </nav>
                                <!--End Nav Button  -->
                            </div>
                        </div>
                    </div>
                    <!-- Tab content -->
                    <div class="row">
                        <div class="col-12">
                            <!-- Nav Card -->
                            <div class="tab-content" id="nav-tabContent">
                                <!-- card one -->
                                @foreach ($magazines as $key => $magazine)
                                    <div class="tab-pane fade show {{ $key === 0 ? 'active' : '' }}" id="nav-{{ $key }}" role="tabpanel" aria-labelledby="nav-{{ $key }}-tab">       
                                        <div class="row">
                                            @php $article_id = DB::table('mag_articles')->where('magazine_id', $magazine->id)->pluck('article_id');  $articles = DB::table('articles')->whereIn('id', $article_id)->where('status', 1)->orderBy('id', 'desc')->take(4)->get();@endphp
                                            <!-- Left Details Caption -->
                                            @foreach ($articles as $key => $article)
                                                
                                                @if ($key === 0)
                                                    <div class="col-xl-6 col-lg-12">
                                                        <div class="whats-news-single mb-40 mb-40">
                                                            <div class="whates-img">
                                                                @php $images = DB::table('images')->where('article_id', $article->id)->where('featured_image', 1)->first(); @endphp
                                                                @if(!empty($images->image)) <img src="{{ asset('storage/'. $images->image) }}" alt="" style="height: 200px !important;"> @endif
                                                            </div>
                                                            <div class="whates-caption">
                                                                <h4><a href="{{ route('article.detail', $article->slug) }}">{{ substr($article->title, 0, 90) }}</a></h4>
                                                                @php $users = DB::table('users')->where('id', $article->user_id)->first(); @endphp
                                                                <span>{{ "by ".strtoupper($users->username) . " - ".  date('d F Y', strtotime($article->created_at)) }}</span>
                                                                
                                                                <div> <p> {{ substr(strip_tags($article->content), 0, 240). '....' }}</p></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                            <!-- Right single caption -->
                                            <div class="col-xl-6 col-lg-12">
                                                <div class="row">
                                                    <!-- single -->
                                                    @foreach ($articles as $key => $article)
                                                        @if ($key > 0)
                                                            <div class="col-xl-12 col-lg-6 col-md-6 col-sm-10">
                                                                <div class="whats-right-single mb-20">
                                                                    <div class="whats-right-img">
                                                                        @php $images = DB::table('images')->where('article_id', $article->id)->where('featured_image', 1)->first(); @endphp
                                                                        @if(!empty($images->image)) <img src="{{ asset('storage/'. $images->image) }}" alt="" style="height: 100px !important; width: 120px !important;"> @endif
                                                                        
                                                                    </div>
                                                                    <div class="whats-right-cap">
                                                                        <span class="colorb">{{ $magazine->name }}</span>
                                                                        <h4><a href="{{ route('article.detail', $article->slug) }}">{{ substr($article->title, 0, 55) }}</a></h4>
                                                                        <p>{{ date('F d, Y', strtotime($article->created_at)) }}</p> 
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        <!-- End Nav Card -->
                        </div>
                    </div>
                </div>
                <!-- Banner -->
                <div class="banner-one mt-20 mb-30">
                    <img src="{{ asset('front_assets/img/gallery/body_card1.png') }}" alt="">
                </div>
                </div>
                <div class="col-lg-4">
                    <!-- Flow Socail -->
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
                   </div>
                    <!-- Most Recent Area -->
                    <div class="most-recent-area">
                        <!-- Section Tittle -->
                        <div class="small-tittle mb-20">
                            <h4>Most Recent</h4>
                        </div>
                        <!-- Details -->
                        @php $articles = DB::table('articles')->where('status', 1)->orderBy('id', 'desc')->take(3)->get();@endphp
                        @foreach ($articles as $key => $article)
                                                
                        @if ($key === 0)
                            <div class="most-recent mb-40">
                                <div class="most-recent-img">
                                    @php $images = DB::table('images')->where('article_id', $article->id)->where('featured_image', 1)->first(); @endphp
                                    @if(!empty($images->image)) <img src="{{ asset('storage/'. $images->image) }}" alt="" style="height: 250px !important;"> @endif
                                    <div class="most-recent-cap">
                                        @php $magazine_id = DB::table('mag_articles')->where('article_id', $article->id)->pluck('magazine_id'); $magazines = DB::table('magazines')->whereIn('id', $magazine_id)->get(); @endphp
                                        @foreach ($magazines as $magazine)
                                            <span class="bgbeg">{{ $magazine->name }}</span>
                                        @endforeach
                                        <h4><a href="{{ route('article.detail', $article->slug) }}">{{ substr($article->title, 0, 40) }}</a></h4>
                                         @php $users = DB::table('users')->where('id', $article->user_id)->first(); @endphp
                                        <p>{{ strtoupper($users->username) . " | ".  date('d F Y', strtotime($article->created_at)) }}</p>  
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Single -->
                            <div class="most-recent-single">
                                <div class="most-recent-images">
                                    @php $images = DB::table('images')->where('article_id', $article->id)->where('featured_image', 1)->first(); @endphp
                                    @if(!empty($images->image)) <img src="{{ asset('storage/'. $images->image) }}" alt="" style="height: 70px !important;"> @endif
                                </div>
                                <div class="most-recent-capt">
                                    <h4><a href="{{ route('article.detail', $article->slug) }}">{{ substr($article->title, 0, 80) }}</a></h4>
                                    @php $users = DB::table('users')->where('id', $article->user_id)->first(); @endphp
                                        <p>{{ strtoupper($users->username) . " | ".  date('d F Y', strtotime($article->created_at)) }}</p> 
                                </div>
                            </div>
                        @endif
                        @endforeach
                        <div class="d-flex justify-content-center mt-2">
                       <a href="{{ route('article.all') }}" class="text-primary text-center">View more -></a>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Whats New End -->
    <!--   Weekly2-News start -->
    <div class="weekly2-news-area pt-50 pb-30 gray-bg">
        <div class="container">
            <div class="weekly2-wrapper">
                <div class="row">
                    <!-- Banner -->
                    <div class="col-lg-3">
                        <div class="home-banner2 d-none d-lg-block">
                            <img src="{{ asset('front_assets/img/gallery/body_card2.png') }}" alt="">
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="slider-wrapper">
                            <!-- section Tittle -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="small-tittle mb-30">
                                        <h4>Most Popular</h4>
                                    </div>
                                </div>
                            </div>
                            <!-- Slider -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="weekly2-news-active d-flex">
                                        @php $articles = DB::table('articles')->where('status', 1)->orderBy('id', 'desc')->get();@endphp
                                        @foreach ($articles as $key => $article)
                                            <!-- Single -->
                                            <div class="weekly2-single">
                                                <div class="weekly2-img">
                                                    @php $images = DB::table('images')->where('article_id', $article->id)->where('featured_image', 1)->first(); @endphp
                                                    @if(!empty($images->image)) <img src="{{ asset('storage/'. $images->image) }}" alt="" style="height: 170px !important;"> @endif
                                                </div>
                                                <div class="weekly2-caption">
                                                    <h4><a href="{{ route('article.detail', $article->slug) }}">{{ substr($article->title, 0, 60) }}</a></h4>
                                                    <p>{{ strtoupper($users->username) . " | ".  date('d F Y', strtotime($article->created_at)) }}</p>
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
    <div class="youtube-area video-padding d-none d-sm-block">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="video-items-active">
                        @php $articles = DB::table('articles')->where('status', 1)->orderBy('id', 'desc')->get();@endphp
                        @foreach ($articles as $key => $article)
                            <div class="video-items text-center">
                                <video controls>
                                    @php $videos = DB::table('videos')->where('article_id', $article->id)->where('featured_video', 1)->orderBy('id', 'desc')->first(); @endphp
                                    @if(!empty($videos->video)) <source src="{{ asset('storage/'. $videos->video) }}" type="video/mp4">  @endif
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        @endforeach
                        
                    </div>
                </div>
            </div>
            <div class="video-info">
                <div class="row">
                    <div class="col-12">
                        <div class="testmonial-nav text-center">
                        @php $articles = DB::table('articles')->where('status', 1)->orderBy('id', 'desc')->get();@endphp
                        @foreach ($articles as $key => $article)
                            <div class="single-video">
                                <video controls>
                                    @php $videos = DB::table('videos')->where('article_id', $article->id)->where('featured_video', 1)->orderBy('id', 'desc')->first(); @endphp
                                    @if(!empty($videos->video)) <source src="{{ asset('storage/'. $videos->video) }}" type="video/mp4"> @endif
                                    Your browser does not support the video tag.
                                </video>
                                <div class="video-intro">
                                        <a href="{{ route('article.detail', $article->slug) }}" class="text-muted">{{ substr($article->title, 0, 40) }}</a>
                                </div>
                            </div>
                        @endforeach
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
    <div class="weekly3-news-area pt-80 pb-130">
        <div class="container">
            <div class="weekly3-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="slider-wrapper">
                            <!-- Slider -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="weekly3-news-active dot-style d-flex">
                                        @php $articles = DB::table('articles')->where('user_id', $user->id)->where('status', 1)->get(); $total_articles = $articles->count(); $midpoint = ceil($total_articles / 2); $articles_mid = $articles->slice($midpoint - 1)->take(5);@endphp
                                        @if (isset($articles_mid) && !empty($articles_mid))
                                            @foreach ($articles_mid as $mid_article)
                                                <div class="weekly3-single">
                                                    <div class="weekly3-img">
                                                        @php $images = DB::table('images')->where('article_id', $mid_article->id)->where('featured_image', 1)->first(); @endphp
                                                        @if(!empty($images->image)) <img src="{{ asset('storage/'. $images->image) }}" alt="" style="height: 220px !important;"> @endif
                                                        
                                                    </div>
                                                    <div class="weekly3-caption">
                                                        <h4><a href="{{ route('article.detail', $article->slug) }}">{{ substr($article->title, 0, 60) }}</a></h4>
                                                        <p>{{ date('d F Y', strtotime($article->created_at)) }}</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif 
                                        <div class="weekly3-single">
                                            <div class="weekly3-img">
                                                <img src="{{ asset('front_assets/img/gallery/weekly2News2.png') }}" alt="">
                                            </div>
                                            <div class="weekly3-caption">
                                                <h4><a href="latest_news.html">What to Expect From the 2020 Oscar Nomin ations</a></h4>
                                                <p>19 Jan 2020</p>
                                            </div>
                                        </div> 
                                        <div class="weekly3-single">
                                            <div class="weekly3-img">
                                                <img src="{{ asset('front_assets/img/gallery/weekly2News3.png') }}" alt="">
                                            </div>
                                            <div class="weekly3-caption">
                                                <h4><a href="latest_news.html">What to Expect From the 2020 Oscar Nomin ations</a></h4>
                                                <p>19 Jan 2020</p>
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
    <div class="banner-area gray-bg pt-90 pb-90">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-md-10">
                    <div class="banner-one">
                        <img src="{{ asset('front_assets/img/gallery/body_card3.png') }}" alt="">
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</main>

@endsection
@section('js-lib')
    
@endsection