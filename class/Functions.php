<?php
require_once 'DatabaseManager.php';
require_once './enum/Department.php';
class Functions
{
    private $db;
    private $dept;

    public function __construct()
    {
        $this->db = new DatabaseManager();
        $this->dept = new Department();
    }

    public function dashboard()
    {

        if ($_SESSION['dept'] > 0 && $_SESSION['dept'] < 5) {
            $listClass = 'col-lg-12';
            $thisDept = false;
        } else {
            $listClass = 'col-lg-8';
            $thisDept = true;
        }
        echo '
            <div class="row">
                <div class="' . $listClass . '">
                    <div class="card">
                        <div class="card-header border-0">
                            <h3 class="card-title">List of Appointment</h3>
                        </div>
                        <div class="card-body visitor-list px-0">
                            <table id="visitorTable" class="display" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Department</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
            ';
        if ($thisDept) {
            echo '
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title">Available Department</h3>
                </div>
                <div class="card-body">
                    ';
            $data = $this->db->selectNative("SELECT department FROM user_account WHERE dept_active = '1' AND department BETWEEN '1' AND '4' GROUP BY department");

            if ($data) {
                foreach ($data as $row) {

                    switch ($row['department']) {
                        case $this->dept::Cashier:
                            $icon = '<span class="info-box-icon"><i class="fas fa-coins"></i></span>';
                            $bg = 'bg-success';
                            break;

                        case $this->dept::Registrar:
                            $icon = '<span class="info-box-icon"><i class="fas fa-file-alt text-white"></i></span>';
                            $bg = 'bg-warning';
                            break;

                        case $this->dept::Clinic:
                            $icon = '<span class="info-box-icon"><i class="fas fa-clinic-medical"></i></span>';
                            $bg = 'bg-primary';
                            break;

                        case $this->dept::DisciplineOffice:
                            $icon = '<span class="info-box-icon"><i class="fas fa-hands-helping"></i></span>';
                            $bg = 'bg-danger';
                            break;

                        default:
                            $icon = '';
                            break;
                    }

                    echo '
                                    <div class="info-box mb-1 ' . $bg . '" style="min-height:46px">
                                        ' . $icon . '

                                        <div class="info-box-content">
                                            <span class="info-box-text">' . $this->dept->get_name($row['department']) . '</span>
                                        </div>
                                        <!-- /.info-box-content -->
                                    </div>
                                ';
                }
            } else {
                echo 'No Available Offices';
            }
            echo '
                </div>
                <!-- /.card-body -->
            </div>
        </div>
        ';
        }

        // Row end div
        echo '
            </div>
        ';
    }

    public function departmentInput()
    {
        $data = $this->db->selectNative("SELECT department FROM user_account WHERE dept_active = '1' AND department BETWEEN '1' AND '4' GROUP BY department");
        if ($data) {
            foreach ($data as $row) {
                switch ($row['department']) {
                    case $this->dept::Cashier:
                        echo '
                        <!-- Cashier -->
                        <div class="form-group purpose-select">
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" name="p[]" data-target="cashier-option" data-options="p-cash" id="deptCashier" value="<?php echo $dept::Cashier ?>">
                                <label for="deptCashier" class="custom-control-label">Cashier</label>
                            </div>
                        </div>
                        <div class="container cashier-option" style="display:none">

                            <div class="cashier-option-div"><!-- Data goes here --></div>

                            <div class="input-group mb-3">
                                <input type="text" name="p-cash-other" placeholder="Other" data-btn="p-cash-add" class="form-control other-input p-cash-other">
                                <div class="input-group-append">
                                    <button type="button" class="btn input-group-text p-cash-add" disabled><i class="fas fa-check" style="color: #00bd39;"></i></button>
                                </div>
                            </div>

                            <small class="err d-block text-danger err_cashieroption"></small>
                        </div>
                        ';
                        break;

                    case $this->dept::Registrar:
                        echo '
                        <!-- Registrar -->
                        <div class="form-group purpose-select">
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" name="p[]" data-target="reg-option" data-options="p-reg" id="deptRegistrar" value="<?php echo $dept::Registrar ?>">
                                <label for="deptRegistrar" class="custom-control-label">Registrar</label>
                            </div>
                        </div>
                        <div class="container reg-option" style="display:none">
                            <!-- Data goes here -->
                            <div class="reg-option-div"></div>
                            <div class="input-group mb-3">
                                <input type="text" name="p-reg-other" placeholder="Other" data-btn="p-reg-add" class="form-control other-input p-reg-other">
                                <div class="input-group-append">
                                    <button type="button" class="btn input-group-text p-reg-add" disabled><i class="fas fa-check" style="color: #00bd39;"></i></button>
                                </div>
                            </div>
                            <small class="err d-block text-danger err_regoption"></small>
                        </div>
                        ';
                        break;

                    case $this->dept::Clinic:
                        echo '
                        <!-- Clinic -->
                        <div class="form-group purpose-select">
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" name="p[]" data-target="clinic-option" data-options="p-clinic" id="deptClinic" value="<?php echo $dept::Clinic ?>">
                                <label for="deptClinic" class="custom-control-label">Clinic</label>
                            </div>
                        </div>
                        <div class="container clinic-option" style="display:none">
                            <!-- Data goes here -->
                            <div class="clinic-option-div"></div>
                            <div class="input-group mb-3">
                                <input type="text" name="p-clinic-other" placeholder="Other" data-btn="p-clinic-add" class="form-control other-input p-clinic-other">
                                <div class="input-group-append">
                                    <button type="button" class="btn input-group-text p-clinic-add" disabled><i class="fas fa-check" style="color: #00bd39;"></i></button>
                                </div>
                            </div>
                            <small class="err d-block text-danger err_clinicoption"></small>
                        </div>
                        ';
                        break;

                    case $this->dept::DisciplineOffice:
                        echo '
                        <!-- DisciplineOffice -->
                        <div class="form-group purpose-select">
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" name="p[]" data-target="discipline-option" data-options="p-discipline" id="deptDisciplineOffice" value="<?php echo $dept::DisciplineOffice ?>">
                                <label for="deptDisciplineOffice" class="custom-control-label">Discipline Office</label>
                            </div>
                        </div>
                        <div class="container discipline-option" style="display:none">
                            <!-- Data goes here -->
                            <div class="discipline-option-div"></div>
                            <div class="input-group mb-3">
                                <input type="text" name="p-discipline-other" placeholder="Other" data-btn="p-discipline-add" class="form-control other-input p-discipline-other">
                                <div class="input-group-append">
                                    <button type="button" class="btn input-group-text p-discipline-add" disabled><i class="fas fa-check" style="color: #00bd39;"></i></button>
                                </div>
                            </div>
                            <small class="err d-block text-danger err_disciplineoption"></small>
                        </div>
                        ';
                        break;

                    default:
                        break;
                }
            }
        } else {
            echo '<span class="text-danger">No Available Department</span>';
        }
    }
}
