<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ReportController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ReportModel');
    }


    //====================== view render ===============================//
    public function TicketRecordView()
    {
        $this->load->view('reports/TicketRecordView');
    }

    public function TicketSummaryView()
    {
        $this->load->view('reports/TicketSumView');
    }

    public function MvfRecordView()
    {
        $this->load->view('reports/MvfRecordView');
    }



    //============================== fetching =============================//
    public function FetchAllTicketRecords()
    {
        $data = $this->ReportModel->FindAllTicketRecords();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function FetchAllTicketSummary()
    {
        $data = $this->ReportModel->FindAllTicketSummary();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function FetchAllMvfRecords()
    {
        $data = $this->ReportModel->FindAllMvfRecords();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    

    //======================= operates ================================//
   
}
