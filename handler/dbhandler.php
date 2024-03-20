<?php
session_start();
date_default_timezone_set('Asia/Manila');
require '../class/DatabaseManager.php';
require '../enum/UserRole.php';
require '../enum/Department.php';
require '../enum/NotifType.php';
$db = new DatabaseManager();
$user_role = new UserRole();
$dept = new Department();
$notif_type = new NotifType();

// POST
if(isset($_POST['action'] )) {
    $result = [];

    switch ($_POST['action']) {
        case 'login':
            // validation
            $valid = 0;
            $errMsg = array();
            if ($_POST['username'] == "") {
                $errMsg['err_username'] = "This field is required";
                $valid++;
            }

            if ($_POST['password'] == "") {
                $errMsg['err_password'] = "This field is required";
                $valid++;
            }
            
            if ($valid == 0) {
                $conditions = array(
                    "username" => $_POST['username'],
                    'active' => 1
                );
                $data = $db->selectWhere("user_account", $conditions);
                if (count($data) > 0) {
                    $row = $data[0];
                    //verify password from db
                    $verification = password_verify($_POST['password'], $row['password']);

                    if ($verification) {
                        $result['success'] = true;
                        
                        $_SESSION['uid'] = $row['id'];
                        $_SESSION['uname'] = $row['firstname'] .' '. $row['lastname'];
                        $_SESSION['role'] = $row['role'];
                        $_SESSION['dept'] = $row['department'];
                    } else {
                        $result['success'] = false;
                        $errMsg['err_msg'] = "Login Failed";
                    }
                    
                } else {
                    $result['success'] = false;
                    $errMsg['err_msg'] = "Login Failed";
                }
            } else {
                $result['success'] = false;
            }

            $result['error'] = $errMsg;
            break;
        
        case 'add_user':
            $valid = 0;
            $errMsg = array();
            if ($_POST['firstname'] == "") {
                $errMsg['err_firstname'] = "This field is required";
                $valid++;
            }

            if ($_POST['lastname'] == "") {
                $errMsg['err_lastname'] = "This field is required";
                $valid++;
            }

            if (!isset($_POST['role']) || $_POST['role'] == "") {
                $errMsg['err_role'] = "This field is required";
                $valid++;
            } else if ($_POST['role'] == 3 && (!isset($_POST['dept']) || $_POST['dept'] == "") ){
                $errMsg['err_dept'] = "This field is required";
                $valid++;
            }

            if ($_POST['username'] == "") {
                $errMsg['err_username'] = "This field is required";
                $valid++;
            } else {
                if (strlen($_POST['username']) > 10) {
                    $errMsg['err_username'] = "Username must not be exceed to 10 characters.";
                    $valid++;
                } else {
                    $data = $db->selectWhere("user_account", array("username" => $_POST['username']));
                    if (!empty($data)) {
                        $errMsg['err_username'] = "Username already taken.";
                        $valid++;
                    }
                }
            }

            if ($_POST['password'] == "") {
                $errMsg['err_password'] = "This field is required";
                $valid++;
            } else {
                if (strlen($_POST['password']) < 8) {
                    $errMsg['err_password'] = "Password must be atleast 8 characters.";
                    $valid++;
                } else if (strlen($_POST['password']) > 10) {
                    $errMsg['err_password'] = "Password must not be exceed to 10 characters.";
                    $valid++;
                }
            }

            if ($_POST['cpassword'] == "") {
                $errMsg['err_cpassword'] = "This field is required";
                $valid++;
            } else {
                if (strlen($_POST['cpassword']) < 8) {
                    $errMsg['err_cpassword'] = "Password must be atleast 8 characters.";
                    $valid++;
                } else if (strlen($_POST['cpassword']) > 10) {
                    $errMsg['err_cpassword'] = "Password must not be exceed to 10 characters.";
                    $valid++;
                }
            }

            if ($_POST['password'] !== "" && $_POST['cpassword'] !== "") {
                if ($_POST['password'] !== $_POST['cpassword']) {
                    $errMsg['err_cpassword'] .= "<br> Password not match.";
                    $valid++;
                }
            }
            
            if ($valid == 0) {
                $insertData['user_account'] = array(
                    "firstname" => $_POST['firstname'], 
                    "lastname" => $_POST['lastname'],
                    "email" => $_POST['email'],
                    "username" => $_POST['username'],
                    "password" => password_hash($_POST['password'], PASSWORD_DEFAULT),
                    "role" => $_POST['role'],
                    "department" => $_POST['role'] == $user_role::Department ? $_POST['dept'] : 0
                );
                $res['user_account'] = $db->insert("user_account", $insertData['user_account']);

                if ($res['user_account'] > 0) {
                    $result['success'] = true;
                    $result['msg'] = "Account Successfully Saved!";
                } else {
                    $result['success'] = false;
                    $errMsg['err_msg'] = "There was a problem saving your account.";
                }
            } else {
                $result['success'] = false;
            }

            $result['error'] = $errMsg;

            break;

        case 'add_visitor':
            $valid = 0;
            $errMsg = array();
            if ($_POST['name'] == "") {
                $errMsg['err_name'] = "This field is required";
                $valid++;
            }

            if ($_POST['email'] == "") {
                $errMsg['err_email'] = "This field is required";
                $valid++;
            }

            if ($_POST['rfid'] == "") {
                $errMsg['err_rfid'] = "This field is required";
                $valid++;
            }

            if ($_POST['validid'] == "") {
                $errMsg['err_validid'] = "This field is required";
                $valid++;
            }

            // Multiple purpose
            if (isset($_POST['p'])) {
                $purpose = [];
                foreach ($_POST['p'] as $pur) {
                    switch ($pur) {
                        case $dept::Cashier:
                            if (isset($_POST['p-cash'])) {
                                foreach ($_POST['p-cash'] as $val) {
                                    $purpose[$pur][] = $val;
                                }
                            }

                            if (isset($_POST['p-cash-input'])) {
                                foreach ($_POST['p-cash-input'] as $val) {
                                    $purpose[$pur][] = $val;
                                }
                            }

                            if (!isset($_POST['p-cash']) && !isset($_POST['p-cash-input'])) {
                                $errMsg['err_cashieroption'] = "Please select from the options";
                                $valid++;
                            }

                            break;

                        case $dept::Registrar:
                            if (isset($_POST['p-reg'])) {
                                foreach ($_POST['p-reg'] as $val) {
                                    $purpose[$pur][] = $val;
                                }
                            }

                            if (isset($_POST['p-reg-input'])) {
                                foreach ($_POST['p-reg-input'] as $val) {
                                    $purpose[$pur][] = $val;
                                }
                            }

                            if (!isset($_POST['p-reg']) && !isset($_POST['p-reg-input'])) {
                                $errMsg['err_regoption'] = "Please select from the options";
                                $valid++;
                            }

                            break;

                        case $dept::Clinic:
                            if (isset($_POST['p-clinic'])) {
                                foreach ($_POST['p-clinic'] as $val) {
                                    $purpose[$pur][] = $val;
                                }
                            }

                            if (isset($_POST['p-clinic-input'])) {
                                foreach ($_POST['p-clinic-input'] as $val) {
                                    $purpose[$pur][] = $val;
                                }
                            }

                            if (!isset($_POST['p-clinic']) && !isset($_POST['p-clinic-input'])) {
                                $errMsg['err_clinicoption'] = "Please select from the options";
                                $valid++;
                            }

                            break;

                        default:

                            if (isset($_POST['p-discipline'])) {
                                foreach ($_POST['p-discipline'] as $val) {
                                    $purpose[$pur][] = $val;
                                }
                            }

                            if (isset($_POST['p-discipline-input'])) {
                                foreach ($_POST['p-discipline-input'] as $val) {
                                    $purpose[$pur][] = $val;
                                }
                            }

                            if (!isset($_POST['p-discipline']) && !isset($_POST['p-discipline-input'])) {
                                $errMsg['err_disciplineoption'] = "Please select from the options";
                                $valid++;
                            }

                            break;
                    }
                }
            } else {
                $errMsg['err_purpose'] = "This is a required field";
                $valid++;
            }

            if ($_POST['img'] == "") {
                $errMsg['err_img'] = "Photo is required";
                $valid++;
            }

            if ($valid == 0) {
                $filename = Date('YmdHis').'.png';

                $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $_POST['img']));
                // Save the decoded image to a file (optional)
                file_put_contents('../assets/img/visitor/'.$filename, $data);

                // Update old user where rfid is registered
                $db->update('visitors', array('rfid' => ''), array('rfid' => $_POST['rfid']));

                // Insert new purpose
                if (isset($_POST['p-cash-input'])) {
                    foreach ($_POST['p-cash-input'] as $val) {
                        $db->insert("purpose", array('department'=>$dept::Cashier, 'name'=>$val));
                    }
                }
                if (isset($_POST['p-reg-input'])) {
                    foreach ($_POST['p-reg-input'] as $val) {
                        $db->insert("purpose", array('department'=>$dept::Registrar, 'name'=>$val));
                    }
                }
                if (isset($_POST['p-clinic-input'])) {
                    foreach ($_POST['p-clinic-input'] as $val) {
                        $db->insert("purpose", array('department'=>$dept::Clinic, 'name'=>$val));
                    }
                }
                if (isset($_POST['p-discipline-input'])) {
                    foreach ($_POST['p-discipline-input'] as $val) {
                        $db->insert("purpose", array('department'=>$dept::DisciplineOffice, 'name'=>$val));
                    }
                }

                $insertData = array(
                    "name" => $_POST['name'], 
                    "email" => $_POST['email'],
                    "gender" => $_POST['gender'],
                    "birthday" => $_POST['birthday'],
                    "age" => $_POST['age'],
                    "address" => $_POST['address'],
                    "purpose" => json_encode($purpose),
                    "rfid" => $_POST['rfid'],
                    "valid_id" => $_POST['validid'],
                    "image" => $filename
                );
                $res = $db->insert("visitors", $insertData);

                // Notif
                foreach ($_POST['p'] as $pur) {
                    $insertData = array(
                        'visitor_id'    => $res,
                        'activity_id'   => '',
                        'type'          => $notif_type::Appointment,
                        'is_read'       => 0,
                        'role_dept'     => $user_role::Department . ':' . $pur
                    );
                    $db->insert('notif', $insertData);
                }
                

                if ($res > 0) {
                    $result['success'] = true;
                    $result['msg'] = "Visitor Successfully Saved!";
                } else {
                    $result['success'] = false;
                    $errMsg['err_msg'] = "There was a problem saving your Data.";
                }
            } else {
                $result['success'] = false;
            }
            
            $result['error'] = $errMsg;
            break;

        case 'edit_visitor':
            $valid = 0;
            $errMsg = array();
            if ($_POST['name'] == "") {
                $errMsg['err_name'] = "This field is required";
                $valid++;
            }

            if ($_POST['email'] == "") {
                $errMsg['err_email'] = "This field is required";
                $valid++;
            }

            if ($_POST['rfid'] == "") {
                $errMsg['err_rfid'] = "This field is required";
                $valid++;
            }

            if ($_POST['validid'] == "") {
                $errMsg['err_validid'] = "This field is required";
                $valid++;
            }

            // Multiple purpose
            if (isset($_POST['p'])) {
                $purpose = [];
                foreach ($_POST['p'] as $pur) {
                    switch ($pur) {
                        case $dept::Cashier:
                            if (isset($_POST['p-cash'])) {
                                foreach ($_POST['p-cash'] as $val) {
                                    $purpose[$pur][] = $val;
                                }
                            }

                            if (isset($_POST['p-cash-input'])) {
                                foreach ($_POST['p-cash-input'] as $val) {
                                    $purpose[$pur][] = $val;
                                }
                            }

                            if (!isset($_POST['p-cash']) && !isset($_POST['p-cash-input'])) {
                                $errMsg['err_cashieroption'] = "Please select from the options";
                                $valid++;
                            }

                            break;

                        case $dept::Registrar:
                            if (isset($_POST['p-reg'])) {
                                foreach ($_POST['p-reg'] as $val) {
                                    $purpose[$pur][] = $val;
                                }
                            }

                            if (isset($_POST['p-reg-input'])) {
                                foreach ($_POST['p-reg-input'] as $val) {
                                    $purpose[$pur][] = $val;
                                }
                            }

                            if (!isset($_POST['p-reg']) && !isset($_POST['p-reg-input'])) {
                                $errMsg['err_regoption'] = "Please select from the options";
                                $valid++;
                            }

                            break;

                        case $dept::Clinic:
                            if (isset($_POST['p-clinic'])) {
                                foreach ($_POST['p-clinic'] as $val) {
                                    $purpose[$pur][] = $val;
                                }
                            }

                            if (isset($_POST['p-clinic-input'])) {
                                foreach ($_POST['p-clinic-input'] as $val) {
                                    $purpose[$pur][] = $val;
                                }
                            }

                            if (!isset($_POST['p-clinic']) && !isset($_POST['p-clinic-input'])) {
                                $errMsg['err_clinicoption'] = "Please select from the options";
                                $valid++;
                            }

                            break;

                        default:

                            if (isset($_POST['p-discipline'])) {
                                foreach ($_POST['p-discipline'] as $val) {
                                    $purpose[$pur][] = $val;
                                }
                            }

                            if (isset($_POST['p-discipline-input'])) {
                                foreach ($_POST['p-discipline-input'] as $val) {
                                    $purpose[$pur][] = $val;
                                }
                            }

                            if (!isset($_POST['p-discipline']) && !isset($_POST['p-discipline-input'])) {
                                $errMsg['err_disciplineoption'] = "Please select from the options";
                                $valid++;
                            }

                            break;
                    }
                }
            } else {
                $errMsg['err_purpose'] = "This is a required field";
                $valid++;
            }


            if ($valid == 0) {
                // Update old user where rfid is registered
                $db->update('visitors', array('rfid' => ''), array('rfid' => $_POST['rfid']));

                // Insert new purpose
                if (isset($_POST['p-cash-input'])) {
                    foreach ($_POST['p-cash-input'] as $val) {
                        $db->insert("purpose", array('department' => $dept::Cashier, 'name' => $val));
                    }
                }
                if (isset($_POST['p-reg-input'])) {
                    foreach ($_POST['p-reg-input'] as $val) {
                        $db->insert("purpose", array('department' => $dept::Registrar, 'name' => $val));
                    }
                }
                if (isset($_POST['p-clinic-input'])) {
                    foreach ($_POST['p-clinic-input'] as $val) {
                        $db->insert("purpose", array('department' => $dept::Clinic, 'name' => $val));
                    }
                }
                if (isset($_POST['p-discipline-input'])) {
                    foreach ($_POST['p-discipline-input'] as $val) {
                        $db->insert("purpose", array('department' => $dept::DisciplineOffice, 'name' => $val));
                    }
                }

                $insertData = array(
                    "name" => $_POST['name'], 
                    "email" => $_POST['email'],
                    "gender" => $_POST['gender'],
                    "birthday" => $_POST['birthday'],
                    "age" => $_POST['age'],
                    "address" => $_POST['address'],
                    "purpose" => json_encode($purpose),
                    "rfid" => $_POST['rfid'],
                    "valid_id" => $_POST['validid'],
                );

                if ($_POST['img'] !== "") {
                    $filename = Date('YmdHis').'.png';
                    $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $_POST['img']));
                    file_put_contents('../assets/img/visitor/'.$filename, $data);
                    
                    $insertData["image"] = $filename;
                }
                
                $res = $db->update("visitors", $insertData, array('id' => $_POST['id']));

                // Notif
                foreach ($_POST['p'] as $pur) {
                    $insertData = array(
                        'visitor_id'    => $_POST['id'],
                        'activity_id'   => '',
                        'type'          => $notif_type::Appointment,
                        'is_read'       => 0,
                        'role_dept'     => $user_role::Department . ':' . $pur
                    );
                    $db->insert('notif', $insertData);
                }

                if ($res > 0) {
                    $result['success'] = true;
                    $result['msg'] = "Visitor Successfully Updated!";
                } else {
                    $result['success'] = false;
                    $errMsg['err_msg'] = "There was a problem saving your Data.";
                }
            } else {
                $result['success'] = false;
            }

            $result['error'] = $errMsg;

            break;

        case 'delete_visitor':
            $visitorData = $db->selectWhere("visitors", array('id' => $_POST['id']));
            $img = $visitorData[0]['image'];
            unlink('../assets/img/visitor/'.$img);

            $data = $db->delete('visitors', array('id' => $_POST['id']));

            if ($data) {
                $result['success'] = true;
            } else {
                $result['success'] = false;
                $errMsg['err_msg'] = "There was a problem Deleting. Please Try Later.";
                $result['error'] = $errMsg;
            }
            break;
        case 'edit_user':
            $userData = $db->selectWhere("user_account", array('id' => $_POST['id']));
            $user = $userData[0];

            $valid = 0;
            $errMsg = array();
            if ($_POST['firstname'] == "") {
                $errMsg['err_firstname'] = "This field is required";
                $valid++;
            }

            if ($_POST['lastname'] == "") {
                $errMsg['err_lastname'] = "This field is required";
                $valid++;
            }

            if (!isset($_POST['role']) || $_POST['role'] == "") {
                $errMsg['err_role'] = "This field is required";
                $valid++;
            } else if ($_POST['role'] == 3 && (!isset($_POST['dept']) || $_POST['dept'] == "") ){
                $errMsg['err_dept'] = "This field is required";
                $valid++;
            }

            if ($_POST['username'] == "") {
                $errMsg['err_username'] = "This field is required";
                $valid++;
            } else {
                if (strlen($_POST['username']) > 10) {
                    $errMsg['err_username'] = "Username must not be exceed to 10 characters.";
                    $valid++;
                } else {
                    $condition = array(
                        "username" => array($_POST['username'],'!='.$user['username'])
                    );
                    $data = $db->selectWhere("user_account", $condition);
                    if (!empty($data)) {
                        $errMsg['err_username'] = "Username already taken.";
                        $valid++;
                    }
                }
            }

            // change password
            if ($_POST['oldpass'] != "") {
                $changePass = true;
                if (!password_verify($_POST['oldpass'], $user['password'])) {
                    $errMsg['err_oldpass'] = "You've entered the wrong password.";
                    $valid++;
                }

                if ($_POST['password'] == "") {
                    $errMsg['err_password'] = "This field is required";
                    $valid++;
                } else {
                    if (strlen($_POST['password']) < 8) {
                        $errMsg['err_password'] = "Password must be atleast 8 characters.";
                        $valid++;
                    } else if (strlen($_POST['password']) > 10) {
                        $errMsg['err_password'] = "Password must not be exceed to 10 characters.";
                        $valid++;
                    }
                }
    
                if ($_POST['cpassword'] == "") {
                    $errMsg['err_cpassword'] = "This field is required";
                    $valid++;
                } else {
                    if (strlen($_POST['cpassword']) < 8) {
                        $errMsg['err_cpassword'] = "Password must be atleast 8 characters.";
                        $valid++;
                    } else if (strlen($_POST['cpassword']) > 10) {
                        $errMsg['err_cpassword'] = "Password must not be exceed to 10 characters.";
                        $valid++;
                    }
                }
    
                if ($_POST['password'] !== "" && $_POST['cpassword'] !== "") {
                    if ($_POST['password'] !== $_POST['cpassword']) {
                        $errMsg['err_cpassword'] .= "<br> Password not match.";
                        $valid++;
                    }
                }
            }
            
            if ($valid == 0) {
                $updateData = array(
                    "firstname" => $_POST['firstname'], 
                    "lastname" => $_POST['lastname'],
                    "email" => $_POST['email'],
                    "username" => $_POST['username'],
                    "role" => $_POST['role'],
                    "department" => $_POST['role'] == $user_role::Department ? $_POST['dept'] : 0
                );

                if ($_POST['password'] !== "") {
                    $updateData['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
                }

                $res = $db->update("user_account", $updateData, array('id' => $_POST['id']));

                if ($res > 0) {
                    $result['success'] = true;
                    $result['msg'] = "Account Successfully Updated!";
                } else {
                    $result['success'] = false;
                    $errMsg['err_msg'] = "There was a problem saving your account.";
                }
            } else {
                $result['success'] = false;
            }

            $result['error'] = $errMsg;

            break;
        case 'enable_disable':
            $enable = filter_var($_POST['state'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
            $data = $db->update('user_account', array('active' => $enable), array('id' => $_POST['id']));

            if ($data) {
                $result['success'] = true;
            } else {
                $result['success'] = false;
                $errMsg['err_msg'] = "There was a problem Saving. Please Try Later.";
                $result['error'] = $errMsg;
            }
            $result['state'] = $enable;

            break;

        case 'tap_rfid':
            $data = $db->selectWhere('visitors', array('rfid' => $_POST['rfid']));
            if ($data) {
                $visitor = $data[0];
                $case = $db->selectVisitorActivity($visitor['id'], $_SESSION['dept']);
                $logId = "";
                $type = 0;

                $purpose = "";
                $purposeArr = [];
                $arr = json_decode($visitor['purpose'], true);

                if (isset($arr[$_SESSION['dept']])) {
                    $purpose = '<ol><li>'.implode('</li><li>', $arr[$_SESSION['dept']]).'</li></ol>';
                    $purposeArr = $arr[$_SESSION['dept']];
                }
                // $purpose = $val;

                switch ($case) {
                    case 'insert':
                        
                        $insertData = array(
                            'visitor_id' => $visitor['id'],
                            'department' => $_SESSION['dept'],
                            'purpose' => json_encode($purposeArr),
                            'time_in' => Date('Y-m-d H:i:s')
                        );
                        $logId = $db->insert('visitors_activity', $insertData);

                        // Notif Type
                        $type = 1;
                        break;
                    
                    default:
                        $db->update('visitors_activity', array('time_out' => Date('Y-m-d H:i:s')), array('id' => $case));
                        $logId = $case;

                        // Notif Type
                        $type = 2;
                        break;
                }

                $logData = $db->selectWhere('visitors_activity', array('id' => $logId));

                $inDate = new DateTime($logData[0]['time_in']);
                $outDate = $logData[0]['time_out'] == null ? '--/--/-- --:--' : new DateTime($logData[0]['time_out']);
                $outDate = ($outDate == '--/--/-- --:--') ? $outDate :$outDate->format("M d Y D, h:i A");

                $result['image'] = "assets/img/visitor/".$visitor['image'];
                $result['name'] = $visitor['name'];
                $result['purpose'] = $purpose;
                $result['timeIn'] = $inDate->format("M d Y D, h:i A");
                $result['timeOut'] = $outDate;

                // Notif
                $insertData = array(
                    'visitor_id'    => $visitor['id'],
                    'activity_id'   => $logId,
                    'type'          => $type,
                    'is_read'       => 0,
                    'role_dept'     => $user_role::SecurityHead
                );
                $db->insert('notif', $insertData);

                $result['success'] = true;
            } else {
                $result['success'] = false;
            }
            
            break;

        case 'rpt_top_office_month':
            /* for new visitors data
            SELECT va.department, v.id AS visitor_id, v.rfid, v.name, v.email, v.age, v.gender, v.birthday, v.address, v.image, v.valid_id, v.purpose, va.time_in FROM visitors_activity va JOIN visitors v ON va.visitor_id = v.id WHERE DATE(va.time_in) >= '2024-01-01' AND va.visitor_id IN (SELECT id FROM visitors WHERE created_at >= '2024-01-01%');
            */
            /* for old visitors data
            SELECT va.department, v.id AS visitor_id, v.rfid, v.name, v.email, v.age, v.gender, v.birthday, v.address, v.image, v.valid_id, v.purpose, va.time_in FROM visitors_activity va JOIN visitors v ON va.visitor_id = v.id WHERE DATE(va.time_in) >= '2024-01-01' AND va.visitor_id IN (SELECT id FROM visitors WHERE created_at < '2024-01-01%' AND updated_at > '2024-01-01"."%');
            */

            $data = [];
            $dayArr = [];
            $departmentLabel = array();
            $value = [];
            $value['new'] = [];
            $value['old'] = [];
            
            $date = new DateTime();
            $currentMonth = $date->format('Y-m');

            $query3 = "SELECT department, COUNT(DISTINCT visitor_id) AS department_count FROM visitors_activity WHERE DATE(time_in) >= '".$currentMonth."-01"."' AND visitor_id IN (SELECT id FROM visitors WHERE created_at >= '".$currentMonth."-01". "%') AND department <> '" . $dept::Guard . "' GROUP BY department;";
            $dbNewData = $db->selectNative($query3);
            
            $query4 = "SELECT department, COUNT(DISTINCT visitor_id) AS department_count FROM visitors_activity WHERE DATE(time_in) >= '".$currentMonth."-01"."' AND visitor_id IN (SELECT id FROM visitors WHERE created_at < '".$currentMonth."-01"."%' AND updated_at >= '".$currentMonth."-01"."%') AND department <> '".$dept::Guard."' GROUP BY department;";
            $dbOldData = $db->selectNative($query4);
            $result['query1'] = $query3;
            $result['query2'] = $query4;

            // Initialize department index
            $deptIndex = 1;

            // Loop through each department index
            for ($i = 1; $i <= 4; $i++) {
                // Check if data for new visitor exists for current department index
                $newCount = 0;
                foreach ($dbNewData as $data) {
                    if ($data['department'] == $deptIndex) {
                        $newCount = $data['department_count'];
                        break;
                    }
                }
                // Push new visitor count to array
                $value['new'][] = $newCount;

                // Check if data for old visitor exists for current department index
                $oldCount = 0;
                foreach ($dbOldData as $data) {
                    if ($data['department'] == $deptIndex) {
                        $oldCount = $data['department_count'];
                        break;
                    }
                }
                // Push old visitor count to array
                $value['old'][] = $oldCount;

                // Get department name and push to department label array
                $departmentLabel[] = $dept->get_name($deptIndex);

                // Increment department index
                $deptIndex++;
            }
           
            $data['labels'] = $departmentLabel;
            $dataSet['new'] = array(
                'label' => 'New Visitor',
                'backgroundColor' => 'rgba(60,141,188,0.9)',
                'borderColor' => 'rgba(60,141,188,0.8)',
                'pointRadius' => false,
                'pointColor' => '#3b8bba',
                'pointStrokeColor' => 'rgba(60,141,188,1)',
                'pointHighlightFill' => '#fff',
                'pointHighlightStroke' => 'rgba(60,141,188,1)',
                'data' => $value['new']
            );
            $dataSet['old'] = array(
                'label' => 'Old Visitors',
                'backgroundColor' => 'rgba(210, 214, 222, 1)',
                'borderColor' => 'rgba(210, 214, 222, 1)',
                'pointRadius' => false,
                'pointColor' => '#3b8bba',
                'pointStrokeColor' => 'rgba(210, 214, 222, 1)',
                'pointHighlightFill' => '#fff',
                'pointHighlightStroke' => 'rgba(220,220,220,1)',
                'data' => $value['old']
            );

            $data['datasets'] = array($dataSet['old'], $dataSet['new']);

            $result['result'] = $data;
            break;

        case 'rpt_top_office_month_page':
            $data = [];
            $dayArr = [];
            $departmentLabel = array();
            $value = [];
            $value['new'] = [];
            $value['old'] = [];
            
            $date = new DateTime();
            $currentMonth = $date->format('Y-m');

            $fromDate = $_POST['from'];
            $toDate  = $_POST['to'];

            // $query3 = "SELECT department, COUNT(DISTINCT visitor_id) AS department_count FROM visitors_activity va JOIN visitors v ON va.visitor_id = v.id WHERE DATE(va.time_in) >= '".$fromDate."' AND DATE(va.time_in) <= '".$toDate."' AND va.visitor_id IN (SELECT id FROM visitors WHERE created_at >= '".$fromDate."%') AND department != '".$dept::Guard."' GROUP BY department";
            $query3 = "SELECT department, COUNT(DISTINCT visitor_id) AS department_count FROM visitors_activity WHERE department <> '" . $dept::Guard . "' AND time_in BETWEEN '" . $fromDate . "' AND '" . $toDate . "' AND visitor_id IN (SELECT id FROM visitors WHERE created_at >= '" . $fromDate . "%') GROUP BY department ORDER BY department;";
            $dbNewData = $db->selectNative($query3);
            
            // $query4 = "SELECT department, COUNT(DISTINCT visitor_id) AS department_count FROM visitors_activity va JOIN visitors v ON va.visitor_id = v.id WHERE DATE(va.time_in) >= '".$fromDate."' AND DATE(va.time_in) <= '".$toDate."' AND va.visitor_id IN (SELECT id FROM visitors WHERE created_at < '".$fromDate."%' AND updated_at >= '".$fromDate."%') AND department != '".$dept::Guard."' GROUP BY department";
            $query4 = "SELECT department, COUNT(DISTINCT visitor_id) AS department_count FROM visitors_activity WHERE department <> '" . $dept::Guard . "' AND time_in BETWEEN '" . $fromDate . "' AND '" . $toDate . "' AND visitor_id IN (SELECT id FROM visitors WHERE created_at < '" . $fromDate . "%') GROUP BY department ORDER BY department;";
            $dbOldData = $db->selectNative($query4);

            $result['query1'] = json_encode($dbNewData);
            $result['query2'] = json_encode($dbOldData);

            // ----------------------------------

            // NEW
            // Initialize department index
            $deptIndex = 1;

            // Loop through each department index
            for ($i = 1;
                $i <= 5;
                $i++
            ) {
                // Check if data for new visitor exists for current department index
                $newCount = 0;
                foreach ($dbNewData as $data) {
                    if ($data['department'] == $deptIndex) {
                        $newCount = $data['department_count'];
                        break;
                    }
                }
                // Push new visitor count to array
                $value['new'][] = $newCount;

                // Check if data for old visitor exists for current department index
                $oldCount = 0;
                foreach ($dbOldData as $data) {
                    if ($data['department'] == $deptIndex) {
                        $oldCount = $data['department_count'];
                        break;
                    }
                }
                // Push old visitor count to array
                $value['old'][] = $oldCount;

                // Get department name and push to department label array
                $departmentLabel[] = $dept->get_name($deptIndex);

                // Increment department index
                $deptIndex++;
            }
            // ----------------------------------
           
            $data['labels'] = $departmentLabel;
            $dataSet['new'] = array(
                'label' => 'New Visitor',
                'backgroundColor' => 'rgba(60,141,188,0.9)',
                'borderColor' => 'rgba(60,141,188,0.8)',
                'pointRadius' => false,
                'pointColor' => '#3b8bba',
                'pointStrokeColor' => 'rgba(60,141,188,1)',
                'pointHighlightFill' => '#fff',
                'pointHighlightStroke' => 'rgba(60,141,188,1)',
                'data' => $value['new']
            );
            $dataSet['old'] = array(
                'label' => 'Old Visitors',
                'backgroundColor' => 'rgba(210, 214, 222, 1)',
                'borderColor' => 'rgba(210, 214, 222, 1)',
                'pointRadius' => false,
                'pointColor' => '#3b8bba',
                'pointStrokeColor' => 'rgba(210, 214, 222, 1)',
                'pointHighlightFill' => '#fff',
                'pointHighlightStroke' => 'rgba(220,220,220,1)',
                'data' => $value['old']
            );

            $data['datasets'] = array($dataSet['old'], $dataSet['new']);

            $result['result'] = $data;
            break;

        case 'rpt_top_day_month':

            $data = [];
            $dayArr = [];
            $dayLabel = array();
            $value = [];
            
            $date = new DateTime();
            $currentMonth = $date->format('Y-m');

            $query = "SELECT DAYNAME(time_in) AS day_of_week, COUNT(DAYNAME(time_in)) AS visitor_count FROM visitors_activity WHERE time_in >= '".$currentMonth."-01"."' AND department != '".$dept::Guard."' GROUP BY day_of_week ORDER BY FIELD(day_of_week, 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');";
            $dbNewData = $db->selectNative($query);
            $result['queryDays'] = $query;

            $result['json'] = json_encode($dbNewData);

            $dayLabel = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            $c = 0;
            foreach ($dayLabel as $dayRow) {
                $found = false;
                foreach ($dbNewData as $row) {
                    if ($dayRow == $row['day_of_week']) {
                        $value[] = $row['visitor_count'];
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $value[] = '0';
                }
            }
            
            $data['labels'] = $dayLabel;
            $dataSet = array(
                'label' => 'Visitors',
                'backgroundColor' => 'rgba(60,141,188,0.9)',
                'borderColor' => 'rgba(60,141,188,0.8)',
                'pointRadius' => false,
                'pointColor' => '#3b8bba',
                'pointStrokeColor' => 'rgba(60,141,188,1)',
                'pointHighlightFill' => '#fff',
                'pointHighlightStroke' => 'rgba(60,141,188,1)',
                'data' => $value
            );

            $data['datasets'] = array($dataSet);

            $result['result'] = $data;
            break;

        case 'rpt_top_day_month_page':
            $data = [];
            $dayArr = [];
            $dayLabel = array();
            $value = [];
            
            if (!isset($_POST['from']) && !isset($_POST['to'])) {
                $fromDate = date('Y-m-01');
                $toDate  = date('Y-m-t');
            } else {
                $fromDate = $_POST['from'];
                $toDate  = $_POST['to'];
            }

            $query = "SELECT DAYNAME(time_in) AS day_of_week, COUNT(DAYNAME(time_in)) AS visitor_count FROM visitors_activity WHERE time_in >= '".$fromDate."' AND time_in <= '".$toDate."' AND department <> '".$dept::Guard."' GROUP BY day_of_week ORDER BY FIELD(day_of_week, 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');";
            $dbNewData = $db->selectNative($query);
            $result['querysd'] = $query;

            $result['json'] = json_encode($dbNewData);

            $dayLabel = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            $c = 0;
            foreach ($dayLabel as $dayRow) {
                $found = false;
                foreach ($dbNewData as $row) {
                    if ($dayRow == $row['day_of_week']) {
                        $value[] = $row['visitor_count'];
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $value[] = '0';
                }
            }
            
            $data['labels'] = $dayLabel;
            $dataSet = array(
                'label' => 'Visitors',
                'backgroundColor' => 'rgba(60,141,188,0.9)',
                'borderColor' => 'rgba(60,141,188,0.8)',
                'pointRadius' => false,
                'pointColor' => '#3b8bba',
                'pointStrokeColor' => 'rgba(60,141,188,1)',
                'pointHighlightFill' => '#fff',
                'pointHighlightStroke' => 'rgba(60,141,188,1)',
                'data' => $value
            );

            $data['datasets'] = array($dataSet);

            $result['result'] = $data;
            break;

        case 'rpt_old_vs_new_visitor':
            /*for new visitors
                SELECT * FROM visitors WHERE created_at >= '2024-01-01%';

                OLD
                SELECT * FROM visitors WHERE created_at < '2024-01-01%' AND updated_at >= '2024-01-01%';
            */
            $data = [];
            $dayArr = [];
            $day = "";

            $date = new DateTime();
            $currentMonth = $date->format('Y-m-01');

            $query1 = "SELECT COUNT(id) as new FROM visitors WHERE created_at >= '".$currentMonth."%'";
            $new = $db->selectNative($query1);

            $query2 = "SELECT COUNT(id) as old FROM visitors WHERE created_at < '".$currentMonth."%' AND updated_at >= '".$currentMonth."%'";
            $old = $db->selectNative($query2);

            
            $data['labels'] = ['Old Visitors', 'New Visitors'];
            $dataset = array(
                "data"=> array($old[0]['old'], $new[0]['new']),
                "backgroundColor" => array('#f56954', '#00a65a')
            );
            
            $data['datasets'] = array($dataset);
            

            $result['result'] = $data;
            break;
            
        case 'rpt_old_vs_new_visitor_page':
            /*for new visitors
                SELECT * FROM visitors WHERE created_at >= '2024-01-01%';

                OLD
                SELECT * FROM visitors WHERE created_at < '2024-01-01%' AND updated_at >= '2024-01-01%';
            */
            $data = [];
            $dayArr = [];
            $day = "";

            if (!isset($_POST['from']) && !isset($_POST['to'])) {
                $fromDate = date('Y-m-01');
                $toDate  = date('Y-m-t');
            } else {
                $fromDate = $_POST['from'];
                $toDate  = $_POST['to'];
            }

            $date = new DateTime();
            $currentMonth = $date->format('Y-m-01');

            // $query1 = "SELECT COUNT(id) as new FROM visitors WHERE created_at >= '".$currentMonth."%'";
            // $new = $db->selectNative($query1);

            // $query2 = "SELECT COUNT(id) as old FROM visitors WHERE created_at < '".$currentMonth."%' AND updated_at >= '".$currentMonth."%'";
            // $old = $db->selectNative($query2);

            // NEW
            $new = $db->selectNative("SELECT COUNT(id) as new FROM visitors WHERE created_at >= '".$fromDate."%' AND created_at <= '".$toDate."%'");
            // OLD
            $old = $db->selectNative("SELECT COUNT(id) as old FROM visitors WHERE created_at < '".$fromDate."%' AND updated_at >= '".$fromDate."%' AND updated_at <= '".$toDate."%'");
            
            $data['labels'] = ['Old Visitors', 'New Visitors'];
            $dataset = array(
                "data"=> array($old[0]['old'], $new[0]['new']),
                "backgroundColor" => array('#d2d6de', '#00a65a')
            );
            
            $data['datasets'] = array($dataset);
            

            $result['result'] = $data;
            break;

        case 'get_notif':

            if ($_SESSION['role'] == $user_role::SecurityHead || $_SESSION['dept'] == $dept::Guard) {
                $where = "role_dept = '".$user_role::SecurityHead."'";
            } else {
                $where = "role_dept = '".$user_role::Department.":".$_SESSION['dept']."'";
            }

            $dbData = $db->selectNative("SELECT * FROM notif WHERE is_read = '0' AND $where ORDER BY id DESC");
            $notifCount = count($dbData);
            $_SESSION['notif_count'] = $notifCount;

            $html = '<span class="dropdown-item dropdown-header notif-count">'.$notifCount.' Notification(s)</span>';
            if ($notifCount == 0) {
                $html .='
                    <div class="dropdown-divider"></div>
                        <a href="javascript:void(0)" class="dropdown-item text-center">
                            No new Notification
                        </a>
                    <div class="dropdown-divider"></div>
                ';
                $notifCount = "";
            } else {
                foreach ($dbData as $row) {
                    $vsData = $db->selectNative("SELECT * FROM visitors WHERE id = '".$row['visitor_id']."'");
                    
                    if ($vsData) {
                        
                        $visitor = $vsData[0];
                        $created_at = strtotime($row['created_at']);

                        
                        if ($_SESSION['role'] == $user_role::SecurityHead || $_SESSION['dept'] == $dept::Guard) {
                            $acData = $db->selectNative("SELECT * FROM visitors_activity WHERE id = '".$row['activity_id']."'");
                            $activity = $acData[0];
                            $content = $notif_type->get_name($row['type']).' to '.$dept->get_name($activity['department']);
                        } else {
                            $deptVal = explode(':', $row['role_dept'])[1];
                            $content = $notif_type->get_name($row['type']).' '.$dept->get_name($deptVal);
                        }
                        $html .= '
                            <div class="dropdown-divider"></div>
                            <a href="details.php?id='.$row['id'].'" class="dropdown-item">
                                <div class="row">
                                    <div class="col-3">
                                        <img class="notif-img notif-img-50" src="assets/img/visitor/'.$visitor['image'].'" alt="" onerror="this.src=\'assets/img/empty-img.jpg\'">
                                    </div>
                                    <div class="col-9 d-flex align-items-center">
                                        <p class="card-text lh-1">
                                            <strong>'.$visitor['name'].'</strong>  '.$content.'<br>
                                            <small class="text-secondary">'.date('M d, Y H:i:s', $created_at).'</small>
                                        </p>
                                    </div>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                        ';
                    }

                    
                }
            }
            

            $result['notif'] = $html;
            $result['count'] = $notifCount;

            break;

        case 'get_purpose':
            $cashier = $db->selectAll('purpose');

            foreach ($cashier as $row) {
                switch ($row['department']) {
                    case $dept::Cashier:
                        $result['cashier'][] = '
                            <div class="form-group purpose-select">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" name="p-cash[]" id="cash' . $row['id'] . '" value="' . $row['name'] . '">
                                    <label for="cash' . $row['id'] . '" class="custom-control-label font-weight-normal" >' . $row['name'] . '</label>
                                </div>
                            </div>
                        ';
                        break;
                    case $dept::Registrar:
                        $result['registrar'][] = '
                            <div class="form-group purpose-select">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox"  name="p-reg[]" id="reg' . $row['id'] . '" value="' . $row['name'] . '">
                                    <label for="reg' . $row['id'] . '" class="custom-control-label font-weight-normal" >' . $row['name'] . '</label>
                                </div>
                            </div>
                        ';
                        break;
                    case $dept::Clinic:
                        $result['clinic'][] = '
                            <div class="form-group purpose-select">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox"  name="p-clinic[]" id="clinic' . $row['id'] . '" value="' . $row['name'] . '">
                                    <label for="clinic' . $row['id'] . '" class="custom-control-label font-weight-normal" >' . $row['name'] . '</label>
                                </div>
                            </div>
                        ';
                        break;
                    case $dept::DisciplineOffice:
                        $result['discipline'][] = '
                            <div class="form-group purpose-select">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox"  name="p-discipline[]" id="discipline' . $row['id'] . '" value="' . $row['name'] . '">
                                    <label for="discipline' . $row['id'] . '" class="custom-control-label font-weight-normal" >' . $row['name'] . '</label>
                                </div>
                            </div>
                        ';
                        break;
                    default:
                        # code...
                        break;
                }
                
            }

            break;

        case 'delete_purpose':

            $del = $db->delete('purpose', array('id'=>$_POST['id']));
            if ($del) {
                $result['success'] = true;
            } else {
                $result['success'] = false;
            }
            
            break;
        default:
            # code...
            break;
    }
    echo json_encode($result);
}
$db->closeConnection();