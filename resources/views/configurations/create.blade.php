{{-- <div class="modal fade" id="configCreateModalCenter" tabindex="-1" role="dialog" aria-labelledby="configCreateModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header text-center bg-success py-3">
                <h5 class="modal-title text-light" id="configCreateModalCenterTitle">Add New Configuration</h5>
                <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> --}}
<div class="modal fade" id="configCreateModal" tabindex="-1" role="dialog" aria-labelledby="configCreateModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header text-center bg-success py-3">
                <h5 class="modal-title text-light" id="configCreateModalLabel">Add New Configuration</h5>
                <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="configCreateForm" method="post">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group row mb-2">
                                <strong class="col-sm-2 text-dark">Key</strong>
                                <input type="text" name="key" class="key form-control col-sm-10"
                                    placeholder="New Key">
                            </div>
                            <div class="form-group row mb-2">
                                <strong class="col-sm-2 text-dark">value</strong>
                                <input type="text" name="value" class="value form-control col-sm-10"
                                    placeholder="New Value">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-outline-success">Create</button>
                </div>
            </form>

        </div>
    </div>
</div>
