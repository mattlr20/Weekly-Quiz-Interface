<?php
if (!isset($action)) {$action = "";}
if (!isset($view)) {$view = "week";}
if (!isset($Edit_ID)) {$Edit_ID = 0;}
if (!isset($del_ID)) {$del_ID = 0;}
if (!isset($rAnswer)) {$rAnswer = 0;}
if (!isset($ddlQType)) {$ddlQType = "";}
if (!isset($Question)) {$Question = "";}
if (!isset($weeknumber)) {$weeknumber =  date("m/d/Y");}
if (!isset($qID)) {$qID = 0;}
$x = 0; 

$checked = "";
//get week number
$ddate = date("m/d/Y");
$duedt = explode("/", $ddate);
$date  = mktime(0, 0, 0, $duedt[0], $duedt[1], $duedt[2] );
$week  = (int)date('W', $date);

$ddate = $weeknumber;
$duedt = explode("/", $ddate);
$date  = mktime(0, 0, 0, $duedt[0], $duedt[1], $duedt[2] );
$weekofDate  = (int)date('W', $date);

function getAnswers($dbh, $aID, $QuestionID) {
    $sql="SELECT * FROM Test_Answers WHERE QuestionID = :qID ORDER BY AnswerID";
    $sth = $dbh->prepare($sql); 
    $params = array(':qID' => $QuestionID);
    $sth->execute($params);
    $Answers = $sth->fetchAll(PDO::FETCH_ASSOC);
    $sth = null;
    
    return $Answers;
}

if ($action == "add"){
    $sql="INSERT INTO Test_Questions (Code_Text,AnswerID,Code_Type,WeekNumber) 
        values(:Code_Text,0,:type,:week)";
    $sth = $dbh->prepare($sql); 
    $params = array(
        ':Code_Text' => $Code_Text,
        ':type' => $ddlQType, 
        ':week' => $weekofDate);
    $sth->execute($params); 
    $Edit_ID = $dbh->lastInsertId(); 
    $sth = null;
    
    if ($ddlQType == "TF") {
        $sql="INSERT INTO Test_Answers (Code_Text,QuestionID) 
            values('True',:qID1)
        INSERT INTO Test_Answers (Code_Text,QuestionID) 
            values('False',:qID2)";
    $sth = $dbh->prepare($sql); 
    $params = array(
        ':qID1' => $Edit_ID, 
        ':qID2' => $Edit_ID);
    // $rc = sql_debug($sql,$params);
	// echo $rc;
	// exit();
    $sth->execute($params); 
    $sth = null;
    }
}

if ($action == "addAnswer"){
    $sql="INSERT INTO Test_Answers (Code_Text,QuestionID) 
        values(:text,:qID)";
    $sth = $dbh->prepare($sql); 
    $params = array(
        ':text' => $txtNewQuestion,
        ':qID' => $Edit_ID);
    // $rc = sql_debug($sql,$params);
	// echo $rc;
	// exit();
    $sth->execute($params); 
    $sth = null;
}

if ($action == "delete") {
    $sql="DELETE FROM Test_Answers WHERE AnswerID = :del_ID";
  	$sth = $dbh->prepare($sql);  
	$params = array(':del_ID' => $del_ID);
    // $rc = sql_debug($sql,$params);
	// echo $rc;
	// exit();
	$sth->execute($params);
	$sth = null;
    $action = "";
}

if ($action == "deleteAll") {
    $sql="DELETE FROM Test_Answers WHERE QuestionID = :del_ID1
          DELETE FROM Test_Questions WHERE QuestionID = :del_ID2";
  	$sth = $dbh->prepare($sql);  
	$params = array(':del_ID1' => $del_ID, ':del_ID2' => $del_ID);
    // $rc = sql_debug($sql,$params);
	// echo $rc;
	// exit();
	$sth->execute($params);
	$sth = null;
    $Edit_ID = 0;
    $action = "";
}

//get row for editing
if ($Edit_ID > 0) {
    $sql="Select Code_Text as Question, AnswerID as Answer, weeknumber from Test_Questions where QuestionID = :qID";
    $sth = $dbh->prepare($sql); 
        $params = array(
            ':qID' => $Edit_ID);
        $sth->execute($params); 
        $EditQuestion = $sth->fetchAll(PDO::FETCH_ASSOC);
        $sth = null;  
        foreach ($EditQuestion as $rs) {
            extract($rs);
        }
    
    $sql="Select QuestionID, Code_Text, AnswerID from Test_Answers where QuestionID = :qID";
        $sth = $dbh->prepare($sql); 
        $params = array(
            ':qID' => $Edit_ID);
        $sth->execute($params); 
        $EditRow = $sth->fetchAll(PDO::FETCH_ASSOC);
        $sth = null;  
    
    function getStartAndEndDate($week, $year) {
        $dto = new DateTime();
        $startDate = $dto->setISODate($year, $week)->format('m/d/Y');
        //$ret['week_end'] = $dto->modify('+6 days')->format('Y-m-d');
        return $startDate;
    }
    $weeknumber = getStartAndEndDate($weeknumber,date('Y'));
    // //print_r($week_array);
}
if ($view == "all") {
    $sql="SELECT * FROM Test_Questions ORDER BY QuestionID";
    $sth = $dbh->prepare($sql); 
    $sth->execute(); 
    $Questions = $sth->fetchAll(PDO::FETCH_ASSOC);
    $sth = null;  
}
if ($view == "week") {
    $ddate = $txtdate;
    $duedt = explode("/", $ddate);
    $date  = mktime(0, 0, 0, $duedt[0], $duedt[1], $duedt[2] );
    $viewWeek  = (int)date('W', $date);

    
    $sql="SELECT * FROM Test_Questions where weeknumber = :week ORDER BY QuestionID";
    $sth = $dbh->prepare($sql); 
    $params = array(':week' => $viewWeek);
    $sth->execute($params);
    $Questions = $sth->fetchAll(PDO::FETCH_ASSOC);
    $sth = null;  
}
?>
