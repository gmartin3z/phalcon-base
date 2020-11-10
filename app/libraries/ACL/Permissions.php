<?php

namespace App\Libraries\ACL;

use Phalcon\Mvc\User\Component;
use Phalcon\Acl;
use Phalcon\Acl\Adapter\Memory as AclMemory;
use Phalcon\Acl\Role;
use Phalcon\Acl\Resource;

class Permissions extends Component
{
    private $acl;
    private $filePath;
    private $arrResources = array();

    public function isAllowed($profile, $controller, $action)
    {
        return $this->getAcl()->isAllowed($profile, $controller, $action);
    }

    public function getAcl()
    {
        if (is_object($this->acl)) {
            return $this->acl;
        }

        if (function_exists('apc_fetch')) {
            $acl = apc_fetch('app-acl');
            if (is_object($acl)) {
                $this->acl = $acl;
                return $acl;
            }
        }

        $filePath = $this->getFilePath();

        if (!file_exists($filePath)) {
            $this->acl = $this->rebuild();
            return $this->acl;
        }

        $data = file_get_contents($filePath);
        $this->acl = unserialize($data);

        if (function_exists('apc_store')) {
            apc_store('app-acl', $this->acl);
        }

        return $this->acl;
    }

    public function getResources()
    {
        return $this->arrResources;
    }

    public function rebuild()
    {
        $acl = new AclMemory();
        $acl->setDefaultAction(Acl::DENY);
        $arrRoles = array_keys($this->arrResources);

        foreach ($arrRoles as $arrRole) {
            $role = new Role($arrRole);
            $acl->addRole($role);
        }

        foreach($this->arrResources as $arrResource){
            foreach($arrResource as $controller => $arrMethods){
                $acl->addResource(new Resource($controller), $arrMethods);
            }
        }

        foreach ($acl->getRoles() as $objRole) {
            $roleName = $objRole->getName();
            if($roleName == 'visitor'){
                foreach ($this->arrResources['visitor'] as $resource => $method) {
                    $acl->allow($roleName,$resource,$method);
                }
            }

            if($roleName == 'limited'){
                foreach ($this->arrResources['limited'] as $resource => $method) {
                    $acl->allow($roleName,$resource,$method);
                }
            }

            if($roleName == 'admin'){
                foreach ($this->arrResources['admin'] as $resource => $method) {
                    $acl->allow($roleName,$resource,$method);
                }
            }
        }

        $filePath = $this->getFilePath();

        if (touch($filePath) && is_writable($filePath)) {

            file_put_contents($filePath, serialize($acl));

            if (function_exists('apc_store')) {
                apc_store('app-acl', $acl);
            }

        } else {
            $this->flash->error(
                'Cannot create the ACL list at ' . $filePath
            );
        }

        return $acl;
    }

    protected function getFilePath()
    {
        if (!isset($this->filePath)) {
            $this->filePath = rtrim($this->config->application->cacheDir, '\\/') . '/acl/acl-list.acl';
        }

        return $this->filePath;
    }

    public function addResources(array $resources) {
        if (count($resources) > 0) {
            $this->arrResources = array_merge($this->arrResources, $resources);
            if (is_object($this->acl)) {
                $this->acl = $this->rebuild();
            }
        }
    }
}
