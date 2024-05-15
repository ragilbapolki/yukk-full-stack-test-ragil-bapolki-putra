<style>
    .select2-selection__rendered {
        line-height: 36px !important;
    }

    .select2-container .select2-selection--single {
        height: 38px !important;
    }

    .select2-selection__arrow {
        height: 36px !important;
    }
</style>
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>{{$title}}</h2>
            </div>
            <div class="card-body">
                <div class="col-md-12">
                    <form class="needs-validation" id="form_transaction">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-row">
                            <label for="code">Code</label>
                            <input type="text" class="form-control" id="code" name="code" value="<?= $code ?>" readonly>
                        </div>
                        <div class="form-row">
                            <label for="type">Type</label>
                            <select class="col-md-12 js-example-basic-single" id="type" name="type" required>
                                <option value=""></option>
                                <option value="1">Top Up</option>
                                <option value="2">Transaction</option>
                            </select>
                        </div>
                        <div class="form-row">
                            <label for="amount">Amount</label>
                            <input type="text" class="form-control number-separator" id="amount" name="amount" value="" placeholder="Amount" required>
                        </div>
                        <div class="form-row">
                            <label for="keterangan">Keterangan</label>
                            <textarea class="form-control" id="note" name="note" rows="2"></textarea>
                        </div>

                        <div class="form-row">
                            <label for="keterangan">Bukti</label>
                            <div class="form-group col-md-12">
                                <input type="file" class="form-control custom-file-input" id="file_bukti" name="file_bukti" required>
                                <label class="custom-file-label" for="validatedCustomFile">Choose file...</label>
                            </div>
                        </div>
                    </form>
                    <button class="btn btn-success" id="saveBtn">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $('document').ready(function() {
        $('.js-example-basic-single').select2({
            placeholder: "Please select a type"
        });
        $("#saveBtn").click(function(event) {
            event.preventDefault();
            $.ajax({
                url: "{{ route('transaction.store') }}",
                method: "POST",
                data: new FormData($('#form_transaction')[0]),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    setTimeout(function() {
                        window.location.href = "/transaction";
                    }, 2000);
                }
            })
            toastr.success('We do have the Kapua suite available.', 'Success Alert', {
                timeOut: 5000
            })
        });
        // table serverside
        var table = $('#tableUser').DataTable({
            processing: false,
            serverSide: true,
            ordering: false,
            dom: 'Bfrtip',
            buttons: [],
            ajax: "{{ route('users.index') }}",
            columns: [{
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'email',
                    name: 'email',
                },
                {
                    data: 'level',
                    name: 'level',
                    render: function(data, type, row, meta) {
                        return data == 1 ? 'Operator' : 'Member';
                    }
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });
        $(document).on('input', '.number-separator', function(e) {
            if (/^[0-9.,]+$/.test($(this).val())) {
                $(this).val(
                    parseFloat($(this).val().replace(/,/g, '')).toLocaleString('en')
                );
            } else {
                $(this).val(
                    $(this)
                    .val()
                    .substring(0, $(this).val().length - 1)
                );
            }
        });
    });
</script>
@endpush