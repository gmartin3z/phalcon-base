{% extends 'main.volt.php' %}

{% block title %}404{% endblock %}

{% block content %}
  <div class="row">
    <div class="col-lg-12">
      <div class="page-header">
        {{ partial('partials/alerts') }}
        <h1 id="containers"><b>404</b></h1>
      </div>
    </div>
    <div class="col-lg-12">
      <p>
        <h3 class="text-center"><i class="fa fa-exclamation-triangle fa-4x"></i></h3>
        <h3 class="cta-title text-center">{{ tr('404_not_found') }}</h3>
        <center>
          <a href="{{ url('inicio') }}" class="btn btn-danger">
            <i class="fa fa-chevron-circle-left"></i> {{ tr('go_to_main_page') }}
          </a>
        </center>
      </p>
    </div>
  </div>
{% endblock %}
