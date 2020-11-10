<?php

use Phalcon\Mvc\Router as Router;

$di->set('router', function() {
    $router = new Router(false);
    $router->removeExtraSlashes(true);

    $router->addGet('/', array(
       'controller' => 'index',
       'action' => 'public'
    ));

    $router->addGet('/inicio', array(
       'controller' => 'index',
       'action' => 'public'
    ));

    $router->addGet('/preguntas-frecuentes', array(
       'controller' => 'faqs',
       'action' => 'public'
    ));

    $router->addGet('/captcha/generar/{time:\d+}', array(
       'controller' => 'captcha',
       'action' => 'generateCaptcha'
    ));

    $router->add('/perfil/registro', array(
       'controller' => 'profile',
       'action' => 'register'
      ))->via(['GET', 'POST']);

    $router->add('/perfil/ingreso', array(
       'controller' => 'profile',
       'action' => 'login'
    ))->via(['GET', 'POST']);

    $router->add('/perfil/recuperar', array(
       'controller' => 'profile',
       'action' => 'recover'
    ))->via(['GET', 'POST']);

    $router->add('/perfil/recuperar/finalizar/{admin_id:\d+}/{token:([A-Za-z0-9]+)}', array(
       'controller' => 'profile',
       'action' => 'finishRecover'
    ))->via(['GET', 'POST']);

    $router->addGet('/perfil', array(
       'controller' => 'profile',
       'action' => 'index'
    ));

    $router->addGet('/perfil/salir', array(
       'controller' => 'profile',
       'action' => 'logout'
    ));

    $router->add('/perfil/actualizar-alias', array(
       'controller' => 'profile',
       'action' => 'updateAlias'
    ))->via(['GET', 'POST']);

    $router->add('/perfil/actualizar-correo', array(
       'controller' => 'profile',
       'action' => 'updateEmail'
    ))->via(['GET', 'POST']);

    $router->addGet('/perfil/actualizar-correo/finalizar/{admin_id:\d+}/{token:([A-Za-z0-9]+)}', array(
       'controller' => 'profile',
       'action' => 'finishUpdateEmail'
    ));

    $router->add('/perfil/actualizar-contrasenia', array(
       'controller' => 'profile',
       'action' => 'updatePassword'
    ))->via(['GET', 'POST']);

    $router->add('/perfil/actualizar-contrasenia/finalizar/{admin_id:\d+}/{token:([A-Za-z0-9]+)}', array(
       'controller' => 'profile',
       'action' => 'finishUpdatePassword'
    ))->via(['GET', 'POST']);

    $router->add('/perfil/borrar', array(
       'controller' => 'profile',
       'action' => 'delete'
    ))->via(['GET', 'POST']);

    $router->addGet('/perfil/borrar/finalizar/{admin_id:\d+}/{token:([A-Za-z0-9]+)}', array(
       'controller' => 'profile',
       'action' => 'finishDelete'
    ));

    $router->add('/perfil/actualizar-perfil-temporal', array(
       'controller' => 'profile',
       'action' => 'updateTempProfile'
    ))->via(['GET', 'POST']);

    $router->addGet('/perfil/verificar', array(
       'controller' => 'profile',
       'action' => 'verify'
    ));

    $router->addGet('/perfil/verificar/finalizar/{admin_id:\d+}/{token:([A-Za-z0-9]+)}', array(
       'controller' => 'profile',
       'action' => 'finishVerify'
    ));

    $router->addPost('/idioma', array(
       'controller' => 'language',
       'action' => 'change'
    ));

    // 404
    $router->notFound(array(
        'controller' => 'errors',
        'action' => 'show404'
    ));

    return $router;
});
