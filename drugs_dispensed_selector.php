<?php
$now=date("Y-m-d");
$uid=$this->session->userdata("UID");

$dataset = array();
$sql=" select who_drug.wd_id,who_drug.name";
$sql .= " FROM  who_drug " ;
$sql .=" LEFT JOIN drug_count ON who_drug.wd_id = drug_count.who_drug_id ";
$sql .=" LEFT JOIN drug_stock ON drug_count.drug_stock_id = drug_stock.drug_stock_id ";
$sql .= " WHERE drug_stock.UID='$uid' AND drug_count.Active=1" ;
$sql .= " ORDER BY who_drug.name" ;
        $result =  $this->db->query($sql);
        if ($result->num_rows() >0){
            foreach ($result->result_array() as $row){
                $dataset[] = $row;
            }
        }
       


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
$model
    .= "                    <th>From: <input type=\"text\" class=\"span2\" value=\"$now\" id=\"" . $id . "_dpd1\"></th>\n";
$model .= "                    <th>To: <input type=\"text\" class=\"span2\" value=\"$now\" id=\"" . $id . "_dpd2\"></th>\n";
$model .= "                </tr>\n";
$model .= "                </thead>\n";
$model .= "            </table>\n";

//$model .= '<input id="searchInput" value="Type To Filter"   style="width: 300px; border-color: #9ecaed; box-shadow: 0 0 10px #9ecaed;" >';
$model .= '<table class="table table-condensed table-bordered table-striped table-hover">';
$model .= '<tr>';
$model .= '<th>';
$model .= '<b>#</b>';
$model .= '</th>';
$model .= '<th>';
$model .= '<b>Drug Name</b>';
$model .= '</th>';
$model .= '<tbody id="fbody">';
for ($i=0; $i<count($dataset); ++$i){
$model .= '<tr>';
$model .= '<td><input type="checkbox" name="print[]" id="print" value=' . $dataset[$i]["wd_id"] . ' /></td>';
$model .= '<td >';
$model .= $dataset[$i]["name"];
$model .= '</td>';
$model .= '</tr>';
	}
                                                                
$model .= "</tbody></table>";

if (isset($description)) {
    $model .= "            <div class='alert alert-info' style='margin-top:18px; '>\n";
    $model .= "            $description\n";
    $model .= "            </div>\n";
}
$model .= "        </div>\n";
$model .= "        <div class=\"modal-footer\">\n";
$model .= "            <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>\n";
$model .= "            <button type=\"button\" class=\"btn btn-primary\" onclick=\"drugsDispensed('" . site_url("report/pdf/Drugs_dispensed/print/") . "')\">Generate</button>\n";
$model .= "        </div>\n";
$model .= "    </div>\n";
$model .= "</div>";

$js = "<script type=\"text/javascript\">";


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

$js .= "function drugsDispensed(url){\n";
$js .= "    var data='?';\n";
$js .= "    $.each( $(\"#print:checked\" ), function( key, value ) {\n";
$js .= "        data+='print[]='+$(this).val()+'&';\n";
$js .= "    });\n";
$js .= "    var dPd1=$(\"#{$id}_dpd1\").val()\n";
$js .= "    var dPd2=$(\"#{$id}_dpd2\").val()\n";
$js .= "    data+='dpd1='+dPd1+'&';\n";
$js .= "    data+='dpd2='+dPd2;\n";
$js .= "    var params = \"menubar=no,location=no,resizable=yes,scrollbars=yes,status=no,width=750,height=700\";\n";
$js .= "    window.open(url + data, \"lookUpW\",params);\n";
$js .= "}\n";
                        
/*$js .=  "$('#searchInput').keyup(function () { ";
    //split the current value of searchInput
$js .=  "var data = this.value.split(' ')";
    //create a jquery object of the rows
$js .=   "var jo = $('#fbody').find('tr')";
$js .=    "if (this.value == '') {";
$js .=    "jo.show()";
$js .=      "return";
$js .=    "}";
$js .=  "alert(jo)";
    //hide all the rows
$js .=    "jo.hide()";

    //Recusively filter the jquery object to get results.
$js .=   "jo.filter(function (i, v) {";
$js .=    "var t = $(this)";
$js .=     "for (var d = 0; d < data.length; ++d) {";
$js .=   "if (t.is(':contains(' + data[d] + ')')) {";
$js .=     "return true";
$js .=   "}" ;
$js .=   " }";
$js .=  "return false";
$js .=    " })";
    //show the rows that match.
$js .=    " .show()";
$js .= " }).focus(function () {";
$js .=   " this.value = ''";
$js .=   "$(this).css({";
$js .=   "'color': 'black' ";
$js .=    " })";
$js .=    "$(this).unbind('focus')";
$js .= " }).css({";
$js .=  " 'color': '#C0C0C0'";
$js .= " })"; */

/*$js .= "    function $id() {\n";
$js .= "        var params = \"menubar=no,location=no,resizable=yes,scrollbars=yes,status=no,width=750,height=700\";\n";
//$js .= "        $(\"#modal\").hide();\n";
$js .= "        var lookUpW = window.open(\"" . $url
    . "/\" + $(\"#{$id}_dpd1\").val() + \"/\" + $(\"#{$id}_dpd2\").val(), \"lookUpW\", params);\n";
$js .= "    }\n"; */

$js .= "</script>";
echo $model . $js;

?>