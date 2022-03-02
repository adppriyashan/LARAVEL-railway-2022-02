<script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ asset('assets/js/sb-admin-2.min.js') }}"></script>
<script src="{{ asset('assets/vendor/chart.js/Chart.min.js') }}"></script>
<script src="{{ asset('assets/js/demo/chart-area-demo.js') }}"></script>
<script src="{{ asset('assets/js/demo/chart-pie-demo.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"
integrity="sha512-CNgIRecGo7nphbeZ04Sc13ka07paqdeTu0WR1IM4kNcpmBAUSHSQX0FslNhTDadL4O5SAGapGt4FodqL8My0mA=="
crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function showAlert(message, func) {
        $.confirm({
            title: 'Confirmation',
            content: message,
            buttons: {
                cancel: function() {},
                confirm: {
                    btnClass: 'btn-primary',
                    text: 'OK',
                    action: func
                }
            }
        });
    }

    $('.showqr').on('click', function() {
        let code = $(this).attr('record');
        $.ajax({
            type: "GET",
            url: "{{ route('seasons.print') }}",
            data: {
                'id': code
            },
            success: function(data) {
                $('#print_content').html(data);
                new QRCode(document.getElementById("qrcodeseason"), {
                    text: code,
                    width: 100,
                    height: 100,
                });
                $('#seasonprint').modal('show');
            }
        });
    });

    function seasonqrprint() {
        doPrintHtml($('#print_content').html());
    }

    function doPrintHtml(response) {
        var printWindow = window.open('', 'Print-Window');
        var doc = printWindow.document;
        doc.write(response);
        doc.close();

        function show() {
            if (doc.readyState === "complete") {
                printWindow.focus();
                printWindow.print();
                printWindow.document.close();
                setTimeout(function() {
                    printWindow.close();
                }, 100);
            } else {
                setTimeout(show, 100);
            }
        };
        show();
    }
</script>
