class AlertManager {
    static DEFAULT_SUCCESS_MESSAGE = "Opération effectuée avec succès !";
    static DEFAULT_WARNING_MESSAGE = "Attention, certains éléments requière votre attention.";
    static DEFAULT_ERROR_MESSAGE = "Une erreur imprévue est survenue...";
    static ALERT_SUCCESS = "success";
    static ALERT_WARNING = "warning";
    static ALERT_ERROR = "danger";


    static displaySuccessAlert(message = AlertManager.DEFAULT_SUCCESS_MESSAGE) {
        AlertManager.displayAlert(message, AlertManager.ALERT_SUCCESS);
    }

    static displayWarningAlert(message = AlertManager.DEFAULT_WARNING_MESSAGE) {
        AlertManager.displayAlert(message, AlertManager.ALERT_WARNING);
    }

    static displayErrorAlert(message = AlertManager.DEFAULT_ERROR_MESSAGE) {
        AlertManager.displayAlert(message, AlertManager.ALERT_ERROR);
    }

    static displayAlert(message, type) {
        let alertContainer = document.createElement("div");

        alertContainer.classList.add('alert');
        alertContainer.classList.add('alert-' + type);
        alertContainer.classList.add('alert-dismissible');
        alertContainer.classList.add('fade');
        alertContainer.classList.add('show');
        alertContainer.setAttribute("role", "alert");
        alertContainer.textContent = message;

        let alertCloseBtn = document.createElement("button");
        alertCloseBtn.classList.add('btn-close');
        alertCloseBtn.setAttribute("type", "button");
        alertCloseBtn.setAttribute("data-bs-dismiss", "alert");
        alertCloseBtn.setAttribute("aria-label", "Close");
        alertContainer.appendChild(alertCloseBtn);

        DomManager.deleteAll('.alert');

        document.body.appendChild(alertContainer);

        setTimeout(() => {
            // We should use bootstrap for dismiss https://getbootstrap.com/docs/5.0/components/alerts/#dismissing
            alertCloseBtn.click();
        }, 3000);
    }

}
