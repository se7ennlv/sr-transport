<body class="init-page">
    <div class="container">
        <div class="row justify-content-center pt-4">
            <div class="col-2">
                <div class="nav flex-column nav-pills" id="pills-tab" role="tablist" aria-orientation="vertical">
                    <a class="nav-link active font-weight-bold text-center" id="pills-wc-tab" data-toggle="pill" href="#pills-wc" role="tab" aria-controls="pills-wc" aria-selected="false">WC-SR</a>
                    <a class="nav-link font-weight-bold text-center" id="pills-mvf-tab" data-toggle="pill" href="#pills-mvf" role="tab" aria-controls="pills-mvf" aria-selected="false">MVF</a>
                </div>
            </div>
            <div class="col-8">
                <div class="tab-content" id="pills-tabContent" style="background-image: linear-gradient(to top, #dfe9f3 0%, white 100%);">
                    <div class="tab-pane show active" id="pills-wc" role="tabpanel" aria-labelledby="pills-wc-tab">
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
        $('#pills-tab a[href="#pills-wc"]').on('shown.bs.tab', function(e) {
            clearViewRender();
            wcViewRender();
        });

        $('#pills-tab a[href="#pills-mvf"]').on('shown.bs.tab', function(e) {
            clearViewRender();
            mvfViewRender();
        });

        function clearViewRender() {
            $('#pills-wc').html('');
            $('#pills-mvf').html('');
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
                url: "<?= site_url('AppController/MVFTicketView/1'); ?>"
            }).done(function(data) {
                $('#pills-mvf').html(data);
            });
        }

        function initail() {
            clearViewRender();
            wcViewRender();
        }

        $(function() {
            initail();
        });

       
    </script>

</body>

</html>