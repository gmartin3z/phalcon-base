# phalcon-base

### Instalación

**IMPORTANTE**

Estos pasos deben ser realizados por alguien que tenga conocimiento de cómo configurar servidores, bases de datos y gestión de archivos, de lo contrario el proyecto puede quedar configurado incorrectamente y funcionar mal. Poner mucha atención a lo siguiente. La estructura y los nombres de carpetas y archivos serán diferentes de acuerdo al proveedor de hosting.

**Esto es solo una guía general y posiblemente se requieran ajustes extras.**

**Antes de llevar a cabo estos pasos primero deben leerse con atención.**

#### Instrucciones

Suponiendo que el .zip de phalcon-base se haya descomprimido en `/home/miusuario/` debería existir una estructura como ésta:

```html
.
|── ...
|── home
|   └── miusuario
|       └── phalcon-base
|           ├── app
|           ├   |── config
|           │   ├── controllers
|           |   |── ...
|           │   ├── models
|           |   |── ...
|           │   └── views
|           |── backups
|           |   └── ...
|           |── cache
|           |   └── ...
|           |── database
|           |   └── phalcon_base_app.sql
|           |── logs
|           |   └── ...
|           |── public
|           |   |── css
|           |   |── fonts
|           |   |── images
|           |   └── ...
|           |── ...
|           |── manager.sh
|──  ...
|── var
|    └── www
|       └── html
|           └── index.html
└── ...
```

- Ubicarse en la carpeta raíz del proyecto: 
  
  ```shell
  cd /home/miusuario/phalcon-base
  ```

- Después instalar las dependencias con `composer install`.

- Crear una base de datos en mysql (llamada `phalcon_base_app` de preferencia, aunque el nombre es libre):
  
  ```sql
  CREATE DATABASE IF NOT EXISTS `nombre_base_de_datos` CHARACTER SET `utf8mb4` DEFAULT CHARACTER SET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
  ```

- En caso de que el proveedor no permitiera la creación de nuevas bases de datos queda intentar modificar la existente con los parámetros de arriba (`CHARACTER SET utf8mb4 DEFAULT CHARACTER SET=utf8mb4 COLLATE=utf8mb4_unicode_ci`).

- Y si fallara igual entonces tocará configurar con la base de datos por defecto que traiga el proveedor tal cual.

- Importar la estructura de la base datos:
  
  ```shell
  mysql -h(ip_host_hosting) -u(usuario_mysql) -p(contraseña_mysql) (nombre_base_de_datos) < /databases/phalcon_base_app.sql
  ```
  
  *Claro que sin los paréntesis y con los parámetros del usuario creado o los que haya dado el proveedor.*

- Crear un nuevo usuario en mysql (con el mismo nombre que la base de datos) o modificar el existente asignándole permisos:
  
  ```sql
  GRANT Grant option ON usuario_base_de_datos.* TO 'nombre_base_de_datos'@'ip_host_hosting';
  GRANT Insert ON usuario_base_de_datos.* TO 'nombre_base_de_datos'@'ip_host_hosting';
  GRANT References ON usuario_base_de_datos.* TO 'nombre_base_de_datos'@'ip_host_hosting';
  GRANT Select ON usuario_base_de_datos.* TO 'nombre_base_de_datos'@'ip_host_hosting';
  GRANT Show view ON usuario_base_de_datos.* TO 'nombre_base_de_datos'@'ip_host_hosting';
  GRANT Trigger ON usuario_base_de_datos.* TO 'nombre_base_de_datos'@'ip_host_hosting';
  GRANT Update ON usuario_base_de_datos.* TO 'nombre_base_de_datos'@'ip_host_hosting';
  GRANT Delete ON usuario_base_de_datos.* TO 'nombre_base_de_datos'@'ip_host_hosting';
  FLUSH PRIVILEGES;
  ```

- Borrar la carpeta html:
  
  ```shell
  rm -rf /var/www/html
  ```

- Crear un enlace directo:
  
  ```shell
  ln -s /home/miusuario/phalcon-base/public /var/www/html
  ```

- Ubicarse otra vez en la carpeta raíz del proyecto. Asignar permisos a los siguientes directorios y al script:
  
  ```shell
  chmod -R 777 /home/miusuario/phalcon-base/backups
  chmod -R 777 /home/miusuario/phalcon-base/cache
  chmod -R 777 /home/miusuario/phalcon-base/logs
  chmod 775 /manager.sh
  ```

- Reiniciar servicios http y de la base de datos para aplicar los cambios.

#### Configurar por primera vez el proyecto

- Checar que haya un archivo llamado `config.php` en la ruta `/home/miusuario/phalcon-base/app/config`. En caso que no esté hay que crear uno:
  
  ```shell
  cd /home/miusuario/phalcon-base/app/config
  cp config_example.php config.php
  ```

