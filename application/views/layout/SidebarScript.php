<script>
    
    //============================== LR Service ============================//
    function linkSRService() {
        window.open('http://172.16.98.171/srtpsys/AppController/SRView', '_blank');
        return false;
    }

    function linkWCService() {
        window.open('http://172.16.98.171/srtpsys/AppController/WCView', '_blank');
        return false;
    }


    //============================== Ticket Reports =============================//
    function linkTicketRecords() {
        $.ajax({
            url: "<?= site_url('ReportController/TicketRecordView'); ?>",
            beforeSend: function() {
                blockUI('Loading...');
            }
        }).done(function(data) {
            unblockUI();
            $("#mainApp").html(data);
        });

        return false;
    }

    function linkTicketSummary() {
        $.ajax({
            url: "<?= site_url('ReportController/TicketSummaryView'); ?>",
            beforeSend: function() {
                blockUI('Loading...');
            }
        }).done(function(data) {
            unblockUI();
            $("#mainApp").html(data);
        });

        return false;
    }


    //============================== MVF Reports =============================//
    function linkMVFRecords() {
        $.ajax({
            url: "<?= site_url('ReportController/MvfRecordView'); ?>",
            beforeSend: function() {
                blockUI('Loading...');
            }
        }).done(function(data) {
            unblockUI();
            $("#mainApp").html(data);
        });

        return false;
    }



    //================================= change pass =============================//
    $(function() {
        $('#frmChgPass').on('submit', function(e) {
            e.preventDefault();

            if ($(this).smkValidate()) {
                var formData = $(this).serialize();

                $.ajax({
                    method: 'POST',
                    url: "<?= site_url('UserController/InitChangePass') ?>",
                    data: formData,
                    beforeSend: function() {
                        blockUI('Processing...');
                    }
                }).done(function(data) {
                    unblockUI();
                    smkAlert(data.message, data.status);
                    $('#changePwdModal').modal('hide');
                    $('#frmChgPass').smkClear();
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    smkAlert('Something went wrong, please contact IT!', 'danger');
                });
            }
        });
    });
</script>