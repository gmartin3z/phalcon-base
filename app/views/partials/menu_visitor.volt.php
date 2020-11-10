<div class="navbar navbar-expand-lg fixed-top navbar-dark bg-primary">
  <div class="container">
    <a href="{{ url('inicio') }}" class="navbar-brand"><img src="{{ url('img/logo.svg') }}" style="width:25px"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="{{ url('inicio') }}">
            <i class="fa fa-home"></i>
            {{ tr('visitor_home') }}
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ url('preguntas-frecuentes') }}">
            <i class="fa fa-question"></i>
            {{ tr('visitor_faqs') }}
          </a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" id="opt_menu">
            <i class="fa fa-chess-pawn"></i>
            {{ tr('visitor_options') }}
            <span class="caret"></span>
          </a>
          <div class="dropdown-menu" aria-labelledby="opt_menu">
            <a class="dropdown-item" href="#">
              <i class="fa fa-chess-knight"></i>
              {{ tr('visitor_example') }} 1
            </a>
            <a class="dropdown-item" href="#">
              <i class="fa fa-chess-rook"></i>
              {{ tr('visitor_example') }} 2
            </a>
            <!-- sep -->
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">
              <i class="fa fa-chess-bishop"></i>
              {{ tr('visitor_example') }} 3
            </a>
            <a class="dropdown-item" href="#">
              <i class="fa fa-chess-queen"></i>
              {{ tr('visitor_example') }} 4
            </a>
            <!-- sep -->
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">
              <i class="fa fa-chess-king"></i>
              {{ tr('visitor_example') }} 5
            </a>
          </div>
        </li>
      </ul>
      <ul class="nav navbar-nav ml-auto">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" id="opt_menu">
            <i class="fa fa-globe-americas"></i>
            <span class="caret"></span>
          </a>
           <div class="dropdown-menu dropdown-menu-right" aria-labelledby="opt_menu">
            <a class="dropdown-item" href="#"
              onclick="event.preventDefault();
              document.getElementById('lang').value = 'en';
              document.getElementById('lang_form').submit();">en
            </a>
            <a class="dropdown-item" href="#"
              onclick="event.preventDefault();
              document.getElementById('lang').value = 'es';
              document.getElementById('lang_form').submit();">es
            </a>
            <form id="lang_form" action="{{ url('idioma') }}" method="post" style="display: none;">
              <input type="hidden" name="lang" id="lang">
              </input>
            </form>
          </div>
        </li>
      </ul>
    </div>
  </div>
</div>
