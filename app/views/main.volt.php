<!DOCTYPE html>
<html lang="{{ app_lang }}">
  <head>
    <meta charset="utf-8">
    <title>{% block title %}{% endblock %} :: Phalcon-base</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="phalcon-database">
    <meta name="keywords" content="starter page, phalcon, webapp">
    <meta name="author" content="gmartin3z">

    <!-- css libraries -->
    <link rel="stylesheet" href="{{ url('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ url('css/custom.min.css') }}">
    <link rel="stylesheet" href="{{ url('css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ url('css/solid.css') }}">
    <link rel="stylesheet" href="{{ url('css/lato.css') }}">

    <link rel="shortcut icon" href="{{ url('favicon.ico') }}">

    <!-- custom css -->
    {% block css_extra_libs %}{% endblock %}

    {% block css_custom %}{% endblock %}
  </head>
  <body>

    <div class="container">
      <!-- header -->
      {% set auth = session.get('auth-identity') %}
      {% if auth['permission_id'] == 1 %}
        {{ partial('partials/menu_admin') }}
      {% elseif auth['permission_id'] == 2 %}
        {{ partial('partials/menu_limited') }}
      {% else %}
        {{ partial('partials/menu_visitor') }}
      {% endif %}
      <!-- end header -->

      <!-- content -->
      {% block content %}{% endblock %}
      <!-- end content -->

      <!-- footer -->
      {{ partial('partials/footer') }}
      <!-- end footer -->
    </div>

    <!-- js libraries -->
    <script type="text/javascript" src="{{ url('js/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/popper.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/custom.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/scroll.js') }}"></script>

    <!-- custom js -->
    {% block js_extra_libs %}{% endblock %}

    {% block js_custom %}{% endblock %}  
  </body>
</html>