- Abrir el archivo `config.php` Las partes a editar son la siguientes:
  
  ```php
  # Base de datos
  'database' => [
      'adapter'   => 'MYSQL',
      'host'      => '127.0.0.1', # <- ip/host base de datos hosting
      'username'  => 'us3r', # <- Nombre de usuario creado/hosting
      'password'  => 'p@ss', # <- Contraseña de usuario creado/hosting
      'dbname'    => 'phalcon_base_app', # <- Nombre de base de datos creada/hosting
      'charset'   => 'utf8mb4', # <- Codificación de base de datos creada/hosting
  ],
  
  # Aplicación
  'application' => [
      'publicUrl'   => 'http://localhost', # <- Dominio del sitio
      'baseUri'     => '/phalcon-base/public/', # <- Ruta carpeta public
      'cryptSalt'   => '', # <- Token generado
      'defaultTimezone' => 'America/Mexico_City', # <- Zona horaria
      'allowSignup'     => true # <- Bloquear/permitir nuevos registros de usuarios
  ],
  
  # Correo
  'mail' => [
      'useMail' => true,  # <- Activar envío de correos
      'fromName' => 'PHALCON-BASE', # <- Nombre de usuario de correo
      'fromEmail' => 'phalconbase@email.app', # <- Dirección de correo
      'smtp' => [
          'server' => '127.0.0.1', # <- ip/host correo
          'port' => 25, # <- Puerto del correo
          'security' => '', # <- Protocolo de encriptado de correo
          'username' => 'phalconbase@email.app', # <- Dirección de correo
          'password' => 'ph@Lc0n_B4s3' # <- Contraseña de autenticación
      ]
  ],
  
  # Status
  'status' => [
      'maintenance' => false, # <- Activar modo mantenimiento
      'debuggable'  => false # <- Desactivar mensajes de error en pantalla
  ]
  ```

He aquí algunas observaciones:

- Escribir los parámetros de acuerdo a como el archivo de configuración los vaya pidiendo.

- Para agregar el dominio propio es necesario especificarlo al iugal que en el ejemplo agregando **http://** y **https://** al inicio de la dirección (si el dominio es abc.net entonces se guarda como http://abc.net, si es xyz.com entonces se guarda como https://xyz.com, etc).

- En `application -> baseUri` agregar la ruta relativa que apunta al directorio `public`. En el ejemplo anterior de la estructura de carpetas quedaría como `/phalcon-base/public`.

- En caso de ser una primera instalación se debe generar un nuevo token para crear contraseñas. Luego ese valor se va guadar en `application -> cryptSalt`. Para crear un token hay que ejecutar el script de mantenimiento (`manager.sh`) y después se selecciona [**C**]ontinuar > Opción [**5**]. Si ya se estaba usando el proyecto o trabajando en modificarlo simplemente se pone el token que se usaba previamente y listo (para evitar que los usuarios deban reestablecer su cuenta). 

- Para saber más sobre las zonas horarias disponibles consultar el manual de php sobre zonas horarias.

- Al inicializar el proyecto y prevenir nuevos registros de cuentas (por seguridad, ya que cualquier persona que verifique su cuenta podría controlar el sistema) el valor de `application -> allowSignup` es `false`. Para permitir la creación de nuevas cuentas el valor se debe cambiar a `true` (recomendado si es la primera vez que se van a configurar cuentas principales).

- Recordar que los parámetros del correo dependen del proveedor de dicho servicio.

- Donde dice `mail -> useMail`, si el valor es `true` entonces se enviarán correos normales mediante la dirección configurada (recomendado en producción), si es `false` entonces solo se van a guardar en un archivo `log` (recomendado en desarrollo).

- En la sección `mail -> smtp -> security` en caso de no usar seguridad ese campo puede quedar vacío.

- Respecto a `status -> maintenance` si el valor es `true` se deshabilitarán todas las funciones de la página. Si es `false` entonces funcionará normalmente.

- Y en `status -> debuggable` cuando el valor está en `true` los errores que ocurran durante el uso del sitio se mostrarán en pantalla. Para ocultarlos el valor debe estar en `false` (lo recomendable estando en producción, pero aún se registrarán en un archivo `log`).

- Antes de ponerlo oficialmente al público, abrir el sitio de la organización en un navegador y comprobar que la instalación y configuración sean correctas.

#### Rutas principales

**Ingreso al administrador:** http(s)://{dominiositio}/perfil/ingreso

**Registro de nuevas cuentas:** http(s)://{dominiositio}/perfil/registro

**Recuperación:** http(s)://(dominiositio)/perfil/recuperar

#### Mantenimiento

Por el momento el script dedicado para ello únicamente tendrá la función de limpiar caché de archivos, corregir permisos en las carpetas necesarias e importar y exportar información de la base de datos. Ah, y también se pueden generar nuevos tokens cuando sea requiera.

Se necesita que `mysql`, `mysqldump`, `gzip` y `php` se encuentren disponibles en sistema.

- Ejecutar la herramienta de mantenimiento del sitio (`./manager.sh`).

- Confirmar que se desea comtinuar.

- En caso que no se ejecute el script se deben verificar sus permisos y que exista el archivo de configuración. Si no existe hay que crearlo:
  
  ```shell
  cd /home/miusuario/phalcon-base/app/config
  cp config_console_example.ini config_console.ini
  ```

- El contenido será similar a lo siguiente:
  
  ```
  # configuration file for manager.sh
  # write the params here
  #
  
  mysqldump_route="mysqldump"
  mysql_route="mysql"
  mysql_host="127.0.0.1"
  mysql_database="phalcon_base_app"
  mysql_user="us3r"
  mysql_password="p@ss"
  gzip_route="gzip"
  php_route="php"
  ```

- Aquí no hay tanta complicación ya que un buen hosting debe ofrecer acceso ssh y ftp, aparte de contar con los binarios de `mysql`, `mysqldump`, `php` y `gzip`, que pueden invocarse directamente en terminal, sin necesidad de especificar la ruta completa en donde están instalados. En el extraño caso que no sea así se tendría que poner la ruta completa (`mysql_route=/usr/bin/mysql`, `gzip_route=/bin/gzip`, `php_route=/usr/bin/php`, etc).

- En los campos `mysql_host`, `mysql_database`, `mysql_user`y `mysql_password` solo hay que añadir los mismos parámetros de la base de datos al igual que `/home/miusuario/phalcon-base/app/config/config.php`.

- Ya en caso que todo esté en orden, las opciones mencionadas se mostrarán en sl script sin problema.

#### Soporte

Cualquier pregunta o comentario ponerse en contacto con el desarrollador.
