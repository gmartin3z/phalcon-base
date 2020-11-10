{% extends 'main.volt.php' %}

{% block title %}{{ tr('index_title') }}{% endblock %}

{% block content %}
  <div class="row">
    <div class="col-lg-12">
      <div class="page-header">
        {{ partial('partials/verify') }}
        {{ partial('partials/alerts') }}
        <h1 id="containers">{{ tr('index_title') }}</h1>
      </div>
      <div class="jumbotron">
        <h1 class="display">{{ tr('index_intro') }}</h1>
        <p class="lead">
          {{ tr('index_description') }}
        </p>
      </div>
    </div>
    <div class="col-lg-12">
      <p>
        {{ tr('index_content') }}
      </p>
    </div>
  </div>
{% endblock %}
