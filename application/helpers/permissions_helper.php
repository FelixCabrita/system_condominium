<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('checkForPermissions'))
{ 
    function checkForPermissions($Endpoint,$Permission){ 
        $CI =& get_instance();  
        $DoesHavePermission = false; 
      
        switch($Permission){
            case PERMISSION_READ:
                $PermissionValue = $CI->getPermissions()->{$Endpoint}->R; 
                $DoesHavePermission = $PermissionValue === 1;
            break;
            case PERMISSION_CREATE:
                $PermissionValue = $CI->getPermissions()->{$Endpoint}->C;
                $DoesHavePermission = $PermissionValue === 1;
            break;
            case PERMISSION_UPDATE:
                $PermissionValue = $CI->getPermissions()->{$Endpoint}->U;
                $DoesHavePermission = $PermissionValue === 1;
            break;
            case PERMISSION_DELETE:
                $PermissionValue = $CI->getPermissions()->{$Endpoint}->D;
                $DoesHavePermission = $PermissionValue === 1;
            break;
        } 
        return $DoesHavePermission;
    } 
}

if (!function_exists('checkRole'))
{ 
    function checkRole($rol){ 
        $CI =& get_instance();   
        $role_text =$rol;
        switch($rol){
            case ROLE_ADMIN:
                $role_text = "SUPER ADMINISTRADOR";
            break;
            case ROLE_MANAGER:
                $role_text = "ADMINISTRADOR";
            break;
            case ROLE_VENDOR:
                $role_text = "CAJA";
            break;
            case ROLE_EMPLOYEE:
                $role_text = "INQUILINO";
            break;
        } 
        return $role_text;
    } 
}

?>