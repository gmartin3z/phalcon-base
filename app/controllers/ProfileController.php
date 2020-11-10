<?php

namespace App\Controllers;

use Phalcon\Http\Response as Response;
use App\Validators\Profile\RegisterValidator;
use App\Validators\Profile\LoginValidator;
use App\Validators\Profile\UpdateAliasValidator;
use App\Validators\Profile\UpdateEmailValidator;
use App\Validators\Profile\UpdatePasswordValidator;
use App\Validators\Profile\FinishUpdatePasswordValidator;
use App\Validators\Profile\DeleteValidator;
use App\Validators\Profile\RecoverValidator;
use App\Validators\Profile\FinishRecoverValidator;
use App\Validators\Profile\UpdateTempProfileValidator;
use App\Libraries\Auth\Exception as AuthException;
use App\Models\Admins as Admin;
use App\Models\Operations as Operation;

class ProfileController extends ControllerBase
{
    public function registerAction()
    {
        if ($this->request->isPost()) {
            if ($this->security->checkToken() == false) {
                $this->flash->error($this->translate('invalid_token'));
                return $this->response->redirect($this->request->getHTTPReferer());
            }

            $validation = new RegisterValidator();
            $messages = $validation->validate($this->request->getPost());

            if (count($messages)) {
                foreach ($messages as $error) {
                    $this->flash->error($error->getMessage());
                }

                $columns = [];
                foreach($this->request->getPost() as $field => $input) {
                    $columns[$field] = $input;
                }

                $old_input = (object) $columns;

                $this->view->old = $old_input;
                return $this->view->render('profile', 'register');
            }

            $filters = array('trim', 'striptags', 'lower');

            $admin = new Admin();
            $admin->alias = $this->request->getPost('alias', $filters);
            $admin->email = $this->request->getPost('correo', $filters);
            $admin->password = $this->security->hash(
                $this->request->getPost('contrasenia', ['trim', 'striptags'])
            );
            $admin->created_at = date('Y-m-d H:i:s');
            $admin->permission_id =2;
            $admin->remaining_emails = 5;

            if ($admin->create() == false) {
                $this->flash->error($this->translate('info_not_saved'));
                return $this->response->redirect('perfil/registro');
            }

            $this->flash->success($this->translate('info_saved'));
            return $this->response->redirect('inicio');
        } else {
            return $this->view->render('profile', 'register');
        }
    }

    public function loginAction()
    {
        if ($this->request->isPost()) {
            if ($this->security->checkToken() == false) {
                $this->flash->error($this->translate('invalid_token'));
                return $this->response->redirect($this->request->getHTTPReferer());
            }

            if ($this->admin_auth->hasRememberMe()) {
                return $this->admin_auth->loginWithRememberMe();
            }

            try {
                $validation = new LoginValidator();
                $messages = $validation->validate($this->request->getPost());

                if (count($messages)) {
                    foreach ($messages as $error) {
                        $this->flash->error($error->getMessage());
                    }

                    $columns = [];
                    foreach($this->request->getPost() as $field => $input){
                        $columns[$field] = $input;
                    }

                    $old_input = (object) $columns;
                    $this->view->old = $old_input;

                    return $this->view->render('profile', 'login');
                }

                $filters = array('trim', 'striptags', 'lower');

                $this->admin_auth->check([
                    'correo' => $this->request->getPost('correo', $filters),
                    'contrasenia' => $this->request->getPost('contrasenia'),
                    'recordar_sesion' => $this->request->getPost('recordar_sesion')
                ]);

                $this->flash->success($this->translate('session_started'));
                return $this->response->redirect('inicio');
            } catch (AuthException $e) {
                $this->flash->error($e->getMessage());
                return $this->view->render('profile', 'login');
            }
        } else {
            return $this->view->render('profile', 'login');
        }
    }

    public function indexAction()
    {
        return $this->view->render('profile', 'index');
    }

    public function logoutAction()
    {
        $this->persistent->destroy('old_index_section');
        $this->persistent->destroy('old_index_content');
        $this->persistent->destroy('old_faqs_content');
        $this->persistent->destroy('old_terms_content');
        $this->persistent->destroy('old_contact_content');

        $this->admin_auth->remove();
        $this->flash->success($this->translate('session_finished'));
        return $this->response->redirect('inicio');
    }

