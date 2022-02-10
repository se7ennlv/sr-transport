<section class="mb-2">
    <h5 class="m-0 font-weight-bold text-primary"><i class="fas fa-th-list"></i> <strong>Ticket Records</strong></h5>
</section>

<div class="card mb-4 border-top-primary">
    <div class="card-body">
        <div class="table-responsive">
            <div id="toolbar" class="mb-2">
                <form class="form-inline" action="#" autocomplete="off" method="POST">
                    <div class="input-group ml-1 mr-1">
                        <input type="text" name="fromDate" id="fromDate" class="form-control date-picker" readonly style="width: 120px;">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-calendar-alt mr-1"></i> To <i class="fas fa-calendar-alt ml-1"></i></span>
                        </div>
                        <input type="text" name="toDate" id="toDate" class="form-control date-picker" readonly style="width: 120px;">
                    </div>
                    <div class="mr-1">
                        <select name="location" id="location" class="form-control">
                            <option value="">All Location</option>
                            <option value="SR">SR</option>
                            <option value="WC">WC</option>
                        </select>
                    </div>
                    <button type="button" class="btn btn-success" onclick="initData();"><i class="fas fa-sync-alt"></i> Refresh Data</button>
                </form>
            </div>

            <table data-classes="table table-bordered table-sm table-hover table-striped" id="dataTable" data-toolbar="#toolbar" data-export-footer="true" data-search="true" data-show-toggle="true" data-show-fullscreen="true" data-show-columns="true" data-show-columns-toggle-all="true" data-show-export="true" data-minimum-count-columns="2" data-show-pagination-switch="true" data-pagination="true" data-id-field="id" data-page-size="25" data-page-list="[25, 50, 100, all]" data-sort-name="TranID" data-sort-order="desc">
                <thead class="thead-light">
                    <tr>
                        <th class="text-center" data-formatter="rowFormat">#</th>
                        <th data-field="TranID" class="text-nowrap text-center" data-formatter="documents">Doc No</th>
                        <th data-field="TranEmpID" class="text-nowrap text-center">Emp ID</th>
                        <th data-field="TranEmpName" data-halign="center" class="text-nowrap">Emp Name</th>
                        <th data-field="TranPosition" data-halign="center" class="text-nowrap">Position</th>
                        <th data-field="TranTier" data-halign="center" class="text-nowrap text-center">Tier</th>
                        <th data-field="TranDeptCode" class="text-nowrap text-center">Dept</th>
                        <th data-field="TranLocationCode" data-halign="center" class="text-nowrap text-center">Locations</th>
                        <th data-field="TranCounter" class="text-nowrap text-center" data-formatter="badgeValFormat">Trip</th>
                        <th data-field="TranUseFor" class="text-nowrap text-center" data-formatter="useType">User For</th>
                        <th data-field="TranCreatedAt" class="text-nowrap text-center" data-formatter="dateTimeFormat">Date Time</th>
                        <!-- <th class="text-nowrap text-center" data-formatter="printTmpl" data-events="rePrint">Operates</th> -->
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>


<script>
    //===================== initail ==========================//
    var table = $('#dataTable');
    var currDate = moment().format('YYYY-MM-DD');

    $(function() {
        $('#fromDate').val(currDate);
        $('#toDate').val(currDate);
    });

    $(function() {
        $('.date-picker').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd'
        });
    });

    function initData() {
        var objs = {
            fDate: $('#fromDate').val(),
            tDate: $('#toDate').val(),
            location: $('#location').val()
        }

        $.ajax({
            type: 'POST',
            url: "<?= site_url('ReportController/FetchAllTicketRecords') ?>",
            data: $.param(objs),
            dataType: 'JSON',
            beforeSend: function() {
                blockUI('Processing...');
            }
        }).done(function(data) {
            unblockUI();
            initTable(data);
        }).fail(function(jqXHR, textStatus, errorThrown) {
            smkAlert('Something went wrong, please contact IT', 'danger');
        });

        return false;
    }

    function initTable(data) {
        table.bootstrapTable('destroy').bootstrapTable({
            height: 620,
            data: data,
            exportDataType: 'all',
            exportOptions: {
                fileName: 'sr-ticket-records'
            }
        });
    }

    $(function() {
        initData();
    });

    window.rePrint = {
        'click .btn-print': function(e, value, row, index) {
            rePrint(row.RedID);
        }
    }
</script>