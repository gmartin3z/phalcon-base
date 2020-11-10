{% extends 'main.volt.php' %}

{% block title %}500{% endblock %}

{% block content %}
  <div class="row">
    <div class="col-lg-12">
      <div class="page-header">
        {{ partial('partials/alerts') }}
        <h1 id="containers"><b>500</b></h1>
      </div>
    </div>
    <div class="col-lg-12">
      <p>
        <h3 class="text-center"><i class="fa fa-times-circle fa-3x"></i></h3>
        <h3 class="cta-title text-center">{{ tr('500_internal_server') }}</h3>
      </p>
    </div>
  </div>
{% endblock %}
