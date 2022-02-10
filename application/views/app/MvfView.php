<?php header('Content-Type: text/html; charset=utf-8'); ?>

<div class="table-responsive p-3">
    <table class="table table-sm table-borderless mb-0">
        <tr>
            <td colspan="5" class="text-center text-nowrap">
                <h6 class="font-weight-bold text-primary">SAVAN LEGEND RESORTS SOLE CO.LTD</h6>
                <h6 class="font-weight-bold text-primary">MANUAL VEHICLE FORM</h6>
            </td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
            <td colspan="2" class="text-nowrap text-right"><strong>S/N:</strong> <span id="TranSN"></span></td>
        </tr>
        <tr>
            <td class="text-nowrap"><strong>Date / Time:</strong></td>
            <td class="text-nowrap"><span id="TranDate"></span> <span id="TranTime"></span></td>
            <td>&nbsp;</td>
            <td colspan="2" class="text-nowrap text-right hand" data-toggle="modal" data-target="#numeralModal">
                <strong>No. of Pax:</strong> <span class="badge badge-info" id="TranNoOfPax">0</span>
            </td>
        </tr>
        <tr>
            <td class="text-nowrap" style="vertical-align: middle"><strong>Driver / ID:</strong></td>
            <td class="text-nowrap">
                <form action="#" method="POST" autocomplete="off">
                    <div class="input-gorup m-0">
                        <input type="text" class="form-control" name="TranDriverID" id="TranDriverID" required>
                    </div>
                </form>
            </td>
            <td colspan="3" class="text-nowrap text-truncate" style="vertical-align: middle; max-width: 70px;">
                <strong>Name:</strong> <span id="TranDriverName"></span>
            </td>
        </tr>
        <tr>
            <td class="text-nowrap" style="vertical-align: middle"><strong>License Plate:</strong></td>
            <td class="text-nowrap">
                <div class="form-group m-0">
                    <select style="font-family: 'Phetsarath OT'; " name="TranLPCode" id="TranLPCode" class="form-control" onchange="getVehicleType($(this).val())" required>
                        <option value="">Select</option>
                        <?php foreach ($lps as $lp) : ?>
                            <option value="<?= $lp->LPCode; ?>" data-desc="<?= $lp->LPDesc; ?>"><?= $lp->LPDesc; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </td>
            <td colspan="3"><strong>Dept:</strong> <span id="TranDept"></span></td>
        </tr>
        <tr>
            <td colspan="5"></td>
        </tr>
        <tr>
            <td class="text-nowrap"><strong>Vehicle Type:</strong></td>
            <td colspan="4">
                <?php foreach ($vts as $vt) : ?>
                    <div class="form-check-inline">
                        <label class="form-check-label" for="<?= $vt->VTCode; ?>">
                            <input required type="radio" class="form-check-input" name="TranVTCode" id="<?= $vt->VTCode; ?>" value="<?= $vt->VTCode; ?>"><strong><?= $vt->VTDesc; ?></strong>
                        </label>
                    </div>
                <?php endforeach; ?>
            </td>
        </tr>
        <tr>
            <td colspan="5">
                <hr>
            </td>
        </tr>
        <tr>
            <td class="text-nowrap"><strong>Remarks:</strong></td>
            <td>
                <div class="form-group m-0">
                    <select name="TranRemark" id="TranRemark" class="form-control" onchange="getRemark($(this).val());" required>
                        <option value="">Select</option>
                        <?php foreach ($trips as $trip) : ?>
                            <option value="<?= $trip->TCode; ?>"><?= $trip->TDesc; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </td>
            <td colspan="3"></td>
        </tr>
        <tr class="d-none" id="boxRemark">
            <td colspan="5">
                <textarea name="othRemark" id="othRemark" cols="35" rows="3" class="form-control"></textarea>
            </td>
        </tr>
        <tr>
            <td colspan="5"></td>
        </tr>
        <tr>
            <td class="text-nowrap"><strong>Prepared by:</strong></td>
            <td><u id="TranPreparedBy"></u></td>
            <td colspan="3"></td>
        </tr>
        <tr>
            <td colspan="5" class="text-center">
                <hr class="mt-0">
                <button type="button" class="btn btn-primary btn-lg" onclick="submit();" id="btnSubmit"><i class="fas fa-share"></i> Submit</button>
                <button type="button" class="btn btn-warning btn-lg" onclick="initPage();"><i class="fas fa-sync-alt"></i> Refresh</button>
            </td>
        </tr>
    </table>
