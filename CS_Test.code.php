<?php 
if (!isset($RecordID)) {$RecordID = 0;}
if (!isset($displayname)) {$displayname = "John Doe";}
if (!isset($Answer)) {$Answer = "";}
if (!isset($next)) {$next = 1;}
if (!isset($showBtn)) {$showBtn = "false";}
$parts = explode(" ", $displayname);
$firstname = array_shift($parts);
$i=$x=0;
$pass=0;
$checked = "";
$AnswerID = "Answer$i";
//get weeknumber
$ddate = date("m/d/Y");
$duedt = explode("/", $ddate);
$date  = mktime(0, 0, 0, $duedt[0], $duedt[1], $duedt[2] );
$week  = (int)date('W', $date);

$sql="SELECT * FROM Test_Questions WHERE WeekNumber = :Week ORDER BY QuestionID";
$sth = $dbh->prepare($sql); 
$params = array(':Week' => $week);
$sth->execute($params); 
$Questions = $sth->fetchAll(PDO::FETCH_ASSOC);
$sth = null;

function getAnswers($dbh, $aID, $QuestionID) {
    $sql="SELECT * FROM Test_Answers WHERE QuestionID = :qID ORDER BY AnswerID";
    $sth = $dbh->prepare($sql); 
    $params = array(':qID' => $QuestionID);
    $sth->execute($params);
    $Answers = $sth->fetchAll(PDO::FETCH_ASSOC);
    $sth = null;
    
    return $Answers;
}

function getUserAnswer($dbh, $QuestionID) {
    if (!isset($user)) {$user = "john.doe";}
    //get start and end dates of week
    $day = date('w');
    $week_start = date('m/d/Y', strtotime('-'.$day.' days'));
    $week_end = date('m/d/Y', strtotime('+'.(6-$day).' days'));

    $sql="SELECT RecordID, Answer FROM Test_Results where username = :username and datestamp >= :startdate and datestamp <= :enddate and Question = :Question";
    $sth = $dbh->prepare($sql);
    $params = array(':username' => $user,':startdate' => $week_start,':enddate' => $week_end,':Question' => $QuestionID);
    $sth->execute($params);
    $userAnswers = $sth->fetchAll(PDO::FETCH_ASSOC);
    $sth = null;

    return $userAnswers;
}

?>
