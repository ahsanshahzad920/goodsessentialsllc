@extends('layouts.guest')

@section('content')
    <!-- start banner Area -->
    <section class="about-banner-area banner-area d-flex align-items-center" id="hero">
        <div class="container">
            <div class="single-slide">
                <div class="banner-content text-center">
                    <h1>About Us</h1>
                    <p class="fs-5 text-dark">
                        Flareoncorp is your one-stop destination for high-quality
                        <br class="d-none d-md-block" />
                        products across various categories, including baby essentials,
                        <br class="d-none d-md-block" />
                        automotive accessories, beauty products, and home and kitchen items.
                    </p>
                </div>
            </div>
        </div>
    </section>
    <!-- End banner Area -->

    <!-- About section -->
    <section class="my-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 mt-3">
                    <h3 class="heading">Combining Years of Hard Work</h3>
                    <p>
                        At Flareoncorp, we've combined years of dedication and passion to bring you a diverse range of
                        products
                        that cater to your everyday needs. Our journey is built on hard work and a commitment to quality,
                        ensuring that every item we offer meets the highest standards. From baby essentials to automotive
                        accessories, beauty products, and home goods, we strive to provide reliable solutions for your life.
                    </p>

                    <p>
                        With a focus on customer satisfaction, our team continuously works to source and deliver the best
                        products across various categories. Whether you're shopping for your home, family, or personal use,
                        Flareoncorp is here to make your experience smooth and enjoyable, backed by the hard work we've put
                        in over the years.
                    </p>
                </div>
                <div class="col-md-6 mt-3">
                    <img src="assets/img/about.jpg" alt="" class="img-fluid" />
                </div>
            </div>
        </div>
    </section>
    <!-- End about section -->

    <!-- Start category Area -->
    <section class="category-area">
        <div class="container">
            <div class="text-center">
                <h1 class="fw-bold">Popular Categories</h1>
            </div>
            <div class="row justify-content-center mt-4">
                <div class="col-lg-8 col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="{{ route('shop', 'Baby Products') }}" class="text-decoration-none">
                                <div class="single-deal">
                                    <div class="overlay"></div>
                                    <img class="img-fluid" src="assets/img/category-3.jpg" alt=""
                                        style="width: 100%; height: 13rem; object-fit: cover" />
                                    <div class="deal-details">
                                        <h6 class="deal-title">Baby Products</h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('shop', 'Beauty Products') }}" class="text-decoration-none">
                                <div class="single-deal">
                                    <div class="overlay"></div>
                                    <img class="img-fluid" src="assets/img/category-2.jpg" alt=""
                                        style="width: 100%; height: 13rem; object-fit: cover" />
                                    <div class="deal-details">
                                        <h6 class="deal-title">Beauty</h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-12">
                            <a href="{{ route('shop', 'Automotive') }}" class="text-decoration-none">
                                <div class="single-deal">
                                    <div class="overlay"></div>
                                    <img class="img-fluid" src="assets/img/category-4.jpg" alt=""
                                        style="width: 100%; height: 15rem; object-fit: cover" />
                                    <div class="deal-details">
                                        <h6 class="deal-title">Automative</h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <a href="{{ route('shop', 'Home and Kitchen') }}" class="text-decoration-none">
                        <div class="single-deal">
                            <div class="overlay"></div>
                            <img class="img-fluid w-100" src="assets/img/category-1.webp" alt=""
                                style="width: 100%; height: 30rem; object-fit: cover" />
                            <div class="deal-details">
                                <h6 class="deal-title">Kitchen</h6>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <!-- End category Area -->

    <!-- Testimonial section -->
    <section class="testimonial-area my-5 pt-5" style="background: #f9f9ff">
        <div class="container">
            <div class="text-center">
                <h2 class="heading">Testimonials</h2>
                <p class="m-0">What Client Say About Us?</p>
            </div>
            <div class="testimonial">
                <div class="container p-3">
                    <div class="testimonial-slider">
                        <div class="testimonial-slide">
                            <div class="testimonial_box">
                                <div class="testimonial_box-icon py-2">
                                    <i class="fas fa-quote-right fs-2"></i>
                                </div>
                                <div class="testimonial_box-text">
                                    <p>
                                        I received my suit today,I'm so happy after seeing the
                                        fabric quality as well as stitching is super awesome and
                                        professionally stitched with fast delivery service. Now
                                        I'm your regular customer..I'll buy my all big event suits
                                        from Flareoncorp lnc. Richard Susan Richard Susan
                                        Country:USA I have had a very delightful experience in
                                        shopping with them. They are affordable and i am satisfied
                                        with their 100% original products. Super Friendly and
                                        Comforting Staff. Robert Patricia Robert Patricia Country:
                                        USA I have had a wonderful experience with Flareoncorp
                                        lnc. I placed two orders and their customer service was
                                        awesome. They were very patient throughout the process of
                                        me adding and removing items and made sure everything was
                                        perfect. I am going to make all my future purchases from
                                        Flareoncorp.
                                    </p>
                                </div>
                                <div class="testimonial_box-img"></div>
                                <div class="testimonial_box-name heading fw-bold">
                                    <h4>Naurine Chaurania</h4>
                                </div>
                                <div class="testimonial_box-job">
                                    <p>USA</p>
                                </div>
                            </div>
                        </div>
                        <div class="testimonial-slide">
                            <div class="testimonial_box">
                                <div class="testimonial_box-icon py-2">
                                    <i class="fas fa-quote-right fs-2"></i>
                                </div>
                                <div class="testimonial_box-text">
                                    <p>
                                        Robert Patricia Robert Patricia Country: USA I have had a
                                        wonderful experience with Flareoncorp lnc. I placed two
                                        orders and their customer service was awesome. They were
                                        very patient throughout the process of me adding and
                                        removing items and made sure everything was perfect. I am
                                        going to make all my future purchases from Flareoncorp.
                                        Naurine Chaurania Naurine Chaurania Country: USA I
                                        received my suit today,I'm so happy after seeing the
                                        fabric quality as well as stitching is super awesome and
                                        professionally stitched with fast delivery service. Now
                                        I'm your regular customer..I'll buy my all big event suits
                                        from Flareoncorp lnc.
                                    </p>
                                </div>
                                <div class="testimonial_box-img"></div>
                                <div class="testimonial_box-name heading fw-bold">
                                    <h4>Richard Susan</h4>
                                </div>
                                <div class="testimonial_box-job">
                                    <p>USA</p>
                                </div>
                            </div>
                        </div>
                        <div class="testimonial-slide">
                            <div class="testimonial_box">
                                <div class="testimonial_box-icon py-2">
                                    <i class="fas fa-quote-right fs-2"></i>
                                </div>
                                <div class="testimonial_box-text">
                                    <p>
                                        I have had a very delightful experience in shopping with
                                        them. They are affordable and i am satisfied with their
                                        100% original products. Super Friendly and Comforting
                                        Staff.
                                    </p>
                                </div>
                                <div class="testimonial_box-img"></div>
                                <div class="testimonial_box-name heading fw-bold">
                                    <h4>Robert Patricia</h4>
                                </div>
                                <div class="testimonial_box-job">
                                    <p>USA</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End testimonial section -->
@endsection

@push('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" />
    <style>
        .testimonial-slide {
            padding: 40px 20px;
        }

        .testimonial_box {
            /* background-color: #e0fbfc; */
            /* background: #d1d1d1; */
            /* background: #f9f9ff; */
            padding: 20px 30px;
            border-radius: 15px;
            text-align: center;
            /* box-shadow: 5px 5px 20px rgba(152, 193, 217, 0.493); */
            /* box-shadow: 5px 5px 20px #d1d1d1; */
        }

        .testimonial_box-img {
            padding: 20px 0 10px;
            display: flex;
            justify-content: center;
        }

        .testimonial_box-img img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            border: 2px solid #e5e5e5;
        }
    </style>
@endpush

@push('script')
    <script>
        $(document).ready(function() {
            $(".testimonial-slider").slick({
                autoplay: true,
                autoplaySpeed: 1000,
                speed: 600,
                draggable: true,
                infinite: true,
                slidesToShow: 1,
                slidesToScroll: 1,
                arrows: false,
                dots: false,
                responsive: [{
                        breakpoint: 991,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1,
                        },
                    },
                    {
                        breakpoint: 575,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1,
                        },
                    },
                ],
            });
        });
    </script>
@endpush
