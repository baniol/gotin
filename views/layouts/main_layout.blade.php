<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Gotin &middot; Laravel 3 auth bundle</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    {{ Asset::container('header')->styles() }}

    @yield('styles')

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="/bundles/gotin/js/html5shiv.js"></script>
    <![endif]-->
    <link rel="shortcut icon" href="/favicon.png">
  </head>

  <body class="main">

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <a class="brand pull-left" href="/">Gotin</a>
          <ul class="nav pull-right">
            <li>
              {{HTML::link_to_action('gotin::editprofile',Auth::user()->username,array(),array('class'=>'navbar-link'))}}
            </li>
            <li class="divider-vertical"></li>
            <li class="dropdown">
              {{HTML::link_to_action('gotin::login@logout','Logout','',array('class'=>'navbar-link','id'=>'logout'))}}
            </li>
          </ul>
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row-fluid">
        <div class="span3">
          <div class="sidebar-nav">
            <ul class="nav nav-list bs-docs-sidenav">
              @if(Auth::is('Admin'))
              <li class="@if($active=='users') active @endif">
                {{HTML::link_to_action('gotin::users','Users')}}
              </li>
              <li class="@if($active=='roles') active @endif">
                {{HTML::link_to_action('gotin::roles','Roles')}}
              </li>
              @else
              <li class="">
                {{HTML::link_to_action('gotin::dashboard','Some action...')}}
              </li>
              <li class="">
                {{HTML::link_to_action('gotin::dashboard','Some action...')}}
              </li>
              @endif
            </ul>
          </div>
        </div>

        <div class="span9">

          <ul class="breadcrumb shadowed">
            <li>
              {{HTML::link_to_action('gotin::dashboard','Start')}}
              <span class="divider">/</span>
            </li>
            @foreach($b_links as $l)
            <li>
              {{$l}}
              <span class="divider">/</span>
            </li>
            @endforeach
          </ul>

          @yield('content')

        </div>

      </div>

      <hr>

      <footer>
        <p>2013 &copy; Gotin</p>
      </footer>

    </div><!--/.fluid-container-->

    <script src="/bundles/gotin/js/jquery.js"></script>
    <script src="/bundles/gotin/js/bootstrap.min.js"></script>
    <!-- // <script src="/js/pages.js"></script> -->

    @yield('scripts')

  </body>
</html>
