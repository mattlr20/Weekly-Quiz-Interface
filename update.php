<?php
include 'include/auth.php';
if (!isset($user)) {$user = "john.doe";}
$updateType = $_GET["updateType"];
$iType = $_GET["rtype"];
$iValue = $_GET["value"];
$QuestionID = $_GET["QuestionID"];
$AnswerID = $_GET["AnswerID"];
$ID = $_GET["ID"];

if ($updateType == "Results") {
    if ($ID == 0) {
        $sql="INSERT INTO CyberSecurityTestResults (username,Question) VALUES (:username,0)";
        $sth = $dbh->prepare($sql);
        $params = array(':username' =>  $user);
        $dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
        $sth->execute($params); 
        $ID = $dbh->lastInsertId(); 
        $sth = null;
    }
    $sql = "update CyberSecurityTestResults set Question = :qID, Answer = :aID where RecordID = :RecordID";
    $sth = $dbh->prepare($sql);  
    $params = array(':qID' => $QuestionID, ':aID' => $AnswerID, ':RecordID' => $ID);
    $sth->execute($params); 
    $sth = null;
}

if ($updateType == "Question") {
    $iType = mssql_escape($iType);
    
    if ($iType == "weeknumber") {
        //get week number
        $ddate = $iValue;
        $duedt = explode("/", $ddate);
        $date  = mktime(0, 0, 0, $duedt[0], $duedt[1], $duedt[2] );
        $iValue = (int)date('W', $date);
    }
    
    $sql = "update CyberSecurityTestQuestions set $iType = :iType where QuestionID = :qID";
    $sth = $dbh->prepare($sql);  
    $params = array(':iType' => $iValue, ':qID' => $ID);
    $sth->execute($params); 
    $sth = null;
}

if ($updateType == "Answer") {
    $iType = mssql_escape($iType);
    $sql = "update CyberSecurityTestAnswers set $iType = :iType where AnswerID = :aID";
    $sth = $dbh->prepare($sql);  
    $params = array(':iType' => $iValue, ':aID' => $ID);
    $sth->execute($params); 
    $sth = null;
}

function mssql_escape($data) {
    if ( !isset($data) or empty($data) ) return '';
    if ( is_numeric($data) ) return $data;
    $non_displayables = array(
        '/%0[0-8bcef]/',            // url encoded 00-08, 11, 12, 14, 15
        '/%1[0-9a-f]/',             // url encoded 16-31
        '/[\x00-\x08]/',            // 00-08
        '/\x0b/',                   // 11
        '/\x0c/',                   // 12
        '/\x27/',                   // quote
        '/\x3b/',                   // semicolon
        '/[\x0e-\x1f]/'             // 14-31
    );
    foreach ( $non_displayables as $regex )
    $data = preg_replace( $regex, '', $data );
    $data = str_replace("'", "''", $data );
    return $data;
}
?>