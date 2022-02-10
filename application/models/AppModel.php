<?php
header('Content-Type: text/html; charset=utf-8');

class AppModel extends CI_Model
{
    //===================== Shared fetching ======================//
    public function FindOneEmp($empId)
    {
        $this->db->where('EmpCode', $empId);
        $query = $this->db->get('Employees');

        return $query->row();
    }

    public function FindOneOtherEmp($empId)
    {
        $this->db->where('EmpID', $empId);
        $query = $this->db->get('OtherEmployees');

        return $query->row();
    }

    public function FindCounter($empId, $location)
    {
        $sql = "SELECT COUNT(*) AS Counter FROM TTKTransactions WHERE TranEmpID = '{$empId}' 
                AND YEAR(TranDate) = YEAR(GETDATE()) 
                AND TranLocationCode = '{$location}'
                AND TranIsVoid = 0 ";
        $query = $this->db->query($sql);

        return $query->row('Counter');
    }

    public function FindTicketData($empId)
    {
        $this->db->select('TOP(1) *');
        $this->db->where('TranEmpID', $empId);
        $this->db->where('TranPrintState', 0);
        $this->db->where('TranDate', date('Y-m-d'));
        $this->db->where('TranIsVoid', 0);
        $this->db->order_by('TranID', 'asc');
        $query = $this->db->get('TTKTransactions');

        return $query->row();
    }

    public function FindSN()
    {
        $sql = "SELECT ISNULL(MAX(TranSN), 0) + 1 AS SN FROM MVFTransactions  ";
        $query = $this->db->query($sql);

        return $query->row('SN');
    }

    public function FindAllLicensePlates()
    {
        $query = $this->db->get('LicensePlates');
        return $query->result();
    }

    public function FindAllTrips($location)
    {
        if ($location == 0) {
            $this->db->where('TLocation', 'SR');
        } else {
            $this->db->where('TLocation', 'WC');
        }

        $query = $this->db->get('Trips');
        return $query->result();
    }

    public function FindAllVTypes()
    {
        $query = $this->db->get('VehicleTypes');
        return $query->result();
    }

    public function FindVTCode($lpCode)
    {
        $sql = "SELECT * FROM LicensePlates WHERE LPCode = '{$lpCode}' ";
        $query = $this->db->query($sql);

        return $query->row();
    }

    public function FindMvfData($tranId)
    {
        $this->db->where('TranID', $tranId);
        $query = $this->db->get('MVFTransactions');

        return $query->row();
    }

    public function FindRelatedEmp($empId)
    {
        $sql = "SELECT COUNT(*) AS CountRow FROM Controllers WHERE EmpID = '{$empId}' ";
        $data = $this->db->query($sql);

        return $data->row('CountRow');
    }



    //=================== operates events =========================//
    public function LoginValid($usr, $pwd)
    {
        $this->db->where('UserEmpCode', $usr);
        $this->db->where('UserPassword', $pwd);
        $this->db->where('UserIsActive', 1);
        $query = $this->db->get('Users');

        return $query->row();
    }

    public function FindMGroupBySession()
    {
        $this->db->where('MGroupIsActive', 1);
        $this->db->where_in('MGroupID', $this->session->userdata('userMgroupId'), false);
        $this->db->order_by('MGroupOrderBy', 'ASC');
        $qurey = $this->db->get('MenuGroups');

        return $qurey->result();
    }

    public function FindMenuBySession()
    {
        $this->db->where('MenuIsActive', 1);
        $this->db->where_in('MenuID', $this->session->userdata('userMenuId'), false);
        $this->db->order_by('MenuOrderBy', 'ASC');
        $qurey = $this->db->get('Menus');

        return $qurey->result();
    }

    public function ExecuteInsertTicket()
    {
        $data = array(
            'TranEmpID' => $this->input->post('empId'),
            'TranEmpName' => $this->input->post('empName'),
            'TranPosition' => $this->input->post('position'),
            'TranTier' => $this->input->post('tier'),
            'TranDeptCode' => $this->input->post('dept'),
            'TranDate' => date('Y-m-d'),
            'TranLocation' => $this->input->post('location'),
            'TranLocationCode' => $this->input->post('locateCode'),
            'TranCounter' => $this->input->post('counter'),
            'TranUseFor' => $this->input->post('useFor')
        );

        $this->db->insert('TTKTransactions', $data);
    }

    public function ExecuteSetPrintState($tranId)
    {
        $data = array(
            'TranPrintState' => 1
        );

        $this->db->where('TranID', $tranId);
        $this->db->update('TTKTransactions', $data);
    }

    public function ExecuteInsertMvf()
    {
        $sn = $this->input->post('sn');

        $data = array(
            'TranSN' => $sn,
            'TranDate' => $this->input->post('tDate'),
            'TranTime' => $this->input->post('tTime'),
            'TranDriverID' => $this->input->post('dvId'),
            'TranDriverName' => $this->input->post('dvName'),
            'TranDept' => $this->input->post('dept'),
            'TranNoOfPax' => $this->input->post('noOfPax'),
            'TranLPCode' => $this->input->post('lpCode'),
            'TranLPCodeDesc' => htmlspecialchars($this->input->post('lpDesc')),
            'TranVTCode' => $this->input->post('vtCode'),
            'TranRemark' => $this->input->post('remark'),
            'TranRemarkDesc' => $this->input->post('remDesc'),
            'TranPreparedBy' => $this->input->post('preparedBy')
        );

        $this->db->insert('MVFTransactions', $data);
        $lastId = $this->db->insert_id();

        $this->output->set_content_type('application/json')->set_output(json_encode($lastId));
    }
}
