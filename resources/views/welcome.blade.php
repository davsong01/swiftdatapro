@extends('includes.frontend.app')
@section('content')
<section id="hero" class="hero d-flex align-items-center">
    <div class="container">
        <div class="row">
            <div class="row gy-4 d-flex justify-content-between">
                <div class="col-lg-6 order-1 order-lg-1 d-flex flex-column justify-content-center">
                    <h2 data-aos="fade-up">The Best Online Recharge & Subscriptions</h2>
                    <p data-aos="fade-up" data-aos-delay="100">Welcome to Nigeria's Favourite Online Vendor, Delivering
                        Efficient Service Without Compromising Quality. Join The Club To Start Flexing Today!</p>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 order-2 order-lg-1" data-aos="zoom-out">
                    @guest
                    <a href="{{ route('register') }}"><button class="btn btn-primary">Register</button></a>
                    <a href="{{ route('login') }}"><button class="btn btn-primary">Sign In</button></a>
                    @endguest
                    @auth
                    <a href="{{ route('dashboard') }}"><button class="btn btn-primary">My Account</button></a>
                    @endauth
                </div>
            </div>


        </div>


    </div>
    </div>
</section><!-- End Hero Section -->

<!-- ======= Featured Services Section ======= -->
<section id="featured-services" class="featured-services">
    <div class="container">

        <div class="row gy-4">

            <div class="col-lg-4 col-md-6 service-item d-flex" data-aos="fade-up">
                <div class="icon flex-shrink-0"><i class="fa-solid far fa-paper-plane"></i></div>
                <div>
                    <h4 class="title">
                        Swift Delivery Service</h4>
                    <p class="description">We offer instant recharge of Airtime, Databundle, CableTV, Electricity
                        Bill Payment
                        and Educational PIN(s) with instant delivery.</p>
                </div>
            </div>
            <!-- End Service Item -->

            <div class="col-lg-4 col-md-6 service-item d-flex" data-aos="fade-up" data-aos-delay="100">
                <div class="icon flex-shrink-0"><i class="fa-solid fa-server"></i></div>
                <div>
                    <h4 class="title">
                        Secured & Automated</h4>
                    <p class="description">We use cutting-edge technology to run our services. Which make All our
                        services are
                        delivered to you almost instantly in nano-seconds.</p>
                </div>
            </div><!-- End Service Item -->

            <div class="col-lg-4 col-md-6 service-item d-flex" data-aos="fade-up" data-aos-delay="200">
                <div class="icon flex-shrink-0"><i class="fa-solid fas fa-headset"></i></div>
                <div>
                    <h4 class="title">Full Customer Support</h4>
                    <p class="description">Our customers are premium to us, hence satisfying them is our topmost
                        priority. Our
                        customer service is just a click away.</p>
                </div>
            </div><!-- End Service Item -->

        </div>

    </div>
</section><!-- End Featured Services Section -->

<!-- ======= About Us Section ======= -->
<section id="about" class="about pt-0 pb-3">
    <div class="container" data-aos="fade-up">

        <div class="row gy-4">
            <div class="col-lg-6 position-relative align-self-start order-lg-last order-first">
                <img src="homepage/assets/img/vision.jpg" class="img-fluid" style="height: 460px" alt="">
            </div>
            <div class="col-lg-6 content order-last  order-lg-first">
                <h3>About Us</h3>
                <p>
                    What We Do & Our Aim:
                    {{ config('app.name') }} Enterprises is a private limited liability company
                    registered under the Laws of
                    the Federal Republic of Nigeria. {{ config('app.name') }} Enterprises is a
                    Value Added Services (VAS)
                    organization.
                </p>
                <strong> We Offer Instant Services For : </strong>
                <ul>
                    <li data-aos="fade-up" data-aos-delay="100">
                        <i class="bi bi-wifi"></i>
                        <div>
                            <h5>Internet Data Subscription</h5>
                            <p>Begin Leveling up your online world today with our smart databundles.</p>
                        </div>
                    </li>
                    <li data-aos="fade-up" data-aos-delay="200">
                        <i class="bi bi-telephone"></i>
                        <div>
                            <h5>Airtime Recharge</h5>
                            <p>Purchase airtime at your conviniency from our website at a low rates, we've done the
                                stress for
                                you.</p>
                        </div>
                    </li>
                    <li data-aos="fade-up" data-aos-delay="300">
                        <i class="bi bi-broadcast"></i>
                        <div>
                            <h5>Cable Subscription and Electricity Bill Payment</h5>
                            <p> Let us handle all your utilities bill which run autonomously at relatively low rate.
                            </p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

    </div>
</section><!-- End About Us Section -->


