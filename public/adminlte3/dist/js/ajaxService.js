// ajaxService.js
class AjaxService {
    constructor(csrfToken) {
        this.csrfToken = csrfToken || $('meta[name="csrf-token"]').attr('content');
    }

    request({ method = 'POST', url, data, success, error }) {
        $.ajax({
            type: method,
            url: url,
            data: data,
            contentType: data instanceof FormData ? false : 'application/x-www-form-urlencoded; charset=UTF-8',
            processData: !(data instanceof FormData),
            headers: { 'X-CSRF-TOKEN': this.csrfToken },
            success: function(res) {
                if (typeof success === 'function') success(res); 
                else AjaxService.defaultSuccess(res);          
            },
            error: function(err) {
                if (typeof error === 'function') error(err);   
                else AjaxService.defaultError(err);            
            }
        });
    }

    // Default success behavior
    static defaultSuccess(res) {
        toastr.success(res.success || 'Success');
        setTimeout(() => window.location.reload(), 1000);
    }

    // Default error behavior
    static defaultError(err) {
        if (err.responseJSON?.error) toastr.error(err.responseJSON.error);
        if (err.responseJSON?.errors) {
            Object.values(err.responseJSON.errors).flat().forEach(msg => toastr.error(msg));
        }
    }
}

// make it globally accessible
window.AjaxService = AjaxService;
