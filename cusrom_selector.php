<?php
$now=date("Y-m-d");
$model = "<div class=\"modal-dialog\">\n";
$model .= "    <div class=\"modal-content\">\n";
$model .= "        <div class=\"modal-header\">\n";
$model .= "            <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">×</button>\n";
$model .= "            <h4 class=\"modal-title\">" . $title . "</h4>\n";
$model .= "        </div>\n";
$model .= "        <div class=\"modal-body\" style='padding-bottom: 5px;'>\n";
$model .= "            <table style=\"width: 100%\">\n";
$model .= "                <thead>\n";
$model .= "                 <th>   <tr>From: <input type=\"text\" class=\"span2\" value=\"$now\" id=\"" . $id . "_dpd1\">\n";
$model .= "                    To: <input type=\"text\" class=\"span2\" value=\"$now\" id=\"" . $id . "_dpd2\">\n";
$model .= "                </tr>\n";
$model .= "                <tr></th>\n";
$model .= "                    <th style=\"width: 40%\" > Occupation:</th><th> ".$TRA_OCC_ID." </th>\n";
$model .= "                </tr>\n";

$model .= "                <tr>\n";
$model .= "                    <th> Place of Injury:</th><th> ".$TRA_PINJ_ID." </th>\n";
$model .= "                </tr>\n";

$model .= "                <tr>\n";
$model .= "                    <th> Activity Time of Injury:</th><th> ".$TRA_AINJ_ID." </th>\n";
$model .= "                </tr>\n";

$model .= "                <tr>\n";
$model .= "                    <th> Cause of Injury:</th><th> ".$TRA_CINJ_ID." </th>\n";
$model .= "                </tr>\n";

$model .= "                <tr>\n";
$model .= "                    <th> Road User:</th><th> ".$TRA_TRAP_RUID." </th>\n";
$model .= "                </tr>\n";

$model .= "                <tr>\n";
$model .= "                    <th> Vehicle Involved:</th><th> ".$TRA_ACC_VID." </th>\n";
$model .= "                </tr>\n";

$model .= "                <tr>\n";
$model .= "                    <th> Specific Cause:</th><th> ".$TRA_VIO_CID." </th>\n";
$model .= "                </tr>\n";

$model .= "                <tr>\n";
$model .= "                    <th> Type of Violence:</th><th> ".$TRA_VIO_TID." </th>\n";
$model .= "                </tr>\n";

$model .= "                <tr>\n";
$model .= "                    <th> Relationship of victim:</th><th> ".$TRA_VIO_RID." </th>\n";
$model .= "                </tr>\n";

$model .= "                <tr>\n";
$model .= "                    <th> Self Inflicted Injuries:</th><th> ".$TRA_SII_ID." </th>\n";
$model .= "                </tr>\n";

$model .= "                <tr>\n";
$model .= "                    <th> Other Accident:</th><th> ".$TRA_OACC_ID." </th>\n";
$model .= "                </tr>\n";

$model .= "                <tr>\n";
$model .= "                    <th> SeatBealt/Healmet :</th><th> ".$TRA_SeatBelt_Helmet." </th>\n";
$model .= "                </tr>\n";

$model .= "                <tr>\n";
$model .= "                    <th> Alchol:</th><th> ".$TRA_Alchol." </th>\n";
$model .= "                </tr>\n";



$model .= "                </thead>\n";
$model .= "            </table>\n";
if (isset($description)) {
    $model .= "            <div class='alert alert-info' style='margin-top:18px; '>\n";
    $model .= "            $description\n";
    $model .= "            </div>\n";
}
$model .= "        </div>\n";
$model .= "        <div class=\"modal-footer\">\n";
$model .= "            <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>\n";
$model .= "            <button type=\"button\" class=\"btn btn-primary\" onclick='$id()'>Generate</button>\n";
$model .= "        </div>\n";
$model .= "    </div>\n";
$model .= "</div>";

$js = "<script type=\"text/javascript\">\n";

$js .= " var nowTemp = new Date();\n";
$js .= "    var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);\n";
$js .= "\n";
$js .= "    var checkin = $('#{$id}_dpd1').datepicker({\n";
$js .= "        onRender: function (date) {\n";
//$js .= "            return date.valueOf() < now.valueOf() ? 'disabled' : '';\n";
$js .= "        },\n";
$js .= "        format:'yyyy-mm-dd'\n";
$js .= "    }).on('changeDate',function (ev) {\n";
$js .= "            if (ev.date.valueOf() > checkout.date.valueOf()) {\n";
$js .= "                var newDate = new Date(ev.date)\n";
$js .= "                newDate.setDate(newDate.getDate() + 1);\n";
$js .= "                checkout.setValue(newDate);\n";
$js .= "            }\n";
$js .= "            checkin.hide();\n";
$js .= "            $('#{$id}_dpd2')[0].focus();\n";
$js .= "        }).data('datepicker');\n";
$js .= "    var checkout = $('#{$id}_dpd2').datepicker({\n";
$js .= "        onRender: function (date) {\n";
//$js .= "            return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';\n";
$js .= "        },\n";
$js .= "        format:'yyyy-mm-dd'\n";
$js .= "    }).on('changeDate',function (ev) {\n";
$js .= "            checkout.hide();\n";
$js .= "        }).data('datepicker');\n";


$js .= "    function $id() {\n";
$js .= "        var params = \"menubar=no,location=no,resizable=yes,scrollbars=yes,status=no,width=750,height=700\";\n";
$js .= "        var lookUpW = window.open(\"" . $url 
    ."/\" + $(\"#TRA_OCC_ID\").val() +\"/\"
    + $(\"#TRA_PINJ_ID\").val() +\"/\"
    + $(\"#TRA_AINJ_ID\").val() +\"/\"
    + $(\"#TRA_CINJ_ID\").val() +\"/\"
    + $(\"#TRA_TRAP_RUID\").val() +\"/\"
    + $(\"#TRA_ACC_VID\").val() +\"/\"
    + $(\"#TRA_VIO_CID\").val() +\"/\"
    + $(\"#TRA_VIO_TID\").val() +\"/\"
    + $(\"#TRA_VIO_RID\").val() +\"/\"
    + $(\"#TRA_SII_ID\").val() +\"/\" 
    + $(\"#TRA_OACC_ID\").val() +\"/\"
    + $(\"#TRA_SeatBelt_Helmet\").val() +\"/\" 
    + $(\"#TRA_Alchol\").val() +\"/\"    
    + $(\"#{$id}_dpd1\").val() +\"/\" 
    + $(\"#{$id}_dpd2\").val() "
        
 . ",\"lookUpW\", params);\n";
$js .= "    }\n";
$js .= "</script>";

echo $model . $js;
?>