<?php require "include/require.php";
require "CS_Test.code.php";
?>
<script>
var ele, que, rec;
var valuenow = 0;
var next = $("#next").val();
var completed = 'f';
function createRequestObject() {
    var ro;
    var browser = navigator.appName;
    if(browser == "Microsoft Internet Explorer"){
        ro = new ActiveXObject("Microsoft.XMLHTTP");
    }else{
        ro = new XMLHttpRequest();
    }
    return ro;
}
var http = createRequestObject();
function fUpdate() {
    //alert('AnswerID='+ele+'&QuestionID='+que+'&ID='+rec+'&updateType=Results');
    http.open('post','update?AnswerID='+ele+'&QuestionID='+que+'&ID='+rec+'&updateType=Results', true);http.send(null);
}

$(function() {
    $('#msg').hide();
    
    $("textarea").keyup(function () { //make sure short answer isn't blank before submit
        if (this.value == "") {
            $('#btnNext').fadeOut();
        } else {
            $('#btnNext').fadeIn();
        }
    });
            
    fProgress();  
    document.getElementById('next').value = next;
    if($('#' + next).length !== null && $('#' + next).length != 0) { //check if next table id exist
        $('#' + next).show();
    } else {
        next = 1;
        document.getElementById('next').value = next;
        $('#' + next).show();
        $('#btnNext').show();
        completed = 't';
    }
});

function fProgress() { //animation and calculations for progess bar
    var i = 1;
    var totalQ = 0;
    var answeredQ = 0;
    
    next = 1;
    while (i > 0) { //loop to get total num of questions
        if ($('#' + i).length > 0) {
            totalQ += 1;
            i += 1;
        } else {
            i = 0;
        }
    }
    i = 1;
    while (i > 0) { //loop to get total questions answered
        if ($('#' + next).find("textarea").length == 0 && $('#' + next + ' input[type=radio]:checked').length > 0 || $('#' + next).find("textarea").length != 0 && $('#' + next).find("textarea").val() != '') { 
            next = Number(next) + 1;
            answeredQ += 1;
        } else {
            i = 0;
        }
    }

    valuenow = (answeredQ / totalQ) * 100;
    document.getElementById('lblProgress').innerHTML = answeredQ + '/' + totalQ + ' Complete';
    $('.progress-bar').css('width', valuenow+'%').attr('aria-valuenow', valuenow); 
}

function fGetAnswer(element,QuestionID,RecordID) {
    ele = element.value;
    que = QuestionID;
    rec = RecordID;
    //alert(ele+" "+que+" "+rec);
    fBrownie(rec);
    $('#btnNext').fadeIn();
}

function fNext() {
    if (que != null) {
        fUpdate();
    }
    fProgress(); 
    next = Number(document.getElementById('next').value) + 1;
    var last = document.getElementById('next').value;
    document.getElementById('next').value = next;
    if(document.getElementById(next)=== null) { //check if next table id exist
        $('#' + last).fadeOut("slow");
        $('#btnNext').fadeOut("slow");
        $('#divProgress').fadeOut("slow");
        fThanks();
    } else {
        $('#' + next).slideToggle("slow").promise().done(function () {
            $('#' + last).slideUp({ queue: false }, "slow");
            if (completed == 'f') {
                $('#btnNext').slideUp({ queue: false }, "slow").promise().done(function () {});
            }
        });
    }
}

function fBrownie(rec) { //animation when answer select
    var msg = $('#msg');
    if (msg.text().length == 0 && rec == 0) {
        msg.css({fontSize: "13px",fontWeight: "bold",color: "green",fontfamily: "Comic Sans MS"})
            .prepend('+10'+'<img id="brownie" height="20" width="25" src="images/brownie.PNG" />'+' Pts')
            .toggle("slow")
            .fadeIn("slow")
            .animate({left: '170px'}, 1000)
            .fadeToggle();
    }
}

function fThanks() { //animate when test complete
    var firstname = '<?php echo $firstname ?>';
    var thanks = $('#thanks')
    thanks.text("Thanks "+firstname+"!")
    .fadeIn("slow")
    .animate({fontSize: '3em'}, "slow")
    .fadeOut(3000);
}

</script>
<center>
<form action="CS_Test.php" method="post" id="csTest" style="display: inline; margin: 0;">
<input type="hidden" id="next" name="next" value="<?php echo $next ?>" />

<table width="640px">
    <tr>
    <td width="43%" align="left"><div id="msg" style="position:relative;white-space:nowrap;"></div></td>
    <td width="29%" align="right"><input type="button" name="btnNext" id="btnNext" class="btn btn-xs" value="Next" onclick="fNext()"/></td>
    </tr>
</table>

<div id="thanks"></div>

<?php
foreach ($Questions as $rs) : extract($rs);
    $aID = $AnswerID;
    if ($pass != 0) : ?>
        </div>
    <?php endif; $pass=1; ?>
    <div id="<?php $x+=1; echo $x ?>" class="panel panel-default QA_Test"><!--question header-->
    <div class="panel-heading" align="left">
        <table width="100%">
        <tr>
        <td valign="top" width="5%"><b>Q<?php echo $x ?>:</b></td>
        <td><?php echo $Code_Text ?></td><td align="right"></td>
        </tr>
        </table>
    </div>
    
    <?php $i+=1; $option = "optionID$i"; //create unique id for radio ?>

    <table class="table table-condensed borderless" width="100%">
    <?php
    foreach (getAnswers($dbh, $aID, $QuestionID) as $rs) : extract($rs); $gotone = 0;
        foreach (getUserAnswer($dbh, $QuestionID) as $rs) { extract($rs); $gotone = 1; }
        if ($gotone == 0) { $RecordID = 0; $Answer = ""; }
        if ($Code_Type == "short") : //check if short answer ?>
            <tr>
            <td align="center" colspan=2 width="570px">
            <textarea name="<?php echo $option ?>" id="option" maxlength="255" class="form-control" rows="5" onKeyUp="fGetAnswer(this,<?php echo $QuestionID ?>,<?php echo $RecordID ?>)"><?php echo $Answer ?></textarea>
            </td>
            </tr>
        <?php else : 
            if ($Answer == $AnswerID) { $checked = "checked"; } else { $checked = ""; } ?>
            <tr>
            <td align="center" width="6.3%"><input type="radio" name="<?php echo $option ?>" id="option" value="<?php echo $AnswerID ?>" <?php echo $checked ?> onChange="fGetAnswer(this,<?php echo $QuestionID ?>,<?php echo $RecordID ?>)"/></td>
            <td><?php echo $Code_Text ?></td>
            </tr>
        <?php endif;
    endforeach ?>
    </table><script>$("#<?php echo $x ?>").hide();</script>
<?php endforeach ?>
</div>

<div id="divProgress">
    <span id="lblProgress"></span>
    <table class="progress" width="640px"><tr><td>
        <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
    </td></tr></table>
</div>
<script>$("#btnNext").hide();</script>
</form>
</center>
</body>
</html>
<?php require "../../include/close.php"; ?>