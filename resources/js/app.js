import ModalService from "./ModalService";

// Initialize modal service and expose global helpers
ModalService.init();
window.showConfirm = (title, message, okText = "OK", cancelText = "Batal") =>
    ModalService.showConfirm(title, message, okText, cancelText);
window.showAlert = (title, message, okText = "OK") =>
    ModalService.showAlert(title, message, okText);
window.showPrompt = (title, message, placeholder = "") =>
    ModalService.showPrompt(title, message, placeholder);

// Export for other modules
export { ModalService };