</div>


<script>
    var glbQtyEntry = '0';

    $(function() {
        initPage();
    });

    function initPage() {
        getSN();
        getDateTimeDynamic();

        $('#TranDriverID').val('').trigger('focus');
        $('#TranDriverName').text('');
        $('#TranNoOfPax').text(0);
        $('#TranDept').text('');
        $('#TranLPCode').prop('selectedIndex', 0).trigger('change');
        $('#TranRemark').prop('selectedIndex', 0).trigger('change');
        $('#TranPreparedBy').text('');
    }

    function getSN() {
        $.getJSON("<?= site_url('AppController/FetchSN') ?>", function(data) {
            var newQno = ('0000' + data).slice(-5);
            $('#TranSN').text(newQno);
        });
    }

    function getDateTimeDynamic() {
        var update;

        (update = function() {
            $('#TranTime').text(moment().format('h:mm:ss A'));
            $('#TranDate').text(moment().format('YYYY-MM-DD'));
        })();

        setInterval(update, 1000);
    }

    function getVehicleType(lpCode) {
        if (lpCode.length > 0) {
            $.ajax({
                method: 'GET',
                url: "<?= site_url('AppController/FetchVTCode') ?>/" + lpCode,
                dataType: 'JSON'
            }).done(function(data) {
                if (!$.isEmptyObject(data)) {
                    $('#' + data.LPVTCode).prop('checked', true);
                }
            }).fail(function(jqXHR, textStatus, errorThrown) {
                smkAlert('Something went wrong, please contact IT', 'danger');
            });
        } else {
            $("input[name='TranVTCode']").prop('checked', false);
        }
    }

    $(function() {
        $('form').on('submit', function(e) {
            e.preventDefault();

            if ($(this).smkValidate()) {
                var rawStr = $('#TranDriverID').val();
                var empId = DataFilter(rawStr);

                $('#TranDriverID').val(empId);

                $.ajax({
                    type: 'GET',
                    url: "<?= base_url('AppController/FetchOneEmp'); ?>/" + empId,
                    dataType: 'JSON'
                }).done(function(data) {
                    if (!$.isEmptyObject(data.emp)) {
                        $('#TranDriverName').text(data.emp.FullName);
                        $('#TranDept').text(data.emp.DeptCode);
                        $('#TranPreparedBy').text(data.emp.EmpCode);
                        getSN();
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

    function getRemark(remark) {
        if (remark.length > 0 && remark === 'OTH') {
            $('#boxRemark').removeClass('d-none');
            $('#othRemark').trigger('focus');
        } else {
            $('#boxRemark').addClass('d-none');
        }
    }

    function submit() {
        var objs = {
            sn: $('#TranSN').text(),
            tDate: $('#TranDate').text(),
            tTime: $('#TranTime').text(),
            dvId: $('#TranDriverID').val(),
            dvName: $('#TranDriverName').text(),
            dept: $('#TranDept').text(),
            noOfPax: $('#TranNoOfPax').text(),
            lpCode: $('#TranLPCode').val(),
            lpDesc: $('#TranLPCode').children('option:selected').data('desc'),
            vtCode: $("input[name='TranVTCode']:checked").val(),
            remark: $('#TranRemark').val(),
            remDesc: $('#othRemark').val(),
            preparedBy: $('#TranPreparedBy').text()
        }

        if (objs.dvId.length <= 0) {
            swalAlert('Please enter driver ID', 'error');
        } else if (objs.noOfPax <= 0) {
            swalAlert('Please enter No of Pax', 'error');
        } else if (objs.lpCode <= 0) {
            swalAlert('Please select license plate', 'error');
        } else if (objs.remark <= 0) {
            swalAlert('Please select remark', 'error');
        } else {
            $.ajax({
                method: 'POST',
                url: "<?= site_url('AppController/InitInsertMvf') ?>",
                data: objs
            }).done(function(data) {
                fetchMvfData(data);
            }).fail(function(jqXHR, textStatus, errorThrown) {
                smkAlert('Something went wrong, please contact IT', 'danger');
            });
        }
    }


    //========================== print control ================================//
    function fetchMvfData(tranId) {
        $.ajax({
            type: 'GET',
            url: "<?= site_url('AppController/FetchMvfData') ?>/" + tranId,
            dataType: 'JSON',
            async: false
        }).done(function(data) {
            if (!$.isEmptyObject(data)) {
                $('#mvfInvSN').text(data.TranSN);
                $('#mvfInvDateTime').text(moment(data.TranDate + ' ' + data.TranTime).format('DD-MMM-YY h:mm:ss A'));
                $('#mvfInvDriver').text(data.TranDriverID + '-' + data.TranDriverName);
                $('#mvfInvDept').text(data.TranDept);
                $('#mvfInvNoOfPax').text(data.TranNoOfPax);
                $('#mvfInvLP').text(data.TranLPCodeDesc);
                $('#mvfInvVtype').text(data.TranVTCode);

                if ($.isEmptyObject(data.TranRemarkDesc)) {
                    $('#mvfInvRemark').text(data.TranRemark);
                } else {
                    $('#mvfInvRemark').text(data.TranRemark + ' ' + data.TranRemarkDesc);
                }

                $('#mvfInvPreparedBy').text(data.TranPreparedBy);

                for (var i = 1; i < 2; i++) {
                    printDoc();
                }
            }
        });
    }

    function printDoc() {
        $('#docBody').printThis({
            importCSS: true,
            header: null,
            footer: null,
            afterPrint: function() {
                printCopy();
            }
        });
    }

    function printCopy(empId) {
        $('#docBody').printThis({
            importCSS: true,
            header: null,
            footer: null,
            beforePrint: function() {
                $('#setCopy').html('<small>(Copy)</small>');
            },
            afterPrint: function() {
                initPage();
                $('#setCopy').html('');
            }
        });
    }

    //========================== Modal numeral control =====================//
    $(function() {
        $('#numeralModal').on('show.bs.modal keydown', function(e) {
            var related = $(e.relatedTarget);
            var current = $(this);
            var itemId = related.data('item-code');

            glbMdType = related.data('btn');

            if (glbMdType === 'qty') {
                var getQty = parseInt($('#itemSales tr[id=item_' + itemId + '] u').text());
                var valPrice = parseFloat($('#itemSales tr[id=item_' + itemId + '] td').eq(1).data('item-price'));

                current.find('.header-title').text('Quantity');
                glbItemId = itemId;
                glbItemPrice = valPrice;

                updateNumberScreen(getQty);
            } else {
                updateNumberScreen(0);
            }

            if ((e.keyCode >= 96 && e.keyCode <= 105) || (e.keyCode == 8 || e.keyCode == 67)) {
                getNumberStrKey(e.key);
            }
        });
    })

    $(function() {
        $('#numeralModal').on('hide.bs.modal', function() {
            glbQtyEntry = '0';
        });
    });

    function getNumberStrKey(keyStr) {
        if (!$.isEmptyObject(keyStr)) {
            if (keyStr === "c") {
                glbQtyEntry = '0';
            } else if (keyStr === "Backspace") {
                glbQtyEntry = glbQtyEntry.substring(0, glbQtyEntry.length - 1);

                if ($.isEmptyObject(glbQtyEntry)) glbQtyEntry = '0';
            } else {
                if (glbQtyEntry === '0') glbQtyEntry = keyStr;
                else glbQtyEntry = glbQtyEntry + keyStr;
            }

            updateNumberScreen(glbQtyEntry);
        }
    }

    $(function() {
        $('.number-clicked').on('click', function() {
            var getClicked = $(this).data('val').toString();
            getNumberStrKey(getClicked)
        });
    });

    function updateNumberScreen(getVal) {
        var dispVal = getVal.toString();
        $('.number-screen').text(numeral(dispVal.substring(0, 10)).format('0,0'));
    };

    function getNumberEntered() {
        var numberEntered = parseFloat($('.number-screen').text().replace(/,/g, ''));
        $('#TranNoOfPax').text(numberEntered);
        $('#numeralModal').modal('hide');
    }
    //=========================== End ==================================//
</script>