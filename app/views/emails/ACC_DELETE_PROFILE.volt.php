<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>{{ tr('delete_profile') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ tr('delete_profile') }}">
    <meta name="author" content="The Drunk Team">
    <style type="text/css">
      body {
        font-family: 'Calibri', 'Candara', 'Segoe', 'Segoe UI', 'Optima', 'Arial', 'sans-serif';
      }
    </style>
  </head>
  <body>
    <h2>{{ tr('acc_acc_delete_profile_greetings', ['alias': alias]) }}</h2>
    <p>
      <strong>
        {{ tr('del_pfr_disclaimer') }}
      </strong>
    </p>
    {{ tr('acc_delete_profile_instructions') }}
    <p>
      <code>
        {{ config.application.publicUrl }}{{ url('perfil/borrar/finalizar/' ~ admin_id ~ '/' ~ token) }}
      </code>
    </p>
    {{ tr('acc_delete_profile_notice') }}
    <hr>
    <small>
      {{ tr('acc_delete_profile_note') }}
    </small>
  </body>
</html>