    public function updateAliasAction()
    {
        $identity = $this->admin_auth->getIdentity();

        $admin = Admin::findFirst($identity['admin_id']);

        if (!$admin) {
            $this->flash->error($this->translate('registry_not_found'));
            return $this->response->redirect('inicio');
        }

        $this->view->admin = $admin;

        if ($this->request->isPost()) {
            if ($this->security->checkToken() == false) {
                $this->flash->error($this->translate('invalid_token'));
                return $this->response->redirect($this->request->getHTTPReferer());
            }

            $validation = new UpdateAliasValidator();
            $messages = $validation->validate($this->request->getPost());

            if (count($messages)) {
                foreach ($messages as $error) {
                    $this->flash->error($error->getMessage());
                }

                $columns = [];
                foreach($this->request->getPost() as $field => $input) {
                    $columns[$field] = $input;
                }

                $old_input = (object) $columns;

                $this->view->old = $old_input;
                return $this->view->render('profile', 'update_alias');
            }

            $filters = array('trim', 'striptags', 'lower');

            $admin->alias = $this->request->getPost('alias', $filters);
            $admin->updated_at = date('Y-m-d H:i:s');

            if ($admin->update() == false) {
                $this->flash->error($this->translate('info_not_saved'));
                return $this->response->redirect('perfil/actualizar-alias');
            }

            $this->admin_auth->refreshSession($admin->admin_id);

            $this->flash->success($this->translate('info_saved'));
            return $this->response->redirect('perfil');
        } else {
            return $this->view->render('profile', 'update_alias');
        }
    }

    public function updateEmailAction()
    {
        $identity = $this->admin_auth->getIdentity();

        $admin = Admin::findFirst($identity['admin_id']);

        if (!$admin) {
            $this->flash->error($this->translate('registry_not_found'));
            return $this->response->redirect('inicio');
        }

        $this->view->admin = $admin;

        if ($this->request->isPost()) {
            if ($this->security->checkToken() == false) {
                $this->flash->error($this->translate('invalid_token'));
                return $this->response->redirect($this->request->getHTTPReferer());
            }

            $validation = new UpdateEmailValidator();
            $messages = $validation->validate($this->request->getPost());

            if (count($messages)) {
                foreach ($messages as $error) {
                    $this->flash->error($error->getMessage());
                }

                $columns = [];
                foreach($this->request->getPost() as $field => $input) {
                    $columns[$field] = $input;
                }

                $old_input = (object) $columns;

                $this->view->old = $old_input;
                return $this->view->render('profile', 'update_email');
            }

            $filters = array('trim', 'striptags');

            $email = $this->request->getPost('correo', $filters);
            $password = $this->request->getPost('contrasenia');
            $password_repeat = $this->request->getPost('repetir_contrasenia');

            if (!$this->security->checkHash($password, $admin->password)) {
                $this->flash->error($this->translate('wrong_password'));
                return $this->response->redirect('perfil/actualizar-correo');
            }

            $remaining_emails = $admin->remaining_emails;

            if ($remaining_emails == 0) {
                $now = date('Y-m-d H:i:s');
                $block_emails_until = $admin->block_emails_until;

                if ($block_emails_until) {
                    if ($now > $block_emails_until) {
                        $admin->remaining_emails = 5;
                        $admin->block_emails_until = NULL;

                        if ($admin->update() == false) {
                            $this->flash->error($this->translate('operation_not_updated'));
                            return $this->response->redirect('perfil/actualizar-correo');
                        }
                    }

                    $dc = date_create($block_emails_until);
                    $df = date_format($dc, 'h:i A');

                    $this->flash->error($this->translate('retry_after') . ' ' . $df);
                    return $this->response->redirect('perfil/actualizar-correo');
                } else {
                    $admin->block_emails_until = date('Y-m-d H:i:s', strtotime('+30 minutes'));

                    if ($admin->update() == false) {
                        $this->flash->error($this->translate('operation_not_updated'));
                        return $this->response->redirect('perfil/actualizar-correo');
                    }

                    $dc = date_create($admin->block_emails_until);
                    $df = date_format($dc, 'h:i A');

                    $this->flash->error($this->translate('retry_after') . ' ' . $df);
                    return $this->response->redirect('perfil/actualizar-correo');
                }
            } else {
                $admin_id = $admin->admin_id;
                $operation_type = 2;
                $alias = $admin->alias;
                $token =  bin2hex(openssl_random_pseudo_bytes(24));

                $operation = Operation::findFirstByAdminId($admin_id);
                $filters = array('trim', 'striptags');

                if ($operation) {
                    $operation->operation_type_id = $operation_type;
                    $operation->admin_id = $admin_id;
                    $operation->email = $email;
                    $operation->token = $token;
                    $operation->ip = $this->request->getClientAddress();
                    $operation->browser = $this->request->getUserAgent();
                    $operation->expiration = date('Y-m-d H:i:s', strtotime('+30 minutes'));

                    if ($operation->update() == false) {
                        $this->flash->error($this->translate('operation_not_updated'));
                        return $this->response->redirect('perfil/actualizar-correo');
                    }
                } else {
                    $operation = new Operation();
                    $operation->operation_type_id = $operation_type;
                    $operation->admin_id = $admin_id;
                    $operation->email = $email;
                    $operation->token = $token;
                    $operation->ip = $this->request->getClientAddress();
                    $operation->browser = $this->request->getUserAgent();
                    $operation->expiration = date('Y-m-d H:i:s', strtotime('+30 minutes'));

                    if ($operation->create() == false) {
                        $this->flash->error($this->translate('operation_not_saved'));
                        return $this->response->redirect('perfil/actualizar-correo');
                    }
                }

                $admin->remaining_emails = $remaining_emails - 1;

                if ($admin->update() == false) {
                    $this->flash->error($this->translate('operation_not_updated'));
                    return $this->response->redirect('perfil/actualizar-correo');
                }

                $this->mailer->send([$email => $alias],
                    $this->translate('update_email'), 'ACC_UPDATE_EMAIL', [
                    'alias'      => $alias,
                    'admin_id'   => $admin_id,
                    'token'      => $token
                ]);
            }

            $this->flash->warning($this->translate('check_email_reminder'));
            return $this->response->redirect('perfil');
        } else {
            return $this->view->render('profile', 'update_email');
        }
    }

