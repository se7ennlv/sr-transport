<body class="init-page">
    <div class="container">
        <div class="row justify-content-center pt-4">
            <div class="col-2">
                <div class="nav flex-column nav-pills" id="pills-tab" role="tablist" aria-orientation="vertical">
                    <a class="nav-link active font-weight-bold text-center" id="pills-sr-tab" data-toggle="pill" href="#pills-sr" role="tab" aria-controls="pills-sr" aria-selected="false">SR-WC</a>
                    <a class="nav-link font-weight-bold text-center" id="pills-wc-tab" data-toggle="pill" href="#pills-wc" role="tab" aria-controls="pills-wc" aria-selected="false">WC-SR</a>
                    <a class="nav-link font-weight-bold text-center" id="pills-mvf-tab" data-toggle="pill" href="#pills-mvf" role="tab" aria-controls="pills-mvf" aria-selected="false">MVF</a>
                </div>
            </div>
            <div class="col-8">
                <div class="tab-content" id="pills-tabContent" style="background-image: linear-gradient(to top, #dfe9f3 0%, white 100%);">
                    <div class="tab-pane show active" id="pills-sr" role="tabpanel" aria-labelledby="pills-sr-tab">
                        <!-- dynamic -->
                    </div>
                    <div class="tab-pane" id="pills-wc" role="tabpanel" aria-labelledby="pills-wc-tab">
                        <!-- dynamic -->
                    </div>
                    <div class="tab-pane" id="pills-mvf" role="tabpanel" aria-labelledby="pills-mvf-tab">
                        <!-- dynamic -->
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        $('#pills-tab a[href="#pills-sr"]').on('shown.bs.tab', function(e) {
            clearViewRender();
            srViewRender();
        });

        $('#pills-tab a[href="#pills-wc"]').on('shown.bs.tab', function(e) {
            clearViewRender();
            switching(1);
        });

        $('#pills-tab a[href="#pills-mvf"]').on('shown.bs.tab', function(e) {
            clearViewRender();
            mvfViewRender();
        });

        function clearViewRender() {
            $('#pills-sr').html('');
            $('#pills-wc').html('');
            $('#pills-mvf').html('');
        }

        function srViewRender() {
            $.ajax({
                method: 'GET',
                url: "<?= site_url('AppController/SRTicketView'); ?>"
            }).done(function(data) {
                $('#pills-sr').html(data);
            });
        }

        function wcViewRender() {
            $.ajax({
                method: 'GET',
                url: "<?= site_url('AppController/WCTicketView'); ?>"
            }).done(function(data) {
                $('#pills-wc').html(data);
            });
        }

        function mvfViewRender() {
            $.ajax({
                method: 'GET',
                url: "<?= site_url('AppController/MVFTicketView/0'); ?>"
            }).done(function(data) {
                $('#pills-mvf').html(data);
            });
        }

        function initail() {
            clearViewRender();
            srViewRender();
        }

        $(function() {
            initail();
        });

        function switching(tabIndex) {
            Swal.fire({
                title: 'Enter Related Staff ID',
                text: "",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Confirm',
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off',
                    required: true
                },
            }).then((result) => {
                if (result.value) {
                    var rawStr = result.value;
                    var empId = DataFilter(rawStr);

                    $.ajax({
                        type: 'GET',
                        url: "<?= site_url('AppController/CheckRelatedEmp') ?>/" + empId,
                        dataType: 'json'
                    }).done(function(data) {
                        if (data > 0) {
                            if (tabIndex == 1) {
                                wcViewRender();
                            }
                        } else {
                            swalAlert('Sorry, you are not in related staff', 'error');
                        }
                    }).fail(function(jqXHR, textStatus, errorThrown) {
                        smkAlert('Something went wrong, please contact IT', 'danger');
                    });
                }
            });
        }
    </script>

</body>

</html>