<!-- ======= Services Section ======= -->
<section id="service" class="services pt-0">
    <div class="container" data-aos="fade-up">

        <div class="section-header">
            <h2>Our Services</h2>

        </div>

        <div class="row gy-4">

            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="card">
                    <div class="card-img">
                        <img src="homepage/assets/img/storage-service.jpg" alt="" class="img-fluid">
                    </div>
                    <h3>Buy Data</h3>
                    <p>Start enjoying this low rates Data plan for your internet browsing with conviniency, step up
                        your
                        online world with our quality databundles in no time.</p>
                </div>
            </div><!-- End Card Item -->

            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="card">
                    <div class="card-img">
                        <img src="homepage/assets/img/trucking-service.jpg" alt="" class="img-fluid">
                    </div>
                    <h3>Airtime Top-Up</h3>
                    <p>Buy airtime from our website with no stress today, we've made an online recharge very easy
                        for you and
                        it's safe on our Website. Start enjoying now.</p>
                </div>
            </div><!-- End Card Item -->

            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="card">
                    <div class="card-img">
                        <img src="{{ asset('homepage/assets/img/logistics-service.jpg') }}"
                            alt="" class="img-fluid">
                    </div>
                    <h3>Airtime To Cash</h3>
                    <p>Excess <b>airtime?</b> <br> Convert that your excess airtime easily on our platform to cash
                        on
                        {{ config('app.name') }} data with less discounts.</p>
                </div>
            </div><!-- End Card Item -->

            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
                <div class="card">
                    <div class="card-img">
                        <img src="{{ asset('homepage/assets/img/packaging-service.jpg') }}"
                            alt="" class="img-fluid">
                    </div>
                    <h3>Cable Subscription</h3>
                    <p>Want to Instant cable subscription? Activate from our Cable subscription package with
                        favourable
                        discount compare to other you'll find elsewhere.</p>
                </div>
            </div><!-- End Card Item -->

            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="600">
                <div class="card">
                    <div class="card-img">
                        <img src="{{ asset('homepage/assets/img/warehousing-service.jpg') }}"
                            alt="" class="img-fluid">
                    </div>
                    <h3>Electricity Bill Payment</h3>
                    <p>Because we understand your needs, we have made bills and utilities payment more convenient
                        for you with
                        our automated service for all your payments.</p>
                </div>
            </div><!-- End Card Item -->

            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="600">
                <div class="card">
                    <div class="card-img">
                        <img src="{{ asset('homepage/assets/img/warehousing-service2.jpg') }}"
                            alt="" class="img-fluid">
                    </div>
                    <h3>Referal Programme</h3>
                    <p>Don't Let your loved ones be left out. Refer a friend as a reseller and earn 50% bonus on
                        their
                        activation with no stress. <br>It's a Win-Win game!!!</p>
                </div>
            </div><!-- End Card Item -->

        </div>

    </div>
</section><!-- End Services Section -->

<!-- ======= Call To Action Section ======= -->
<section id="call-to-action" class="call-to-action">
    <div class="container" data-aos="zoom-out">

        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <!-- <h3>Call To Action</h3> -->
                <p> {{ config('app.name') }} is a technology platform that offers solutions to
                    digital needs at best
                    possible price. We Are The Best Recharge & Utility Bills Payment Platform..</p>
                <a class="cta-btn" href="{{ config('app.url') }}/register">Get Started</a>
                </dic>
            </div>

        </div>
</section><!-- End Call To Action Section -->