    public function finishUpdateEmailAction()
    {
        $admin_id = $this->dispatcher->getParam('admin_id');
        $token = $this->dispatcher->getParam('token');

        $operation = Operation::findFirst([
            'conditions' => '
                operation_type_id = :operation_type_id:
                and admin_id = :admin_id:
                and token = :token:
            ',
            'bind' => [
                'operation_type_id' => 2,
                'admin_id' => $admin_id,
                'token' => $token
            ],
            'order' => 'operation_type_id asc'
        ]);

        if (!$operation) {
            $this->flash->error($this->translate('operation_not_found'));
            return $this->response->redirect('inicio');
        }

        $now = date('Y-m-d H:i:s');
        $expiration = $operation->expiration;

        if ($now > $expiration) {
            if ($operation->delete() == false) {
                $this->flash->error($this->translate('operation_not_deleted'));
                return $this->response->redirect('inicio');
            }

            $this->flash->error($this->translate('operation_has_expired'));
            return $this->response->redirect('inicio');
        }

        $admin = Admin::findFirst($admin_id);

        if (!$admin) {
            $this->flash->error($this->translate('registry_not_found'));
            return $this->response->redirect('inicio');
        }

        $admin->email = $operation->email;
        $admin->remaining_emails = 5;
        $admin->block_emails_until = NULL;
        $admin->updated = date('Y-m-d H:i:s');

        if ($admin->update() == false) {
            $this->flash->error($this->translate('info_not_saved'));
            return $this->response->redirect('inicio');
        }

        if ($operation->delete() == false) {
            $this->flash->error($this->translate('operation_not_deleted'));
            return $this->response->redirect('inicio');
        }

        $this->flash->success($this->translate('info_saved'));
        return $this->response->redirect('inicio');
    }

    public function updatePasswordAction()
    {
        $identity = $this->admin_auth->getIdentity();

        $admin = Admin::findFirst($identity['admin_id']);

        if (!$admin) {
            $this->flash->error($this->translate('registry_not_found'));
            return $this->response->redirect('inicio');
        }

        if ($this->request->isPost()) {
            if ($this->security->checkToken() == false) {
                $this->flash->error($this->translate('invalid_token'));
                return $this->response->redirect($this->request->getHTTPReferer());
            }

            $validation = new UpdatePasswordValidator();
            $messages = $validation->validate($this->request->getPost());

            if (count($messages)) {
                foreach ($messages as $error) {
                    $this->flash->error($error->getMessage());
                }

                $columns = [];
                foreach($this->request->getPost() as $field => $input) {
                    $columns[$field] = $input;
                }

                $old_input = (object) $columns;

                $this->view->old = $old_input;
                return $this->view->render('profile', 'update_password');
            }

            $filters = array('trim', 'striptags');

            $email = $admin->email;
            $password = $this->request->getPost('contrasenia');
            $password_repeat = $this->request->getPost('repetir_contrasenia');

            if (!$this->security->checkHash($password, $admin->password)) {
                $this->flash->error($this->translate('wrong_password'));
                return $this->response->redirect('perfil/actualizar-contrasenia');
            }

            $remaining_emails = $admin->remaining_emails;

            if ($remaining_emails == 0) {
                $now = date('Y-m-d H:i:s');
                $block_emails_until = $admin->block_emails_until;

                if ($block_emails_until) {
                    if ($now > $block_emails_until) {
                        $admin->remaining_emails = 5;
                        $admin->block_emails_until = NULL;

                        if ($admin->update() == false) {
                            $this->flash->error($this->translate('operation_not_updated'));
                            return $this->response->redirect('perfil/actualizar-contrasenia');
                        }
                    }

                    $dc = date_create($block_emails_until);
                    $df = date_format($dc, 'h:i A');

                    $this->flash->error($this->translate('retry_after') . ' ' . $df);
                    return $this->response->redirect('perfil/actualizar-correo');
                } else {
                    $admin->block_emails_until = date('Y-m-d H:i:s', strtotime('+30 minutes'));

                    if ($admin->update() == false) {
                        $this->flash->error($this->translate('operation_not_updated'));
                        return $this->response->redirect('perfil/actualizar-contrasenia');
                    }

                    $dc = date_create($admin->block_emails_until);
                    $df = date_format($dc, 'h:i A');

                    $this->flash->error($this->translate('retry_after') . ' ' . $df);
                    return $this->response->redirect('perfil/actualizar-contrasenia');
                }
            } else {
                $admin_id = $admin->admin_id;
                $operation_type = 3;
                $alias = $admin->alias;
                $token =  bin2hex(openssl_random_pseudo_bytes(24));

                $operation = Operation::findFirstByAdminId($admin_id);

                if ($operation) {
                    $operation->operation_type_id = $operation_type;
                    $operation->admin_id = $admin_id;
                    $operation->email = $email;
                    $operation->token = $token;
                    $operation->ip = $this->request->getClientAddress();
                    $operation->browser = $this->request->getUserAgent();
                    $operation->expiration = date('Y-m-d H:i:s', strtotime('+30 minutes'));

                    if ($operation->update() == false) {
                        $this->flash->error($this->translate('operation_not_updated'));
                        return $this->response->redirect('perfil/actualizar-contrasenia');
                    }
                } else {
                    $operation = new Operation();
                    $operation->operation_type_id = $operation_type;
                    $operation->admin_id = $admin_id;
                    $operation->email = $email;
                    $operation->token = $token;
                    $operation->ip = $this->request->getClientAddress();
                    $operation->browser = $this->request->getUserAgent();
                    $operation->expiration = date('Y-m-d H:i:s', strtotime('+30 minutes'));

                    if ($operation->create() == false) {
                        $this->flash->error($this->translate('operation_not_saved'));
                        return $this->response->redirect('perfil/actualizar-contrasenia');
                    }
                }

                $admin->remaining_emails = $remaining_emails - 1;

                if ($admin->update() == false) {
                    $this->flash->error($this->translate('operation_not_updated'));
                    return $this->response->redirect('perfil/actualizar-contrasenia');
                }

                $this->mailer->send([$email => $alias],
                    $this->translate('update_password'), 'ACC_UPDATE_PASSWORD', [
                    'alias'      => $alias,
                    'admin_id'   => $admin_id,
                    'token'      => $token
                ]);
            }

            $this->flash->warning($this->translate('check_email_reminder'));
            return $this->response->redirect('perfil');
        } else {
            return $this->view->render('profile', 'update_password');
        }
    }

