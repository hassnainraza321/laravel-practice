@extends('dashboard.index')
@section('title', Helper::getSiteTitle('Packages'))

@section('css-lib')
    
@endsection

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Packages</li>
                    </ol>
                </div>
                <h4 class="page-title">Subscripe Packages</h4>
            </div>
        </div>
    </div>
	
    <div class="row">
        @if (isset($packages) && !empty($packages))
            @foreach ($packages as $element)
                <div class="col-lg-4">
                    <div class="text-center card ribbon-box">
                        @if (Auth::user()->package_id === null && $element->amount == 0)
                                <div class="ribbon ribbon-success float-end"><i class="mdi mdi-access-point"></i> Subscriped</div>
                            @elseif(Auth::user()->package_id === $element->id)
                                <div class="ribbon ribbon-success float-end"><i class="mdi mdi-access-point"></i> Subscriped</div>
                            @endif
                        <div class="card-body">
                            
                            <div class="pt-2 pb-2">
                                <span class="bg-light rounded-circle p-2" style="font-size: 100px;">
                                  <i class="ti-cup"></i>
                                </span>
                                {{-- <img src="assets/images/users/user-3.jpg" class="rounded-circle img-thumbnail avatar-xl" alt="profile-image"> --}}

                                <h4 class="mt-3"><a href="extras-profile.html" class="text-dark">{{ $element->name }}</a></h4>
                                <p class="text-muted">@Founder <span> | </span> <span> <a href="#" class="text-pink">{{ $element->description }}</a> </span></p>

                                @php
                                 if ($element->id === 2){
                                        $price_id = 'price_1OWcz3Cib8oSjAB9OZyLXCT6';
                                 }elseif ($element->id === 3){
                                    $price_id = 'price_1OWxupCib8oSjAB9hcMHc078';
                                 }else{
                                    $price_id = 0;
                                 }
                                @endphp
                                <form action="{{ route('charge', $element->id) }}" method="post">
                                    @csrf
                                    <input type="hidden" name="price_id" value="{{ $price_id }}">
                                    <button type="submit" class="btn btn-primary btn-sm waves-effect waves-light {{ $element->amount == 0 ? 'disabled' : '' }}" >Subscripe</button>
                                </form>

                                {{-- <a href="{{ route('charge', $price_id) }}" class="btn btn-primary btn-sm waves-effect waves-light {{ $element->amount == 0 ? 'disabled' : '' }}" >Subscripe</a> --}}

                                <div class="row mt-4 d-flex justify-content-between">
                                    <div class="col-4">
                                        <div class="mt-3">
                                            <h4>{{ $element->amount != 0 ? '$ '.$element->amount : 'FREE'}}</h4>
                                            <p class="mb-0 text-muted text-truncate">Amount</p>
                                        </div>
                                    </div>
                                    <div class="col-4 mb-2">
                                        <div class="mt-3">
                                            <h4>{{ $element->article_limit != 0 ? $element->article_limit : 'Unlimited'}}</h4>
                                            <p class="mb-0 text-muted text-truncate">Article Limit</p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

@endsection
@section('js-lib')
    
@endsection
