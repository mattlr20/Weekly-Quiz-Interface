<?php 
ini_set('session.cache_limiter','public');
session_cache_limiter(false);
require "include/require.php";
if (!isset($CurrentStep)) {$CurrentStep = "Results";}
if (!isset($btnVal)) {$btnVal = "Questions";}
if ($CurrentStep == "Questions") {$btnVal = "Results";} else 
if ($CurrentStep == "Results") {$btnVal = "Questions";}
if (!isset($txtdate)) {$txtdate = date("m/d/Y");}
?>
<center>
<form action="." method="post" id="csTest" style="display: inline; margin: 0;">
<input type="hidden" id="CurrentStep" name="CurrentStep" value="<?php echo $CurrentStep ?>">
<input type="hidden" name="scrolly" id="scrolly" value="0" />
<div style="padding:3px 16px 0 0" align="right"><input type="submit" id="btnStep" class="btn btn-sm" value="<?php echo $btnVal ?>" onclick="fNAV(this)"/></div>
<?php 
if ($CurrentStep == "Results") {
    require "CS_Results.php";
    $btnVal = "Questions";
} else 
if ($CurrentStep == "Questions") {
    require "CS_Questions.php";
    $btnVal = "Results";
}
$scrolly = 0; if(!empty($_REQUEST['scrolly'])) { $scrolly = $_REQUEST['scrolly']; }
?>
<script>window.scrollTo(0, <?php echo "$scrolly" ?>);</script>
</form>
</center>
</body>
</html>
<?php
$dbh = null;
?>