    public function finishUpdatePasswordAction()
    {
        $admin_id = $this->dispatcher->getParam('admin_id');
        $token = $this->dispatcher->getParam('token');

        $this->view->admin_id = $admin_id;
        $this->view->token = $token;

        $operation = Operation::findFirst([
            'conditions' => '
                operation_type_id = :operation_type_id:
                and admin_id = :admin_id:
                and token = :token:
            ',
            'bind' => [
                'operation_type_id' => 3,
                'admin_id' => $admin_id,
                'token' => $token
            ],
            'order' => 'operation_type_id asc'
        ]);

        if (!$operation) {
            $this->flash->error($this->translate('operation_not_found'));
            return $this->response->redirect('inicio');
        }

        $now = date('Y-m-d H:i:s');
        $expiration = $operation->expiration;

        if ($now > $expiration) {
            if ($operation->delete() == false) {
                $this->flash->error($this->translate('operation_not_deleted'));
                return $this->response->redirect('inicio');
            }

            $this->flash->error($this->translate('operation_has_expired'));
            return $this->response->redirect('inicio');
        }

        $identity = $this->admin_auth->getIdentity();

        $admin = Admin::findFirst($identity['admin_id']);

        if (!$admin) {
            $this->flash->error($this->translate('registry_not_found'));
            return $this->response->redirect('inicio');
        }

        if ($this->request->isPost()) {
            if ($this->security->checkToken() == false) {
                $this->flash->error($this->translate('invalid_token'));
                return $this->response->redirect($this->request->getHTTPReferer());
            }

            $validation = new FinishUpdatePasswordValidator();
            $messages = $validation->validate($this->request->getPost());

            if (count($messages)) {
                foreach ($messages as $error) {
                    $this->flash->error($error->getMessage());
                }

                $columns = [];
                foreach($this->request->getPost() as $field => $input) {
                    $columns[$field] = $input;
                }

                $old_input = (object) $columns;

                $this->view->old = $old_input;
                return $this->view->render('profile', 'finish_update_password');
            }

            $filters = array('trim', 'striptags');

            $password = $this->request->getPost('contrasenia', $filters);

            $admin->password = $this->security->hash($password);
            $admin->remaining_emails = 5;
            $admin->block_emails_until = NULL;
            $admin->updated_at = date('Y-m-d H:i:s');

            if ($admin->update() == false) {
                $this->flash->error($this->translate('info_not_saved'));
                return $this->response->redirect('inicio');
            }

            if ($operation->delete() == false) {
                $this->flash->error($this->translate('operation_not_deleted'));
                return $this->response->redirect('inicio');
            }

            $this->flash->success($this->translate('info_saved'));
            return $this->response->redirect('inicio');
        } else {
            return $this->view->render('profile', 'finish_update_password');
        }
    }

