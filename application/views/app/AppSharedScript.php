<script>
    var oneCopyLimit = ['A1', 'A2', 'B1', 'B2', 'C1'];
    var locate = $('#locateCode').text();

    function resetData() {
        $('#enterId').val('').trigger('focus');
        $('#imgProfile').html('<i class="fas fa-user-circle fa-7x"></i>');
        $('#infoEmpId').text('');
        $('#infoEmpName').text('');
        $('#infoJob').text('');
        $('#infoDept').text('');
        $('#infoTier').text('');
        $('#infoCounter').text(0);
        $('#infoCopy').val(1);
        $('#btnPrint').attr('disabled', true);
        $('input:radio').prop("checked", false);
    }


    $(document).ready(function() {
        resetData();
    });

    function fetchCounter($empId) {
        $.getJSON("<?= site_url('AppController/FetchCounter') ?>/" + $empId + '/' + locate, function(data) {
            $('#infoCounter').text(data);
        });
    }

    $(function() {
        $('form').on('submit', function(e) {
            e.preventDefault();

            if ($(this).smkValidate()) {
                var rawStr = $('#enterId').val();
                var empId = DataFilter(rawStr);
                var urlTarget;

                if (empId.startsWith(2)) {
                    urlTarget = "<?= base_url('AppController/FetchOneEmp'); ?>/" + empId;
                } else {
                    urlTarget = "<?= base_url('AppController/FetchOneOtherEmp'); ?>/" + empId;
                }

                $.ajax({
                    type: 'GET',
                    url: urlTarget,
                    dataType: 'JSON'
                }).done(function(data) {
                    resetData();

                    if (!$.isEmptyObject(data.emp) && data.emp.FullName.length > 0) {
                        if (!$.isEmptyObject(data.emp.PhotoFile)) {
                            $('#imgProfile').html('<img src="http://172.16.98.81:8090/psa/files/' + data.emp.PhotoFile + '" class="img rounded-circle" style="width: 120px; height: 130px">');
                        } else {
                            $('#imgProfile').html('<i class="fas fa-user-circle fa-8x"></i>');
                        }

                        $('#enterId').val(empId);
                        $('#infoEmpId').text(data.emp.EmpCode);
                        $('#infoEmpName').text(data.emp.FullName);
                        $('#infoJob').text(data.emp.Positions);
                        $('#infoDept').text(data.emp.DeptCode);
                        $('#infoTier').text(data.emp.Tier);

                        fetchCounter(data.emp.EmpCode);

                        $('#btnPrint').attr('disabled', false);
                    } else {
                        if (empId.length == 6) {
                            swalAlert('Sorry, (' + empId + ') find not found on the system, Please contact to HR to register your information on (ID Card)', 'error');
                        } else {
                            swalAlert('Sorry, (' + empId + ') find not found on the system, Please try again', 'error');
                        }
                    }
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    smkAlert('Something went wrong, please contact IT', 'danger');
                });
            }
        });
    });


    //========================== print control ================================//
    function printDoc(empId, tranId) {
        $('#docBody').printThis({
            importCSS: true,
            header: null,
            footer: null,
            afterPrint: function() {
                setPrintedState(tranId);
                printCopy(empId);
            }
        });
    }

    function printCopy(empId) {
        $('#docBody').printThis({
            importCSS: true,
            header: null,
            footer: null,
            beforePrint: function() {
                $('#copyTitle').removeClass('d-none');
            },
            afterPrint: function() {
                fetchTicketData(empId);
                $('#copyTitle').addClass('d-none');
            }
        });
    }

    function fetchTicketData(empId) {
        $.ajax({
            type: 'GET',
            url: "<?= site_url('AppController/FetchTicketData') ?>/" + empId,
            dataType: 'JSON',
            async: false
        }).done(function(data) {
            if (!$.isEmptyObject(data)) {
                $('.invLocate').html(data.TranLocation + ' <small class="d-none" id="copyTitle">(Copy)</small>');
                $('#invDocNo').text(('0000' + data.TranID).slice(-4));
                $('#invEmpId').text(data.TranEmpID);
                $('#invEmpName').text(data.TranEmpName);
                $('#invPosition').text(data.TranPosition);
                $('#invDept').text(data.TranDeptCode);
                $('#invTier').text(data.TranTier);
                $('#invCounter').text(data.TranCounter);
                $('#invDate').text(moment(data.TranCreatedAt).format('DD-MMM-YY h:mm:ss A'));
                $('#invUseFor').text(data.TranUseFor);

                printDoc(empId, data.TranID);
            } else {
                resetData();
            }
        });
    }

    function submitTicket() {
        if ($("input[name='infoUseFor']").is(':checked')) {
            Swal.fire({
                title: 'Confirm',
                text: "",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.value) {
                    var person = parseInt($('#infoCopy').val());
                    var counter = parseInt($('#infoCounter').text());

                    var objs = {
                        empId: $('#infoEmpId').text(),
                        empName: $('#infoEmpName').text(),
                        position: $('#infoJob').text(),
                        dept: $('#infoDept').text(),
                        tier: $('#infoTier').text(),
                        location: $('#locate').text(),
                        locateCode: locate,
                        useFor: $('input:radio:checked').val()
                    }

                    for (var i = 0; i < person; i++) {
                        counter = counter + 1

                        if ($.inArray(objs.tier, oneCopyLimit) >= 0) {
                            if (person > 1) {
                                swalAlert("The tier (< C2) can get the ticket only one copy (per time)", 'error');
                                return false;
                            }
                        }

                        $('#infoCounter').text(counter);

                        $.ajax({
                            method: 'POST',
                            url: "<?= site_url('AppController/InitInsertTicket') ?>",
                            data: $.param(objs) + '&' + $.param({
                                counter: counter
                            }),
                            async: false
                        }).fail(function(jqXHR, textStatus, errorThrown) {
                            smkAlert('Something went wrong, please contact IT', 'danger');
                        });
                    }

                    fetchTicketData(objs.empId);
                }
            })
        } else {
            Swal.fire({
                title: 'Use for Personal or Work',
                text: "",
                icon: 'question',
                confirmButtonText: 'OK'
            })
        }
    }

    function setPrintedState(tranId) {
        $.ajax({
            url: "<?= site_url('AppController/InitSetPrintedState') ?>/" + tranId
        });
    }


    //========================== copy control =============================
    function increaseNumber() {
        var val = parseInt($('#infoCopy').val());
        var currNum = (val + 1);
        if (currNum > 5) {
            swalAlert('Maximum copy is 5', 'error');
        } else {
            $('#infoCopy').val(currNum);
        }
    }

    function decreaseNumber() {
        var val = parseInt($('#infoCopy').val());
        var currNum = (val - 1);

        if (currNum < 1) {
            swalAlert('Minimum copy is 1', 'error');
        } else {
            $('#infoCopy').val(currNum);
        }
    }

    
</script>