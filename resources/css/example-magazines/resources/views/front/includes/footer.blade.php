<footer>
    <!-- Footer Start-->
    <div class="footer-main footer-bg">
        <div class="footer-area footer-padding">
            <div class="container">
                <div class="row d-flex justify-content-between">
                    <div class="col-xl-3 col-lg-3 col-md-5 col-sm-8">
                        <div class="single-footer-caption mb-50">
                            <div class="single-footer-caption mb-30">
                                <!-- logo -->
                                <div class="footer-logo">
                                    <a href="index.html"><img src="{{ asset('front_assets/img/logo/logo2_footer.png') }}" alt=""></a>
                                </div>
                                <div class="footer-tittle">
                                    <div class="footer-pera">
                                        <p class="info1">
                                            <ul class="list-unstyled p-2">    
                                                <li><a href="#">Home</a></li>
                                                <li><a href="#">About</a></li>
                                                <li><a href="#">Magazines</a></li>
                                                <li> <a href="#">Privacy Policy</a></li>
                                            </ul>
                                        </p>
                                        <p class="info2">Yousal Plaza, China Chowk Sialkot, Punjab, Pakistan.</p>
                                        <p class="info2">Phone: +92 321 6261885</p>
                                        <p class="info2">WhatsApp: +92 345 3531926</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-5 col-sm-7">
                        <div class="single-footer-caption mb-50">
                            <div class="blog_right_sidebar">
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
                            <div class="">
                                <!-- Heder social -->
                                <ul class="list-unstyled d-flex justify-content-between p-2">    
                                    <li><a href="https://www.fb.com/sai4ull"><i class="fab fa-facebook-f"></i></a></li>
                                    <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                    <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                                    <li> <a href="#"><i class="fab fa-youtube"></i></a></li>
                                </ul>

                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-5 col-sm-7">
                        <div class="single-footer-caption mb-50">
                            <div class="banner">
                                <img src="{{ asset('front_assets/img/gallery/body_card4.png') }}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- footer-bottom aera -->
        <div class="footer-bottom-area footer-bg">
            <div class="container">
                <div class="footer-border">
                    <div class="row d-flex align-items-center">
                        <div class="col-xl-12 ">
                            <div class="footer-copy-right text-center">
                                <p>{{ date('Y') }} © {{ Helper::getSiteTitle() }} | All rights reserved
                                    {{-- <i class="fa fa-heart" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a> --}}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End-->
</footer>