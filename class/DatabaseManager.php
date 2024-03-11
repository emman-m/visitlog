<?php
date_default_timezone_set('Asia/Manila');
Class DatabaseManager {
    private $connection;

    public function __construct() {
        // Create a connection to the database
        $this->connection = new mysqli("localhost", "root", "", "db_visitlog");

        // Check for connection errors
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }

    public function selectAll($table) {
        // Select all records from a table
        $query = "SELECT * FROM $table";
        $result = $this->connection->query($query);

        if ($result->num_rows > 0) {
            // Fetch data from the result and return as an array
            $data = $result->fetch_all(MYSQLI_ASSOC);
            return $data;
        } else {
            return [];
        }
    }

    public function selectWhere($table, $conditions, $order = 'id ASC') {
        // Select records from a table based on multiple conditions
        $where = "";
        if (is_array($conditions) && count($conditions) > 0) {
            $where = " WHERE ";
            $conditionsArray = [];
            foreach ($conditions as $column => $value) {
                if (is_array($value)) {
                    foreach ($value as $val) {
                        // Check if the value starts with '!='
                        if (strpos($val, "!=") === 0) {
                            // If yes, use the not equal condition
                            $val = substr($val, 2); // Remove '!=' from the val
                            $conditionsArray[] = "$column != '$val'";
                        } else {
                            // Otherwise, use the equal condition
                            $conditionsArray[] = ($val == null) ? "$column IS NULL" : "$column = '$val'" ;
                        }
                    }
                    
                } else {
                    // Check if the value starts with '!='
                    if (strpos($value, "!=") === 0) {
                        // If yes, use the not equal condition
                        $value = substr($value, 2); // Remove '!=' from the value
                        $conditionsArray[] = "$column != '$value'";
                    } else {
                        // Otherwise, use the equal condition
                        $conditionsArray[] = ($value == null) ? "$column IS NULL" : "$column = '$value'" ;
                    }
                }
            }
            $where .= implode(" AND ", $conditionsArray);
        }
    
        $query = "SELECT * FROM $table" . $where . " ORDER BY ". $order;
        $result = $this->connection->query($query);
        
        if ($result->num_rows > 0) {
            $data = $result->fetch_all(MYSQLI_ASSOC);
            return $data;
        } else {
            return [];
        }
    }

    public function selectWhereLike($table, $conditions, $likes, $order = 'id ASC') {
        // Select records from a table based on multiple conditions
        $where = "";
        if (is_array($conditions) && count($conditions) > 0) {
            $where = " WHERE ";
            $conditionsArray = [];
            foreach ($conditions as $column => $value) {
                if (is_array($value)) {
                    foreach ($value as $val) {
                        // Check if the value starts with '!='
                        if (strpos($val, "!=") === 0) {
                            // If yes, use the not equal condition
                            $val = substr($val, 2); // Remove '!=' from the val
                            $conditionsArray[] = "$column != '$val'";
                        } else {
                            // Otherwise, use the equal condition
                            $conditionsArray[] = ($val == null) ? "$column IS NULL" : "$column = '$val'" ;
                        }
                    }
                    
                } else {
                    // Check if the value starts with '!='
                    if (strpos($value, "!=") === 0) {
                        // If yes, use the not equal condition
                        $value = substr($value, 2); // Remove '!=' from the value
                        $conditionsArray[] = "$column != '$value'";
                    } else {
                        // Otherwise, use the equal condition
                        $conditionsArray[] = ($value == null) ? "$column IS NULL" : "$column = '$value'" ;
                    }
                }
            }
            $where .= implode(" AND ", $conditionsArray);
        }

        $like = "";
        if (is_array($likes) && count($likes) > 0) {
            if ($where !== "") {
                $like = "AND ";
            } else {
                $like = "";
            }
            $likesArray = [];
            foreach ($likes as $column => $value) {
                if (is_array($value)) {
                    foreach ($value as $val) {
                        // Check if the value starts with '!='
                        if (strpos($val, "!=") === 0) {
                            // If yes, use the not equal condition
                            $val = substr($val, 2); // Remove '!=' from the val
                            $likesArray[] = "$column != '%$val%'";
                        } else {
                            // Otherwise, use the equal condition
                            $likesArray[] = ($val == null) ? "$column IS NULL" : "$column LIKE '%$val%'" ;
                        }
                    }
                    
                } else {
                    // Check if the value starts with '!='
                    if (strpos($value, "!=") === 0) {
                        // If yes, use the not equal condition
                        $value = substr($value, 2); // Remove '!=' from the value
                        $likesArray[] = "$column != '%$value%'";
                    } else {
                        // Otherwise, use the equal condition
                        $likesArray[] = ($value == null) ? "$column IS NULL" : "$column LIKE '%$value%'" ;
                    }
                }
            }
            $like .= implode(" OR ", $likesArray);
        }
    
        $query = "SELECT * FROM $table" . $where . " " .$like. " ORDER BY ". $order;
        $result = $this->connection->query($query);
        
        if ($result->num_rows > 0) {
            $data = $result->fetch_all(MYSQLI_ASSOC);
            return $data;
        } else {
            return [];
        }
    }

    public function selectVisitorActivity($id, $dept) {
        $query = "SELECT * FROM visitors_activity WHERE visitor_id = $id AND department = $dept AND time_in LIKE '".Date('Y-m-d')."%'";
        $result = $this->connection->query($query);
        
        if ($result->num_rows > 0) {
            $query2 = "SELECT * FROM visitors_activity WHERE visitor_id = $id AND department = $dept AND time_in LIKE '".Date('Y-m-d')."%' AND time_out IS NULL";
            $result2 = $this->connection->query($query2);
           
            if ($result2->num_rows > 0) {
                $data = $result->fetch_all(MYSQLI_ASSOC);
                return $data[0]['id'];
            } else {
                return 'insert';
            }
        } else {
            return 'insert';
        }
    }

    public function insert($table, $data) {
        // Insert data into a table
        $columns = implode(", ", array_keys($data));
        $values = "'" . implode("', '", array_values($data)) . "'";
        $query = "INSERT INTO $table ($columns) VALUES ($values)";

        if ($this->connection->query($query) === TRUE) {
            return $this->connection->insert_id;
        } else {
            return "Error: " . $query . "<br>" . $this->connection->error;
        }
    }

    public function delete($table, $conditions) {
        if (is_array($conditions) && count($conditions) > 0) {
            $where = " WHERE ";
            $conditionsArray = [];
            foreach ($conditions as $column => $value) {
                // Check if the value starts with '!='
                if (strpos($value, "!=") === 0) {
                    // If yes, use the not equal condition
                    $value = substr($value, 2); // Remove '!=' from the value
                    $conditionsArray[] = "$column != '$value'";
                } else {
                    // Otherwise, use the equal condition
                    $conditionsArray[] = ($value == null) ? "$column IS NULL" : "$column = '$value'" ;
                }
            }
            $where .= implode(" AND ", $conditionsArray);
        }
        // Delete records from a table based on a condition
        $query = "DELETE FROM $table $where";
        
        if ($this->connection->query($query) === TRUE) {
            return "Record(s) deleted successfully";
        } else {
            return "Error: " . $query . "<br>" . $this->connection->error;
        }
    }

    public function update($table, $data, $conditions) {
        // Update records in a table based on a condition
        $set = '';
        foreach ($data as $key => $value) {
            $set .= "$key = '$value', ";
        }
        $set = rtrim($set, ', ');

        if (is_array($conditions) && count($conditions) > 0) {
            $where = " WHERE ";
            $conditionsArray = [];
            foreach ($conditions as $column => $value) {
                // Check if the value starts with '!='
                if (strpos($value, "!=") === 0) {
                    // If yes, use the not equal condition
                    $value = substr($value, 2); // Remove '!=' from the value
                    $conditionsArray[] = "$column != '$value'";
                } else {
                    // Otherwise, use the equal condition
                    $conditionsArray[] = ($value == null) ? "$column IS NULL" : "$column = '$value'" ;
                }
            }
            $where .= implode(" AND ", $conditionsArray);
        }

        $query = "UPDATE $table SET $set $where";

        if ($this->connection->query($query) === TRUE) {
            return true;
        } else {
            return "Error: " . $query . "<br>" . $this->connection->error;
        }
    }

    public function dt_select($table, $orderBy, $sort, $search, $limit, $offset, $columns, $condition=array(), $table_data=true) {
        
        if ($orderBy != null) {
            $orderBy = "$orderBy $sort";
        } else {
            $orderBy = "id ASC";
        }
        
        $limit = "LIMIT $offset, $limit";

        $where = "";
        if(!empty($search)){
            $where = "WHERE";
        	$x = 0;
        	foreach($columns as $col){
        		if($x == 0){
                    $where .= " $col LIKE '%$search%'";
        		} else {
        			if ($col !== 'id') {
                        $where .= " OR $col LIKE '%$search%'";
        			}
        		}
        		$x++;
        	}
        }
        $where2 = "";
        if (is_array($condition) && count($condition) > 0) {
            $where2 = " WHERE ";
            $conditionsArray = [];
            foreach ($condition as $column => $value) {
                if (is_array($value)) {
                    foreach ($value as $val) {
                        // Check if the value starts with '!='
                        if (strpos($val, "!=") === 0) {
                            // If yes, use the not equal condition
                            $val = substr($val, 2); // Remove '!=' from the val
                            $conditionsArray[] = "$column != '$val'";
                        } else {
                            // Otherwise, use the equal condition
                            $conditionsArray[] = ($val == null) ? "$column IS NULL" : "$column = '$val'" ;
                        }
                    }
                    
                } else {
                    // Check if the value starts with '!='
                    if (strpos($value, "!=") === 0) {
                        // If yes, use the not equal condition
                        $value = substr($value, 2); // Remove '!=' from the value
                        $conditionsArray[] = "$column != '$value'";
                    } else {
                        // Otherwise, use the equal condition
                        $conditionsArray[] = ($value == null) ? "$column IS NULL" : "$column = '$value'" ;
                    }
                }
            }
            $where2 .= implode(" AND ", $conditionsArray);
        }


        $query = "SELECT * FROM $table $where $where2 ORDER BY $orderBy $limit";
        $result = $this->connection->query($query);

        if ($result->num_rows > 0) {
            // Fetch data from the result and return as an array
            if($table_data){
                $data = $result->fetch_all(MYSQLI_ASSOC);
            }else{
                $data = $result->num_rows;
            }
            return $data;
        } else {
            return [];
        }
    }

    public function dt_select_join_va($dept, $orderBy, $sort, $search, $limit, $offset, $columns, $condition = array(), $table_data = true)
    {

        if ($orderBy != null) {
            $orderBy = "$orderBy $sort";
        } else {
            $orderBy = "id ASC";
        }

        $limit = "LIMIT $offset, $limit";

        $where = "";
        $whereAnd = "";
        if (!empty($search)) {
            $x = 0;
            foreach ($columns as $col) {
                if ($x == 0) {
                    $where .= " $col LIKE '%$search%'";
                } else {
                    if ($col !== 'id') {
                        $where .= " OR $col LIKE '%$search%'";
                    }
                }
                $x++;
            }
            $whereAnd = "AND ($where)";
        }

        $where2 = "";
        if (is_array($condition) && count($condition) > 0) {
            $where2 = "AND";
            $conditionsArray = [];
            foreach ($condition as $column => $value) {
                if (is_array($value)) {
                    foreach ($value as $val) {
                        // Check if the value starts with '!='
                        if (strpos($val, "!=") === 0) {
                            // If yes, use the not equal condition
                            $val = substr($val, 2); // Remove '!=' from the val
                            $conditionsArray[] = "$column != '$val'";
                        } else {
                            // Otherwise, use the equal condition
                            $conditionsArray[] = ($val == null) ? "$column IS NULL" : "$column = '$val'";
                        }
                    }
                } else {
                    // Check if the value starts with '!='
                    if (strpos($value, "!=") === 0) {
                        // If yes, use the not equal condition
                        $value = substr($value, 2); // Remove '!=' from the value
                        $conditionsArray[] = "$column != '$value'";
                    } else {
                        // Otherwise, use the equal condition
                        $conditionsArray[] = ($value == null) ? "$column IS NULL" : "$column = '$value'";
                    }
                }
            }
            $where2 .= implode(" AND ", $conditionsArray);
        }
        $deptQR = "";
        if ((intVal($dept) !== 0) && (intVal($dept) !== 5)) {
            $deptQR = "AND va.department = ". $dept;
        }

        $query = "SELECT va.time_in, va.time_out, va.id, va.visitor_id, va.purpose, v.name, v.address, va.department FROM `visitors_activity` va JOIN visitors v WHERE va.visitor_id = v.id $deptQR $whereAnd $where2 ORDER BY $orderBy $limit";
        
        $result = $this->connection->query($query);

        if ($result->num_rows > 0) {
            // Fetch data from the result and return as an array
            if ($table_data) {
                $data = $result->fetch_all(MYSQLI_ASSOC);
            } else {
                $data = $result->num_rows;
            }
            return $data;
        } else {
            return [];
        }
    }

    public function selectCount($table, $condition = array()) {
        $where = "";
        if (is_array($condition) && count($condition) > 0) {
            $where = "WHERE ";
            $conditionsArray = [];
            foreach ($condition as $column => $value) {
                if (is_array($value)) {
                    foreach ($value as $val) {
                        // Check if the value starts with '!='
                        if (strpos($val, "!=") === 0) {
                            // If yes, use the not equal condition
                            $val = substr($val, 2); // Remove '!=' from the val
                            $conditionsArray[] = "$column != '$val'";
                        } else {
                            // Otherwise, use the equal condition
                            $conditionsArray[] = ($val == null) ? "$column IS NULL" : "$column = '$val'";
                        }
                    }
                } else {
                    // Check if the value starts with '!='
                    if (strpos($value, "!=") === 0) {
                        // If yes, use the not equal condition
                        $value = substr($value, 2); // Remove '!=' from the value
                        $conditionsArray[] = "$column != '$value'";
                    } else {
                        // Otherwise, use the equal condition
                        $conditionsArray[] = ($value == null) ? "$column IS NULL" : "$column = '$value'";
                    }
                }
            }
            $where .= implode(" AND ", $conditionsArray);
        }


        $query = "SELECT COUNT(*) as total FROM $table $where";
        $result = $this->connection->query($query);

        if ($result->num_rows > 0) {
            // Fetch data from the result and return as an array
            $data = $result->fetch_all(MYSQLI_ASSOC);
            return $data[0]['total'];
        } else {
            return [];
        }
    }

    public function selectNative($query) {
        $result = $this->connection->query($query);
        
        if ($result->num_rows > 0) {
            $data = $result->fetch_all(MYSQLI_ASSOC);
            return $data;
        } else {
            return [];
        }
    }

    public function closeConnection() {
        // Close the database connection
        $this->connection->close();
    }
}

// // Usage example:
// $db = new DatabaseManager("localhost", "username", "password", "database_name");

// // Example of using the selectAll function
// $data = $db->selectAll("your_table_name");
// print_r($data);

// // Example of using the selectWhere function
// $conditions = array(
//     "column1" => "value1",
//     "column2" => "value2",
//     "column3" => "value3"
// );
// $dataWhere = $db->selectWhere("your_table_name", $conditions);
// print_r($dataWhere);

// // Example of using the insert function
// $insertData = array("column1" => "value1", "column2" => "value2");
// $result = $db->insert("your_table_name", $insertData);
// echo $result;

// // Example of using the delete function
// $deleteCondition = "id = 5";
// $result = $db->delete("your_table_name", $deleteCondition);
// echo $result;

// // Example of using the update function
// $updateData = array("column1" => "new_value1", "column2" => "new_value2");
// $updateCondition = "id = 3";
// $result = $db->update("your_table_name", $updateData, $updateCondition);
// echo $result;

// // Don't forget to close the connection when done
// $db->closeConnection();
?>
