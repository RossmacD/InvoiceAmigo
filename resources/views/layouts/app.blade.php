<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'InvoiceAmigo') }}</title>
    {{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
      integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"> --}}

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!--Autocomplete -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <!--Scripts-->
    @yield('headScripts')
    <script src="https://js.stripe.com/v3/"></script>

    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
          }

          @media (min-width: 768px) {
            .bd-placeholder-img-lg {
              font-size: 3.5rem;
            }
          }

          /* GLOBAL STYLES
          -------------------------------------------------- */
          /* Padding below the footer and lighter body text */

          body {
            padding-top: 3rem;
            padding-bottom: 3rem;
            color: #5a5a5a;
          }


          /* CUSTOMIZE THE CAROUSEL
          -------------------------------------------------- */

          /* Carousel base class */
          .carousel {
            margin-bottom: 4rem;
          }
          /* Since positioning the image, we need to help out the caption */
          .carousel-caption {
            bottom: 3rem;
            z-index: 10;
          }

          /* Declare heights because of positioning of img element */
          .carousel-item {
            height: 32rem;
          }
          .carousel-item > img {
            position: absolute;
            top: 0;
            left: 0;
            min-width: 100%;
            height: 32rem;
          }


          /* MARKETING CONTENT
          -------------------------------------------------- */

          /* Center align the text within the three columns below the carousel */
          .marketing .col-lg-4 {
            margin-bottom: 1.5rem;
            text-align: center;
          }
          .marketing h2 {
            font-weight: 400;
          }
          .marketing .col-lg-4 p {
            margin-right: .75rem;
            margin-left: .75rem;
          }


          /* Featurettes
          ------------------------- */

          .featurette-divider {
            margin: 5rem 0; /* Space out the Bootstrap <hr> more */
          }

          /* Thin out the marketing headings */
          .featurette-heading {
            font-weight: 300;
            line-height: 1;
            letter-spacing: -.05rem;
          }


          /* RESPONSIVE CSS
          -------------------------------------------------- */

          @media (min-width: 40em) {
            /* Bump up size of carousel content */
            .carousel-caption p {
              margin-bottom: 1.25rem;
              font-size: 1.25rem;
              line-height: 1.4;
            }

            .featurette-heading {
              font-size: 50px;
            }
          }

          @media (min-width: 62em) {
            .featurette-heading {
              margin-top: 7rem;
            }
          }
        </style>


  </head>
  <body>

    @include('inc.navbar')

    <main role="main" class="container">
        <div class="mt-4">
                <!-- Content -->
                @yield('content')
        </div>
    </main>

    @include('inc.footer')
    <!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script> -->
    {{-- <script>window.jQuery || document.write('<script src="https://getbootstrap.com/docs/4.3/assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
    <script src="https://getbootstrap.com/docs/4.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-xrRywqdh3PHs8keKZN+8zzc5TX0GRTLCcmivcbNJWm2rs5C8PRhcEn3czEjhAO9o" crossorigin="anonymous"></script> --}}
    <!-- Scripts -->
    @yield('scripts');


    <script src="{{ asset('js/app.js') }}" defer></script>

    @if(session('status')) {{-- <- If session key exists --}}
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{session('status')}} test{{-- <- Display the session value --}}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
@endif

  <script>
      //close the alert after 3 seconds.
      $(document).ready(function(){
        setTimeout(function() {
            $(".alert").alert('close');
        }, 30000);
      });
  </script>
  </body>
</html>
