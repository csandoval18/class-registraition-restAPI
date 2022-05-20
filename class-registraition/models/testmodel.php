<?php

use LDAP\Result;

class TestModel extends Model {
  public function getQuestions() {
    // define SQL statement
    $sql = "SELECT id, question FROM `question_bank`";
    // set SQL statement
    $this->setSql($sql);
    // return results
    $questionList = $this->getAll();
    return  $questionList;
  }
  
  public function gradeTest() {
    $correct_answers = [];
    $score = 0;
    $input_data = file_get_contents('php://input');
    $data = json_decode($input_data, true);  // convert data to an associative array
    $user_answers = $data['answers'];
    // define SQL statement
    $sql = "SELECT answer, category from `question_bank`";
    // set SQL statement
    $this->setSql($sql);
    // Bind values to named parameters 
    $result = $this->getAll();
    $testLength = sizeof($result);
    for ($i=0; $i<$testLength; ++$i) {
      array_push($correct_answers, $result[$i]);
    }
    //Grade answers
    for ($i=0; $i<$testLength; ++$i) {
      if ($user_answers[$i] == $correct_answers[$i]->answer) {
        switch($correct_answers[$i]->category) {
          case 1: 
            $score += 10;
            break;
          case 2: 
            $score += 5;
            break;
          case 3:
            $score += 5;
            break;
        }  
      }
    }
    return $score;
  }
}
?>