<!-- ======= Pricing Section ======= -->
<section id="pricing" class="pricing pt-0">
    <div class="container" data-aos="fade-up">

        <div class="section-header">
            <h2>Pricing</h2>

        </div>

        <div class="row gy-4">

            <div class="col-lg-3" data-aos="fade-up" data-aos-delay="100">
                <div class="pricing-item" style="color:#ffc107">
                    <h3 style="color:#ffc107">MTN DATA</h3>
                    <h4><sup>₦</sup>230<span style="color:#ffc107">/ month</span></h4>
                    <ul>
                        <li><i class="bi bi-check"></i> 1GB</li>
                        <li><i class="bi bi-check"></i> One Month</li>
                        <li><i class="bi bi-check"></i>Support 4G</li>
                        <li><i class="bi bi-check"></i>Hotspot Sharing</li>
                        <li><i class="bi bi-check"></i>Multiple Device</li>
                    </ul>
                    <a href="{{ route('login') }}" class="buy-btn" style="color:#ffc107">Buy
                        Now</a>
                </div>
            </div><!-- End Pricing Item -->

            <div style="color: #28a745;" class="col-lg-3" data-aos="fade-up" data-aos-delay="100">
                <div class="pricing-item">
                    <h3 style="color: #28a745;">GLO DATA</h3>
                    <h4><sup>₦</sup>440<span style="color: #28a745;">/ month</span></h4>
                    <ul>
                        <li><i class="bi bi-check"></i> 1GB</li>
                        <li><i class="bi bi-check"></i> One Month</li>
                        <li><i class="bi bi-check"></i>Support 4G</li>
                        <li><i class="bi bi-check"></i>Hotspot Sharing</li>
                        <li><i class="bi bi-check"></i>Multiple Device</li>
                    </ul>
                    <a href="{{ config('app.url') }}/login" class="buy-btn"
                        style="color: #28a745;">Buy Now</a>
                </div>
            </div><!-- End Pricing Item -->

            <div class="col-lg-3" data-aos="fade-up" data-aos-delay="100">
                <div class="pricing-item">
                    <h3 style="color: #dc3545;">AIRTEL DATA</h3>
                    <h4><sup>₦</sup>460<span style="color: #dc3545;">/ month</span></h4>
                    <ul>
                        <li><i class="bi bi-check"></i> 1GB</li>
                        <li><i class="bi bi-check"></i> One Month</li>
                        <li><i class="bi bi-check"></i>Support 4G</li>
                        <li><i class="bi bi-check"></i>Hotspot Sharing</li>
                        <li><i class="bi bi-check"></i>Multiple Device</li>
                    </ul>
                    <a href="{{ config('app.url') }}/login" class="buy-btn"
                        style="color: #dc3545;">Buy Now</a>
                </div>
            </div><!-- End Pricing Item -->

            <div class="col-lg-3" data-aos="fade-up" data-aos-delay="100">
                <div class="pricing-item">
                    <h3 style="color: rgb(10, 236, 10);">9MOBILE DATA</h3>
                    <h4><sup>₦</sup>400<span style="color: rgb(10, 236, 10);">/ month</span></h4>
                    <ul>
                        <li style="text-align: center;"><i class="bi bi-check"></i> 1GB</li>
                        <li style="text-align: center;"><i class="bi bi-check"></i> One Month</li>
                        <li style="text-align: center;"><i class="bi bi-check"></i>Support 4G</li>
                        <li style="text-align: center;"><i class="bi bi-check"></i>Hotspot Sharing</li>
                        <li style="text-align: center;"><i class="bi bi-check"></i>Multiple Device</li>
                    </ul>
                    <a href="{{ config('app.url') }}/login" class="buy-btn"
                        style="color: rgb(10, 236, 10);">Buy Now</a>
                </div>
            </div><!-- End Pricing Item -->

        </div>

    </div>
</section><!-- End Pricing Section -->

<!-- ======= Testimonials Section ======= -->
<section id="testimonials" class="testimonials">
    <div class="container">

        <div class="slides-1 swiper" data-aos="fade-up">
            <div class="swiper-wrapper">

                <div class="swiper-slide">
                    <div class="testimonial-item">
                        <!-- <img src="homepage/assets/img/testimonials/testimonials-1.png" class="testimonial-img" alt=""> -->
                        <h3>Adeoye Adheem</h3>
                        <h4>Ceo &amp; Founder</h4>
                        <div class="stars">
                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                class="bi bi-star-fill"></i>
                        </div>
                        <p>
                            <i class="bi bi-quote quote-icon-left"></i>
                            Wow. This is a legit datahub for me. I haven't disappointed my customers since they
                            never disappoint
                            me too. Thank you for your usual action. Cheers Guys!
                            <i class="bi bi-quote quote-icon-right"></i>
                        </p>
                    </div>
                </div><!-- End testimonial item -->

                <div class="swiper-slide">
                    <div class="testimonial-item">
                        <!-- <img src="homepage/assets/img/testimonials/testimonials-2.png" class="testimonial-img" alt=""> -->
                        <h3>Olayode Benjamin </h3>
                        <h4>Student</h4>
                        <div class="stars">
                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                class="bi bi-star-fill"></i>
                        </div>
                        <p>
                            <i class="bi bi-quote quote-icon-left"></i>
                            Great!!! EVerything is cool with {{ config('app.name') }} The admin is
                            super-active because he gave
                            response to all my request. Love.
                            <i class="bi bi-quote quote-icon-right"></i>
                        </p>
                    </div>
                </div><!-- End testimonial item -->

                <div class="swiper-slide">
                    <div class="testimonial-item">
                        <!-- <img src="homepage/assets/img/testimonials/testimonials-3.png" class="testimonial-img" alt=""> -->
                        <h3>Johnny Sam</h3>
                        <h4>Business Owner</h4>
                        <div class="stars">
                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                class="bi bi-star-fill"></i>
                        </div>
                        <p>
                            <i class="bi bi-quote quote-icon-left"></i>
                            Oh my God! Don't know what to say but it is cool to partner with this platform. They
                            have provided me
                            all the best i want. Thank you.
                            <i class="bi bi-quote quote-icon-right"></i>
                        </p>
                    </div>
                </div><!-- End testimonial item -->

            </div>
            <div class="swiper-pagination"></div>
        </div>

    </div>
