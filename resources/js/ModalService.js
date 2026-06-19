class ModalService {
    static init() {
        if (this._inited) return;
        this._inited = true;
        const tpl = `
        <div id="global-modal-overlay" class="global-modal-overlay hidden" style="display:none;position:fixed;inset:0;z-index:99999;align-items:center;justify-content:center;background:rgba(2,6,23,0.6);backdrop-filter:blur(4px);">
            <div id="global-modal" style="width:92%;max-width:560px;background:linear-gradient(180deg,#0b1220,#08101a);color:#e6eef8;border-radius:12px;padding:18px;box-shadow:0 10px 30px rgba(2,6,23,0.7);">
                <div id="global-modal-title" style="font-weight:800;font-size:1.1rem;margin-bottom:8px"></div>
                <div id="global-modal-message" style="color:#c9d6e8;margin-bottom:12px;line-height:1.4"></div>
                <div id="global-modal-input" style="margin-bottom:12px"></div>
                <div style="display:flex;justify-content:flex-end;gap:8px">
                    <button id="global-modal-cancel" style="background:transparent;border:1px solid rgba(255,255,255,0.06);color:#e6eef8;padding:8px 12px;border-radius:8px;cursor:pointer">Batal</button>
                    <button id="global-modal-ok" style="background:#6d28d9;border:none;color:white;padding:8px 12px;border-radius:8px;cursor:pointer">OK</button>
                </div>
            </div>
        </div>`;

        document.addEventListener("DOMContentLoaded", () => {
            document.body.insertAdjacentHTML("beforeend", tpl);
            this._bind();
        });

        // If DOM already loaded
        if (
            document.readyState === "interactive" ||
            document.readyState === "complete"
        ) {
            if (!document.getElementById("global-modal-overlay")) {
                document.body.insertAdjacentHTML("beforeend", tpl);
                this._bind();
            }
        }
    }

    static _bind() {
        this._overlay = document.getElementById("global-modal-overlay");
        this._title = document.getElementById("global-modal-title");
        this._message = document.getElementById("global-modal-message");
        this._input = document.getElementById("global-modal-input");
        this._ok = document.getElementById("global-modal-ok");
        this._cancel = document.getElementById("global-modal-cancel");

        this._ok.addEventListener("click", () => {
            if (!this._resolver) return;
            const inputEl = this._input.querySelector("input, textarea");
            const value = inputEl ? inputEl.value : null;
            const resolve = this._resolver;
            this._resolver = null;
            this.hide();
            resolve({ ok: true, value });
        });

        this._cancel.addEventListener("click", () => {
            if (!this._resolver) return;
            const resolve = this._resolver;
            this._resolver = null;
            this.hide();
            resolve({ ok: false, value: null });
        });

        this._overlay.addEventListener("click", (e) => {
            if (e.target === this._overlay) {
                if (!this._resolver) return;
                const resolve = this._resolver;
                this._resolver = null;
                this.hide();
                resolve({ ok: false, value: null });
            }
        });

        document.addEventListener("keydown", (e) => {
            if (!this._overlay || this._overlay.style.display === "none")
                return;
            if (e.key === "Escape") {
                if (!this._resolver) return;
                const resolve = this._resolver;
                this._resolver = null;
                this.hide();
                resolve({ ok: false, value: null });
            }
        });
    }

    static async showConfirm(
        title = "",
        message = "",
        okText = "OK",
        cancelText = "Batal",
    ) {
        this.init();
        await this._ensureBound();
        this._title.innerText = title || "";
        this._message.innerHTML = message || "";
        this._input.innerHTML = "";
        this._ok.innerText = okText;
        this._cancel.innerText = cancelText;
        return new Promise((resolve) => {
            this._resolver = ({ ok, value }) => resolve(ok);
            this._overlay.style.display = "flex";
        });
    }

    static async showAlert(title = "", message = "", okText = "OK") {
        this.init();
        await this._ensureBound();
        this._title.innerText = title || "";
        this._message.innerHTML = message || "";
        this._input.innerHTML = "";
        this._ok.innerText = okText;
        this._cancel.style.display = "none";
        return new Promise((resolve) => {
            this._resolver = ({ ok, value }) => {
                this._cancel.style.display = "";
                resolve();
            };
            this._overlay.style.display = "flex";
        });
    }

    static async showPrompt(title = "", message = "", placeholder = "") {
        this.init();
        await this._ensureBound();
        this._title.innerText = title || "";
        this._message.innerHTML = message || "";
        this._input.innerHTML = `<input type="text" placeholder="${placeholder}" style="width:100%;padding:10px;border-radius:8px;border:1px solid rgba(255,255,255,0.06);background:transparent;color:#e6eef8">`;
        this._ok.innerText = "OK";
        this._cancel.innerText = "Batal";
        return new Promise((resolve) => {
            this._resolver = ({ ok, value }) => resolve(ok ? value : null);
            this._overlay.style.display = "flex";
            const inputEl = this._input.querySelector("input");
            if (inputEl) setTimeout(() => inputEl.focus(), 50);
        });
    }

    static hide() {
        if (this._overlay) this._overlay.style.display = "none";
    }

    static _ensureBound() {
        return new Promise((resolve) => {
            if (this._overlay) return resolve();
            const id = setInterval(() => {
                if (this._overlay) {
                    clearInterval(id);
                    resolve();
                }
            }, 50);
        });
    }
}

// Expose Promise-based helpers on `window` when this module is imported.
// This will override the lightweight SSR fallback defined in the Blade partial.
if (typeof window !== "undefined") {
    window.showConfirm = (...args) => ModalService.showConfirm(...args);
    window.showAlert = (...args) => ModalService.showAlert(...args);
    window.showPrompt = (...args) => ModalService.showPrompt(...args);
}

export default ModalService;
