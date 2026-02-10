// crudHandler.js
class CrudHandler {
    constructor(config) {
        this.routes = config.routes;
        this.modals = config.modals;
        this.ajax = new AjaxService();
        this.init();
    }

    init() {
        this.handleCreate();
        this.handleUpdate();
        this.handleDelete();
        this.handleRead();
    }

    handleCreate() {
        $(this.modals.create.form).on('submit', (e) => {
            e.preventDefault();
            let fd = new FormData(e.target);

            this.ajax.request({
                url: this.routes.store,
                data: fd,
                success: (res) => this.success(res),
                error: (err) => this.error(err),
            });
        });
    }

    handleUpdate() {
        $('.edit-btn').on('click', (e) => {
            Object.entries($(e.currentTarget).data()).forEach(([key, val]) => {
                $(`#edit_${key}`).val(val).trigger('change');
            });
            $(this.modals.update.modal).modal('show');
        });

        $(this.modals.update.form).on('submit', (e) => {
            e.preventDefault();
            let fd = new FormData(e.target);
            const id = $('#edit_id').val();
            let url = this.routes.update.replace(':id', id);

            this.ajax.request({
                url: url,
                data: fd,
                success: (res) => this.success(res),
                error: (err) => this.error(err),
            });
        });
    }

    handleDelete() {
        $('.delete-btn').on('click', (e) => {
            $('#delete_id').val($(e.currentTarget).data('id'));
            $(this.modals.delete.modal).modal('show');
        });

        $(this.modals.delete.form).on('submit', (e) => {
            e.preventDefault();
            const id = $('#delete_id').val();
            let url = this.routes.delete.replace(':id', id);

            this.ajax.request({
                url: url,
                data: $(e.target).serialize(),
                success: (res) => this.success(res),
                error: (err) => this.error(err),
            });
        });
    }

    handleRead() {
        $('.read-btn').on('click', (e) => {
            const id = $(e.currentTarget).data('id');
            let url = this.routes.read.replace(':id', id);

            this.ajax.request({
                method: 'GET',
                url: url,
                success: (res) => {
                    // populate modal fields
                    Object.entries(res.data).forEach(([key, val]) => {
                        $(`#show_${key}`).text(val);
                    });
                    $(this.modals.read.modal).modal('show');
                },
                error: (err) => this.error(err),
            });
        });
    }

    success(res) {
        toastr.success(res.success || 'Success');
        setTimeout(() => window.location.reload(), 1000);
    }

    error(err) {
        if (err.responseJSON?.error) toastr.error(err.responseJSON.error);
        if (err.responseJSON?.errors) {
            Object.values(err.responseJSON.errors).flat().forEach(msg => toastr.error(msg));
        }
    }
}

// make globally accessible
window.CrudHandler = CrudHandler;
