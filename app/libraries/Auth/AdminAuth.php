<?php

namespace App\Libraries\Auth;

use Phalcon\Mvc\User\Component;
use App\Models\Admins as Admin;

class AdminAuth extends Component
{
    public function translate($key){
        $tr = $this->translator->getTranslation();
        return $tr->_($key);
    }

    public function check($credentials)
    {
        $admin = Admin::findFirstByEmail($credentials['correo']);
        if ($admin == false) {
            throw new Exception($this->translate('wrong_email_password'));
        }

        if (!$this->security->checkHash($credentials['contrasenia'], $admin->password)) {
            throw new Exception($this->translate('wrong_email_password'));
        }

        if (!$admin->activated_at) {
            $now = date('Y-m-d H:i:s');
            $expiration = date('Y-m-d H:i:s', strtotime($admin->created_at . '+1 day'));

            if ($now > $expiration) {
                if (!$admin->blocked_at) {
                    $admin->password = '###BLOCKED###';
                    $admin->blocked_at = date('Y-m-d H:i:s');
                    $admin->permission_id = 4;

                    if ($admin->update() == false) {
                        throw new Exception($this->translate('operation_not_updated'));
                    }
                }

                throw new Exception($this->translate('profile_blocked'));
            }
        }

        if (isset($credentials['recordar_sesion'])) {
            $this->createRememberEnvironment($admin);
        }

        $this->session->set('auth-identity', [
            'admin_id' => $admin->admin_id,
            'alias' => $admin->alias,
            'email' => $admin->email,
            'created_at' => $admin->created_at,
            'updated_at' => $admin->updated_at,
            'activated_at' => $admin->activated_at,
            'permission_id' => $admin->permission_id
        ]);
    }

    public function createRememberEnvironment(Admin $admin)
    {
        $user_agent = $this->request->getUserAgent();
        $token = md5($admin->email . $admin->password . $user_agent);
        $expire = time() + 86400 * 8;

        $remember = Admin::findFirstByAdminId($admin->admin_id);
        $remember->remember_session = $token;
        $remember->session_expiration = $expire;

        if ($remember->save() != false) {
            $this->cookies->set('RMU', $admin->admin_id, $expire);
            $this->cookies->set('RMT', $token, $expire);
        }
    }

    public function hasRememberMe()
    {
        return $this->cookies->has('RMU');
    }

    public function loginWithRememberMe()
    {
        $admin_id = $this->cookies->get('RMU')->getValue();
        $cookie_token = $this->cookies->get('RMT')->getValue();

        $admin = Admin::findFirstByAdminId($admin_id);
        if ($admin) {
            $user_agent = $this->request->getUserAgent();
            $token = md5($admin->email . $admin->password . $user_agent);

            if ($cookie_token == $token) {
                $remember = $admin->remember_session;

                if ($remember) {
                    if ((time() - (86400 * 8)) < $admin->session_expiration) {
                        $this->session->set('auth-identity', [
                            'admin_id' => $admin->admin_id,
                            'alias' => $admin->alias,
                            'email' => $admin->email,
                            'created_at' => $admin->created_at,
                            'updated_at' => $admin->updated_at,
                            'activated_at' => $admin->activated_at,
                            'permission_id' => $admin->permission_id
                        ]);

                        return $this->response->redirect('perfil');
                    }
                }
            }
        }

        $this->cookies->get('RMU')->delete();
        $this->cookies->get('RMT')->delete();

        return $this->response->redirect('perfil/ingreso');
    }

    public function refreshSession($admin_id)
    {
        $admin = Admin::findFirstByAdminId($admin_id);

        if ($admin == false) {
            throw new Exception($this->translate('session_not_renewed'));
        }

        $this->session->set('auth-identity', [
            'admin_id' => $admin->admin_id,
            'alias' => $admin->alias,
            'email' => $admin->email,
            'created_at' => $admin->created_at,
            'updated_at' => $admin->updated_at,
            'activated_at' => $admin->activated_at,
            'permission_id' => $admin->permission_id
        ]);
    }

    public function getIdentity()
    {
        return $this->session->get('auth-identity');
    }

    public function getName()
    {
        $identity = $this->session->get('auth-identity');
        return $identity['alias'];
    }

    public function remove()
    {
        if ($this->cookies->has('RMU')) {
            $this->cookies->get('RMU')->delete();
        }
        
        if ($this->cookies->has('RMT')) {
            $token = $this->cookies->get('RMT')->getValue();

            $admin_id = $this->findFirstByToken($token);
            if ($admin_id) {
                $this->deleteToken($admin_id);
            }
            
            $this->cookies->get('RMT')->delete();
        }

        $this->session->remove('auth-identity');
    }

    public function authUserById($admin_id)
    {
        $admin = Admin::findFirstByAdminId($admin_id);

        if ($admin == false) {
            throw new Exception($this->translate('registry_not_found'));
        }

        $this->session->set('auth-identity', [
            'admin_id' => $admin->admin_id,
            'alias' => $admin->alias,
            'email' => $admin->email,
            'created_at' => $admin->created_at,
            'updated_at' => $admin->updated_at,
            'activated_at' => $admin->activated_at,
            'permission_id' => $admin->permission_id
        ]);
    }

    public function getUser()
    {
        $identity = $this->session->get('auth-identity');

        if (isset($identity['admin_id'])) {
            $admin = Admin::findFirstByAdminId($identity['admin_id']);

            if ($admin == false) {
                throw new Exception($this->translate('registry_not_found'));
            }

            return $admin;
        }

        return false;
    }
    
    public function findFirstByToken($token)
    {
        $user_token = Admin::findFirst([
            'conditions' => 'remember_session = :token:',
            'bind'       => [
                'token' => $token,
            ],
        ]);
        
        $admin_id = ($user_token) ? $user_token->admin_id : false;        
        return $admin_id;
    }

    public function deleteToken($admin_id) 
    {
        $admin = Admin::find([
            'conditions' => 'admin_id = :id:',
            'bind'       => [
                'id' => $admin_id
            ]
        ]);

        $admin->update([
            'remember_session' => NULL,
            'session_expiration' => NULL
        ]);
    }
}
