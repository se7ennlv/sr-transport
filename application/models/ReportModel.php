<?php

class ReportModel extends CI_Model
{

    //=================================== fetching =================================//
    public function FindAllTicketRecords()
    {
        $fDate = $this->input->post('fDate');
        $tDate = $this->input->post('tDate');
        $lc = $this->input->post('location');

        if (!empty($lc)) {
            $condition = "CONVERT(DATE, TranCreatedAt) BETWEEN '{$fDate}' AND '{$tDate}' AND TranLocationCode = '{$lc}' ";
        } else {
            $condition = "CONVERT(DATE, TranCreatedAt) BETWEEN '{$fDate}' AND '{$tDate}' ";
        }

        $this->db->where($condition, "", FALSE);
        $this->db->where('TranIsVoid', 0);
        $query = $this->db->get('TTKTransactions');

        return $query->result();
    }

    public function FindAllTicketSummary()
    {
        $fDate = $this->input->post('fDate');
        $tDate = $this->input->post('tDate');

        $sql = "SELECT TranEmpID, TranEmpName, TranPosition, TranTier, TranDeptCode, 
            (SELECT COUNT(*) FROM TTKTransactions WHERE TranLocationCode = 'SR' AND TranEmpID = trans.TranEmpID AND CONVERT(DATE, TranCreatedAt) BETWEEN '{$fDate}' AND '{$tDate}') AS SRTotal,
            (SELECT COUNT(*) FROM TTKTransactions WHERE TranLocationCode = 'WC' AND TranEmpID = trans.TranEmpID AND CONVERT(DATE, TranCreatedAt) BETWEEN '{$fDate}' AND '{$tDate}') AS WCTotal,
            (SELECT COUNT(*) FROM TTKTransactions WHERE TranEmpID = trans.TranEmpID AND CONVERT(DATE, TranCreatedAt) BETWEEN '{$fDate}' AND '{$tDate}') AS Totals
            FROM TTKTransactions trans
            WHERE CONVERT(DATE, TranCreatedAt) BETWEEN '{$fDate}' AND '{$tDate}' AND TranIsVoid = 0
            GROUP BY TranEmpID, TranEmpName, TranPosition, TranTier, TranDeptCode
            ";
        $query = $this->db->query($sql);

        return $query->result();
    }

    public function FindAllMvfRecords()
    {
        $fDate = $this->input->post('fDate');
        $tDate = $this->input->post('tDate');

        $condition = "CONVERT(DATE, TranCreatedAt) BETWEEN '{$fDate}' AND '{$tDate}' ";

        $this->db->where($condition, "", FALSE);
        $this->db->where('TranIsVoid', 0);
        $query = $this->db->get('MVFTransactions');

        return $query->result();
    }


    //========================== operates ================================//

}
