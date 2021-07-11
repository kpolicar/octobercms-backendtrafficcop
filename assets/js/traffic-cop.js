$(document).on('ajaxError', function (event, context, errorMsg, textStatus, jqXHR) {
    if (context.handler === 'onSave' && jqXHR.status === 409) {
        return false;
    }
});

$(document).on('ajaxSetup', function (event, context) {
    if (context.handler === 'onSave') {
        context.options.update = {
            'plugins/kpolicar/backendtrafficcop/layouts/retrievedat_formfield': '[js-retrieved-at]'
        };
    }
});

$(document).on('ajaxFail', function (event, context, textStatus, jqXHR) {
    if (context.handler === 'onSave' && jqXHR.status === 409) {
        $.oc.confirm(jqXHR.responseText, function () {
            $(event.target).request(context.handler, {
                data: {
                    _kpolicar_backendtrafficcop_confirmed: true
                }
            });
        })
    }
});
