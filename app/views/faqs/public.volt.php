{% extends 'main.volt.php' %}

{% block title %}{{ tr('faqs_title') }}{% endblock %}

{% block content %}
  <div class="row">
    <div class="col-lg-12">
      <div class="page-header">
        {{ partial('partials/alerts') }}
        <h1 id="containers">{{ tr('faqs_title') }}</h1>
      </div>
      <div class="jumbotron">
        <h1 class="display">{{ tr('faqs_intro') }}</h1>
        <p class="lead">
          {{ tr('faqs_description') }}
        </p>
      </div>
    </div>
    <div class="col-lg-12">
      <p>
        {{ tr('faqs_content') }}
      </p>
    </div>
  </div>
{% endblock %}
