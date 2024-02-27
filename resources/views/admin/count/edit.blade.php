@extends('layout.dashboard.app', ['title' => 'Edit Count'])

@section('content')

<section class="section">
    @foreach ($label as $item)
    @if ($item->code == 'count.edit')
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
            <div class="card border-0 shadow rounded">
                <div class="card-header">
                    <h4>Edit</h4>
                    <div class="card-header-action">
                        <a href="{{ route('count.index') }}" class="btn btn-warning" data-toggle="tooltip"
                           title="Back"><i class="fas fa-backward"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <form id="fmlabel-edit" action="{{ route('count.update', $data->id) }}" method="POST"
                          enctype="multipart/form-data" class="needs-validation" novalidate="">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <!-- ICON -->
                            <div class="col-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">ICON</label>
                                    <select class="form-control" name="icon" required="">
                                        <option value="">-- Choose --</option>
                                        <option value="award" {{ $data->image == 'award' ? 'selected' : '' }}>Award
                                        </option>
                                        <option value="bar-chart-fill" {{ $data->image == 'bar-chart-fill' ? 'selected'
                                            : '' }}>
                                            Bar Chart
                                        </option>
                                        <option value="bicycle" {{ $data->image == 'bicycle' ? 'selected' : ''
                                            }}>Bicycle
                                        </option>
                                        <option value="book" {{ $data->image == 'book' ? 'selected' : '' }}>Book
                                        </option>
                                        <option value="people" {{ $data->image == 'people' ? 'selected' : '' }}>People
                                        </option>
                                    </select>
                                    <div class="invalid-feedback alert alert-danger mt-2">
                                        Please fill in the icon
                                    </div>
                                </div>
                            </div>
                            <!-- TITLE -->
                            <div class="col-8">
                                <div class="form-group">
                                    <label class="font-weight-bold">TITLE</label>
                                    <input type="text" class="form-control" name="title" value="{{ $data->title }}"
                                           placeholder="Enter title" required="">
                                    <div class="invalid-feedback alert alert-danger mt-2">
                                        Please fill in the title
                                    </div>
                                </div>
                            </div>
                            <!-- COUNT -->
                            <div class="col-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">COUNT</label>
                                    <input type="number" class="form-control" name="count" value="{{ $data->count }}"
                                           placeholder="Enter count" required="">
                                    <div class="invalid-feedback alert alert-danger mt-2">
                                        Please fill in the count
                                    </div>
                                </div>
                            </div>
                            <!-- ORDERING -->
                            <div class="col-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">ORDERING</label>
                                    <input type="number" class="form-control" name="ordering"
                                           value="{{ $data->ordering }}" placeholder="Enter ordering" required="">
                                    <div class="invalid-feedback alert alert-danger mt-2">
                                        Please fill in the ordering
                                    </div>
                                </div>
                            </div>
                            <!-- STATUS -->
                            <div class="col-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">STATUS</label>
                                    <select class="form-control" name="status" required="">
                                        <option value="ACTIVE" {{ $data->status == 'ACTIVE' ? 'selected' : '' }}>
                                            ACTIVE
                                        </option>
                                        <option value="INACTIVE" {{ $data->status == 'INACTIVE' ? 'selected' : '' }}>
                                            INACTIVE
                                        </option>
                                    </select>
                                    <div class="invalid-feedback alert alert-danger mt-2">
                                        Please select the status
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- BUTTON -->
                        <div class="form-group">
                            <button type="submit" style="width:100px" class="btn btn-success btn-action"
                                    data-toggle="tooltip" title="Save"><i class="fas fa-save"></i></button>
                            <button type="reset" onclick="myReset()" class="btn btn-dark btn-action"
                                    data-toggle="tooltip" title="Reset"><i class="fas fa-redo-alt"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection