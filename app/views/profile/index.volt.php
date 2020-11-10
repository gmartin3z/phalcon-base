{% extends 'main.volt.php' %}

{% block title %}{{ tr('profile_title') }}{% endblock %}

{% block css_custom %}
  <style type="text/css">
    .btn-update-captcha {
      padding: 6px 6px;
      font-size: 60%;
      line-height: 1.2;
    }

    .col-centered {
      float: none;
      margin: 0 auto;
    }

    .break-words {
      word-spacing: 400px;
    }
  </style>
{% endblock %}

{% block content %}
  <div class="row">
    <div class="col-lg-8 col-centered">
      <p>
        <div class="card text-white bg-primary mb-8">
          <div class="card-body">
            <h2 class="card-title">{{ tr('control_your_profile') }}</h2>
            <p class="card-text">
              {{ partial('partials/alerts') }}
              <br>
              <div class="row">
                <div class="col-sm-6">
                  <div class="card text-white bg-info mb-3" style="max-width: 18rem;">
                    <div class="card-body">
                      <h5 class="card-title">
                        <center>
                          <i class="fa fa-3x fa-address-card"></i>
                        </center>
                      </h5>
                      <p class="card-text">
                        <h3>
                          <center>
                            <span class="break-words">{{ tr('update_alias') }}</span>
                          </center>
                        </h3>
                      </p>
                      <a href="{{ url('perfil/actualizar-alias') }}" class="btn btn-info btn-lg btn-block">
                        <i class="fas fa-arrow-circle-right"></i>
                      </a>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="card text-white bg-info mb-3" style="max-width: 18rem;">
                    <div class="card-body">
                      <h5 class="card-title">
                        <center>
                          <i class="fa fa-3x fa-envelope-open"></i>
                        </center>
                      </h5>
                      <p class="card-text">
                        <h3>
                          <center>
                            <span class="break-words">{{ tr('update_email') }}</span>
                          </center>
                        </h3>
                      </p>
                      <a href="{{ url('perfil/actualizar-correo') }}" class="btn btn-info btn-lg btn-block">
                        <i class="fa fa-arrow-circle-right"></i>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-sm-6">
                  <div class="card text-white bg-warning mb-3" style="max-width: 18rem;">
                    <div class="card-body">
                      <h5 class="card-title">
                        <center>
                          <i class="fa fa-3x fa-key"></i>
                        </center>
                      </h5>
                      <p class="card-text">
                        <h3>
                          <center>
                            <span class="break-words">{{ tr('update_password') }}</span>
                          </center>
                        </h3>
                      </p>
                      <a href="{{ url('perfil/actualizar-contrasenia') }}" class="btn btn-warning btn-lg btn-block">
                        <i class="fas fa-arrow-circle-right"></i>
                      </a>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="card text-white bg-danger mb-3" style="max-width: 18rem;">
                    <div class="card-body">
                      <h5 class="card-title">
                        <center>
                          <i class="fa fa-3x fa-user-times"></i>
                        </center>
                      </h5>
                      <p class="card-text">
                        <h3>
                          <center>
                            <span class="break-words">{{ tr('delete_profile') }}</span>
                          </center>
                        </h3>
                      </p>
                      <a href="{{ url('perfil/borrar') }}" class="btn btn-danger btn-lg btn-block">
                        <i class="fa fa-arrow-circle-right"></i>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </p>
          </div>
        </div>
      </p>
    </div>
  </div>
{% endblock %}