    public function deleteAction()
    {
        $identity = $this->admin_auth->getIdentity();

        $admin = Admin::findFirst($identity['admin_id']);

        if (!$admin) {
            $this->flash->error($this->translate('registry_not_found'));
            return $this->response->redirect('inicio');
        }

        $this->view->admin = $admin;

        if ($this->request->isPost()) {
            if ($this->security->checkToken() == false) {
                $this->flash->error($this->translate('invalid_token'));
                return $this->response->redirect($this->request->getHTTPReferer());
            }

            $validation = new DeleteValidator();
            $messages = $validation->validate($this->request->getPost());

            if (count($messages)) {
                foreach ($messages as $error) {
                    $this->flash->error($error->getMessage());
                }

                $columns = [];
                foreach($this->request->getPost() as $field => $input) {
                    $columns[$field] = $input;
                }

                $old_input = (object) $columns;

                $this->view->old = $old_input;
                return $this->view->render('profile', 'delete');
            }

            $filters = array('trim', 'striptags');

            $email = $admin->email;
            $password = $this->request->getPost('contrasenia', $filters);
            $password_repeat = $this->request->getPost('repetir_contrasenia', $filters);

            if (!$this->security->checkHash($password, $admin->password)) {
                $this->flash->error($this->translate('wrong_password'));
                return $this->response->redirect('perfil/borrar');
            }

            $remaining_emails = $admin->remaining_emails;

            if ($remaining_emails == 0) {
                $now = date('Y-m-d H:i:s');
                $block_emails_until = $admin->block_emails_until;

                if ($block_emails_until) {
                    if ($now > $block_emails_until) {
                        $admin->remaining_emails = 5;
                        $admin->block_emails_until = NULL;

                        if ($admin->update() == false) {
                            $this->flash->error($this->translate('operation_not_updated'));
                            return $this->response->redirect('perfil/borrar');
                        }
                    }

                    $dc = date_create($block_emails_until);
                    $df = date_format($dc, 'h:i A');

                    $this->flash->error($this->translate('retry_after') . ' ' . $df);
                    return $this->response->redirect('perfil/borrar');
                } else {
                    $admin->block_emails_until = date('Y-m-d H:i:s', strtotime('+30 minutes'));

                    if ($admin->update() == false) {
                        $this->flash->error($this->translate('operation_not_updated'));
                        return $this->response->redirect('perfil/borrar');
                    }

                    $dc = date_create($admin->block_emails_until);
                    $df = date_format($dc, 'h:i A');

                    $this->flash->error($this->translate('retry_after') . ' ' . $df);
                    return $this->response->redirect('perfil/borrar');
                }
            } else {
                $admin_id = $admin->admin_id;
                $operation_type = 4;
                $alias = $admin->alias;
                $token =  bin2hex(openssl_random_pseudo_bytes(24));

                $operation = Operation::findFirstByAdminId($admin_id);
                $filters = array('trim', 'striptags');

                if ($operation) {
                    $operation->operation_type_id = $operation_type;
                    $operation->admin_id = $admin_id;
                    $operation->email = $email;
                    $operation->token = $token;
                    $operation->ip = $this->request->getClientAddress();
                    $operation->browser = $this->request->getUserAgent();
                    $operation->expiration = date('Y-m-d H:i:s', strtotime('+30 minutes'));

                    if ($operation->update() == false) {
                        $this->flash->error($this->translate('operation_not_updated'));
                        return $this->response->redirect('perfil/borrar');
                    }
                } else {
                    $operation = new Operation();
                    $operation->operation_type_id = $operation_type;
                    $operation->admin_id = $admin_id;
                    $operation->email = $email;
                    $operation->token = $token;
                    $operation->ip = $this->request->getClientAddress();
                    $operation->browser = $this->request->getUserAgent();
                    $operation->expiration = date('Y-m-d H:i:s', strtotime('+30 minutes'));

                    if ($operation->create() == false) {
                        $this->flash->error($this->translate('operation_not_saved'));
                        return $this->response->redirect('perfil/borrar');
                    }
                }

                $admin->remaining_emails = $remaining_emails - 1;

                if ($admin->update() == false) {
                    $this->flash->error($this->translate('operation_not_updated'));
                    return $this->response->redirect('perfil/borrar');
                }

                $this->mailer->send([$email => $alias],
                    $this->translate('delete_profile'), 'ACC_DELETE_PROFILE', [
                    'alias'      => $alias,
                    'admin_id'   => $admin_id,
                    'token'      => $token
                ]);
            }

            $this->flash->warning($this->translate('check_email_reminder'));
            return $this->response->redirect('perfil');
        } else {
            return $this->view->render('profile', 'delete');
        }
    }

