/**
 * Smart Campus - Custom Web Dialog System (SCDialog)
 * Menggantikan seluruh dialog bawaan peramban ("localhost says" alert, confirm, prompt)
 * dengan modal dialog terintegrasi bernuansa modern khas Smart Campus.
 * Berjalan mandiri tanpa dependensi wajib (zero-dependency vanilla JS), kompatibel dengan Bootstrap.
 */
(function(window, document) {
    'use strict';

    if (window.SCDialog && window.SCDialog.__initialized) {
        return;
    }

    var queue = Promise.resolve();

    function getIconHtml(type) {
        switch (type) {
            case 'success':
                return '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>';
            case 'warning':
                return '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>';
            case 'danger':
            case 'error':
                return '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>';
            case 'confirm':
            case 'info':
            default:
                return '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#0284c7" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>';
        }
    }

    function renderDialog(options) {
        return new Promise(function(resolve) {
            var kind = options.kind || 'alert';
            var isPrompt = kind === 'prompt';
            var isConfirm = kind === 'confirm';
            var type = options.type || (isConfirm ? 'confirm' : (kind === 'alert' ? 'info' : 'info'));
            var result = isConfirm ? false : null;
            var settled = false;

            // Buat overlay container
            var overlay = document.createElement('div');
            overlay.className = 'sc-dialog-overlay';

            // Dialog modal box
            var card = document.createElement('div');
            card.className = 'sc-dialog-card';
            card.setAttribute('role', 'dialog');
            card.setAttribute('aria-modal', 'true');

            // Header
            var header = document.createElement('div');
            header.className = 'sc-dialog-header';

            var iconWrap = document.createElement('div');
            iconWrap.className = 'sc-dialog-icon sc-dialog-icon-' + type;
            iconWrap.innerHTML = getIconHtml(type);

            var titleEl = document.createElement('h5');
            titleEl.className = 'sc-dialog-title';
            titleEl.textContent = options.title || (isPrompt ? 'Masukkan Data' : (isConfirm ? 'Konfirmasi Tindakan' : 'Pemberitahuan Sistem'));

            var closeBtn = document.createElement('button');
            closeBtn.type = 'button';
            closeBtn.className = 'sc-dialog-close';
            closeBtn.setAttribute('aria-label', 'Tutup');
            closeBtn.innerHTML = '&times;';

            header.appendChild(iconWrap);
            header.appendChild(titleEl);
            header.appendChild(closeBtn);

            // Body
            var body = document.createElement('div');
            body.className = 'sc-dialog-body';

            var messageEl = document.createElement('div');
            messageEl.className = 'sc-dialog-message';
            messageEl.innerHTML = (options.message || '').replace(/\n/g, '<br>');
            body.appendChild(messageEl);

            var input = null;
            var form = null;
            if (isPrompt) {
                form = document.createElement('form');
                form.className = 'mt-3';
                input = document.createElement('input');
                input.type = options.inputType || 'text';
                input.className = 'form-control sc-dialog-input';
                input.value = options.defaultValue || '';
                input.placeholder = options.placeholder || '';
                input.required = Boolean(options.required);
                form.appendChild(input);
                body.appendChild(form);
            }

            // Footer
            var footer = document.createElement('div');
            footer.className = 'sc-dialog-footer';

            var cancelBtn = document.createElement('button');
            cancelBtn.type = 'button';
            cancelBtn.className = 'btn btn-light sc-dialog-btn sc-dialog-cancel';
            cancelBtn.textContent = options.cancelText || 'Batal';

            var acceptBtn = document.createElement('button');
            acceptBtn.type = 'button';
            var acceptClass = 'btn sc-dialog-btn sc-dialog-accept ';
            if (type === 'danger' || type === 'error') {
                acceptClass += 'btn-danger';
            } else if (type === 'success' || (options.title && options.title.toLowerCase().indexOf('setuju') !== -1)) {
                acceptClass += 'btn-success';
            } else {
                acceptClass += 'btn-primary';
            }
            acceptBtn.className = acceptClass;
            acceptBtn.textContent = options.acceptText || (isPrompt ? 'Simpan' : (isConfirm ? 'Lanjutkan' : 'Mengerti'));

            if (isConfirm || isPrompt) {
                footer.appendChild(cancelBtn);
            }
            footer.appendChild(acceptBtn);

            card.appendChild(header);
            card.appendChild(body);
            card.appendChild(footer);
            overlay.appendChild(card);
            document.body.appendChild(overlay);

            // Trigger animation
            requestAnimationFrame(function() {
                overlay.classList.add('sc-dialog-active');
                card.classList.add('sc-dialog-active');
            });

            function cleanup(val) {
                if (settled) return;
                settled = true;
                result = val;

                overlay.classList.remove('sc-dialog-active');
                card.classList.remove('sc-dialog-active');

                setTimeout(function() {
                    if (overlay.parentNode) {
                        overlay.parentNode.removeChild(overlay);
                    }
                    resolve(result);
                }, 200);
            }

            acceptBtn.addEventListener('click', function() {
                if (isPrompt) {
                    if (input && !input.reportValidity()) return;
                    cleanup(input ? input.value : '');
                } else {
                    cleanup(true);
                }
            });

            cancelBtn.addEventListener('click', function() {
                cleanup(isConfirm ? false : null);
            });

            closeBtn.addEventListener('click', function() {
                cleanup(isConfirm ? false : null);
            });

            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    acceptBtn.click();
                });
            }

            // Keyboard navigation: Escape cancels, Enter on accept
            function keyHandler(e) {
                if (e.key === 'Escape') {
                    document.removeEventListener('keydown', keyHandler);
                    cleanup(isConfirm ? false : null);
                }
            }
            document.addEventListener('keydown', keyHandler);

            // Auto-focus
            setTimeout(function() {
                if (input) input.focus();
                else acceptBtn.focus();
            }, 50);
        });
    }

    function show(options) {
        var result = queue.then(function() { return renderDialog(options); });
        queue = result.then(function() {}, function() {});
        return result;
    }

    var SCDialog = {
        __initialized: true,
        alert: function(message, options) {
            var opts = Object.assign({}, options || {}, { kind: 'alert', message: String(message || '') });
            return show(opts);
        },
        confirm: function(message, options) {
            var opts = Object.assign({}, options || {}, { kind: 'confirm', message: String(message || '') });
            return show(opts);
        },
        prompt: function(message, options) {
            var opts = Object.assign({}, options || {}, { kind: 'prompt', message: String(message || '') });
            return show(opts);
        }
    };

    window.SCDialog = SCDialog;

    // Helper global seragam
    window.scAlert = function(message, title, type) {
        return SCDialog.alert(message, { title: title || 'Informasi', type: type || 'info' });
    };

    window.scConfirm = function(message, title, acceptText, cancelText, type) {
        return SCDialog.confirm(message, {
            title: title || 'Konfirmasi',
            acceptText: acceptText || 'Lanjutkan',
            cancelText: cancelText || 'Batal',
            type: type || 'confirm'
        });
    };

    // Override alert bawaan peramban agar tidak ada "localhost says" sama sekali
    try {
        window.alert = function(msg) {
            return SCDialog.alert(msg, { title: 'Pemberitahuan Sistem', type: 'info' });
        };
    } catch (e) {
        // Abaikan jika read-only
    }

    // Intersepsi form submission dengan data-sc-confirm atau data-confirm
    document.addEventListener('submit', function(event) {
        var form = event.target;
        var submitter = event.submitter;
        var message = (submitter && (submitter.getAttribute('data-sc-confirm') || submitter.getAttribute('data-confirm')))
                   || form.getAttribute('data-sc-confirm')
                   || form.getAttribute('data-confirm');

        if (!message) return;

        if (form.dataset.scConfirmed === 'true') {
            delete form.dataset.scConfirmed;
            return;
        }

        event.preventDefault();
        var title = (submitter && submitter.getAttribute('data-confirm-title')) || 'Konfirmasi Tindakan';
        var acceptText = (submitter && submitter.getAttribute('data-confirm-accept')) || 'Ya, Lanjutkan';

        SCDialog.confirm(message, { title: title, acceptText: acceptText }).then(function(confirmed) {
            if (!confirmed) return;
            form.dataset.scConfirmed = 'true';

            // Jika submitter memiliki name & value, pastikan terbawa saat submit
            if (submitter && submitter.name && !form.querySelector('input[name="' + submitter.name + '"]')) {
                var hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = submitter.name;
                hiddenInput.value = submitter.value;
                hiddenInput.id = 'tempSubmitter_' + submitter.name;
                form.appendChild(hiddenInput);
            }

            if (form.requestSubmit) {
                form.requestSubmit(submitter || undefined);
            } else {
                form.submit();
            }
        });
    }, true);

})(window, document);
