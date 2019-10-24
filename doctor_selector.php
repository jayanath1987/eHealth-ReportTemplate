<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/*
--------------------------------------------------------------------------------
HHIMS - Hospital Health Information Management System
Copyright (c) 2011 Information and Communication Technology Agency of Sri Lanka
<http: www.hhims.org/>
----------------------------------------------------------------------------------
This program is free software: you can redistribute it and/or modify it under the
terms of the GNU Affero General Public License as published by the Free Software
Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
A PARTICULAR PURPOSE. See the GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License along
with this program. If not, see <http://www.gnu.org/licenses/> or write to:
Free Software  HHIMS
ICT Agency,
160/24, Kirimandala Mawatha,
Colombo 05, Sri Lanka
----------------------------------------------------------------------------------
Author: Author: Mr. Jayanath Liyanage   jayanathl@icta.lk
                 
URL: http://www.govforge.icta.lk/gf/project/hhims/
----------------------------------------------------------------------------------
*/

$now   = date("Y-m-d");
$to    = date('Y-m-d', strtotime($now . ' - 5 days'));
$model = "<div class=\"modal-dialog\">\n";
$model .= "    <div class=\"modal-content\">\n";
$model .= "        <div class=\"modal-header\">\n";
$model .= "            <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">×</button>\n";
$model .= "            <h4 class=\"modal-title\">" . $title . "</h4>\n";
$model .= "        </div>\n";
$model .= "        <div class=\"modal-body\">\n";
$model .= "            <table style=\"width: 100%\">\n";
$model .= "                <thead>\n";
$model .= "                <tr>\n";
$model .= "                    <th>Doctor: <select id=\"" . $id . "_doctor\" >\n";
$model .= "                         <option value=\"all\">All Doctors</option>";
foreach ($doctors as $_doctor) {
    $model .= "<option value=\"" . $_doctor->id . "\">" . $_doctor->name . "</option>";
}
$model .= "                         </select>";
$model .= "                    </th>\n";
$model .= "                    <th>From: <input type=\"text\" class=\"span2\" value=\"$to\" id=\"" . $id . "_dpd1\"></th>\n";
$model .= "                    <th>To: <input type=\"text\" class=\"span2\" value=\"$now\" id=\"" . $id . "_dpd2\"></th>\n";
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

$js = "<script type=\"text/javascript\">";


$js .= " var nowTemp = new Date();\n";
$js .= "    var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);\n";
$js .= "\n";
$js .= "    var checkin = $('#old{$id}_dpd1').datepicker({\n";
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
$js .= "    var checkout = $('#old{$id}_dpd2').datepicker({\n";
$js .= "        onRender: function (date) {\n";
//$js .= "            return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';\n";
$js .= "        },\n";
$js .= "        format:'yy-mm-dd'\n";
$js .= "    }).on('changeDate',function (ev) {\n";
$js .= "            checkout.hide();\n";
$js .= "        }).data('datepicker');\n";


$js .= "    function $id() {\n";
$js .= "        var params = \"menubar=no,location=no,resizable=yes,scrollbars=yes,status=no,width=750,height=700\";\n";
//$js .= "        $(\"#modal\").hide();\n";
$js .= "        var lookUpW = window.open(\"" . $url
    . "/\" + $(\"#{$id}_dpd1\").val() + \"/\" + $(\"#{$id}_dpd2\").val()+ \"/\" + $(\"#{$id}_doctor\").val(), \"lookUpW\", params);\n";
$js .= "    }\n";
$js .= "</script>";
echo $model . $js;
  