<?php 
require "CS_Results.code.php";?>
<script type="text/javascript" src="js/loader.js"></script>
<script>
$(function(){
    $("#NewQPanel").draggable();
    if ($("#EditID").val() > 0) {
            $("#NewQPanel").show(100);
    } 
});

google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);
function drawChart() {
    var data = google.visualization.arrayToDataTable([
        ['Genre', 'Correct', 'Incorrect', { role: 'annotation' } ],
            <?php echo $chartArray; ?>
        ]);
    var options = {
        'title':'This Weeks\' Results',
        width: 450, height: 250,
        chartArea: {width: '80%', height: '75%'},
        legend: { position: 'top', maxLines: 3 },
        bar: { groupWidth: '75%' },
        hAxis: {minValue: 0},
        isStacked: 'percent'
    };
    var chart = new google.visualization.BarChart(document.getElementById('chart_div'));
    chart.draw(data, options);
}

function fShow(id) {
    fScroll()
    document.getElementById('EditID').value = id;
    document.forms.csTest.submit();
}

function fCancel() {
    fScroll()
    document.getElementById('EditID').value = 0;
    document.forms.csTest.submit();
}
</script>
<input type="hidden" id="EditID" name="EditID" value="<?php echo $EditID ?>">

<h3>Cyber Security Test Results</h3>

<table>
    <tr><td>Week of</td>
    <td class="date-col">
        <div class="input-group date datepicker">
            <input type="text" name="txtdate" id="txtdate" class="form-control" value="<?php echo $txtdate ?>" onchange="$('#view').val('week');$('#csTest').submit();">
            <span class="input-group-btn">
                <button type="button" class="btn btn-gray">
                    &nbsp;<i class="glyphicon glyphicon-calendar"></i>&nbsp;
                </button>
            </span>
        </div>
    </td></tr>
</table>

<div class="inline-chart" width="400px">
    <div id="chart_div"></div>
</div>

<div class="inline-results">
<table class="table table-condensed borderless" style="font-size:14px;">
    <?php
    $x=0;
    foreach ($Questions as $rs) : extract($rs); ?>
        
        <tr><td><b>Question <?php $x+=1; echo $x ?>:</b><br><?php echo $Code_Text ?><br></td></tr>
        
        <?php foreach (getAnswers($dbh, $AnswerID, $QuestionID) as $rs) : extract($rs);
            if  ($Code_Type <> "short") : ?>
                <tr><td><b>Correct Answer:</b><br><?php echo $Code_Text?><br></td></tr>
            <?php else : ?>
                <tr><td><b>Acceptable Answer(s):</b><br><?php echo $Code_Text ?><br></td></tr>
            <?php endif;
        endforeach;
        if  ($Code_Type <> "short") :
            $Results = getResults($dbh, $week_start, $week_end, $QuestionID);
            $calcResults = calcResults($Results, $AnswerID, $totalCorrect, 0, 0, 0);
                $entries = $calcResults['entries'];
                $totalEntries += $entries;
                $correct = $calcResults['correct'];
                $totalCorrect = $calcResults['totalCorrect'];
                $pCorrect = $calcResults['pCorrect']; ?>
            <tr><td><b>Correct Answers:</b><br><?php echo $correct ?> out of <?php echo $entries ?> (<?php echo $pCorrect ?>%)<hr></td></tr>
        <?php else : ?>
            <tr><td><button id="btnShow" class="btn btn-link" onclick="fShow(<?php echo $QuestionID ?>)">View Answers</button><hr></td></tr>
        <?php endif;
    endforeach; $pTotalCorrect = calcTotals($totalEntries, $totalCorrect, 0); ?>
</table>
</div>

<div class="inline-total" style="font-size:16px">
    <b>Overall Total Correct:</b> <?php echo $totalCorrect ?> out of <?php echo $totalEntries ?> (<?php echo $pTotalCorrect ?>%)
    <br><i>Excluding Short Answers</i>
</div>

<div id="NewQPanel" class="panel panel-default dialog">
    <div class="panel-heading" cellpadding="4">Short Answers</div>
    <div class="panel-body">
    <table align="center">
        <?php if ($EditID > 0) : $z = 0;
            foreach ($shortAnswers as $rs) : extract($rs); ?>
                <tr>
                <td style="padding:0 3px 12px 0; font-weight:600" width="5%">A<?php $z+=1; echo $z ?>:</td>
                <td style="padding:0 0 12px 0" align="left">"<?php echo $Answer ?>"</td>
                </tr>
            <?php endforeach;
        endif ?>
        <tr>
        <td colspan="2">
        <div class="input-group"><br>
            <input type="submit" id="btnCancelEdit" class="btn" value="Done" onclick="fCancel()"/>
        </div>
        </td>
        </tr>
    </table>
    </div>
</div>