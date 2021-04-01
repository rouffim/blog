class DomManager {

    static deleteAll(query, container = document) {
        container.querySelectorAll(query).forEach(element => element.remove());
    }

    static handleFormSubmitSuccess(form, redirect = "/") {
        AlertManager.displaySuccessAlert();

        if(redirect) {
            form.replaceWith(DomManager.createWaitSpinner());

            setTimeout(() => {
                window.location.href = redirect;
            }, 750);
        }
    }

    static handleFormSubmitError(form, err) {
        if(err.status === 400) {
            AlertManager.displayWarningAlert();
            DomManager.displayFormErrors(form, err.response);
        } else if(err.status === 403) {
            AlertManager.displayErrorAlert("Vous n'avez pas le droit d'éffectuer cette action.");
        } else if(err.status === 409) {
            AlertManager.displayErrorAlert("Une version plus récente à déjà été mise à jour. Merci de recharger le formulaire pour avoir la version la plus à jour.");
        } else {
            AlertManager.displayErrorAlert();
        }
    }

    static displayFormErrors(form, errors) {
        errors = JSON.parse(errors);

        // Remove all current valid and invalid classes of form controls
        form.querySelectorAll('.form-control').forEach((control) => {
            control.classList.remove('is-invalid');
            control.classList.remove('is-valid');
        });

        if(Array.isArray(errors)) {
            errors.forEach((error) => DomManager.displayFormError(form, error));
        }

        // Remove valid class to all form controls not invalid
        form.querySelectorAll('.form-control:not(.is-invalid)').forEach((control) => {
            control.classList.add('is-valid');
        })
    }

    static displayFormError(form, error) {
        let input = form.querySelector('[class="form-control"][name="' + error.key + '"]');
        let feedback = input.nextElementSibling;

        input.classList.add('is-invalid');
        feedback.textContent = error.message;
    }

    static createWaitSpinner() {
        let spinnerParent = document.createElement("div");
        spinnerParent.classList.add('text-center');

        let spinnerContainer = document.createElement("div");
        spinnerContainer.classList.add('spinner-border');
        spinnerContainer.setAttribute("role", "status");
        spinnerParent.appendChild(spinnerContainer);

        let spinner = document.createElement("span");
        spinner.classList.add('visually-hidden');
        spinnerContainer.appendChild(spinner);

        return spinnerParent;
    }

    static enabledBtn(btn, enabled = true) {
        btn.attributes.getNamedItem('aria-disabled').value = String(!enabled);
        btn.classList.toggle('disabled', !enabled);
    }

    static toggle(element, show) {
        element.classList.toggle('d-none', show !== undefined ? !show : undefined);
    }

}
