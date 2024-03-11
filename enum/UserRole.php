<?php
class UserRole {
    const Admin = 1;
    const SecurityHead = 2;
    const Department = 3;

    function get_name($role) {
        switch ($role) {
            case UserRole::Admin:
                return 'Admin';
                break;
            
            case UserRole::SecurityHead:
                return 'Security Head';
                break;
            
            case UserRole::Department:
                return 'Department';
                break;
            
            default:
                return "";
                break;
        }
    }
}