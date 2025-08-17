@extends('front.index')
@section('title', Helper::getSiteTitle('Search'))

@section('css-lib')
    
@endsection

@section('content')

	<main>
    <!-- ================ contact section start ================= -->
    <section class="contact-section">
    <div class="container">
        <div class="mb-4">
           <map>
              <iframe class="w-100" src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d53843.99870740336!2d74.5242624!3d32.4927488!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2s!4v1705990967964!5m2!1sen!2s" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
           </map>
        </div>
        <div class="row">
            
            <div class="col-12">
                <h2 class="contact-title">Get in Touch</h2>
            </div>
            
            <div class="col-lg-8">
                <form class="form-contact comment_form" action="{{ route('contact') }}" method="post">
                  @csrf
                  <div class="alert alert-success ajax_response_success d-none"></div>
                    <div class="alert alert-danger ajax_response_error d-none"></div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <textarea class="form-control w-100" name="message" id="message" cols="30" rows="9" placeholder=" Enter Message"></textarea>
                                <span class="text-danger _message"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <input class="form-control valid" name="name" id="name" type="text" placeholder="Enter your name">
                                <span class="text-danger _name"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <input class="form-control valid" name="email" id="email" type="email" placeholder="Email">
                                <span class="text-danger _email"></span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <input class="form-control" name="subject" id="subject" type="text" placeholder="Enter Subject">
                                <span class="text-danger _subject"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-3">
                        <button type="submit" class="button button-contactForm boxed-btn">Send</button>
                    </div>
                </form>
            </div>
            <div class="col-lg-3 offset-lg-1">
                <div class="media contact-info">
                    <span class="contact-info__icon"><i class="ti-home"></i></span>
                    <div class="media-body">
                        <h3>Yousaf Plaza, China Chowk, Sialkot </h3>
                        <p>Punjab, Pakistan</p>
                    </div>
                </div>
                <div class="media contact-info">
                    <span class="contact-info__icon"><i class="ti-tablet"></i></span>
                    <div class="media-body">
                        <h3>+92 321 6261885</h3>
                        <p>Mon to Fri 9am to 6pm</p>
                    </div>
                </div>
                <div class="media contact-info">
                    <span class="contact-info__icon"><i class="ti-email"></i></span>
                    <div class="media-body">
                        <h3>Hassnainkazmi345@gmail.com</h3>
                        <p>Send us your query anytime!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>
    <!-- ================ contact section end ================= -->
</main>

@endsection
@section('js-lib')
    
@endsection