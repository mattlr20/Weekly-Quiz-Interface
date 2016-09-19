<?php 
require "CS_Questions.code.php";?>
<script>
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
    function fUpdate(v1,v2,v3) {
        //alert('updateType='+v3+'&rtype='+v1.name+'&value='+v1.value+'&QuestionID='+v2);
        http.open('post','update?updateType='+v3+'&rtype='+v1.name+'&value='+v1.value+'&ID='+v2, true);http.send(null);
    }
    $(function() {
        $("#NewQPanel").draggable();
        $("#btnNew").click(function() {
            $("#NewQPanel").show(300);
            document.getElementById("headerText").innerHTML  = "New Question";
            document.getElementById("btnAdd").value = 'Create Question';
            $("#editButtons").hide();
            $("#addButtons").show();
            $("#answerTable").hide();
        });
                        
        if ($("#Edit_ID").val() > 0) {
            document.getElementById("headerText").innerHTML = 'Edit Question '+ <?php echo $Edit_ID ?>;
            document.getElementById("btnNew").disabled = true;
            $("#editButtons").show();
            $("#addButtons").hide();
            if (document.getElementById("action").value != '') {
                $("#NewQPanel").show();    
            } else {
                $("#NewQPanel").show(300);
            }
         }
         $("#answerTable").show();
    });
    function fAdd() {
        //if($("#ddlQType").val() != "" && $("#txtQuestion").val() != "" && $("#weeknumber").val() != "") {
        if(document.getElementById("ddlQType").value != "" && document.getElementById("txtQuestion").value != "" && document.getElementById("weeknumber").value != "") {
            document.getElementById("action").value = 'add';
            $("#answerTable").show();
            document.forms.csTest.submit();
        }
    }
    
    function fEdit(CodeID,type) {
        fScroll()
        document.getElementById("ddlQType").value = type;
        document.getElementById("Edit_ID").value = CodeID;
        document.forms.csTest.submit();
    }
    
    function fCancel() {
        fScroll()
        document.getElementById("Edit_ID").value = 0;
        document.getElementById("action").value = '';
        $("#NewQPanel").hide({ queue: false }, "slow").promise().done(function () {
            document.forms.csTest.submit();
        });
    }
    
    function fAddA() {
        fScroll()
        if (document.getElementById("txtNewQuestion").value != "") {
            document.getElementById("action").value = 'addAnswer';
            document.forms.csTest.submit();
        }
    }
    
    function fDelete(aID) {
        if (confirm('Delete answer?')) {
            fScroll()
            document.getElementById("del_ID").value = aID;
            document.getElementById("action").value = "delete";
            document.forms.csTest.submit();
        }
    }
    
    function fDeleteAll(qID) {
        if (confirm('Delete question and answers?')) {
            fScroll()
            document.getElementById("del_ID").value = qID;
            document.getElementById("action").value = 'deleteAll';
            document.forms.csTest.submit();
        }
    }
</script>
<input type="hidden" name="action" id="action" value="<?php echo $action ?>"/>
<input type="hidden" id="view" name="view" value="<?php echo $view ?>"/>
<input type="hidden" id="Edit_ID" name="Edit_ID" value="<?php echo $Edit_ID ?>"/>
<input type="hidden" id="del_ID" name="del_ID"/>
<h3>Cyber Security Questions</h3>
<table>
    <tr>
    <td class="date-label">Week of</td>
    <td class="date-col">
        <div class="input-group date datepicker">
            <input type="text" name="txtdate" id="txtdate" class="form-control" value="<?php echo $txtdate ?>" onchange="$('#view').val('week');$('#csTest').submit();">
            <span class="input-group-btn">
                <button type="button" class="btn btn-gray">
                    &nbsp;<i class="glyphicon glyphicon-calendar"></i>&nbsp;
                </button>
            </span>
        </div>
    </td>
    <td>
        <div class="btn-group">
            <input type="button" id="btnAll" class="btn" value="Show All" onclick="$('#view').val('all');$('#csTest').submit();"/>
            <input type="button" id="btnNew" class="btn" value="New Question"/>
            <input type="button" class="btn" data-toggle="collapse" data-target=".QA" value="Toggle Answers"/>
        </div>
    </td>
    </tr>
</table>