    public function finishDeleteAction()
    {
        $admin_id = $this->dispatcher->getParam('admin_id');
        $token = $this->dispatcher->getParam('token');

        $operation = Operation::findFirst([
            'conditions' => '
                operation_type_id = :operation_type_id:
                and admin_id = :admin_id:
                and token = :token:
            ',
            'bind' => [
                'operation_type_id' => 4,
                'admin_id' => $admin_id,
                'token' => $token
            ],
            'order' => 'operation_type_id asc'
        ]);

        if (!$operation) {
            $this->flash->error($this->translate('operation_not_found'));
            return $this->response->redirect('inicio');
        }

        $now = date('Y-m-d H:i:s');
        $expiration = $operation->expiration;

        if ($now > $expiration) {
            if ($operation->delete() == false) {
                $this->flash->error($this->translate('operation_not_deleted'));
                return $this->response->redirect('inicio');
            }

            $this->flash->error($this->translate('operation_has_expired'));
            return $this->response->redirect('inicio');
        }

        $admin = Admin::findFirst($admin_id);

        if (!$admin) {
            $this->flash->error($this->translate('registry_not_found'));
            return $this->response->redirect('inicio');
        }

        $admin->alias = '#####';
        $admin->email = '#####';
        $admin->password = '#####';
        $admin->remember_session = NULL;
        $admin->session_expiration = NULL;
        $admin->deleted_at = date('Y-m-d H:i:s');
        $admin->permission_id = 5;

        if ($admin->update() == false) {
            $this->flash->error($this->translate('info_not_saved'));
            return $this->response->redirect('inicio');
        }

        if ($operation->delete() == false) {
            $this->flash->error($this->translate('operation_not_deleted'));
            return $this->response->redirect('inicio');
        }

        $this->admin_auth->remove();

        $this->flash->success($this->translate('info_saved'));
        return $this->response->redirect('inicio');
    }

    public function recoverAction()
    {
        if ($this->request->isPost()) {
            if ($this->security->checkToken() == false) {
                $this->flash->error($this->translate('invalid_token'));
                return $this->response->redirect($this->request->getHTTPReferer());
            }

            $validation = new RecoverValidator();
            $messages = $validation->validate($this->request->getPost());

            if (count($messages)) {
                foreach ($messages as $error) {
                    $this->flash->error($error->getMessage());
                }

                $columns = [];
                foreach($this->request->getPost() as $field => $input) {
                    $columns[$field] = $input;
                }

                $old_input = (object) $columns;

                $this->view->old = $old_input;
                return $this->view->render('profile', 'recover');
            }

            $filters = array('trim', 'striptags', 'lower');

            $email = $this->request->getPost('correo', $filters);

            $admin = Admin::findFirstByEmail($email);

            if (!$admin) {
                $this->flash->error($this->translate('registry_not_found'));
                return $this->response->redirect('inicio');
            }

            $remaining_emails = $admin->remaining_emails;

            if ($remaining_emails == 0) {
                $now = date('Y-m-d H:i:s');
                $block_emails_until = $admin->block_emails_until;

                if ($block_emails_until) {
                    if ($now > $block_emails_until) {
                        $admin->remaining_emails = 5;
                        $admin->block_emails_until = NULL;

                        if ($admin->update() == false) {
                            $this->flash->error($this->translate('operation_not_updated'));
                            return $this->response->redirect('perfil/recuperar');
                        }
                    }

                    $dc = date_create($block_emails_until);
                    $df = date_format($dc, 'h:i A');

                    $this->flash->error($this->translate('retry_after') . ' ' . $df);
                    return $this->response->redirect('perfil/recuperar');
                } else {
                    $admin->block_emails_until = date('Y-m-d H:i:s', strtotime('+30 minutes'));

                    if ($admin->update() == false) {
                        $this->flash->error($this->translate('operation_not_updated'));
                        return $this->response->redirect('perfil/recuperar');
                    }

                    $dc = date_create($admin->block_emails_until);
                    $df = date_format($dc, 'h:i A');

                    $this->flash->error($this->translate('retry_after') . ' ' . $df);
                    return $this->response->redirect('perfil/recuperar');
                }
            } else {
                $admin_id = $admin->admin_id;
                $operation_type = 5;
                $alias = $admin->alias;
                $token = bin2hex(openssl_random_pseudo_bytes(24));

                $operation = Operation::findFirstByAdminId($admin_id);
                $filters = array('trim', 'striptags');

                if ($operation) {
                    $operation->operation_type_id = $operation_type;
                    $operation->admin_id = $admin_id;
                    $operation->email = $email;
                    $operation->token = $token;
                    $operation->ip = $this->request->getClientAddress();
                    $operation->browser = $this->request->getUserAgent();
                    $operation->expiration = date('Y-m-d H:i:s', strtotime('+30 minutes'));

                    if ($operation->update() == false) {
                        $this->flash->error($this->translate('operation_not_updated'));
                        return $this->response->redirect('perfil/recuperar');
                    }
                } else {
                    $operation = new Operation();
                    $operation->operation_type_id = $operation_type;
                    $operation->admin_id = $admin_id;
                    $operation->email = $email;
                    $operation->token = $token;
                    $operation->ip = $this->request->getClientAddress();
                    $operation->browser = $this->request->getUserAgent();
                    $operation->expiration = date('Y-m-d H:i:s', strtotime('+30 minutes'));

                    if ($operation->create() == false) {
                        $this->flash->error($this->translate('operation_not_saved'));
                        return $this->response->redirect('perfil/recuperar');
                    }
                }

                $admin->remaining_emails = $remaining_emails - 1;

                if ($admin->update() == false) {
                    $this->flash->error($this->translate('operation_not_updated'));
                    return $this->response->redirect('perfil/recuperar');
                }

                $this->mailer->send([$email => $alias],
                    $this->translate('recover_profile'), 'ACC_RECOVER_PROFILE', [
                    'alias'      => $alias,
                    'admin_id'   => $admin_id,
                    'token'      => $token
                ]);
            }

            $this->flash->warning($this->translate('check_email_reminder'));
            return $this->response->redirect('inicio');
        } else {
            return $this->view->render('profile', 'recover');
        }
    }

