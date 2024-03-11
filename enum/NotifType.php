<?php
class NotifType {
    const TimeIn = 1;
    const TimeOut = 2;
    const Appointment = 3;

    function get_name($id) {
        switch ($id) {
            case NotifType::TimeIn:
                return 'Time In';
                break;
            
            case NotifType::TimeOut:
                return 'Time Out';
                break;
            
            case NotifType::Appointment:
                return 'will be visiting the';
                break;
            
            default:
                return "----";
                break;
        }
    }
}