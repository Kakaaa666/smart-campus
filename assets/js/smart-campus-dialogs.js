(function(window, document, $) {
    'use strict';

    if (!$ || !$.fn.modal) return;

    var queue = Promise.resolve();

    function renderDialog(options) {
        return new Promise(function(resolve) {
            var kind = options.kind || 'alert';
            var isPrompt = kind === 'prompt';
            var isConfirm = kind === 'confirm';
            var result = isConfirm ? false : null;
            var settled = false;
            var modal = document.createElement('div');
            modal.className = 'modal fade sc-dialog';
            modal.tabIndex = -1;
            modal.setAttribute('role', 'dialog');
            modal.setAttribute('aria-modal', 'true');

            var dialog = document.createElement('div');
            dialog.className = 'modal-dialog modal-dialog-centered';
            var content = document.createElement('div');
            content.className = 'modal-content';
            var header = document.createElement('div');
            header.className = 'modal-header';
            var title = document.createElement('h5');
            title.className = 'modal-title';
            title.textContent = options.title || (isPrompt ? 'Masukkan Data' : isConfirm ? 'Konfirmasi' : 'Informasi');
            var close = document.createElement('button');
            close.type = 'button';
            close.className = 'close';
            close.setAttribute('aria-label', 'Tutup');
            close.innerHTML = '<span aria-hidden="true">&times;</span>';
            header.appendChild(title);
            header.appendChild(close);

            var body = document.createElement('div');
            body.className = 'modal-body';
            var message = document.createElement('p');
            message.className = 'sc-dialog-message';
            message.textContent = options.message || '';
            body.appendChild(message);

            var input = null;
            var form = null;
            if (isPrompt) {
                form = document.createElement('form');
                input = document.createElement('input');
                input.type = options.inputType || 'text';
                input.className = 'form-control';
                input.value = options.defaultValue || '';
                input.placeholder = options.placeholder || '';
                input.required = Boolean(options.required);
                form.appendChild(input);
                body.appendChild(form);
            }

            var footer = document.createElement('div');
            footer.className = 'modal-footer';
            var cancel = document.createElement('button');
            cancel.type = 'button';
            cancel.className = 'btn btn-light sc-dialog-cancel';
            cancel.textContent = options.cancelText || 'Batal';
            var accept = document.createElement('button');
            accept.type = 'button';
            accept.className = 'btn btn-primary sc-dialog-accept';
            accept.textContent = options.acceptText || (isPrompt ? 'Simpan' : isConfirm ? 'Lanjutkan' : 'Mengerti');
            if (isConfirm || isPrompt) footer.appendChild(cancel);
            footer.appendChild(accept);

            content.appendChild(header);
            content.appendChild(body);
            content.appendChild(footer);
            dialog.appendChild(content);
            modal.appendChild(dialog);
            document.body.appendChild(modal);

            function finish(value) {
                if (settled) return;
                result = value;
                settled = true;
                $(modal).modal('hide');
            }

            accept.addEventListener('click', function() {
                if (isPrompt) {
                    if (!input.reportValidity()) return;
                    finish(input.value);
                } else {
                    finish(isConfirm ? true : true);
                }
            });
            cancel.addEventListener('click', function() { finish(isConfirm ? false : null); });
            close.addEventListener('click', function() { finish(isConfirm ? false : null); });
            if (form) {
                form.addEventListener('submit', function(event) {
                    event.preventDefault();
                    accept.click();
                });
            }

            $(modal).one('shown.bs.modal', function() {
                if (input) input.focus();
                else accept.focus();
            });
            $(modal).one('hidden.bs.modal', function() {
                modal.remove();
                resolve(result);
            });
            $(modal).modal({ backdrop: 'static', keyboard: true, show: true });
        });
    }

    function show(options) {
        var result = queue.then(function() { return renderDialog(options); });
        queue = result.then(function() {}, function() {});
        return result;
    }

    window.SCDialog = {
        alert: function(message, options) {
            return show(Object.assign({}, options || {}, { kind: 'alert', message: message }));
        },
        confirm: function(message, options) {
            return show(Object.assign({}, options || {}, { kind: 'confirm', message: message }));
        },
        prompt: function(message, options) {
            return show(Object.assign({}, options || {}, { kind: 'prompt', message: message }));
        }
    };

    document.addEventListener('submit', function(event) {
        var form = event.target;
        var submitter = event.submitter;
        var message = form.getAttribute('data-sc-confirm') || (submitter && submitter.getAttribute('data-sc-confirm'));
        if (!message) return;
        if (form.dataset.scConfirmed === 'true') {
            delete form.dataset.scConfirmed;
            return;
        }

        event.preventDefault();
        window.SCDialog.confirm(message).then(function(confirmed) {
            if (!confirmed) return;
            form.dataset.scConfirmed = 'true';
            if (form.requestSubmit) form.requestSubmit(submitter || undefined);
            else form.submit();
        });
    }, true);
})(window, document, window.jQuery);
