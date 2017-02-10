<!-- NAVIGATION MENU BAR -->

<nav class="nav navbar-static-top navbar-default dm-nav-bar">
  <div class="container">

    <!--NAVBAR HEADER-->
    <div class="navbar-header">
      <!--Navbar collapse button (on small screens)-->
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#MyNavBarCollapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>

      <!--Brand Logo-->
      <a class="navbar-brand" href="{{ env('WEB_APP_ENDPOINT', 'http://web.docmanager.app') . '/' }}"><img src="/pics/dm-icon.png" class="small-logo"></a>
    </div>

    <!--NAVBAR MENU-->
    <div class="collapse navbar-collapse" id="MyNavBarCollapse">
      <!--Set of buttons aligned to the right-->
      <ul class="nav navbar-nav navbar-right dm-link">
        <li><a href="{{ env('WEB_APP_ENDPOINT', 'http://web.docmanager.app') . '/' }}">About</a></li>
        <li><a href="{{ env('WEB_APP_ENDPOINT', 'http://web.docmanager.app') . '/' }}">Contact</a></li>
        <li><a href="{{ env('WEB_APP_ENDPOINT', 'http://web.docmanager.app').'/login' }}" class="dm-nav-btn-link"><button type="button" class="btn navbar-btn dm-btn-default">Sign in</button></a></li>
        <li><a href="{{env('WEB_APP_ENDPOINT', 'http://web.docmanager.app'). '/register' }}" class="dm-nav-btn-link"><button type="button" class="btn navbar-btn dm-btn">Sign up</button></a></li>
      </ul>
    </div>

  </div>
</nav>