<div id="NewQPanel" class="panel panel-default dialog"><!-- New Question/Edit Area -->
    <div class="panel-heading" cellpadding="4">
        <label id="headerText"></label>
    </div>
    <div class="panel-body">
    <table align="center">
        <tr><td>Question Type</td></tr>
        <tr><td style="padding:5px 0 12px 0">
            <select name="ddlQType" id="ddlQType" class="selectpicker">
                <option value="" <?php if($ddlQType=="") echo "selected"; ?>>Please select...</option>
                <option value="multi" <?php if($ddlQType=="multi") echo "selected"; ?>>Multiple Choice</option>
                <option value="TF" <?php if($ddlQType=="TF") echo "selected"; ?>>True or False</option>
                <option value="short" <?php if($ddlQType=="short") echo "selected"; ?>>Short Answer</option>
            </select>
        </td></tr>
        <tr><td colspan="2">Question</td></tr>
        <tr><td colspan="2" style="padding:5px 0 12px 0">
            <textarea id="txtQuestion" name="Code_Text" maxlength="255" rows="4" class="form-control" onchange="fUpdate(this,<?php echo $Edit_ID ?>,'Question')"><?php echo $Question ?></textarea>
        </td></tr>
        <tr><td colspan="2">Answers (Select Correct Answer)
        <table id="answerTable" class="answerTable">
            <?php if ($Edit_ID > 0) :
                foreach ($EditRow as $rs) : extract($rs);
                    if ($AnswerID == $Answer) { $checked = "checked"; } else { $checked = ""; } ?>
                    <tr>
                    <td><?php $x+=1; echo $x ?></td>
                    <td><input type="radio" name="AnswerID" value="<?php echo $AnswerID ?>" onchange="fUpdate(this,<?php echo $Edit_ID ?>,'Question')" <?php echo $checked ?>/></td>
                    <td><div class="input-group" style="width: 100%">
                        <input type="text" name="Code_Text" value="<?php echo $Code_Text ?>" maxlength="255" class="form-control" onchange="fUpdate(this,<?php echo $AnswerID ?>,'Answer')"/>
                        <span class="input-group-btn">
                        <button type="button" class="btn btn-gray" title="Delete Answer" onclick="fDelete(<?php echo $AnswerID ?>)">&nbsp;<i class="glyphicon glyphicon-remove"></i>&nbsp;</button>
                        </span></div>
                    </td>
                    </tr>
                <?php endforeach;
            endif ?>
            <tr>
            <td><?php $x+=1; echo $x ?></td>
            <td></td>
            <td><div class="input-group" style="width: 100%">
                <input type='text' id="txtNewQuestion" name="txtNewQuestion" class="form-control" maxlength="255" placeholder="New Answer"/>
                <span class="input-group-btn">
                    <button type="button" class="btn btn-gray" onclick="fAddA()">&nbsp;<i class="glyphicon glyphicon-plus" title="Add Answer"></i>&nbsp;</button>
                </span>
                </div>
            </td>
            </tr>
        </table><br>
        
    </td></tr>
    <tr align="center"><td colspan="2">Week of:</td></tr>
    <tr><td colspan="2">
        <div class="input-group date datepicker" style="width: 500px;">
            <input type="text" name="weeknumber" id="weeknumber" class="form-control" value="<?php echo $weeknumber ?>" size="10" onchange="fUpdate(this,<?php echo $Edit_ID ?>,'Question')"/>
            <span class="input-group-btn">
                <button type="button" class='btn btn-gray'>&nbsp;<i class="glyphicon glyphicon-calendar"></i>&nbsp;</button>
            </span>
        </div>
        </td>
    </tr>
    </table>
    </div>
    <div class="panel-footer">
        <div id="addButtons" class="btn-group">
            <input type="button" id="btnAdd" class="btn" onclick="fAdd()"/>
            <input type="button" id="btnCancel" class="btn" value='Cancel' onclick='$("#NewQPanel").hide(400);'/>
        </div>
        <div id="editButtons" class="btn-group">
            <input type="button" id="btnCancelEdit" class="btn" value="Done" onclick="fCancel()"/>
            <input type="button" id="btnDelete" class="btn" value="Delete All" onclick="fDeleteAll(<?php echo $Edit_ID ?>)"/>
        </div>
    </div>
</div><br><br>

<?php $x = 0;$pass=1;
foreach ($Questions as $rs) : extract($rs); $aID = $AnswerID;
    if ($pass == 0) : ?>
        </div>
    <?php endif; $pass = 0 ?>
    <div class="panel panel-default QA_Test">
        <div class="panel-heading" align="left">
            <table width="100%">
                <tr>
                <td valign="top" width="5%">
                    <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#Q<?php $x+=1; echo $x ?>" title="Toggle Answers"><strong>Q<?php echo $x ?>:</strong></button>
                </td>
                <td><?php echo $Code_Text ?></td>
                <td align="right">
                    <button id="btnEdit" class="btn btn-link" onclick='fEdit(<?php echo $QuestionID ?>,"<?php echo $Code_Type ?>")'><i class="glyphicon glyphicon-pencil" title="Edit"></i></button>
                </td>
                </tr>
            </table>
        </div>
        <div id="Q<?php echo $x ?>" class="collapse in QA">
        <table class="table table-condensed borderless" width="100%">
            <?php foreach (getAnswers($dbh, $aID, $QuestionID) as $rs) : extract($rs); ?>
            <?php if ($AnswerID == $aID) : ?>
                <tr>
                <td align="center" width="5%">&#10004;</td>
                <td><strong><?php echo $Code_Text ?></strong></td>
                </tr>
            <?php else : ?>
                <tr>
                <td align="center" width="5%">&#9679;</td>
                <td><?php echo $Code_Text ?></td>
                </tr>
                <?php endif; 
            endforeach ?>
        </table>
    </div>
<?php endforeach ?>
</div>
