<?php
class Database{
    protected ?PDO $conn;

    function __construct()
    {
        $this->conn = $this->connect();
    }

    private function connect()
    {
        $string = "mysql:host=".DB_HOST.";dbname=".DB_NAME;
        try {
            return new PDO($string, DB_USER, DB_PASS);
        }catch (PDOException $e){
            echo $e->getMessage();
            die();
        }
    }


    public function filterInputData($data){
        foreach ($data as $key => $value){
            $data[$key] = trim($value);
            $data[$key] = stripcslashes($value);
            $data[$key] = htmlspecialchars($value);
            $data[$key] = strip_tags($value);
        }
        return $data;
    }

    protected function insertData($tableName, $columns, $values): bool
    {
        // Prepare the query dynamically
        $query = "INSERT INTO $tableName (" . implode(', ', $columns) . ") VALUES (" . implode(', ', array_fill(0, count($values), '?')) . ")";
        $statement = $this->conn->prepare($query);

        // Bind the values to the prepared statement
        foreach ($values as $index => $value) {
            $statement->bindValue($index + 1, $value);
        }

        // Execute the query
        $statement->execute();

        // Optionally, you can check if the query was successful
        return true;
    }

    protected function insertMultipleData($tableName, $columns, $allValues): bool
    {
        // Check if $columns is empty
        if (empty($columns)) {
            echo "Error: Columns array is empty.";
            return false;
        }

        // Prepare the query dynamically
        $query = "INSERT INTO $tableName (" . implode(', ', $columns) . ") VALUES ";

        $valuePlaceholder = '(' . implode(', ', array_fill(0, count($columns), '?')) . ')';
        $query .= implode(', ', array_fill(0, count($allValues), $valuePlaceholder));

        //echo "Generated SQL Query: $query"; // Add this line for debugging

        $statement = $this->conn->prepare($query);

        // Bind the values to the prepared statement
        $index = 1;
        foreach ($allValues as $setOfValues) {
            foreach ($setOfValues as $value) {
                $statement->bindValue($index++, $value);
            }
        }

        // Execute the query
        $result = $statement->execute();

        // Optionally, you can check if the query was successful
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    protected function returnLastId($tableName, $columns, $values): bool|int
    {

        // Prepare the query dynamically
        $query = "INSERT INTO $tableName (" . implode(', ', $columns) . ") VALUES (" . implode(', ', array_fill(0, count($values), '?')) . ")";
        $statement = $this->conn->prepare($query);

        // Bind the values to the prepared statement
        foreach ($values as $index => $value) {
            $statement->bindValue($index + 1, $value);
        }

        // Execute the query
        $success = $statement->execute();

        // Check if the query was successful
        if ($success) {
            // Return the last inserted ID
            return $this->conn->lastInsertId();
        } else {
            // Return false on failure
            return false;
        }
    }

    protected function selectData($tableName, $columns = "*", $conditions = array(), $conditionType = "AND", $limit = null, $orderByColumn = null, $orderByDirection = "ASC"): false|array
    {
        $query = "SELECT $columns FROM $tableName";

        if (!empty($conditions)) {
            $query .= " WHERE ";
            $conditionsArray = array();
            foreach ($conditions as $key => $value) {
                $conditionsArray[] = "$key = :$key";
            }
            $query .= implode(" $conditionType ", $conditionsArray);
        }

        if ($orderByColumn !== null) {
            $query .= " ORDER BY $orderByColumn $orderByDirection";
        }

        if ($limit !== null) {
            $query .= " LIMIT $limit";
        }

        try {
            $statement = $this->conn->prepare($query);
            foreach ($conditions as $key => $value) {
                $statement->bindValue(":$key", $value);
            }
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    protected function updateData($tableName, $updateData, $conditions = array()): bool
    {
        $query = "UPDATE $tableName SET ";
        $updateArray = array();

        foreach ($updateData as $key => $value) {
            $updateArray[] = "$key = :$key";
        }
        $query .= implode(", ", $updateArray);

        if (!empty($conditions)) {
            $query .= " WHERE ";
            $conditionsArray = array();
            foreach ($conditions as $key => $value) {
                $conditionsArray[] = "$key = :cond_$key";
            }
            $query .= implode(" AND ", $conditionsArray);
        }

        try {
            $statement = $this->conn->prepare($query);

            foreach ($updateData as $key => $value) {
                $statement->bindValue(":$key", $value);
            }

            foreach ($conditions as $key => $value) {
                $statement->bindValue(":cond_$key", $value);
            }

            return $statement->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    protected function deleteData($tableName, $conditions = array()): bool
    {
        $query = "DELETE FROM $tableName";

        if(!empty($conditions)){
            $query .= " WHERE ";
            $conditionsArray = array();
            foreach ($conditions as $key => $value) {
                $conditionsArray[] = "$key = :$key";
            }
            $query .= implode(" AND ", $conditionsArray);
        }

        try {
            $statement = $this->conn->prepare($query);

            foreach ($conditions as $key => $value) {
                $statement->bindValue(":$key", $value);
            }

            return $statement->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    protected function searchData($tableName, $searchColumns, $searchTerm): false|array
    {
        $conditions = array();
        foreach ($searchColumns as $column) {
            $conditions[$column] = "%$searchTerm%";
        }

        $query = "SELECT * FROM $tableName WHERE ";
        $conditionsArray = array();
        foreach ($conditions as $key => $value) {
            $conditionsArray[] = "$key LIKE :$key";
        }
        $query .= implode(" OR ", $conditionsArray);

        try {
            $statement = $this->conn->prepare($query);
            foreach ($conditions as $key => $value) {
                $statement->bindValue(":$key", $value);
            }
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    protected function countRows($tableName, $conditions = array(), $conditionType = "AND") {
        $query = "SELECT COUNT(*) AS count FROM $tableName";

        if (!empty($conditions)) {
            $query .= " WHERE ";
            $conditionsArray = array();
            foreach ($conditions as $key => $value) {
                $conditionsArray[] = "$key = :$key";
            }
            $query .= implode(" $conditionType ", $conditionsArray);
        }

        try {
            $statement = $this->conn->prepare($query);
            foreach ($conditions as $key => $value) {
                $statement->bindValue(":$key", $value);
            }
            $statement->execute();
            $countResult = $statement->fetch(PDO::FETCH_ASSOC);

            return $countResult['count'];
        } catch (PDOException $e) {
            return false;
        }
    }

    protected function sumColumn($tableName, $columnName, $conditions = array(), $conditionType = "AND") {
        $query = "SELECT SUM($columnName) AS total FROM $tableName";

        if (!empty($conditions)) {
            $query .= " WHERE ";
            $conditionsArray = array();
            foreach ($conditions as $key => $value) {
                $conditionsArray[] = "$key = :$key";
            }
            $query .= implode(" $conditionType ", $conditionsArray);
        }

        try {
            $statement = $this->conn->prepare($query);
            foreach ($conditions as $key => $value) {
                $statement->bindValue(":$key", $value);
            }
            $statement->execute();
            $sumResult = $statement->fetch(PDO::FETCH_ASSOC);

            return $sumResult['total'];
        } catch (PDOException $e) {
            return false;
        }
    }

}
