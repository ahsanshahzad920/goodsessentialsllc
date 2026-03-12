 <!-- start footer Area -->
 <footer class="footer-area section_gap py-5">
     <div class="container">
         <div class="row">
             <div class="col-md-3">
                 <div class="single-footer-widget">
                     <a href="{{ route('index') }}" class="text-decoration-none">
                         <img src="{{ asset('dashasset/img/SVG-Logo-White.svg') }}" alt=""
                             class="img-fluid w-50" /> </a>
                     <p class="mt-3">
                        Flareoncorp offers a curated selection of baby, automotive, beauty, and home products, providing quality and value for your everyday needs.
                     </p>
                     <div class="d-flex align-items-center gap-3">
                         <a href="https://www.facebook.com" class="text-decoration-none text-white fs-3">
                             <i class="fa-brands fa-facebook"></i>
                         </a>
                         <a href="https://www.instagram.com" class="text-decoration-none text-white fs-3">
                             <i class="fa-brands fa-square-instagram"></i>
                         </a>
                         <a href="https://www.twitter.com" class="text-decoration-none text-white fs-3">
                             <i class="fa-brands fa-square-x-twitter"></i>
                         </a>
                         <a href="https://www.youtube.com" class="text-decoration-none text-white fs-3">
                             <i class="fa-brands fa-square-youtube"></i>
                         </a>

                     </div>
                 </div>
             </div>
             <div class="col-md-3">
                 <div class="single-footer-widget">
                     <h3 class="text-white fw-bold">Useful Links</h3>
                     <ul class="fs-6 useful-hover">
                         <li class="mt-3">
                             <a href="{{ route('index') }}" class="text-decoration-none text-white"><i
                                     class="fa-solid fa-chevron-right me-2"></i>
                                 Home</a>
                         </li>
                         <li class="mt-2">
                             <a href="{{ route('about') }}" class="text-decoration-none text-white"><i
                                     class="fa-solid fa-chevron-right me-2"></i>
                                 About</a>
                         </li>
                         <li class="mt-2">
                             <a href="{{ route('shop') }}" class="text-decoration-none text-white"><i
                                     class="fa-solid fa-chevron-right me-2"></i>
                                 Shop</a>
                         </li>
                         <li class="mt-2">
                             <a href="{{ route('contacts.index') }}" class="text-decoration-none text-white"><i
                                     class="fa-solid fa-chevron-right me-2"></i>
                                 Contact
                                 Us</a>
                         </li>
                         <li class="mt-2">
                             <a href="{{ route('cart') }}" class="text-decoration-none text-white"><i
                                     class="fa-solid fa-chevron-right me-2"></i> Add
                                 to
                                 cart</a>
                         </li>
                     </ul>
                 </div>
             </div>
             <div class="col-md-3">
                 <div class="single-footer-widget">
                     <h3 class="text-white fw-bold">Categories</h3>
                     <ul class="fs-6 useful-hover">
                         <li class="mt-3">
                             <a href="{{ route('shop', 'Automotive') }}" class="text-decoration-none text-white"><i
                                     class="fa-solid fa-chevron-right me-2"></i>Automotive</a>
                         </li>
                         <li class="mt-2">
                             <a href="{{ route('shop', 'Baby Products') }}" class="text-decoration-none text-white"><i
                                     class="fa-solid fa-chevron-right me-2"></i> Baby
                                 Products</a>
                         </li>
                         <li class="mt-2">
                             <a href="{{ route('shop', 'Beauty Products') }}" class="text-decoration-none text-white"><i
                                     class="fa-solid fa-chevron-right me-2"></i>
                                 Beauty</a>
                         </li>
                         <li class="mt-2">
                             <a href="{{ route('shop', 'Home and Kitchen') }}"
                                 class="text-decoration-none text-white"><i class="fa-solid fa-chevron-right me-2"></i>
                                 Kitchen</a>
                         </li>
                     </ul>
                 </div>
             </div>
             <div class="col-md-3">
                 <div class="single-footer-widget">
                     <h3 class="text-white fw-bold">Payment Method</h3>
                     <img src="{{ asset('assets/img/payment-method.png') }}" alt="" class="img-fluid mt-3" />
                 </div>
             </div>
         </div>
     </div>
 </footer>
 <!-- End footer Area -->
