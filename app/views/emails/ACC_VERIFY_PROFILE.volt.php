<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>{{ tr('verify_profile') }} :: SSHMANAGER</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ tr('verify_profile') }}">
    <meta name="author" content="The Drunk Team">
    <style type="text/css">
      body {
        font-family: 'Calibri', 'Candara', 'Segoe', 'Segoe UI', 'Optima', 'Arial', 'sans-serif';
      }
    </style>
  </head>
  <body>
    <h2>{{ tr('acc_verify_profile_greetings', ['alias': alias]) }}</h2>
    {{ tr('acc_verify_profile_instructions') }}
    <p>
      <code>
        {{ config.application.publicUrl }}{{ url('perfil/verificar/finalizar/' ~ admin_id ~ '/' ~ token) }}
      </code>
    </p>
    {{ tr('acc_verify_profile_notice') }}
    <hr>
    <small>
      {{ tr('acc_verify_profile_note') }}
    </small>
  </body>
</html>
