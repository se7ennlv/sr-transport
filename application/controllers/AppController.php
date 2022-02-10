<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AppController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('AppModel');
        $this->load->model('UserModel');
    }


    //======================= view render =============================//
    public function LoginView()
    {
        $this->load->view('app/LoginView');
    }

    public function SRView()
    {
        $this->load->view('layout/Header');
        $this->load->view('app/SRView');
        $this->load->view('layout/JSCore');
    }

    public function WCView()
    {
        $this->load->view('layout/Header');
        $this->load->view('app/WCView');
        $this->load->view('layout/JSCore');
    }

    public function SRTicketView()
    {
        $this->load->view('app/SRTicketView');
        $this->load->view('app/AppSharedScript');
        $this->load->view('app/ModalTicketInv');
    }

    public function WCTicketView()
    {
        $this->load->view('app/WCTicketView');
        $this->load->view('app/AppSharedScript');
        $this->load->view('app/ModalTicketInv');
    }

    public function MVFTicketView($location)
    {
        $data['sn'] = $this->AppModel->FindSN();
        $data['lps'] = $this->AppModel->FindAllLicensePlates();
        $data['trips'] = $this->AppModel->FindAllTrips($location);
        $data['vts'] = $this->AppModel->FindAllVTypes();

        $this->load->view('app/MVFView', $data);
        $this->load->view('app/ModalNumeral');
        $this->load->view('app/ModalMvfInv');
    }

    

    // public function WCTicketView()
    // {
    //     $this->load->view('layout/Header');
    //     $this->load->view('app/WCTicketView');
    //     $this->load->view('app/ModalTicketInv');
    //     $this->load->view('layout/JSCore');
    //     $this->load->view('app/AppSharedScript');
    // }


    //========================= fetching ============================//
    public function FetchOneEmp($empId)
    {
        $data['emp'] = $this->AppModel->FindOneEmp($empId);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function FetchCounter($empId, $location)
    {
        $data = $this->AppModel->FindCounter($empId, $location);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function FetchOneOtherEmp($empId)
    {
        $data['emp'] = $this->AppModel->FindOneOtherEmp($empId);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function FetchTicketData($empId)
    {
        $data = $this->AppModel->FindTicketData($empId);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function FetchSN()
    {
        $data = $this->AppModel->FindSN();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function FetchVTCode($lpCode)
    {
        $data = $this->AppModel->FindVTCode($lpCode);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function FetchMvfData($tranId)
    {
        $data = $this->AppModel->FindMvfData($tranId);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function CheckRelatedEmp($empId)
    {
        $data = $this->AppModel->FindRelatedEmp($empId);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }


    //========================= operates events ============================//
    public function ExecuteLogin()
    {
        $usr = $this->input->post('Username');
        $pwd = sha1($this->input->post('Password'));
        $data = $this->UserModel->LoginValid($usr, $pwd);

        if (!empty($data)) {
            $setData = array(
                'userId' => $data->UserID,
                'userEmpId' => $data->UserEmpID,
                'userEmpCode' => $data->UserEmpCode,
                'username' => $data->UserUsername,
                'userFname' => $data->UserFname,
                'userLname' => $data->UserLname,
                'userMgroupId' => $data->UserMenuGroupID,
                'userMenuId'  => $data->UserMenuID,
                'userCreatedAt' => $data->UserCreatedAt,
                'userOrgId' => $data->UserOrgID
            );

            $this->session->set_userdata($setData);
            $this->output->set_content_type('application/json')->set_output(json_encode(array('status' => 'success', 'message' => 'Login Success')));
        } else {
            $this->output->set_content_type('application/json')->set_output(json_encode(array('status' => 'danger', 'message' => 'Incorrect username or password')));
            $array_items = array('userId' => '', 'userFname' => '', 'userLname' => '');
            $this->session->unset_userdata($array_items);
        }
    }

    public function ExecuteLogout()
    {
        $this->session->unset_userdata('userId');
        redirect('AppController/LoginView', 'refresh');

        exit;
    }

    public function InitInsertTran()
    {
        $this->AppModel->ExecuteInsertTran();
    }

    public function InitInsertTicket()
    {
        $this->AppModel->ExecuteInsertTicket();
    }

    public function InitSetPrintedState($tranId)
    {
        $this->AppModel->ExecuteSetPrintState($tranId);
    }

    public function InitInsertMvf()
    {
        $this->AppModel->ExecuteInsertMvf();
    }
}
