{% set auth_identity = session.get('auth-identity') %}
{% set creation = auth_identity['created_at'] %}
{% set activation = auth_identity['activated_at'] %}
{% set expiration_day = addExtraDay(creation, time_lang) %}
{% set expiration_hour = extractHour(creation) %}

{% if creation %}
  {% if !activation %}
    <div class="alert alert-warning alert-dismissible fade in" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
      <i class="fa fa-exclamation-circle"></i>
      <strong>{{ tr('alert_warning') }}</strong>
      <ul>
        <li>{{ tr('verify_reminder') }} {{ expiration_day }} {{ expiration_hour }}</li>
      </ul>
    </div>
  {% endif %}
{% endif %}
