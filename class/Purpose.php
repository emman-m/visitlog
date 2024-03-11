<?php
class Purpose {
    const Cashier = 1;
    const Registrar = 2;
    const Clinic = 3;
    const DisciplineOffice = 4;

    function get_purpose($dept) {
        switch ($dept) {
            case Purpose::Cashier:
                return array(
                    'Tuition and Fee Payments',
                    'Submit Promissory note',
                    'Contributing funds to school fundraisers, charity events, or specific school programs or projects.'
                );
                break;
            
            case Purpose::Registrar:
                return array(
                    'Inquire for enrollment ',
                    'Form Submission',
                    'Transcript of record request'
                );
                break;
            
            case Purpose::Clinic:
                return array(
                    'Consulting students health concerns',
                    'Bringing sick or injured students for medical attention',
                    'Conducting health assessment'
                );
                break;
            
            case Purpose::DisciplineOffice:
                return array(
                    'Meeting with school officials to discuss a student’s behavior or disciplinary actions.',
                    'Seeking guidance on how to address a bullying or harassment incident involving their child.',
                    'Appealing a disciplinary decision made against their child and discussing potential resolutions or consequences.'
                );
                break;
            
            default:
                return "----";
                break;
        }
    }
}