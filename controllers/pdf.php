<?php

if (!defined('BASEPATH')) {
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
  with this program. If not, see <http://www.gnu.org/licenses/>
  ----------------------------------------------------------------------------------
  Date : June 2016
  Author: Mr. Jayanath Liyanage   jayanathl@icta.lk

  Programme Manager: Shriyananda Rathnayake
  URL: http://www.govforge.icta.lk/gf/project/hhims/
  ----------------------------------------------------------------------------------
 */

class Pdf extends MX_Controller {

    function __construct() {

        parent::__construct();
        $this->checkLogin();
        $this->load->library('session');
    }

    public function index() {

        //$this->load->view('patient');
        $this->report_home();
    }

    public function report_home() {

        $data = array();
        $this->load->vars($data);
        $this->load->view('report_home', 1);
    }

    private function getHospital() {

        return $this->session->userdata('Hospital');
    }

    /**
     * @param      $type
     * @param null $from
     * @param null $to
     * pharmacy stock balance
     */
    public function getConfig($key, $value, $default = false) {
        if (isset($this->session->userdata['hospital_info']['Code'])) {
            $code = $this->session->userdata['hospital_info']['Code'];
            if (isset($this->config->config[$code]['report'][$key][$value])) {
                return $this->config->config[$code]['report'][$key][$value];
            }
        }
        return $default;
    }

    public function pharmacyBalance($type, $from = null, $to = null) {

        switch ($type) {
            case 'view':
                $data['title'] = 'Daily drugs dispensed';
                $data['url'] = site_url("report/pdf/pharmacyBalance/print");
                $data['id'] = uniqid("_");
                $data['description'] = 'Drugs dispensed daily';
                $this->load->vars($data);
                $this->load->view('date_selector');
                break;
            case 'print':
                $data['from_date'] = $from;
                $this->load->vars($data);
                $this->load->view('pdf/pharmacy_balance');
                break;
        }
    }

    public function MypharmacyBalance($type, $from = null, $to = null) {

        switch ($type) {
            case 'view':
                $data['title'] = 'Daily drugs dispensed';
                $data['url'] = site_url("report/pdf/MypharmacyBalance/print");
                $data['id'] = uniqid("_");
                $data['description'] = 'Drugs dispensed daily';
                $this->load->vars($data);
                $this->load->view('date_selector');
                break;
            case 'print':
                $data['from_date'] = $from;
                $this->load->vars($data);
                $this->load->view('pdf/mypharmacy_balance');
                break;
        }
    }

    public function Drugs_dispensed($type, $from = null, $to = null) {

        switch ($type) {
            case 'view':
                $data['title'] = 'Drugs dispensed';
                $data['url'] = site_url("report/pdf/Drugs_dispensed/print");
                $data['id'] = uniqid("_");
                //$data['description'] = 'Drugs dispensed';
                $this->load->vars($data);
                $this->load->view('drugs_dispensed_selector');
                break;
            case 'print':
                $items = $this->input->get('print');
                $dpd1 = $this->input->get('dpd1');
                $dpd2 = $this->input->get('dpd2');
                //$this->load->model('mpatient');
                // $this->load->model('mprescribe_items');
                $this->load->model('mpersistent');
                $this->load->model('mdrug_stock');
                //$this->load->model('mopd_prescription');
                //$data['patient'] = $this->mpatient->load($pId);

                if (!$items) {
                    echo 'Please select items to print';
                    exit;
                }
                // if (!isset($data['patient'])) {
                //    echo 'Patient doesn\'t exixts';
                //     exit;
                // }

                foreach ($items as $id) {
                    //unset($pItem);
                    $pItem = $this->mdrug_stock->load($id);
                    $drug_info = $this->mpersistent->open_id(
                            $id, "who_drug", "wd_id"
                    );

                    $pItem->drug_name = $drug_info["name"];
                    $pItem->wd_id = $drug_info["wd_id"];
                    $data['items'][] = clone $pItem;
                }


                //$data['prescription'] = $this->mopd_prescription->load($presId);
                $data['hospital'] = $this->getHospital();
                $data['date'] = date('Y-m-d H:i:s');
                $data['from_date'] = $dpd1;
                $data['to_date'] = $dpd2;
                // $data['pageHeight']   = $this->getPrescriptionPageHeight(
                //  $data['patient'], $data['hospital'], $data['date'], $data['items'], $data['prescription']
                // );
                $this->load->vars($data);
                $this->load->view('pdf/drugs_dispensed');
                break;
        }
    }

    public function MyRegistration($type, $from = null, $to = null) {

        switch ($type) {
            case 'view':
                $data['title'] = 'Daily Registration';
                $data['url'] = site_url("report/pdf/MyRegistration/print");
                $data['id'] = uniqid("_");
                $data['description'] = 'Daily Patient Registration';
                $this->load->vars($data);
                $this->load->view('date_selector');
                break;
            case 'print':
                $data['hospital'] = $this->getHospital();
                $data['from_date'] = $from;
                $this->load->vars($data);
                $this->load->view('pdf/myregistration');
                break;
        }
    }

    public function Myprescription($type, $from = null, $to = null) {

        switch ($type) {
            case 'view':
                $data['title'] = 'Daily Prescription';
                $data['url'] = site_url("report/pdf/Myprescription/print");
                $data['id'] = uniqid("_");
                $data['description'] = 'Prescription Daily';
                $this->load->vars($data);
                $this->load->view('date_selector');
                break;
            case 'print':
                $data['from_date'] = $from;
                $this->load->vars($data);
                $this->load->view('pdf/myprescription');
                break;
        }
    }

    public function DrugRequestfromphamacy($type, $from = null, $to = null) {

        switch ($type) {
            case 'view':
                $data['title'] = 'Drug Request From Phamacy';
                $data['url'] = site_url("report/pdf/drugrequestfromphamacy/print");
                $data['id'] = uniqid("_");
                $data['description'] = 'Drug Request From Phamacy';
                $this->load->vars($data);
                $this->load->view('date_range_selector');
                break;
            case 'print':
                $data['from_date'] = $from;
                $data['to_date'] = $to;
                $this->load->vars($data);
                $this->load->view('pdf/drugrequestfromphamacy');
                break;
        }
    }

    public function DrugReturnfromphamacy($type, $from = null, $to = null) {

        switch ($type) {
            case 'view':
                $data['title'] = 'Drug Request From Phamacy';
                $data['url'] = site_url("report/pdf/drugreturnfromphamacy/print");
                $data['id'] = uniqid("_");
                $data['description'] = 'Drug Return From Phamacy';
                $this->load->vars($data);
                $this->load->view('date_range_selector');
                break;
            case 'print':
                $data['from_date'] = $from;
                $data['to_date'] = $to;
                $this->load->vars($data);
                $this->load->view('pdf/drugreturnfromphamacy');
                break;
        }
    }

    public function UserpharmacyBalance($type, $from = null, $to = null) {

        switch ($type) {
            case 'view':
                $data['title'] = 'Daily drugs dispensed';
                $data['url'] = site_url("report/pdf/UserpharmacyBalance/print");
                $data['id'] = uniqid("_");
                $data['description'] = 'Drugs dispensed daily';
                $this->load->vars($data);
                $this->load->view('user_selector');
                break;
            case 'print':
                $data['from_date'] = $from;
                $this->load->vars($data);
                $this->load->view('pdf/userpharmacy_balance');
                break;
        }
    }

    /**
     * @param      $type
     * @param null $from
     * @param null $to
     * pharmacy current stock report
     */
    public function pharmacyCurrentStock($type, $stockId = null) {

        switch ($type) {
            case 'view':
                $data['title'] = 'Current Stock Balance';
                $data['url'] = site_url("report/pdf/pharmacyCurrentStock/print");
                $data['id'] = uniqid("_");
                $query = $this->db->get("drug_stock");
                $options = "<select id='stock_from'>";
                foreach ($query->result() as $row) {
                    $options .= "<option value='" . $row->drug_stock_id . "'>" . $row->name . "</option>";
                }
                $options .= "</select>";
                $data['stocks'] = $options;
                $this->load->vars($data);
                $this->load->view('current_stock_selector');
                break;
            case 'print':
                $data['hospital'] = $this->getHospital();
                $data['drug_stock'] = $this->db->get_where("drug_stock", array("drug_stock_id" => $stockId))->row();

                $this->db->select("who_drug.name,who_drug.formulation,who_drug.dose,drug_count.who_drug_count");
                $this->db->from("drug_count");
                $this->db->join("who_drug", "drug_count.who_drug_id=who_drug.wd_id");
                $this->db->where("drug_count.drug_stock_id", $stockId);
                $this->db->where("drug_count.Active", 1);
                if ($this->session->userdata("UserGroup") == 'Pharm') {
                    $this->db->where("who_drug.countable", 1);
                }

                $data["query"] = $this->db->get();
                $this->load->vars($data);
                $this->load->view('pdf/pharmacy_current_stock');
                break;
        }
    }

    public function order($type, $appId) {

        switch ($type) {
            case 'print':
                $data['hospital'] = $this->getHospital();
                $data['appId'] = $appId;
                $config = $this->config->item('report');
                $data['token_text'] = $config['token_text'];
                $this->load->vars($data);
                $view = $this->getConfig('lab_order', 'view', 'order');
                $this->load->view(sprintf('pdf/%s', $view));
                break;
        }
    }

    /**
     * @param      $type
     * @param null $ops
     * pharmacy order drugs from stock
     */
    public function drugOrder($type = 'view', $minStock = 'null', $stockId = 'null') {

        switch ($type) {
            case 'view':
                $data['title'] = 'Create Drug Order';
                $data['url'] = site_url("report/pdf/drugOrder/print");
                $data['id'] = uniqid("_");

                $query = $this->db->get("drug_stock");
                $options = "<select id='stock_from'>";
                foreach ($query->result() as $row) {
                    $options .= "<option value='" . $row->drug_stock_id . "'>" . $row->name . "</option>";
                }
                $options .= "</select>";
                $data['stocks'] = $options;
                $this->load->vars($data);
                $this->load->view('stock_selector');
                break;
            case 'print':
                $data['hospital'] = $this->getHospital();
                $data['drug_stock'] = $this->db->get_where("drug_stock", array("drug_stock_id" => $stockId))->row();

                $this->db->select("who_drug.name,who_drug_count");
                $this->db->from("drug_count");
                $this->db->join("who_drug", "drug_count.who_drug_id=who_drug.wd_id");
                $this->db->where("drug_stock_id", $stockId);
                $this->db->where("who_drug.Active", 1);
                $this->db->where("who_drug_count <", $minStock);
                $data["query"] = $this->db->get();
                $data["min_stock"] = $minStock;
                $this->load->vars($data);
                $this->load->view('pdf/pharmacy_drug_order');
                break;
        }
    }

    /**
     * @param      $type
     * @param null $from
     * @param null $to
     * print previous prescriptions by date
     */
    public function prescriptions($type, $from = null, $to = null) {

        switch ($type) {
            case 'view':
                $data['title'] = 'OPD Prescriptions';
                $data['url'] = site_url("report/pdf/prescriptions/print");
                $data['id'] = uniqid("_");
//                $data['description'] = 'OPD';
                $this->load->vars($data);
                $this->load->view('date_range_selector');
                break;
            case 'print':
                $data['from_date'] = $from;
                $data['to_date'] = $to;
                $data['hospital'] = $this->getHospital();
                $this->load->vars($data);
                $this->load->view('pdf/clinical_prescriptions');
                break;
        }
    }

    public function myprescriptions($type, $from = null, $to = null) {

        switch ($type) {
            case 'view':
                $data['title'] = 'OPD Prescriptions';
                $data['url'] = site_url("report/pdf/myprescriptions/print");
                $data['id'] = uniqid("_");
//                $data['description'] = 'OPD';
                $this->load->vars($data);
                $this->load->view('date_range_selector');
                break;
            case 'print':
                $data['from_date'] = $from;
                $data['to_date'] = $to;
                $data['hospital'] = $this->getHospital();
                $this->load->vars($data);
                $this->load->view('pdf/myclinical_prescriptions');
                break;
        }
    }

    /**
     * @param      $type
     * @param null $from
     * @param null $to
     * print previous prescriptions by drug
     */
    public function prescriptionsByDrug($type, $from = null, $to = null) {

        switch ($type) {
            case 'view':
                $data['title'] = 'Prescription By Drug';
                $data['url'] = site_url("report/pdf/prescriptionsByDrug/print");
                $data['id'] = uniqid("_");
                $data['description'] = 'Print prescription by drug.';
                $this->load->vars($data);
                $this->load->view('date_range_selector');
                break;
            case 'print':
                $data['from_date'] = $from;
                $data['to_date'] = $to;
                $data['hospital'] = $this->getHospital();
                $this->load->vars($data);
                $this->load->view('pdf/drug_statistics');
                break;
        }
    }

    public function myprescriptionsByDrug($type, $from = null, $to = null) {

        switch ($type) {
            case 'view':
                $data['title'] = 'Prescription By Drug';
                $data['url'] = site_url("report/pdf/myprescriptionsByDrug/print");
                $data['id'] = uniqid("_");
                $data['description'] = 'Print prescription by drug.';
                $this->load->vars($data);
                $this->load->view('date_range_selector');
                break;
            case 'print':
                $data['from_date'] = $from;
                $data['to_date'] = $to;
                $data['hospital'] = $this->getHospital();
                $this->load->vars($data);
                $this->load->view('pdf/mydrug_statistics');
                break;
        }
    }

    /**
     * @param      $type
     * @param null $from
     * @param null $to
     * print encounter stats
     */
    public function encounters($type, $from = null, $to = null) {

        switch ($type) {
            case 'view':
                $data['title'] = 'Encounter Statistics';
                $data['url'] = site_url("report/pdf/encounters/print");
                $data['id'] = uniqid("_");
                $data['description'] = 'Print the encounter statistics in given date period.';
                $this->load->vars($data);
                $this->load->view('date_range_selector');
                break;
            case 'print':
                $data['from_date'] = $from;
                $data['to_date'] = $to;
                $data['hospital'] = $this->getHospital();
                $this->load->vars($data);
                $this->load->view('pdf/patient_registry');
                break;
        }
    }

    /**
     * @param      $type
     * @param null $from
     * @param null $to
     * print visit details
     */
    public function visitDetails($type, $from = null, $to = null) {

        switch ($type) {
            case 'view':
                $data['title'] = 'Visit Details';
                $data['url'] = site_url("report/pdf/visitDetails/print");
                $data['id'] = uniqid("_");
                $data['description'] = 'Print the visit details in given date period.';
                $this->load->vars($data);
                $this->load->view('date_range_selector');
                break;
            case 'print':
                $data['from_date'] = $from;
                $data['to_date'] = $to;
                $data['hospital'] = $this->getHospital();
                $this->load->vars($data);
                $this->load->view('pdf/patient_opd_registry');
                break;
        }
    }

    /**
     * @param      $type
     * @param null $from
     * @param null $to
     * print visit details
     */
    public function visitDetailsILI($type, $from = null, $to = null) {

        switch ($type) {
            case 'view':
                $data['title'] = 'Visit Details';
                $data['url'] = site_url("report/pdf/visitDetailsILI/print");
                $data['id'] = uniqid("_");
                $data['description'] = 'Print the visit details in given date period.';
                $this->load->vars($data);
                $this->load->view('date_range_selector');
                break;
            case 'print':
                $data['from_date'] = $from;
                $data['to_date'] = $to;
                $data['hospital'] = $this->getHospital();
                $this->load->vars($data);
                $this->load->view('pdf/patient_opd_registry_ILI');
                break;
        }
    }
    
    public function traumaSurveillance($type, $from = null, $to = null)
    {

        switch ($type) {
            case 'view':
                $data['title']       = 'Trauma Surveillance';
                $data['url']         = site_url("report/pdf/traumaSurveillance/print");
                $data['id']          = uniqid("_");
                $data['description'] = 'Print the Trauma Surveillance in given date period.';
                $this->load->vars($data);
                $this->load->view('date_range_selector');
                break;
            case 'print':
                $data['from_date'] = $from;
                $data['to_date']   = $to;
                $data['hospital']  = $this->getHospital();
                $this->load->vars($data);
                $this->load->view('pdf/traumaSurveillance');
                break;
        }
    }  

     public function traumaDataBank($type, $from = null, $to = null)
    {

        switch ($type) {
            case 'view':
                $data['title']       = 'Trauma Data Bank';
                $data['url']         = site_url("report/pdf/traumaDataBank/print");
                $data['id']          = uniqid("_");
                $data['description'] = 'Print the Trauma Data Bank in given date period.';
                $this->load->vars($data);
                $this->load->view('date_range_selector');
                break;
            case 'print':
                $data['from_date'] = $from;
                $data['to_date']   = $to;
                $data['hospital']  = $this->getHospital();
                $this->load->vars($data);
                $this->load->view('pdf/traumaDataBank');
                break;
        }
    } 
    /**
     * @param      $type
     * @param null $from
     * @param null $to
     * @param null $vtype
     * @param null $sort
     * visit complaints during given time period
     */
    public function visitComplaints($type, $from = null, $to = null, $vtype = null, $sort = null) {

        switch ($type) {
            case 'view':
                $data['title'] = 'Visit Complaints';
                $data['url'] = site_url("report/pdf/visitComplaints/print");
                $data['id'] = uniqid("_");
                $data['description'] = 'Print the visit complaints in given date period.';
                $this->load->vars($data);
                $this->load->view('visit_complaints_selector');
                break;
            case 'print':
                $data['from_date'] = $from;
                $data['to_date'] = $to;
                $data['visitType'] = $vtype;
                $data['sort'] = $sort;
                $data['hospital'] = $this->getHospital();
                $this->load->vars($data);
                $this->load->view('pdf/visit_complaints_treated');
                break;
        }
    }
    
        public function InjectionDetails($type, $from = null, $to = null) {

        switch ($type) {
            case 'view':
                $data['title'] = 'Injection Details';
                $data['url'] = site_url("report/pdf/InjectionDetails/print");
                $data['id'] = uniqid("_");
                $data['description'] = 'Print the Injection details in given date period.';
                $this->load->vars($data);
                $this->load->view('date_range_selector');
                break;
            case 'print':
                $data['from_date'] = $from;
                $data['to_date'] = $to;
                $data['hospital'] = $this->getHospital();
                $this->load->vars($data);
                $this->load->view('pdf/injection_details');
                break;
        }
    }
    
           public function InjectionGiven($type, $from = null, $to = null) {

        switch ($type) {
            case 'view':
                $data['title'] = 'Injection Given';
                $data['url'] = site_url("report/pdf/InjectionGiven/print");
                $data['id'] = uniqid("_");
                $data['description'] = 'Print the Injection Given in given date period.';
                $this->load->vars($data);
                $this->load->view('date_range_selector');
                break;
            case 'print':
                $data['from_date'] = $from;
                $data['to_date'] = $to;
                $data['hospital'] = $this->getHospital();
                $this->load->vars($data);
                $this->load->view('pdf/injection_given');
                break;
        }
    }

    /**
     * @param $type
     * @param $pid
     * print patient slip
     */
    public function patientSlip($type, $pid) {

        switch ($type) {
            case 'print':
                $data['hospital'] = $this->getHospital();
                $data['pid'] = $pid;
                $this->load->vars($data);
                $this->load->view('pdf/patient_slip');
                break;
        }
    }

    /**
     * @param $type
     * @param $pid
     * print patient card with bar codes
     */
    public function patientCard($type, $pid) {

        switch ($type) {
            case 'print':
                $data['hospital'] = $this->getHospital();
                $data['pid'] = $pid;
                $this->load->vars($data);
                $this->load->view('pdf/patient_cards');
                break;
        }
    }

    /**
     * @param $type
     * @param $pid
     * Print patient visit summery
     */
    public function patientVisitSummary($type, $pid, $opdId) {

        switch ($type) {
            case 'print':
                $data['hospital'] = $this->getHospital();
                $data['pid'] = $pid;
                $this->load->model('mpersistent');
                $this->load->model('mopd');
                $this->load->model('mpatient');
                $data["opd_visits_info"] = $this->mopd->get_info($opdId);
                $visit_date = $data["opd_visits_info"]["DateTimeOfVisit"];
                $today = date("Y-m-d H:i:s");
                $diff = abs(strtotime($today) - strtotime($visit_date));
                $years = floor($diff / (365 * 60 * 60 * 24));
                $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
                ;
                $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
                $data["opd_visits_info"]["days"] = $days + $months * 30 + $years * 365;
                if (isset($data["opd_visits_info"]["PID"])) {
                    $data["patient_info"] = $this->mpersistent->open_id($data["opd_visits_info"]["PID"], "patient", "PID");
                    $data["patient_allergy_list"] = $this->mpatient->get_allergy_list($data["opd_visits_info"]["PID"]);
                    $data["patient_exams_list"] = $this->mpatient->get_exams_list($data["opd_visits_info"]["PID"]);
                    $data["patient_history_list"] = $this->mpatient->get_history_list($data["opd_visits_info"]["PID"]);
                    $data["patient_lab_order_list"] = $this->mpatient->get_lab_order_list($data["opd_visits_info"]["PID"]);
                    $data["patient_prescription_list"] = $this->mpatient->get_prescription_list($opdId);
                    $data["patient_treatment_list"] = $this->mpatient->get_treatment_list($opdId);
                    $data["previous_injection_list"] = $this->mpatient->get_previous_injection_list($data["opd_visits_info"]["PID"]);

                    if (is_array($data["patient_lab_order_list"])) {
                        $this->load->model('mlaboratory');
                        foreach ($data["patient_lab_order_list"] as $labOrder) {
                            if ($labOrder['Status'] == 'Pending') {
                                continue;
                            }
                            $data["orederd_test_list"][$labOrder['TestGroupName']] = $this->mlaboratory->get_ordered_lab_test($labOrder['LAB_ORDER_ID']);
                        }
                    }

                    if ($prescriptions = $data["patient_prescription_list"]) {
                        foreach ($prescriptions as $prescription) {
                            if ($prescription['Status'] == 'Pending') {
                                continue;
                            }
                            $items = $this->mopd->get_prescribe_items($prescription['PRSID']);
                            if (isset($items)) {
                                for ($i = 0; $i < count($items); ++$i) {
                                    if ($items[$i]["drug_list"] == "who_drug") {
                                        $drug_info = $this->mpersistent->open_id($items[$i]["DRGID"], "who_drug", "wd_id");
                                        $data["prescribe_items_list"][$prescription['CreateDate']][$i]["drug_name"] = isset($drug_info["name"]) ? $drug_info["name"] : '';
                                        $data["prescribe_items_list"][$prescription['CreateDate']][$i]["formulation"] = isset($drug_info["formulation"]) ? $drug_info["formulation"] : '';
                                        $data["prescribe_items_list"][$prescription['CreateDate']][$i]["dose"] = isset($drug_info["dose"]) ? $drug_info["dose"] : '';
                                    } else {
                                        //for old version drugs comes from table "drugs"
                                        $drug_info = $this->mpersistent->open_id($items[$i]["DRGID"], "drugs", "DRGID");
                                        $data["prescribe_items_list"][$prescription['CreateDate']][$i]["drug_name"] = isset($drug_info["Name"]) ? $drug_info["Name"] : '';
                                        $data["prescribe_items_list"][$prescription['CreateDate']][$i]["formulation"] = ' ';
                                        $data["prescribe_items_list"][$prescription['CreateDate']][$i]["dose"] = ' ';
                                    }
                                    $data["prescribe_items_list"][$prescription['CreateDate']][$i]["dosage"] = $items[$i]['Dosage'];
                                    $data["prescribe_items_list"][$prescription['CreateDate']][$i]["how_long"] = $items[$i]['HowLong'];
                                    $data["prescribe_items_list"][$prescription['CreateDate']][$i]["frequency"] = $items[$i]['Frequency'];
                                    $data["prescribe_items_list"][$prescription['CreateDate']][$i]["date"] = $items[$i]['Frequency'];
                                }
                            }
                        }
                    }
                }
                $this->load->vars($data);
                $this->load->view('pdf/visit_summary');
                break;
        }
    }

    public function patientSummery($type, $pid) {

        switch ($type) {
            case 'print':
                $data['hospital'] = $this->getHospital();
                $data['pid'] = $pid;
                $this->load->vars($data);
                $this->load->view('pdf/patient_summery');
                break;
        }
    }

    /**
     * @param $type
     * @param $aid
     * print admission bht of $aid
     */
    public function admissionBHT($type, $aid) {

        switch ($type) {
            case 'print':
                $data['hospital'] = $this->getHospital();
                $data['adminId'] = $aid;
                $this->load->vars($data);
                $this->load->view('pdf/admission_bht');
                break;
        }
    }

    /**
     * @param $type
     * @param $aid
     * print admission transfer letter
     */
    public function admissionTransfer($type, $aid) {

        switch ($type) {
            case 'print':
                $data['hospital'] = $this->getHospital();
                $data['adminId'] = $aid;
                $this->load->vars($data);
                $this->load->view('pdf/admission_transfer');
                break;
        }
    }

    /**
     * @param $type
     * @param $aid
     * print admission transfer letter
     */
    public function admissionDischarge($type, $aid) {

        switch ($type) {
            case 'print':
                $data['hospital'] = $this->getHospital();
                $data['adminId'] = $aid;
                $this->load->vars($data);
                $this->load->view('pdf/discharge_ticket');
                break;
        }
    }

    /**
     * @param $type
     * @param $opdId
     * print opd prescription on patient overview
     */
    public function opdPrescription($type, $prisId) {

        switch ($type) {
            case 'print':
                $this->load->model('mpersistent');
                $this->load->model('mopd');
                $this->load->model('mpatient');
                $this->load->helper('string');
                $data['hospital'] = $this->getHospital();
                $data['prisId'] = $prisId;
                $data["opd_presciption_info"] = $this->mpersistent->open_id($prisId, "opd_presciption", "PRSID");
                $data["prescribe_items_list"] = $this->mopd->get_prescribe_items($prisId);
                if (isset($data["prescribe_items_list"])) {
                    for ($i = 0; $i < count($data["prescribe_items_list"]); ++$i) {
                        if ($data["prescribe_items_list"][$i]["drug_list"] == "who_drug") {
                            $drug_info = $this->mpersistent->open_id(
                                    $data["prescribe_items_list"][$i]["DRGID"], "who_drug", "wd_id"
                            );
                        }
                        $data["prescribe_items_list"][$i]["drug_name"] = isset($drug_info["name"]) ? $drug_info["name"] : '';
                    }
                }
                if ($data["opd_presciption_info"]["OPDID"] > 0) {
                    $data["opd_visits_info"] = $this->mopd->get_info($data["opd_presciption_info"]["OPDID"]);
                }
                if ($data["opd_visits_info"]["PID"] > 0) {
                    $data["patient"] = $this->mpersistent->open_id(
                            $data["opd_visits_info"]["PID"], "patient", "PID"
                    );
                }
                $this->load->vars($data);
                $this->load->view('pdf/opd_prescription');
                break;
        }
    }

    /**
     * @param $type
     * @param $opdId
     * print opd lab test on patient overview
     */
    public function opdLabtests($type, $opdId) {

        switch ($type) {
            case 'print':
                $data['hospital'] = $this->getHospital();
                $data['opdId'] = $opdId;
                $this->load->vars($data);
                $this->load->view('pdf/opd_diagnostic_tests');
                break;
        }
    }

    /**
     * @param     $type
     * @param int $year
     * @param int $quarter
     * print hospital immr for given year and quarter
     */
    public function immr($type, $year = 2011, $quarter = 1) {

        switch ($type) {
            case 'print':
                $data['hospital'] = $this->getHospital();
                $data['hospitalId'] = $this->session->userdata('HID');
                $data['year'] = $year;
                $data['quarter'] = $quarter;
                $this->load->vars($data);
                $this->load->view('pdf/immr');
                break;
            case 'view':
                $data['title'] = 'Hospital IMMR';
                $data['url'] = site_url("report/pdf/immr/print");
                $data['id'] = uniqid("_");
                $data['description'] = 'Print hospital immr report.';
                $this->load->helper('hdate');
                $data['quarter'] = currentQuarter();
                $this->load->vars($data);
                $this->load->view('immr_info_selector');
                break;
        }
    }

    public function hospitalPerformance($type, $from = null, $to = null) {

        switch ($type) {
            case 'view':
                $data['title'] = 'Hospital performances';
                $data['url'] = site_url("report/pdf/hospitalPerformance/print");
                $data['id'] = uniqid("_");
//                $data['description'] = 'Print the visit details in given date period.';
                $this->load->vars($data);
                $this->load->view('date_range_selector');
                break;
            case 'print':
                $data['from_date'] = $from;
                $data['to_date'] = $to;
                $data['hospital'] = $this->getHospital();
                $this->load->vars($data);
                $this->load->view('pdf/hospital_indicator');
                break;
        }
    }

    /**
     * @param $type
     * @param $notificationId
     */
    public function notification($type, $notificationId) {

        switch ($type) {
            case 'print':
                $data = array();
                $notification = $this->load->model('mnotification');
                $notification->load($notificationId);
                $data['notification'] = $notification;
                if ($notification->getValue("Episode_Type") == 'admission') {
                    $admid = $notification->getValue("EPISODEID");
                    $admission = $this->load->model('madmission', 'admission');
                    $admission->openId($admid);
                    $data['admission'] = $admission;
                    $patient = $this->load->model('mpatient', 'patient')->load($admission->PID);
                    $this->patient = $patient;
                    $ward = $this->load->model('mward', 'ward')->OpenId($admission->getValue("Ward"));
                    $data['ward'] = $ward;
                    $doctor = $this->load->model('muser', 'doctor');
                    $doctor->openId($notification->getValue("ConfirmedBy"));
                    $data['epicode_type'] = "Admission";
                    $data['subject'] = $notification->getValue("Disease") . " in " . $patient->getValue("Address_Village")
                            . " (NOTIFICATION)";
                } else {
                    if ($notification->getValue("Episode_Type") == 'opd') {
                        $opdid = $notification->getValue("EPISODEID");
                        $opd = $this->load->model('mopd', 'opd');
                        $opd->openId($opdid);
                        $data['opd'] = $opd;
                        $data['epicode_type'] = "Opd";
                        $patient = $this->load->model('mpatient', 'patient')->load($opd->PID);
                        $doctor = $this->load->model('muser', 'doctor');
                        $doctor->openId($notification->getValue("ConfirmedBy"));
                    } else {
                        echo " Episode not found";
                    }
                }
                $data['doctor'] = $doctor;
                $data['subject'] = $notification->getValue("Disease") . " in " . $patient->getValue("Address_Village")
                        . " (NOTIFICATION)";
                $data['hospital'] = $this->load->model('mhospital', 'hospital')->load($patient->HID);
                $data['patient'] = $patient;
                if ($notification->getValue("LabConfirm") == 1) {
                    $pat_lab_d = "Yes";
                } else {
                    $pat_lab_d = "No";
                }
                $data['pat_lab_d'] = $pat_lab_d;
                $data['user'] = $this->load->model('muser', 'user')->load($this->session->userdata('UID'));
                $this->load->vars($data);
                $this->load->view('pdf/notification');
                break;
        }
    }

    /**
     * @param $type
     * @param $opdId
     */
    public function clinicBook($type, $opdId) {

        switch ($type) {
            case 'print':
                $this->load->model('mpersistent');
                $this->load->model('mopd');
                $this->load->model('mpatient');
                $data["opd_visits_info"] = $this->mopd->get_info($opdId);

                if (isset($data["opd_visits_info"]["PID"])) {
                    $data["patient_info"] = $this->mpersistent->open_id(
                            $data["opd_visits_info"]["PID"], "patient", "PID"
                    );
                    $data["patient_allergy_list"] = $this->mpatient->get_allergy_list(
                            $data["opd_visits_info"]["PID"]
                    );
                    $data["patient_exams_list"] = $this->mpatient->get_exams_list(
                            $data["opd_visits_info"]["PID"]
                    );
                    $data["patient_history_list"] = $this->mpatient->get_history_list(
                            $data["opd_visits_info"]["PID"]
                    );
                    $data["patient_lab_order_list"] = $this->mpatient->get_lab_order_list(
                            $data["opd_visits_info"]["PID"]
                    );
                    $data["patient_prescription_list"] = $this->mpatient->get_prescription_list($opdId);
                    $data["patient_treatment_list"] = $this->mpatient->get_treatment_list($opdId);
                    foreach ($data["patient_prescription_list"] as $prescription) {
                        $data["prescribe_items_list"][$prescription['PRSID']] = $this->mopd->get_prescribe_items(
                                $prescription['PRSID']
                        );
                    }
                }
                $this->load->vars($data);
                $this->load->view('pdf/clinic_book');
                break;
        }
    }

    /**
     * @param $type
     * @param $appId
     */
    public function appointment($type, $appId) {

        switch ($type) {
            case 'print':
                $data['hospital'] = $this->getHospital();
                $data['appId'] = $appId;
                $config = $this->config->item('report');
                $data['token_text'] = $config['token_text'];
                $this->load->vars($data);
                $this->load->view('pdf/token');
                break;
            case 'auto_print': //Sachinda for silent printing without dialog box.
                $data['hospital'] = $this->getHospital();
                $data['appId'] = $appId;
                $config = $this->config->item('report');
                $data['token_text'] = $config['token_text'];
                $this->load->vars($data);
                $this->load->view('pdf/token_kiosk');
                break;
        }
    }

    /**
     * @param $type
     */
    public function outsidePrescription($type) {

        switch ($type) {
            case 'print':
                $items = $this->input->get('print');
                $pId = $this->input->get('pid');
                $this->load->model('mpatient');
                $this->load->model('mprescribe_items');
                $this->load->model('mpersistent');
                $this->load->model('mopd_prescription');
                $data['patient'] = $this->mpatient->load($pId);

                if (!$items) {
                    echo 'Please select items to print';
                    exit;
                }
                if (!isset($data['patient'])) {
                    echo 'Patient doesn\'t exixts';
                    exit;
                }

                foreach ($items as $id) {
                    unset($pItem);
                    $pItem = $this->mprescribe_items->load($id);
                    $presId = $pItem->PRES_ID;
                    // if ($pItem->drug_list == "who_drug") {
                    $drug_info = $this->mpersistent->open_id(
                            $pItem->DRGID, "who_drug", "wd_id"
                    );

                    //}
                    $pItem->drug_name = $drug_info["name"];
                    $pItem->drug_dose = $drug_info["dose"];
                    $pItem->drug_formulation = $drug_info["formulation"];
                    $data['items'][] = clone $pItem;
                }

                $data['prescription'] = $this->mopd_prescription->load($presId);
                $data['hospital'] = $this->getHospital();
                $data['date'] = date('Y-m-d H:i:s');
                $data['pageHeight'] = $this->getPrescriptionPageHeight(
                        $data['patient'], $data['hospital'], $data['date'], $data['items'], $data['prescription']
                );
                $this->load->vars($data);
                $this->load->view('pdf/prescription');
                break;
        }
    }

    public function ClinicoutsidePrescription($type) {
        //$dept='cln';
        switch ($type) {
            case 'print':
                $items = $this->input->get('print');
                $pId = $this->input->get('pid');
                $this->load->model('mpatient');
                $this->load->model('mprescribe_items');
                $this->load->model('mpersistent');
                $this->load->model('mopd_prescription');
                $data['patient'] = $this->mpatient->load($pId);

                if (!$items) {
                    echo 'Please select items to print';
                    exit;
                }
                if (!isset($data['patient'])) {
                    echo 'Patient doesn\'t exixts';
                    exit;
                }

                foreach ($items as $id) {
                    unset($pItem);
                    $pItem = $this->mprescribe_items->loadcln($id);
                    $presId = $pItem->clinic_prescription_id;
                    //if ($pItem->drug_list == "who_drug") {
                    $drug_info = $this->mpersistent->open_id(
                            $pItem->DRGID, "who_drug", "wd_id"
                    );

                    // }
                    $pItem->drug_name = $drug_info["name"];
                    $pItem->drug_dose = $drug_info["dose"];
                    $pItem->drug_formulation = $drug_info["formulation"];
                    $data['items'][] = clone $pItem;
                }


                $data['prescription'] = $this->mopd_prescription->loadcln($presId);
                $data['hospital'] = $this->getHospital();
                $data['date'] = date('Y-m-d H:i:s');
                $data['pageHeight'] = $this->getPrescriptionPageHeight(
                        $data['patient'], $data['hospital'], $data['date'], $data['items'], $data['prescription']
                );
                $this->load->vars($data);
                $this->load->view('pdf/prescription');
                break;
        }
    }

    /**
     * @param $type
     * @param $date
     */
    public function dailyVisits($type, $date) {

        switch ($type) {
            case 'print':
                $data['hospital'] = $this->getHospital();
                $data['date'] = $date;
                $this->load->vars($data);
                $this->load->view('pdf/daily_visits');
                break;
        }
    }

    /**
     * @param $type
     * @param $date
     */
    public function dailyReg($type, $date) {

        switch ($type) {
            case 'print':
                $data['hospital'] = $this->getHospital();
                $data['date'] = $date;
                $this->load->vars($data);
                $this->load->view('pdf/daily_reg');
                break;
        }
    }

    /**
     * @param $type
     * @param $date
     */
    public function dailyAdmissions($type, $date) {

        switch ($type) {
            case 'print':
                $data['hospital'] = $this->getHospital();
                $data['date'] = $date;
                $this->load->vars($data);
                $this->load->view('pdf/daily_admissions');
                break;
        }
    }

    /**
     * @param $type
     * @param $date
     */
    public function dailyDischarges($type, $date) {

        switch ($type) {
            case 'print':
                $data['hospital'] = $this->getHospital();
                $data['date'] = $date;
                $this->load->vars($data);
                $this->load->view('pdf/daily_discharges');
                break;
        }
    }

    /**
     * @param $type
     * @param $date
     */
   /* public function dailyClinics($type, $date) {

        switch ($type) {
            case 'print':
                $data['hospital'] = $this->getHospital();
                $data['date'] = $date;
                $query = "SELECT
                              qu_quest_answer.*,
                              concat(p.Personal_Title, ' ', p.Full_Name_Registered) AS name,
                              p.PID,p.HIN,
                              qu_questionnaire.name                                 AS qu_name,
                              c.name                                                AS clinic
                            FROM qu_quest_answer
                              LEFT JOIN `qu_questionnaire` ON qu_questionnaire.qu_questionnaire_id = qu_quest_answer.qu_questionnaire_id
                              LEFT JOIN clinic_patient AS cp
                                ON qu_quest_answer.link_id = cp.clinic_patient_id
                              LEFT JOIN clinic AS c
                                ON cp.clinic_id = c.clinic_id
                              LEFT JOIN patient AS p
                                ON cp.PID = p.PID
                            WHERE (1 = 1) AND (qu_quest_answer.link_type = 'clinic_patient')
                                  AND (qu_quest_answer.Active = 1) AND qu_quest_answer.CreateDate LIKE '$date%'
                            GROUP BY p.PID
                            ORDER BY p.Full_Name_Registered";

                $data['result'] = $this->db->query($query);
                $this->load->vars($data);
                $this->load->view('pdf/daily_clinics');
                break;
        }
    }*/
    
        public function dailyClinics($type, $date) {

        switch ($type) {
            case 'print':
                $data['hospital'] = $this->getHospital();
                $data['date'] = $date;
                $this->load->vars($data);
                $this->load->view('pdf/daily_clinics');
                break;
        }
    }

    /**
     * @param $type
     * @param $pid
     */
    public function clinicToken($type, $cPid, $cId) {

        switch ($type) {
            case 'print':
                $data['hospital'] = $this->getHospital();
                $this->load->model('mclinic_patient');
                $clinic = $this->mclinic_patient->load($cPid);
                $data['clinic'] = $clinic;
                $this->load->model('mpatient');
                $data['patient'] = $this->mpatient->load($clinic->PID);
                $this->load->model('mclinic');
                $data['clinic1'] = $this->mclinic->load($clinic->clinic_id);
                $this->load->vars($data);
                $this->load->view('pdf/clinic_token');
                break;
        }
    }

    /**
     * @param $type
     * @param $date
     */
    public function laboratoryTestsDone($type, $from = null, $to = null) {

        switch ($type) {
            case 'view':
                $data['title'] = 'Lab tests carried out';
                $data['url'] = site_url("report/pdf/laboratoryTestsDone/print");
                $data['id'] = uniqid("_");
                $data['description'] = 'Lab tests carried out';
                $this->load->vars($data);
                $this->load->view('date_range_selector');
                break;
            case 'print':
                $data['from_date'] = $from;
                $data['to_date'] = $to;
                $data['hospital'] = $this->getHospital();
                $this->load->vars($data);
                $this->load->view('pdf/labtests');
                break;
        }
    }

    public function ProceduresDone($type, $from = null, $to = null) {

        switch ($type) {
            case 'view':
                $data['title'] = 'Procedures Done';
                $data['url'] = site_url("report/pdf/ProceduresDone/print");
                $data['id'] = uniqid("_");
                $data['description'] = 'Procedures Done';
                $this->load->vars($data);
                $this->load->view('date_range_selector');
                break;
            case 'print':
                $data['from_date'] = $from;
                $data['to_date'] = $to;
                $data['hospital'] = $this->getHospital();
                $this->load->vars($data);
                $this->load->view('pdf/procedures_done');
                break;
        }
    }
    
        public function ECGDone($type, $from = null, $to = null) {

        switch ($type) {
            case 'view':
                $data['title'] = 'ECG Done';
                $data['url'] = site_url("report/pdf/ECGDone/print");
                $data['id'] = uniqid("_");
                $data['description'] = 'ECG Done';
                $this->load->vars($data);
                $this->load->view('date_range_selector');
                break;
            case 'print':
                $data['from_date'] = $from;
                $data['to_date'] = $to;
                $data['hospital'] = $this->getHospital();
                $this->load->vars($data);
                $this->load->view('pdf/ecg_done');
                break;
        }
    }

    public function CollectionDone($type, $from = null, $to = null) {

        switch ($type) {
            case 'view':
                $data['title'] = 'Collection Done';
                $data['url'] = site_url("report/pdf/CollectionDone/print");
                $data['id'] = uniqid("_");
                $data['description'] = 'Collection Done';
                $this->load->vars($data);
                $this->load->view('date_range_selector');
                break;
            case 'print':
                $data['from_date'] = $from;
                $data['to_date'] = $to;
                $data['hospital'] = $this->getHospital();
                $this->load->vars($data);
                $this->load->view('pdf/collection_done');
                break;
        }
    }

    public function InjectionDone($type, $from = null, $to = null) {

        switch ($type) {
            case 'view':
                $data['title'] = 'Injection Done';
                $data['url'] = site_url("report/pdf/InjectionDone/print");
                $data['id'] = uniqid("_");
                $data['description'] = 'Injection Done';
                $this->load->vars($data);
                $this->load->view('date_range_selector');
                break;
            case 'print':
                $data['from_date'] = $from;
                $data['to_date'] = $to;
                $data['hospital'] = $this->getHospital();
                $this->load->vars($data);
                $this->load->view('pdf/injection_done');
                break;
        }
    }

    public function clinicSummary($type, $clinitVisitId) {
        switch ($type) {
            case 'print':
                $data = $this->_initClinicSummary($clinitVisitId);
                $this->load->vars($data);
                $this->load->view('pdf/clinic_summary');
                break;
        }
    }

    private function _initClinicSummary($clinic_visit_id) {
        $data = array();
        if (!isset($clinic_visit_id) || (!is_numeric($clinic_visit_id))) {
            $data["error"] = "Clinic visit not found";
            $this->load->vars($data);
            $this->load->view('clinic_error');
            return;
        }
        $this->load->model('mpersistent');
        $this->load->model('mclinic');
        $this->load->model('mpatient');
        $this->load->model('mquestionnaire');
        $data["clinic_visit_info"] = $this->mpersistent->open_id($clinic_visit_id, "clinic_visits", "clinic_visits_id");
        if (empty($data["clinic_visit_info"])) {
            $data["error"] = "clinic_visit_info  not found";
            $this->load->vars($data);
            $this->load->view('clinic_error');
            return;
        }

        $data["clinic_info"] = $this->mpersistent->open_id($data["clinic_visit_info"]["clinic"], "clinic", "clinic_id");
        if (empty($data["clinic_info"])) {
            $data["error"] = "clinic_info  not found";
            $this->load->vars($data);
            $this->load->view('clinic_error');
            return;
        }
        $pid = $data["clinic_visit_info"]["PID"];

        if (isset($pid)) {
            $data["patient_info"] = $this->mpersistent->open_id($pid, "patient", "PID");
            //$data["patient_allergy_list"] = $this->mpatient->get_allergy_list($pid);
            //$data["patient_exams_list"] = $this->mpatient->get_exams_list($pid);
            //$data["patient_history_list"] = $this->mpatient->get_history_list($pid);
            //$data["patient_lab_order_list"] = $this->mpatient->get_lab_order_list($pid);
        } else {
            $data["error"] = "Clinic Patient  not found";
            $this->load->vars($data);
            $this->load->view('clinic_error');
            return;
        }
        if (empty($data["patient_info"])) {
            $data["error"] = "Clinic Patient not found";
            $this->load->vars($data);
            $this->load->view('clinic_error');
            return;
        }
        if (isset($data["patient_info"]["DateOfBirth"])) {
            $data["patient_info"]["Age"] = Modules::run('patient/get_age', $data["patient_info"]["DateOfBirth"]);
        }
        $data["patient_info"]["HIN"] = Modules::run('patient/print_hin', $data["patient_info"]["HIN"]);

        $data["patient_prescription_list"] = $this->mclinic->get_prescription_list($data["clinic_visit_info"]["clinic_visits_id"]);
        //$data["clinic_questionnaire_list"] = $this->mquestionnaire->get_questionnaire_list("patient");
        $data["patient_questionnaire_list"] = $this->mquestionnaire->get_questionnaire_list("patient");
        $data["patient_questionnaire_answer_list"] = $this->mquestionnaire->get_answer_list($data["clinic_visit_info"]["PID"], "patient");
        $data["clinic_questionnaire_list"] = $this->mquestionnaire->get_clinic_questionnaire_list($data["clinic_info"]["clinic_id"]);
        $data["clinic_previous_record_list"] = $this->mquestionnaire->get_previous_record_list($data["clinic_visit_info"]["clinic_visits_id"], "clinic_visits");
        $data["last_clinic_prsid"] = $this->mclinic->get_last_prescription_for_patient($data["clinic_visit_info"]["clinic"], $pid);  //200439 //200439 ,
        //print_r($data["last_clinic_prsid"]["PRSID"]);exit;
        //echo $data["last_clinic_prsid"]["PRSID"];
        if (isset($data["last_clinic_prsid"]["PRSID"])) {
            $data["last_clinic_prescription"] = $this->mclinic->get_drug_item_clinic($data["last_clinic_prsid"]["PRSID"]);
        }

        if (!empty($data["clinic_previous_record_list"])) {
            for ($i = 0; $i < count($data["clinic_previous_record_list"]); ++$i) {
                $data["clinic_previous_record_list"][$i]["data"] = $this->mquestionnaire->get_clinic_patient_answer_list($data["clinic_previous_record_list"][$i]["qu_quest_answer_id"]);
                if (!empty($data["clinic_previous_record_list"][$i]["data"])) {
                    for ($j = 0; $j < count($data["clinic_previous_record_list"][$i]["data"]); ++$j) {
                        if ($data["clinic_previous_record_list"][$i]["data"][$j]["question_type"] == "Select") { //answer type select
                            $ans = $this->mpersistent->open_id($data["clinic_previous_record_list"][$i]["data"][$j]["answer"], "qu_select", "qu_select_id");
                            if (isset($ans["select_text"])) {
                                $data["clinic_previous_record_list"][$i]["data"][$j]["answer"] = $ans["select_text"];
                            } else {
                                $data["clinic_previous_record_list"][$i]["data"][$j]["answer"] = '';
                            }
                        }
                        if ($data["clinic_previous_record_list"][$i]["data"][$j]["question_type"] == "MultiSelect") { //answer type multi-select
                            $user_answeres = explode(",", $data["clinic_previous_record_list"][$i]["data"][$j]["answer"]);

                            $output_answer = '';
                            for ($ua = 0; $ua < count($user_answeres); ++$ua) {
                                if ($user_answeres[$ua] > 0) {
                                    $ans = $this->mpersistent->open_id($user_answeres[$ua], "qu_select", "qu_select_id");
                                    $output_answer .= $ans["select_text"] . ', ';
                                }
                            }
                            if (isset($output_answer)) {
                                $data["clinic_previous_record_list"][$i]["data"][$j]["answer"] = $output_answer;
                            } else {
                                $data["clinic_previous_record_list"][$i]["data"][$j]["answer"] = '';
                            }
                        }

                        if ($data["clinic_previous_record_list"][$i]["data"][$j]["question_type"] == "PAIN_DIAGRAM") {
                            $data['pain_diagram_info'] = $this->mquestionnaire->get_diagram_info($data["clinic_previous_record_list"][$i]["data"][$j]["qu_question_id"]);
                            if (!empty($data['pain_diagram_info'])) {
                                //$data['clinic_diagram_info'] = $this->mpersistent->open_id($data['pain_diagram_info']["cln_diagram_id"],"clinic_diagram","clinic_diagram_id");
                                $data['diagram' . $data['clinic_previous_record_list'][$i]["data"][$j]['qu_question_id']] = $this->mpersistent->open_id($data['pain_diagram_info']["cln_diagram_id"], "clinic_diagram", "clinic_diagram_id");
                            }
                        }
                    }
                }
            }
        }
        if (!empty($data["patient_questionnaire_answer_list"])) {
            for ($i = 0; $i < count($data["patient_questionnaire_answer_list"]); ++$i) {
                $data["patient_questionnaire_answer_list"][$i]["data"] = $this->mquestionnaire->get_clinic_patient_answer_list($data["patient_questionnaire_answer_list"][$i]["qu_quest_answer_id"]);
                if (!empty($data["patient_questionnaire_answer_list"][$i]["data"])) {
                    for ($j = 0; $j < count($data["patient_questionnaire_answer_list"][$i]["data"]); ++$j) {
                        if ($data["patient_questionnaire_answer_list"][$i]["data"][$j]["question_type"] == "Select") { //answer type select
                            $ans = $this->mpersistent->open_id($data["patient_questionnaire_answer_list"][$i]["data"][$j]["answer"], "qu_select", "qu_select_id");
                            if (isset($ans["select_text"])) {
                                $data["patient_questionnaire_answer_list"][$i]["data"][$j]["answer"] = $ans["select_text"];
                            } else {
                                $data["patient_questionnaire_answer_list"][$i]["data"][$j]["answer"] = '';
                            }
                        }
                        if ($data["patient_questionnaire_answer_list"][$i]["data"][$j]["question_type"] == "MultiSelect") { //answer type multi-select
                            $user_answeres = explode(",", $data["patient_questionnaire_answer_list"][$i]["data"][$j]["answer"]);

                            $output_answer = '';
                            for ($ua = 0; $ua < count($user_answeres); ++$ua) {
                                if ($user_answeres[$ua] > 0) {
                                    $ans = $this->mpersistent->open_id($user_answeres[$ua], "qu_select", "qu_select_id");
                                    $output_answer .= $ans["select_text"] . ', ';
                                }
                            }
                            if (isset($output_answer)) {
                                $data["patient_questionnaire_answer_list"][$i]["data"][$j]["answer"] = $output_answer;
                            } else {
                                $data["patient_questionnaire_answer_list"][$i]["data"][$j]["answer"] = '';
                            }
                        }

                        if ($data["patient_questionnaire_answer_list"][$i]["data"][$j]["question_type"] == "PAIN_DIAGRAM") {
                            $data['pain_diagram_info'] = $this->mquestionnaire->get_diagram_info($data["patient_questionnaire_answer_list"][$i]["data"][$j]["qu_question_id"]);
                            if (!empty($data['pain_diagram_info'])) {
                                //$data['clinic_diagram_info'] = $this->mpersistent->open_id($data['pain_diagram_info']["cln_diagram_id"],"clinic_diagram","clinic_diagram_id");
                                $data['diagram' . $data['patient_questionnaire_answer_list'][$i]["data"][$j]['qu_question_id']] = $this->mpersistent->open_id($data['pain_diagram_info']["cln_diagram_id"], "clinic_diagram", "clinic_diagram_id");
                            }
                        }
                    }
                }
            }
        }
        $data["pid"] = $pid;
        $data["clinic_id"] = $data["clinic_visit_info"]["clinic"];
        $data["patient_pomr_list"] = $this->mpatient->get_pomr_list($pid);
        $data["hospital"] = $this->getHospital();

//        $this->load->vars($data);

        return $data;
    }

    public function admissionSummary($type, $aId) {

        switch ($type) {
            case 'print':
                $data['hospital'] = $this->getHospital();
                $data['adminId'] = $aId;
                if (!isset($aId) || (!is_numeric($aId))) {
                    $data["error"] = "Admission visit not found";
                    $this->load->vars($data);
                    $this->load->view('admission_error');

                    return;
                }
                $this->load->model('mpersistent');
                $this->load->model('madmission');
                $this->load->model('mpatient');
                $data["admission_drug_order"] = null;
                $data["admission_drug_list"] = null;
                $data["admission_info"] = $this->madmission->get_info($aId);
                $data["admission_lab_order_list"] = $this->madmission->get_lab_order_list(
                        $data["admission_info"]["ADMID"]
                );
                $data["admission_drug_order"] = $this->madmission->get_drug_order($data["admission_info"]["ADMID"]);
                if (isset($data["admission_drug_order"]["admission_prescription_id"])) {
                    $data["admission_drug_list"] = $this->madmission->get_drug_order_list(
                            $data["admission_drug_order"]["admission_prescription_id"]
                    );
                }

                if ($data["admission_info"]["PID"] > 0) {
                    $data["patient_info"] = $this->mpersistent->open_id(
                            $data["admission_info"]["PID"], "patient", "PID"
                    );
                    $data["patient_allergy_list"] = $this->mpatient->get_allergy_list($data["admission_info"]["PID"]);
                } else {
                    $data["error"] = "Admission Patient  not found";
                    $this->load->vars($data);
                    $this->load->view('admission_error');

                    return;
                }
                if (empty($data["patient_info"])) {
                    $data["error"] = "Admission Patient not found";
                    $this->load->vars($data);
                    $this->load->view('admission_error');

                    return;
                }
                if (isset($data["patient_info"]["DateOfBirth"])) {
                    $data["patient_info"]["Age"] = Modules::run(
                                    'patient/get_age', $data["patient_info"]["DateOfBirth"]
                    );
                }
                $data["PID"] = $data["admission_info"]["PID"];
                $data["ADMID"] = $aId;

                $this->load->vars($data);
                $this->load->view('pdf/admission_summary');
                break;
        }
    }

    public function printLabTests($type) {

        switch ($type) {
            case 'print':
                $data = array();
                $items = $this->input->get('print');
                $pId = $this->input->get('pid');
                if (!is_array($items) && empty($items)) {
                    echo "Please select items to print";

                    return;
                }
                $this->load->model('mpersistent');
                $this->load->model('mlaboratory');
                $this->load->model('mpatient');

                $data['patient'] = $this->mpatient->load($pId);
                if (!$data['patient']) {
                    echo "Invalid patient id";

                    return;
                }
                foreach ($items as $item) {
                    $data["orederd_test_list"][] = $this->mlaboratory->get_ordered_lab_item($item);
                }
                $data['hospital'] = $this->getHospital();
                $data['date'] = date('Y-m-d H:i:s');
                $data['pageHeight'] = $this->getLabOrderPageHeight(
                        $data['patient'], $data['hospital'], $data['date'], $data["orederd_test_list"]
                );
                $this->load->vars($data);
                $this->load->view('pdf/laborder');
                break;
        }
    }

    public function midnightCensus($type, $date) {

        switch ($type) {
            case 'print':
                $data['hospital'] = $this->getHospital();
                $data['date'] = $date;
                $this->load->model("mward");
                $data['wards'] = $this->mward->getCollection();
                $this->load->vars($data);
                $this->load->view('pdf/midnight_census');
                break;
        }
    }

    /**
     * @param $patient
     * @param $hospital
     * @param $date
     * @param $items
     * @param $prescription
     *
     * @return mixed
     */
    private function getPrescriptionPageHeight($patient, $hospital, $date, $items, $prescription) {

        $pageHeight = null;

        require_once 'application/libraries/class/MDSReporter.php';
        $pdf = new MDSReporter(array('orientation' => 'P', 'unit' => 'mm', 'format' => array(72, 210),
            'footer' => false));


        $name = $patient->Personal_Title . ' ' . $patient->Full_Name_Registered; //returns the fullname
        $reg_no = $patient->HIN;
        $gender = $patient->Gender;
        $pdf->addPage();
        $pdf->SetAutoPageBreak(false);


        $pdf->SetMargins(1, 1);
        $pdf->SetXY(0, 1);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->MultiCell(0, 2, $hospital, 0, 'R');
        $pdf->SetFont('Arial', 'BI', 5);
        $pdf->MultiCell(0, 4, "Prescription - " . $date, 0, 'R');
        $pdf->Image('images/rx.png', 0, -1, 8, 8);
        $pdf->SetFont('Arial', '', 8);
//$pdf->SetXY(8, 6);
        $pdf->MultiCell(0, 4, $name . '(' . $gender . ')', 0, 'L');
//$pdf->SetX(8);
        $pdf->MultiCell(0, 4, 'HIN: ' . $reg_no . ' Age: ' . $patient->getAge(), 0, 'L');

        $pdf->Line(5, 15, 68, 15);

        $pdf->setY(16);
        foreach ($items as $item) {
            $txt = $item->drug_name . ' ' . $item->drug_formulation . ' ' . $item->Dosage . ' ' . $item->Frequency . ' '
                    . $item->HowLong;
            $pdf->MultiCell(0, 10, $txt, 0, 'L');
        }

        $pdf->Ln();
        $pdf->MultiCell(0, 4, '.......................................................', 0, 'R');
        $pdf->MultiCell(0, 4, 'Prescribed by: ' . $prescription->PrescribeBy, 0, 'R');
//        $pdf->Output('prescription' . $date, 'I');
        $pageHeight = $pdf->GetY();
        unset($pdf);

//        echo $pageHeight;exit;
        return $pageHeight + 2;
    }

    private function getLabOrderPageHeight($patient, $hospital, $date, $orederd_test_list) {

        $pageHeight = null;

        require_once 'application/libraries/class/MDSReporter.php';
        $pdf = new MDSReporter(array('orientation' => 'P', 'unit' => 'mm', 'format' => array(72, 210),
            'footer' => false));


        $name = $patient->Personal_Title . ' ' . $patient->Full_Name_Registered; //returns the fullname
        $reg_no = $patient->HIN;
        $gender = $patient->Gender;
        $pdf->addPage();
        $pdf->SetAutoPageBreak(false);


        $pdf->SetMargins(1, 1);
        $pdf->SetXY(0, 1);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->MultiCell(0, 2, $hospital, 0, 'R');
        $pdf->SetFont('Arial', 'BI', 5);
        $pdf->MultiCell(0, 4, "Prescription - " . $date, 0, 'R');
        $pdf->Image('images/rx.png', 0, -1, 8, 8);
        $pdf->SetFont('Arial', '', 8);
//$pdf->SetXY(8, 6);
        $pdf->MultiCell(0, 4, $name . '(' . $gender . ')', 0, 'L');
//$pdf->SetX(8);
        $pdf->MultiCell(0, 4, 'HIN: ' . $reg_no . ' Age: ' . $patient->getAge(), 0, 'L');

        $pdf->Line(5, 15, 68, 15);

        $pdf->setY(16);
        $orderedBy = '';
        foreach ($orederd_test_list as $item) {
            $txt = $item->Name;
            $orderedBy = $item->CreateUser;
            $pdf->MultiCell(0, 4, $txt, 0, 'L');
        }

        $pdf->Ln();
        $pdf->MultiCell(0, 4, '.......................................................', 0, 'R');
        $pdf->MultiCell(0, 4, 'Ordered by: ' . $orderedBy, 0, 'R');
        $pageHeight = $pdf->GetY();
        unset($pdf);

//        echo $pageHeight;exit;
        return $pageHeight + 2;
    }



public function traumaStatistic($type, $TRA_OCC_ID = null,$TRA_PINJ_ID = null,$TRA_AINJ_ID = null,$TRA_CINJ_ID = null,$TRA_TRAP_RUID = null,$TRA_ACC_VID = null,$TRA_VIO_CID = null,$TRA_VIO_TID = null,$TRA_VIO_RID = null,$TRA_SII_ID = null,$TRA_OACC_ID = null)
    {

        switch ($type) {
            case 'view':
                $data['title'] = 'Trauma Statistic';
                $data['url']   = site_url("report/pdf/traumaStatistic/print");
                $data['id']    = uniqid("_");
                $query         = $this->db->get("trauma_occupation");
                $TRA_OCC_ID       = "<select id='TRA_OCC_ID'>";
                $TRA_OCC_ID .= "<option value='-1'>--select--</option>";
                foreach ($query->result() as $row) {
                    $TRA_OCC_ID .= "<option value='" . $row->TRA_OCC_ID . "'>" . $row->TRA_OCC_Name . "</option>";
                }
                $TRA_OCC_ID .= "</select>";
                $data['TRA_OCC_ID'] = $TRA_OCC_ID;
                
                $query2         = $this->db->get("trauma_place_injury");
                $options2       = "<select id='TRA_PINJ_ID'>";
                $options2 .= "<option value='-1'>--select--</option>";
                foreach ($query2->result() as $row) {
                    $options2 .= "<option value='" . $row->TRA_PINJ_ID . "'>" . $row->TRA_PINJ_Name . "</option>";
                }
                $options2 .= "</select>";
                $data['TRA_PINJ_ID'] = $options2;
                
                $query3         = $this->db->get("trauma_activity_injury");
                $options3       = "<select id='TRA_AINJ_ID'>";
                $options3 .= "<option value='-1'>--select--</option>";
                foreach ($query3->result() as $row) {
                    $options3 .= "<option value='" . $row->TRA_AINJ_ID . "'>" . $row->TRA_AINJ_Name . "</option>";
                }
                $options3 .= "</select>";
                $data['TRA_AINJ_ID'] = $options3;
                
                $query4         = $this->db->get("trauma_cause_injury");
                $options4       = "<select id='TRA_CINJ_ID'>";
                $options4 .= "<option value='-1'>--select--</option>";
                foreach ($query4->result() as $row) {
                    $options4 .= "<option value='" . $row->TRA_CINJ_ID . "'>" . $row->TRA_CINJ_Name . "</option>";
                }
                $options4 .= "</select>";
                $data['TRA_CINJ_ID'] = $options4;
                
                $query5         = $this->db->get("trauma_transport_road_user");
                $options5       = "<select id='TRA_TRAP_RUID'>";
                $options5 .= "<option value='-1'>--select--</option>";
                foreach ($query5->result() as $row) {
                    $options5 .= "<option value='" . $row->TRA_TRAP_RUID . "'>" . $row->TRA_TRAP_RUName . "</option>";
                }
                $options5 .= "</select>";
                $data['TRA_TRAP_RUID'] = $options5;     
                
                $query6         = $this->db->get("trauma_accident_vehicle");
                $options6       = "<select id='TRA_ACC_VID'>";
                $options6 .= "<option value='-1'>--select--</option>";
                foreach ($query6->result() as $row) {
                    $options6 .= "<option value='" . $row->TRA_ACC_VID . "'>" . $row->TRA_ACC_VName . "</option>";
                }
                $options6 .= "</select>";
                $data['TRA_ACC_VID'] = $options6;     
                
                $query7         = $this->db->get("trauma_violence_cause");
                $options7       = "<select id='TRA_VIO_CID'>";
                $options7 .= "<option value='-1'>--select--</option>";
                foreach ($query7->result() as $row) {
                    $options7 .= "<option value='" . $row->TRA_VIO_CID . "'>" . $row->TRA_VIO_CName . "</option>";
                }
                $options7 .= "</select>";
                $data['TRA_VIO_CID'] = $options7;     
                                
                $query8         = $this->db->get("trauma_violence_type");
                $options8       = "<select id='TRA_VIO_TID'>";
                $options8 .= "<option value='-1'>--select--</option>";
                foreach ($query8->result() as $row) {
                    $options8 .= "<option value='" . $row->TRA_VIO_TID . "'>" . $row->TRA_VIO_TName . "</option>";
                }
                $options8 .= "</select>";
                $data['TRA_VIO_TID'] = $options8;     
                                
                $query9         = $this->db->get("trauma_violence_relate");
                $options9       = "<select id='TRA_VIO_RID'>";
                $options9 .= "<option value='-1'>--select--</option>";
                foreach ($query9->result() as $row) {
                    $options9 .= "<option value='" . $row->TRA_VIO_RID . "'>" . $row->TRA_VIO_RName . "</option>";
                }
                $options9 .= "</select>";
                $data['TRA_VIO_RID'] = $options9;     
                                
                $query10         = $this->db->get("trauma_self_inflicted");
                $options10       = "<select id='TRA_SII_ID'>";
                $options10 .= "<option value='-1'>--select--</option>";
                foreach ($query10->result() as $row) {
                    $options10 .= "<option value='" . $row->TRA_SII_ID . "'>" . $row->TRA_SII_Name . "</option>";
                }
                $options10 .= "</select>";
                $data['TRA_SII_ID'] = $options10;     
                                                
                $query11         = $this->db->get("trauma_other_accident");
                $options11       = "<select id='TRA_OACC_ID'>";
                $options11 .= "<option value='-1'>--select--</option>";
                foreach ($query11->result() as $row) {                    
                    $options11 .= "<option value='" . $row->TRA_OACC_ID . "'>" . $row->	TRA_OACC_Name . "</option>";
                }
                $options11 .= "</select>";
                $data['TRA_OACC_ID'] = $options11;  
                
                
                $options12  = "<select id='TRA_SeatBelt_Helmet'>";
                $options12 .= "<option value='-1'>--select--</option>";
                $options12 .= "<option value='0'>Yes</option>";
                $options12 .= "<option value='1'>No</option>";
                $options12 .= "</select>";
                $data['TRA_SeatBelt_Helmet'] = $options12;  
                
                $options13  = "<select id='TRA_Alchol'>";
                $options13 .= "<option value='-1'>--select--</option>";
                $options13 .= "<option value='0'>Yes</option>";
                $options13 .= "<option value='1'>No</option>";
                $options13 .= "</select>";
                $data['TRA_Alchol'] = $options13; 
                
                                                                                                                                    
                $this->load->vars($data);
                $this->load->view('cusrom_selector');
                break;
            case 'print':
                
                $data['hospital']   = $this->getHospital();
                
                
                //$query=" select * from trauma_surveillance ";
                //$data['query']=" select * from trauma_surveillance ";
                        //. " where TRA_OCC_ID = ".$TRA_OCC_ID
                        //. " and TRA_PINJ_ID = ".$TRA_PINJ_ID;
                
                //$data['result']=$this->db->query($data['query']);
                
                // $this->db->select("*");
               // $this->db->from("trauma_surveillance");
                //$this->db->join("who_drug", "drug_count.who_drug_id=who_drug.wd_id");
                //$this->db->where("drug_stock_id", $stockId);
                //$this->db->where("Active", 1);
                //$data["query"] = $this->db->get();		
                //$this->load->vars($data);
                
                //$data['from_date'] = $from;
                //$data['to_date']   = $to;
                                           
                $this->load->vars($data);
                $this->load->view('pdf/traumaStatistic');
                break;
        }

    }
}

//////////////////////////////////////////

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */