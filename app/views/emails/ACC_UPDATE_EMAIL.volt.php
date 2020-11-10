<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>{{ tr('update_email') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ tr('update_email') }}">
    <meta name="author" content="The Drunk Team">
    <style type="text/css">
      body {
        font-family: 'Calibri', 'Candara', 'Segoe', 'Segoe UI', 'Optima', 'Arial', 'sans-serif';
      }
    </style>
  </head>
  <body>
    <h2>{{ tr('acc_update_email_greetings', ['alias': alias]) }}</h2>
    {{ tr('acc_update_email_instructions') }}
    <p>
      <code>
        {{ config.application.publicUrl }}{{ url('perfil/actualizar-correo/finalizar/' ~ admin_id ~ '/' ~ token) }}
      </code>
    </p>
    {{ tr('acc_update_email_notice') }}
    <hr>
    <small>
      {{ tr('acc_update_email_note') }}
    </small>
  </body>
</html>
