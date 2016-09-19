<?php
if (!isset($CorrectAnswerID)) {$CorrectAnswerID = "";}
if (!isset($EditID)) {$EditID = 0;}
$entries = $totalEntries = $correct = $totalCorrect = $pCorrect = $pTotalCorrect = $chartCorrect = $chartIncorrect = $x = $i = 0;
$chartArray = "";
//get week number
$ddate = $txtdate;
$duedt = explode("/", $ddate);
$date  = mktime(0, 0, 0, $duedt[0], $duedt[1], $duedt[2] );
$viewWeek  = (int)date('W', $date);
//get start and end date of this week
$day = (int)date('w', $date);
$week_start = date('m/d/Y', strtotime('-'.$day.' days', $date));
$week_end = date('m/d/Y', strtotime('+'.(6-$day).' days', $date));

$sql="SELECT * FROM Test_Questions where WeekNumber = :WeekNumber";
$sth = $dbh->prepare($sql); 
$params = array(':WeekNumber' => $viewWeek);
$sth->execute($params); 
$Questions = $sth->fetchAll(PDO::FETCH_ASSOC);
$sth = null;    

$pass = 0;
foreach ($Questions as $rs) {    
    extract($rs); 
    $i+=1;
    foreach (getAnswers($dbh, $AnswerID, $QuestionID) as $rs) {
        extract($rs);
    }
    if  ($Code_Type <> "short"){
        $Results = getResults($dbh, $week_start, $week_end, $QuestionID);
        $calcResults = calcResults($Results, $AnswerID, 0, 0, $chartCorrect, $chartIncorrect);
            $chartCorrect = $calcResults['chartCorrect'];
            $chartIncorrect = $calcResults['chartIncorrect'];
    }
    if ($chartArray <> "") {
        $chartArray = $chartArray.",";
    }
    $chartArray =  $chartArray."['Q$i', $chartCorrect, $chartIncorrect, '']";
}

function getAnswers($dbh, $aID, $QuestionID) {
    $sql="SELECT * FROM Test_Answers WHERE QuestionID = :qID and AnswerID = :aID ORDER BY AnswerID";
    $sth = $dbh->prepare($sql); 
    $params = array(':qID' => $QuestionID,':aID' => $aID);
    $sth->execute($params); 
    $Answers = $sth->fetchAll(PDO::FETCH_ASSOC);
    $sth = null;
    
    return $Answers;
}  

function getResults($dbh, $startdate, $enddate, $QuestionID) {
    
    $sql="SELECT Question, Answer FROM Test_Results where datestamp >= :startdate and datestamp <= :enddate and Question = :qID";
    $sth = $dbh->prepare($sql); 
    $params = array(':startdate' => $startdate,':enddate' => $enddate, ':qID' => $QuestionID);
    $sth->execute($params); 
    $Results = $sth->fetchAll(PDO::FETCH_ASSOC);
    $sth = null; 
    
    return $Results;
}  

function calcResults($Results, $aID, $totalCorrect, $pCorrect, $chartCorrect, $chartIncorrect) {
    $entries = $correct = $chartCorrect = $chartIncorrect = 0;
    foreach ($Results as $rs) {
        extract($rs);
        $entries+=1;
        if ($Answer == $aID) {
            $correct+=1;
            $totalCorrect +=1;
        }
        if ($entries > 0) {
            $pCorrect = ($correct / $entries) * 100;
            $pCorrect = round($pCorrect, 0);
        } else {
            $pCorrect = 0;
        }
        //for chart
        if ($Answer == $aID) {
            $chartCorrect+=1;
        } else {
            $chartIncorrect+=1;
        }
    }

    return array('entries' => $entries,
                 'correct' => $correct,
                 'totalCorrect' => $totalCorrect,
                 'pCorrect' => $pCorrect,
                 'chartCorrect' => $chartCorrect,
                 'chartIncorrect' => $chartIncorrect);
}

function calcTotals($totalEntries, $totalCorrect, $pTotalCorrect) {
    if ($totalEntries > 0) {
    $pTotalCorrect = ($totalCorrect / $totalEntries) * 100;
    $pTotalCorrect = round($pTotalCorrect, 0);
    } else {
        $pTotalCorrect = 0;
    }
    return $pTotalCorrect;
}

if ($EditID > 0) {
    $sql="SELECT Answer FROM Test_Results where datestamp >= :startdate and datestamp <= :enddate and Question = :EditID";
    $sth = $dbh->prepare($sql); 
    $params = array(':startdate' => $week_start,':enddate' => $week_end, ':EditID' => $EditID);
    $sth->execute($params); 
    $shortAnswers = $sth->fetchAll(PDO::FETCH_ASSOC);
    $sth = null; 
}
?>
