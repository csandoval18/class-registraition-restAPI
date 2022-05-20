<?php

class UserModel extends Model {
  public function checkLogin() {
    $input_data = file_get_contents('php://input');
    $data = json_decode($input_data, true);  // convert data to an associative array
    // define SQL statement
    $sql = "SELECT  username from `user_list` where username=:username and password = :password";
    // set SQL statement
    $this->setSql($sql);
    // Bind values to named parameters 
    $parameters = [":username" => $data["username"], ":password" => md5($data["password"])];
    // Execute the statement by invoking the getOne() method of the Model class
    $result = $this->getOne($parameters);
    if (is_object($result) && isset($result->username)) {
      return "valid";
    }
    return "invalid";
  }
  
  public function getUserInfo($parameter_values) {
    // define SQL statement
    $username = $parameter_values[0];
    // define SQL statement
    $sql = "SELECT firstName, lastName, level FROM `user_list` WHERE username=:username";
    $this->setSql($sql);
    // define parameters
    $parameters = [":username" => $username];
    // return results
    $result = $this->getAll($parameters);
    return  $result;
  }
  
  public function registerUser() {
    $input_data = file_get_contents('php://input');
    $data = json_decode($input_data, true);
    $sql = "SELECT username FROM `user_list` WHERE username=:username";
		$this->setSql($sql);
		$parameters = [':username' => $data["username"]];
		$result = $this->getAll($parameters);
    $count = count($result);
		if ($count == 0) {
      $sql = "INSERT INTO `user_list` (username, password, firstName, lastName, level) VALUES (:username, :password, 
      :firstName, :lastName, :level)";
			$this->setSql($sql);
      
			$parameters = [":username" => $data["username"], ":password" => md5($data["password"]), ":firstName" => $data["firstName"], 
      ":lastName" => $data["lastName"], ":level" => $data["level"]];
      
			$this->getOne($parameters);
      return "success";
		} else if ($count == 1) {
			return "fail";
		} 
  }
}