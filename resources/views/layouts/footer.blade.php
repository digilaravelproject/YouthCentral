<!-- Start Footer -->
      <footer>
        <div class="row footer-info">
          <div class="footer-col footer-terms">
            {{-- <i class="fa-solid fa-file-contract"></i> --}}
            <a href="{{ route('terms.of.use') }}">Terms of Use</a>
          </div>
          <div class="footer-col footer-privacy">
            {{-- <i class="fa-solid fa-shield-halved"></i> --}}
            <a href="{{ route('privacy.policy') }}">Privacy Policy</a>
          </div>
          <div class="footer-col social-networks">
            {{-- <i class="fa-solid fa-share-nodes"></i> --}}
            <a href="https://www.instagram.com/youthcentral_india/"><i class="fa-brands fa-instagram"></i></a>
            <a href="https://x.com/YouthcentralInd"><i class="fa-brands fa-x-twitter"></i></a>
            <a href="http://www.linkedin.com/in/youth-central-india-a68a562b9"><i class="fa-brands fa-linkedin"></i></a>
          </div>
          <div class="footer-col footer-links">
            {{-- <i class="fa-solid fa-bars"></i> --}}
            <a href="{{ route('about') }}">About Us</a>
            <a href="{{ route('contact') }}">Contact Us</a>
            {{-- <a href="{{ route('directory.index') }}">Business Directory</a>
            <a href="{{ route('vendor.register') }}">Add Your Business</a> --}}
            <a href="{{ route('refund.policy') }}">Refund Policy</a>
            <a href="{{ route('infringement.policy') }}">Infringement Policy</a>
            {{-- <a href="{{ route('login') }}">Login</a>
            <a href="{{ route('register') }}">Register</a> --}}
          </div>
          <div class="footer-col footer-contact">
            {{-- <i class="fa-solid fa-location-dot"></i> --}}
            <div class="footer-address">
              Business Enquiries
            </div>
            <div class="footer-contact-data">
              +91 885-795-0463
            </div>
          </div>
        </div>
        <div class="row footer-logo">
          <a href="#">
            <img alt="" src="assets/images/logo.png"/>
          </a>
        </div>
        <div class="row footer-credits">
          <div class="copyright">
            (C) 2025 Youthcentuary Academy Private Limited, All right reserved
          </div>
        </div>
      </footer>
      <!-- End Footer -->

<style>
/* Simple footer fix for home page */
footer {
    display: block !important;
    visibility: visible !important;
}

.footer-info {
    display: table !important;
    width: 100% !important;
    table-layout: fixed !important;
}

.footer-col {
    display: table-cell !important;
    vertical-align: middle !important;
    text-align: center !important;
    padding: 20px 10px !important;
}

.social-networks a {
    display: inline-block !important;
    margin: 0 5px !important;
    color: var(--primary-color) !important;
    font-size: 18px !important;
}
.social-networks a i{
    color: var(--primary-color) !important;

}

.footer-logo {
    text-align: center !important;
    padding: 20px 0 !important;
}

.footer-credits {
    text-align: center !important;
    padding: 15px 0 !important;
}

/* Mobile responsive */
@media (max-width: 768px) {
    .footer-info {
        display: block !important;
    }

    .footer-col {
        display: block !important;
        width: 100% !important;
        padding: 8px 10px !important;
        margin-bottom: 5px !important;
    }

    .footer-col:last-child {
        margin-bottom: 0 !important;
    }

    .footer-contact {
        display: none !important;
    }

    /* Reduce footer logo padding on mobile */
    .footer-logo {
        padding: 10px 0 !important;
    }

    /* Reduce footer credits padding on mobile */
    .footer-credits {
        padding: 8px 0 !important;
    }

    /* Compact social media icons on mobile */
    .social-networks a {
        margin: 0 3px !important;
        font-size: 16px !important;
    }

    /* Reduce icon margins on mobile */
    .footer-col i {
        margin-right: 6px !important;
        font-size: 14px !important;
    }
    .footer-col i {
        color: var(--primary-color) !important;
    }
}

/* Extra small mobile devices */
@media (max-width: 480px) {
    .footer-col {
        padding: 6px 8px !important;
        margin-bottom: 0px !important;
    }

    .footer-logo {
        padding: 8px 0 !important;
    }

    .footer-credits {
        padding: 6px 0 !important;
        font-size: 12px !important;
    }

    .social-networks a {
        margin: 0 2px !important;
        font-size: 14px !important;
    }

    .footer-col i {
        margin-right: 4px !important;
        font-size: 12px !important;
    }
}
</style>
