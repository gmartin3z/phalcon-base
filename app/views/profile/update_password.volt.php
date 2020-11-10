{% extends 'main.volt.php' %}

{% block title %}{{ tr('update_password_title') }}{% endblock %}

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
  </style>
{% endblock %}

{% block content %}
  <div class="row">
    <div class="col-lg-6 col-centered">
      <p>
        <div class="card text-white bg-primary mb-6">
          <div class="card-body">
            {{ partial('partials/alerts') }}
            <h2 class="card-title">
              {{ tr('update_password_title') }}
              <br>
              <a href="{{ url('perfil') }}" class="btn btn-secondary btn-sm">
                <i class="fa fa-arrow-left"></i> {{ tr('form_back') }}
              </a>
            </h2>
            <p class="card-text">
              {{ form('perfil/actualizar-contrasenia', 'method': 'post', 'id': 'frm') }}
                <div class="form-group">
                  <label class="control-label" for="contrasenia">{{ tr('upd_password_current_password') }}</label>
                  <div class="form-group">
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text">
                          <i class="fa fa-key"></i>
                        </span>
                      </div>
                      <input type="password" name="contrasenia" class="form-control frm-id">
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label" for="repetir_contrasenia">{{ tr('upd_password_confirm_password') }}</label>
                  <div class="form-group">
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text">
                          <i class="fa fa-key"></i>
                        </span>
                      </div>
                      <input type="password" name="repetir_contrasenia" class="form-control frm-id">
                    </div>
                  </div>
                </div>
                <input type="hidden" name="{{ security.getTokenKey() }}" value="{{ security.getToken() }}">
                <div class="form-group">
                  <button type="reset" class="btn btn-danger btn-reset">
                    <i class="fa fa-eraser"></i>
                    {{ tr('form_clear') }}
                  </button>
                  <button type="submit" class="btn btn-primary btn-success btn-submit">
                    <i class="fa fa-chevron-circle-right"></i>
                    <span>{{ tr('form_send') }}</span>
                  </button>
                </div>
              {{ end_form() }}
            </p>
          </div>
        </div>
      </p>
    </div>
  </div>
{% endblock %}

{% block js_custom %}
  <!-- form -->
  <script type="text/javascript" src="{{ url('js/functions/disableFrm.js') }}"></script>
  <script type="text/javascript" src="{{ url('js/functions/resetData.js') }}"></script>

  <script type="text/javascript">
    (function() {
      // config frm
      let frm_options = {
        frm_id: 'frm',
        submit_btn_id: 'btn_submit',
        reset_btn_id: 'btn_reset',
        submit_btn_class: 'btn-submit',
        reset_btn_class: 'btn-reset',
        ondis_btn_spantxt: '{{ tr("form_wait") }}',
        onenb_btn_spantxt: '{{ tr("form_send") }}',
        onenb_btn_icon: 'fa-chevron-circle-right',
        ondis_btn_icon: 'fa-spinner',
        ondis_btn_icon_spin: 'fa-pulse'
      }

      let captcha_options = {
        frm_id: 'frm',
        captcha_btn_id: 'btn_update_captcha',
        captcha_btn_class: 'btn-update-captcha',
        onenb_btn_icon: 'fa-redo',
        ondis_btn_icon_spin: 'fa-spin'
      }

      // initiallize buttons ids
      let btn_submit_frm;
      let btn_reset_frm;

      // set ids
      function setIds() {
        // select buttons by class
        btn_submit_frm = document.getElementsByClassName(frm_options.submit_btn_class)[0];
        btn_reset_frm = document.getElementsByClassName(frm_options.reset_btn_class)[0];

        // assign an Id to each variable
        btn_submit_frm.setAttribute('id', frm_options.submit_btn_id);
        btn_reset_frm.setAttribute('id', frm_options.reset_btn_id);
      }

      setIds();

      // submit data
      btn_submit_frm.addEventListener('click', function prepareData(event) {
        setTimeout(function wait() {
          disableFrm(frm_options);
        }, 0);
      });

      // reset data
      btn_reset_frm.addEventListener('click', function prepareData(event){
        resetData(frm_options.frm_id);
      });
    })();
  </script>
{% endblock %}