    public function finishRecoverAction()
    {
        $admin_id = $this->dispatcher->getParam('admin_id');
        $token = $this->dispatcher->getParam('token');

        $this->view->admin_id = $admin_id;
        $this->view->token = $token;

        $operation = Operation::findFirst([
            'conditions' => '
                operation_type_id = :operation_type_id:
                and admin_id = :admin_id:
                and token = :token:
            ',
            'bind' => [
                'operation_type_id' => 5,
                'admin_id' => $admin_id,
                'token' => $token
            ],
            'order' => 'operation_type_id asc'
        ]);

        if (!$operation) {
            $this->flash->error($this->translate('operation_not_found'));
            return $this->response->redirect('inicio');
        }

        $now = date('Y-m-d H:i:s');
        $expiration = $operation->expiration;

        if ($now > $expiration) {
            if ($operation->delete() == false) {
                $this->flash->error($this->translate('operation_not_deleted'));
                return $this->response->redirect('inicio');
            }

            $this->flash->error($this->translate('operation_has_expired'));
            return $this->response->redirect('inicio');
        }

        $admin = Admin::findFirst($admin_id);

        if (!$admin) {
            $this->flash->error($this->translate('registry_not_found'));
            return $this->response->redirect('inicio');
        }

        if ($this->request->isPost()) {
            if ($this->security->checkToken() == false) {
                $this->flash->error($this->translate('invalid_token'));
                return $this->response->redirect($this->request->getHTTPReferer());
            }

            $validation = new FinishRecoverValidator();
            $messages = $validation->validate($this->request->getPost());

            if (count($messages)) {
                foreach ($messages as $error) {
                    $this->flash->error($error->getMessage());
                }

                $columns = [];
                foreach($this->request->getPost() as $field => $input) {
                    $columns[$field] = $input;
                }

                $old_input = (object) $columns;

                $this->view->old = $old_input;
                return $this->view->render('profile', 'finish_recover');
            }

            $filters = array('trim', 'striptags');

            $password = $this->request->getPost('contrasenia', $filters);

            $admin->password = $this->security->hash($password);
            $admin->remaining_emails = 5;
            $admin->block_emails_until = NULL;
            $admin->updated_at = date('Y-m-d H:i:s');

            if ($admin->update() == false) {
                $this->flash->error($this->translate('info_not_saved'));
                return $this->response->redirect('inicio');
            }

            if ($operation->delete() == false) {
                $this->flash->error($this->translate('operation_not_deleted'));
                return $this->response->redirect('inicio');
            }

            $this->flash->success($this->translate('info_saved'));
            return $this->response->redirect('inicio');
        } else {
            return $this->view->render('profile', 'finish_recover');
        }
    }

    public function updateTempProfileAction()
    {
        $identity = $this->admin_auth->getIdentity();

        $admin = Admin::findFirst($identity['admin_id']);

        if (!$admin) {
            $this->flash->error($this->translate('registry_not_found'));
            return $this->response->redirect('inicio');
        }

        $this->view->admin = $admin;

        if ($this->request->isPost()) {
            if ($this->security->checkToken() == false) {
                $this->flash->error($this->translate('invalid_token'));
                return $this->response->redirect($this->request->getHTTPReferer());
            }

            $validation = new UpdateTempProfileValidator();
            $messages = $validation->validate($this->request->getPost());

            if (count($messages)) {
                foreach ($messages as $error) {
                    $this->flash->error($error->getMessage());
                }

                $columns = [];
                foreach($this->request->getPost() as $field => $input) {
                    $columns[$field] = $input;
                }

                $old_input = (object) $columns;

                $this->view->old = $old_input;
                return $this->view->render('profile', 'update_temp_profile');
            }

            $filters = array('trim', 'striptags', 'lower');

            $admin->email = $this->request->getPost('correo', $filters);
            $admin->updated_at = date('Y-m-d H:i:s');

            if ($admin->update() == false) {
                $this->flash->error($this->translate('info_not_saved'));
                return $this->response->redirect('perfil/actualizar-perfil-temporal');
            }

            $this->admin_auth->refreshSession($admin->admin_id);

            $this->flash->success($this->translate('info_saved'));
            return $this->response->redirect('inicio');
        } else {
            return $this->view->render('profile', 'update_temp_profile');
        }
    }

