<?php
class Department {
    const Cashier = 1;
    const Registrar = 2;
    const Clinic = 3;
    const DisciplineOffice = 4;
    const Guard = 5;

    function get_name($dept) {
        switch ($dept) {
            case Department::Cashier:
                return 'Cashier';
                break;
            
            case Department::Registrar:
                return 'Registrar';
                break;
            
            case Department::Clinic:
                return 'Clinic';
                break;
            
            case Department::DisciplineOffice:
                return 'Discipline Office';
                break;
            
            case Department::Guard:
                return 'Guard';
                break;
            
            default:
                return "----";
                break;
        }
    }
}