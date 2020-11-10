<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>{{ tr('update_password') }} :: SSHMANAGER</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ tr('update_password') }}">
    <meta name="author" content="The Drunk Team">
    <style type="text/css">
      body {
        font-family: 'Calibri', 'Candara', 'Segoe', 'Segoe UI', 'Optima', 'Arial', 'sans-serif';
      }
    </style>
  </head>
  <body>
    <h2>{{ tr('acc_update_password_greetings', ['alias': alias]) }}</h2>
    {{ tr('acc_update_password_instructions') }}
    <p>
      <code>
        {{ config.application.publicUrl }}{{ url('perfil/actualizar-contrasenia/finalizar/' ~ admin_id ~ '/' ~ token) }}
      </code>
    </p>
    {{ tr('acc_update_password_notice') }}
    <hr>
    <small>
      {{ tr('acc_update_password_note') }}
    </small>
  </body>
</html>