    public function verifyAction()
    {
        $identity = $this->admin_auth->getIdentity();

        $admin = Admin::findFirst($identity['admin_id']);

        if (!$admin) {
            $this->flash->error($this->translate('registry_not_found'));
            return $this->response->redirect('inicio');
        }

        $email = $admin->email;

        $remaining_emails = $admin->remaining_emails;

        if ($remaining_emails == 0) {
            $now = date('Y-m-d H:i:s');
            $block_emails_until = $admin->block_emails_until;

            if ($block_emails_until) {
                if ($now > $block_emails_until) {
                    $admin->remaining_emails = 5;
                    $admin->block_emails_until = NULL;

                    if ($admin->update() == false) {
                        $this->flash->error($this->translate('operation_not_updated'));
                        return $this->response->redirect('inicio');
                    }
                }

                $dc = date_create($block_emails_until);
                $df = date_format($dc, 'h:i A');

                $this->flash->error($this->translate('retry_after') . ' ' . $df);
                return $this->response->redirect('inicio');
            } else {
                $admin->block_emails_until = date('Y-m-d H:i:s', strtotime('+30 minutes'));

                if ($admin->update() == false) {
                    $this->flash->error($this->translate('operation_not_updated'));
                    return $this->response->redirect('inicio');
                }

                $dc = date_create($admin->block_emails_until);
                $df = date_format($dc, 'h:i A');

                $this->flash->error($this->translate('retry_after') . ' ' . $df);
                return $this->response->redirect('inicio');
            }
        } else {
            $admin_id = $admin->admin_id;
            $operation_type = 1;
            $alias = $admin->alias;
            $token =  bin2hex(openssl_random_pseudo_bytes(24));

            $operation = Operation::findFirstByAdminId($admin_id);
            $filters = array('trim', 'striptags');

            if ($operation) {
                $operation->operation_type_id = $operation_type;
                $operation->admin_id = $admin_id;
                $operation->email = $email;
                $operation->token = $token;
                $operation->ip = $this->request->getClientAddress();
                $operation->browser = $this->request->getUserAgent();
                $operation->expiration = date('Y-m-d H:i:s', strtotime('+30 minutes'));

                if ($operation->update() == false) {
                    $this->flash->error($this->translate('operation_not_updated'));
                    return $this->response->redirect('inicio');
                }
            } else {
                $operation = new Operation();
                $operation->operation_type_id = $operation_type;
                $operation->admin_id = $admin_id;
                $operation->email = $email;
                $operation->token = $token;
                $operation->ip = $this->request->getClientAddress();
                $operation->browser = $this->request->getUserAgent();
                $operation->expiration = date('Y-m-d H:i:s', strtotime('+30 minutes'));

                if ($operation->create() == false) {
                    $this->flash->error($this->translate('operation_not_saved'));
                    return $this->response->redirect('inicio');
                }
            }

            $admin->remaining_emails = $remaining_emails - 1;

            if ($admin->update() == false) {
                $this->flash->error($this->translate('operation_not_updated'));
                return $this->response->redirect('inicio');
            }

            $this->mailer->send([$email => $alias],
                $this->translate('verify_profile'), 'ACC_VERIFY_PROFILE', [
                'alias'      => $alias,
                'admin_id'   => $admin_id,
                'token'      => $token
            ]);
        }

        $this->flash->warning($this->translate('check_email_reminder'));
        return $this->response->redirect('inicio');
    }

    public function finishVerifyAction()
    {
        $admin_id = $this->dispatcher->getParam('admin_id');
        $token = $this->dispatcher->getParam('token');

        $operation = Operation::findFirst([
            'conditions' => '
                operation_type_id = :operation_type_id:
                and admin_id = :admin_id:
                and token = :token:
            ',
            'bind' => [
                'operation_type_id' => 1,
                'admin_id' => $admin_id,
                'token' => $token
            ],
            'order' => 'operation_type_id asc'
        ]);

        if (!$operation) {
            $this->flash->error($this->translate('operation_not_found'));
            return $this->response->redirect('inicio');
        }

        $now = date('Y-m-d H:i:s');
        $expiration = $operation->expiration;

        if ($now > $expiration) {
            if ($operation->delete() == false) {
                $this->flash->error($this->translate('operation_not_deleted'));
                return $this->response->redirect('inicio');
            }

            $this->flash->error($this->translate('operation_has_expired'));
            return $this->response->redirect('inicio');
        }

        $admin = Admin::findFirst($admin_id);

        if (!$admin) {
            $this->flash->error($this->translate('registry_not_found'));
            return $this->response->redirect('inicio');
        }

        $admin->activated_at = date('Y-m-d H:i:s');
        $admin->permission_id = 1;
        $admin->remaining_emails = 5;
        $admin->block_emails_until = NULL;
        $admin->updated = date('Y-m-d H:i:s');

        if ($admin->update() == false) {
            $this->flash->error($this->translate('info_not_saved'));
            return $this->response->redirect('inicio');
        }

        if ($operation->delete() == false) {
            $this->flash->error($this->translate('operation_not_deleted'));
            return $this->response->redirect('inicio');
        }

        $this->admin_auth->refreshSession($admin->admin_id);

        $this->flash->success($this->translate('info_saved'));
        return $this->response->redirect('inicio');
    }
}