</section><!-- End Testimonials Section -->

<!-- ======= Frequently Asked Questions Section ======= -->
<section id="faq" class="faq">
    <div class="container" data-aos="fade-up">

        <div class="section-header">
            <h2>Have Any Issue? Check Our Frequently Asked Questions</h2>

        </div>

        <div class="row justify-content-center" data-aos="fade-up" data-aos-delay="200">
            <div class="col-lg-10">

                <div class="accordion accordion-flush" id="faqlist">

                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#faq-content-1">
                                <i class="bi bi-question-circle question-icon"></i>
                                How Do I Buy Data?
                            </button>
                        </h3>
                        <div id="faq-content-1" class="accordion-collapse collapse" data-bs-parent="#faqlist">
                            <div class="accordion-body">
                                <ol>
                                    <li>Log in to your account</li>
                                    <li>if not; register as a new member</li>
                                    <li>After loging in, click fund my new account if low balance</li>
                                    <li>Fill the requested data and proceed with the next actions</li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- # Faq item-->

                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#faq-content-2">
                                <i class="bi bi-question-circle question-icon"></i>
                                How To Use Our Services?
                            </button>
                        </h3>
                        <div id="faq-content-2" class="accordion-collapse collapse" data-bs-parent="#faqlist">
                            <div class="accordion-body">
                                <ol>
                                    <li>STEP 1: Fund your wallet.</li>
                                    <li>STEP 2: Fill the data order form.</li>
                                    <li>STEP 3: Wait for 1-15 minutes, the recipient will receive notification(s) of
                                        data
                                        recharge(Except for 9mobile(SME), which should be confirmed with its balance
                                        code)</li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- # Faq item-->

                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#faq-content-3">
                                <i class="bi bi-question-circle question-icon"></i>
                                How Do I Fund My Wallet?
                            </button>
                        </h3>
                        <div id="faq-content-3" class="accordion-collapse collapse" data-bs-parent="#faqlist">
                            <div class="accordion-body">
                                We have provided you some easy way to get money into your account:
                                <ol>
                                    <li>Bank payment for manual funding.</li>
                                    <li>Online Payment with your ATM card details via Pay stack Payment Gateway.
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- # Faq item-->

                </div>

            </div>
        </div>

    </div>
</section><!-- End Frequently Asked Questions Section -->

<!-- ======= Contact Section ======= -->
<section id="contact" class="contact">
    <div class="container" data-aos="fade-up">
        <center> <strong>Contact Us </strong> </center>

        <div class="row gy-4 mt-4">

            <div class="col-lg-4">

                <div class="info-item d-flex">
                    <i class="bi bi-geo-alt flex-shrink-0"></i>
                    <div>
                        <h4>Location:</h4>
                        <p>Okinni, Osun - State.</p>
                    </div>
                </div><!-- End Info Item -->

                <div class="info-item d-flex">
                    <i class="bi bi-envelope flex-shrink-0"></i>
                    <div>
                        <h4>Email:</h4>
                        <p>info@swiftdatang.com</p>
                    </div>
                </div><!-- End Info Item -->

                <div class="info-item d-flex">
                    <i class="bi bi-phone flex-shrink-0"></i>
                    <div>
                        <h4>Call:</h4>
                        <p>+2347046734407</p>
                    </div>
                </div><!-- End Info Item -->

            </div>

            <div class="col-lg-8">
                <form action="forms/contact.php" method="post" role="form" class="php-email-form">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <input type="text" name="name" class="form-control" id="name" placeholder="Your Name"
                                required>
                        </div>
                        <div class="col-md-6 form-group mt-3 mt-md-0">
                            <input type="email" class="form-control" name="email" id="email" placeholder="Your Email"
                                required>
                        </div>
                    </div>
                    <div class="form-group mt-3">
                        <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject"
                            required>
                    </div>
                    <div class="form-group mt-3">
                        <textarea class="form-control" name="message" rows="5" placeholder="Message"
                            required></textarea>
                    </div>
                    <div class="my-3">
                        <div class="loading">Loading</div>
                        <div class="error-message"></div>
                        <div class="sent-message">Your message has been sent. Thank you!</div>
                    </div>
                    <div class="text-center"><button type="submit">Send Message</button></div>
                </form>
            </div><!-- End Contact Form -->

        </div>

    </div>
</section><!-- End Contact Section -->
@endsection