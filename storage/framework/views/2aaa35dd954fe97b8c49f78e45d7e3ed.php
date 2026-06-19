<!-- SSR modal fallback: provides custom Promise-based wrappers for showAlert/showConfirm/showPrompt.
     The richer styled modal is provided by `resources/js/ModalService.js` and overrides these when loaded.
-->
<div id="ssr-modal-fallback-overlay" style="display:none;position:fixed;inset:0;z-index:99998;align-items:center;justify-content:center;background:rgba(2,6,23,0.62);backdrop-filter:blur(4px);padding:18px;">
    <div role="dialog" aria-modal="true" aria-labelledby="ssr-modal-fallback-title" style="width:min(92vw,520px);background:#0b1220;color:#e6eef8;border:1px solid rgba(255,255,255,0.08);border-radius:12px;padding:18px;box-shadow:0 20px 45px rgba(0,0,0,0.45);">
        <div id="ssr-modal-fallback-title" style="font-weight:800;font-size:18px;margin-bottom:8px;"></div>
        <div id="ssr-modal-fallback-message" style="color:#c9d6e8;line-height:1.45;margin-bottom:14px;white-space:pre-wrap;"></div>
        <div id="ssr-modal-fallback-input-wrap" style="display:none;margin-bottom:14px;">
            <input id="ssr-modal-fallback-input" type="text" style="width:100%;box-sizing:border-box;padding:10px 12px;border-radius:8px;border:1px solid rgba(255,255,255,0.12);background:#08101a;color:#e6eef8;">
        </div>
        <div style="display:flex;justify-content:flex-end;gap:8px;flex-wrap:wrap;">
            <button type="button" id="ssr-modal-fallback-cancel" style="background:transparent;border:1px solid rgba(255,255,255,0.12);color:#e6eef8;padding:8px 12px;border-radius:8px;cursor:pointer;">Batal</button>
            <button type="button" id="ssr-modal-fallback-ok" style="background:#6d28d9;border:0;color:#fff;padding:8px 12px;border-radius:8px;cursor:pointer;">OK</button>
        </div>
    </div>
</div>
<script>
(function(){
    if (window.showAlert && window.showConfirm && window.showPrompt) return;

    function openFallbackModal(options) {
        return new Promise(function(resolve) {
            var overlay = document.getElementById('ssr-modal-fallback-overlay');
            var title = document.getElementById('ssr-modal-fallback-title');
            var message = document.getElementById('ssr-modal-fallback-message');
            var inputWrap = document.getElementById('ssr-modal-fallback-input-wrap');
            var input = document.getElementById('ssr-modal-fallback-input');
            var ok = document.getElementById('ssr-modal-fallback-ok');
            var cancel = document.getElementById('ssr-modal-fallback-cancel');

            if (!overlay || !title || !message || !inputWrap || !input || !ok || !cancel) {
                resolve(options.type === 'confirm' ? false : (options.type === 'prompt' ? null : undefined));
                return;
            }

            title.innerText = options.title || '';
            message.innerText = options.message || '';
            ok.innerText = options.okText || 'OK';
            cancel.innerText = options.cancelText || 'Batal';
            cancel.style.display = options.type === 'alert' ? 'none' : '';
            inputWrap.style.display = options.type === 'prompt' ? 'block' : 'none';
            input.value = options.defaultValue || '';
            overlay.style.display = 'flex';

            function close(value) {
                overlay.style.display = 'none';
                ok.removeEventListener('click', onOk);
                cancel.removeEventListener('click', onCancel);
                overlay.removeEventListener('click', onOverlay);
                document.removeEventListener('keydown', onKeydown);
                resolve(value);
            }

            function onOk() {
                close(options.type === 'confirm' ? true : (options.type === 'prompt' ? input.value : undefined));
            }

            function onCancel() {
                close(options.type === 'confirm' ? false : null);
            }

            function onOverlay(event) {
                if (event.target === overlay) onCancel();
            }

            function onKeydown(event) {
                if (event.key === 'Escape') onCancel();
                if (event.key === 'Enter' && options.type === 'prompt') onOk();
            }

            ok.addEventListener('click', onOk);
            cancel.addEventListener('click', onCancel);
            overlay.addEventListener('click', onOverlay);
            document.addEventListener('keydown', onKeydown);

            if (options.type === 'prompt') {
                setTimeout(function(){ input.focus(); }, 30);
            } else {
                setTimeout(function(){ ok.focus(); }, 30);
            }
        });
    }

    window.showAlert = function(title, message, okText) {
        return openFallbackModal({ type: 'alert', title: title, message: message, okText: okText || 'OK' });
    };

    window.showConfirm = function(title, message, okText, cancelText) {
        return openFallbackModal({
            type: 'confirm',
            title: title,
            message: message,
            okText: okText || 'OK',
            cancelText: cancelText || 'Batal'
        });
    };

    window.showPrompt = function(title, message, defaultValue) {
        return openFallbackModal({
            type: 'prompt',
            title: title,
            message: message,
            defaultValue: defaultValue || '',
            okText: 'OK',
            cancelText: 'Batal'
        });
    };
})();
</script>

<script>
// Convert legacy inline confirmation handlers to the Promise-based custom modal.
document.addEventListener('DOMContentLoaded', function() {
    try {
        document.querySelectorAll('form[onsubmit]').forEach(form => {
            const attr = form.getAttribute('onsubmit') || '';
            const m = attr.match(/confirm\((['"])([\s\S]*?)\1\)/);
            if (m) {
                const message = m[2];
                form.removeAttribute('onsubmit');
                form.addEventListener('submit', async function(e) {
                    if (form.dataset.confirmed === '1') return;
                    e.preventDefault();
                    const ok = await window.showConfirm('Konfirmasi', message);
                    if (!ok) return;
                    form.dataset.confirmed = '1';
                    if (form.requestSubmit) {
                        form.requestSubmit();
                    } else {
                        form.submit();
                    }
                });
            }
        });

        document.querySelectorAll('[onclick]').forEach(el => {
            const attr = el.getAttribute('onclick') || '';
            const m = attr.match(/confirm\((['"])([\s\S]*?)\1\)/);
            if (m) {
                const message = m[2];
                const original = attr.replace(/confirm\((['"]).*?\1\)\s*;?/, '');
                el.removeAttribute('onclick');
                el.addEventListener('click', async function(event) {
                    const ok = await window.showConfirm('Konfirmasi', message);
                    if (!ok) {
                        event.preventDefault();
                        return;
                    }
                    if (original && original.trim()) {
                        try { new Function(original).call(this); } catch(err) { console.error(err); }
                    }
                });
            }
        });

        // Handle elements or forms with `data-confirm` attribute
        document.querySelectorAll('[data-confirm]').forEach(el => {
            const message = el.getAttribute('data-confirm');
            if (!message) return;
            if (el.tagName === 'FORM') {
                el.addEventListener('submit', async function(e) {
                    if (el.dataset.confirmed === '1') return;
                    e.preventDefault();
                    const ok = await window.showConfirm('Konfirmasi', message);
                    if (!ok) return;
                    el.dataset.confirmed = '1';
                    if (el.requestSubmit) {
                        el.requestSubmit();
                    } else {
                        el.submit();
                    }
                });
            } else {
                el.addEventListener('click', async function(e) {
                    const ok = await window.showConfirm('Konfirmasi', message);
                    if (!ok) e.preventDefault();
                });
            }
        });
    } catch (e) {
        console.error('modal-dialog fallback conversion error', e);
    }
});
</script>
<?php /**PATH C:\laragon\www\ProyekTI\resources\views/components/modal-dialog.blade.php ENDPATH**/ ?>