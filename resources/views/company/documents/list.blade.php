@forelse($dts as $dt)
<div class="col-xl-4 col-sm-6">
    <div class="card shadow-none border">
        <div class="card-body p-3">
            <div class="">
                <div class="float-end ms-2">
                    <div class="dropdown mb-2">
                        <a class="font-size-16 text-muted" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                            <i class="mdi mdi-dots-horizontal"></i>
                        </a>

                        <div class="dropdown-menu dropdown-menu-end">
                            <a data-id='{{$dt->dt_id}}' class="rename-btn dropdown-item" data-bs-toggle="modal" data-bs-target=".bs-example-modal-sm">Rename</a>
                            <a data-id='{{$dt->dt_id}}' class="dropdown-item remove-btn">Remove</a>
                        </div>
                    </div>
                </div>
                <div class="avatar-xs me-3 mb-3">
                    <div class="avatar-title bg-transparent rounded">
                        <i class="bx bxs-folder font-size-24 text-warning"></i>
                    </div>
                </div>
                <div class="d-flex">
                    <div class="overflow-hidden me-auto">
                        <h5 class="font-size-14 text-truncate mb-1"><a href="javascript: void(0);" class="text-body">{{$dt->dt_name}}</a></h5>
                        <p class="text-muted text-truncate mb-0">PDF or DOCX file</p>
                    </div>
                    <div class="align-self-end ms-2">
                        <small class="text-muted mb-0">5MB Limit</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@section('modals')
<div class="modal fade bs-example-modal-sm" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mySmallModalLabel">Small modal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <form action="" id='dt-edit'>
                        @csrf
                        <div class="card-body">
                            <label for="co_id" class="col-form-label">Company</label>
                            <input type="text" class="form-control bg-light" value='{{Auth()->user()->company->co_name}}' disabled>
                            <input type="hidden" id='modal_dt_id' name='modal_dt_id'>
                            <label for="dt_name" class="col-form-label">Document Name</label>
                            <div class="mb-3">
                                <input id="modal_dt_name" name="modal_dt_name" type="text" class="form-control" placeholder="Document name">
                                <small class="text-danger validations" id='modal_dt_name_error'></small>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-primary w-100" type="submit">
                                <i class="bx bx-save me-1"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- end col -->
@endsection
@empty
<div class="text-center bg-light rounded p-4 mt-2">
    <i class='mdi mdi-text-box-remove-outline' style='font-size: 32px;'></i>
    <p>No Documents Found</p>
</div>
@endforelse