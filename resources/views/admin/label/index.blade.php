@extends('layout.dashboard.app', ['title' => 'List Label'])

@section('content')
<section class="section">
    @foreach ($label as $item)
    @if ($item->code == 'label')
    <div class="section-title">
        <h3>{!! html_entity_decode($item->title) !!}</h3>
    </div>
    <p class="section-lead">
        {!! html_entity_decode($item->desc) !!}
    </p>
    @endif
    @endforeach
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>List</h4>
                    <div class="card-header-action">
                        <a href="{{ route('label.create') }}" class="btn btn-success" data-toggle="tooltip"
                           title="Add"><i class="fas fa-plus-circle"></i></a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead>
                            <tr style="text-align:left">
                                <th>CODE</th>
                                <th>TITLE</th>
                                <th>DESCRIPTION</th>
                                <th>ORDER</th>
                                <th>STATUS</th>
                                <th style="text-align:center">ACTION</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($data as $item)
                            <tr>
                                <td>
                                    <div class="badge badge-dark">
                                        {{ $item->code }}
                                    </div>
                                </td>
                                <td>
                                    {{ $item->title }}
                                </td>
                                <td>
                                    {!! Str::words(html_entity_decode($item->desc), 80, ' ...') !!}
                                </td>
                                <td>
                                    {{ $item->ordering }}
                                </td>
                                <td>
                                    <div class="badge badge-{{ $item->status == 'ACTIVE' ? 'success' : 'danger' }}">
                                        {{ $item->status }}
                                    </div>
                                </td>
                                <td colspan="2">
                                    <div class="row justify-content-md-center">
                                        <a href="{{ route('label.edit', $item->id) }}"
                                           class="btn btn-warning btn-action" data-toggle="tooltip" title="Edit"><i
                                                class="fas fa-pencil-alt"></i></a>
                                        &nbsp;
                                        &nbsp;
                                        <!--                                        <button class="btn btn-danger" onclick="deleteConfirmation('{{$item->id}}')"-->
                                        <!--                                                data-toggle="tooltip" title="Delete"><i class="fas fa-trash"></i>-->
                                        <!--                                        </button>-->
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <div class="alert alert-dark m-5">
                                Data not found
                            </div>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="d-felx justify-content-center">
                    <div class="card-footer text-right">
                        {{ $data->links('vendor.pagination.bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function deleteConfirmation(id) {
            swal({
                title: "Are you sure you delete data ?",
                text: "Please confirm and then confirm !",
                type: "warning",
                showCancelButton: !0,
                confirmButtonText: "Hapus",
                cancelButtonText: "Batal",
                cancelButtonColor: "#F0E701",
                confirmButtonColor: "#1AA13E",
                reverseButtons: !0
            }).then(function (e) {
                if (e.value === true) {
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        type: 'POST',
                        url: "{{url('/label/destroy')}}/" + id,
                        data: {
                            _token: CSRF_TOKEN,
                            "id": id
                        },
                        dataType: 'JSON',
                        success: function (results) {
                            if (results.success === true) {
                                swal("Success", results.message, "success");
                                window.location.replace("{{ url('label') }}");
                            } else {
                                swal("Failed", results.message, "error");
                            }
                        }
                    });
                } else {
                    e.dismiss;
                }
            }, function (dismiss) {
                return false;
            })
        }
    </script>

</section>
@endsection
