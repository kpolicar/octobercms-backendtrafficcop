$(document).on('ajaxError', function (event, context, errorMsg, textStatus, jqXHR) {
    if (jqXHR.status === 409) {
        return false;
    }
});

$(document).on('ajaxSetup', function (event, context) {
    context.options.update = {
        'plugins/kpolicar/backendtrafficcop/layouts/retrievedat_formfield': '[js-retrieved-at]'
    };
});

$(document).on('ajaxFail', function (event, context, textStatus, jqXHR) {
    if (jqXHR.status === 409) {
        $.oc.confirm(jqXHR.responseText, function () {
            $(event.target).request(context.handler, {
                data: {
                    _confirmed: true
                }
            });
        })
    }
});
