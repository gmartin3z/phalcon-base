<footer id="footer">
  <div class="row">
    <div class="col-lg-12">
      <ul class="list-unstyled">
        <li class="float-lg-right">
          <a id="top" href="#top">Ir arriba</a>
        </li>
        {% set auth = session.get('auth-identity') %}
        {% if auth['permission_id'] == 1 or auth['permission_id'] == 2 %}
          <li class="nav-item">
            <b>{{ tr('footer_logged_as', ['alias': auth['alias']]) }}</b>
          </li>
        {% else %}
          <li>
            <a href="{{ url('perfil/ingreso') }}">{{ tr('visitor_login') }}</a>
          </li>
          <li>
            <a href="{{ url('perfil/registro') }}">{{ tr('visitor_register') }}</a>
          </li>
        {% endif %}
      </ul>
      <p>
        <a href="#">Bitbank</a>
        {{ tr('footer_copyright', ['date': date('Y') ]) }}.
      </p>
      <p>{{ tr('footer_theme_by') }}
        <a href="https://bootswatch.com/">Bootswatch</a>
      </p>
    </div>
  </div>
</footer>
