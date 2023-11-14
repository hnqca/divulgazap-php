class GroupForm {
    constructor() {
        this.elements = {
            form: {
                create: $('#form-create-group')
            },
            group: {
                image:       $('.group-image'),
                name:        $('.group-name'),
                category:    $('.group-category-name'),
                description: $('.group-description')
            },
            input: {
                name:        $('#name'),
                link:        $('#link'),
                description: $('#description'),
                id_category: $('#id_category') 
            },
            warning:   $('#warning-create-group'),
            btnSubmit: $('#btn-submit'),
            loading:   $('#loading'),
            btnText:   $('#text')
        };
        this.isLinkValid = false;
        this.setupEventHandlers();
    }

    sanitizeLinkGroup(link) {
        return link.replace("https://chat.whatsapp.com/", '') || link;
    }

    enableButton(status) {
        this.elements.btnSubmit.prop('disabled', !status);
        if (!status) {
            this.elements.btnText.addClass("d-none");
            this.elements.loading.removeClass("d-none");
        } else {
            this.elements.btnText.removeClass("d-none");
            this.elements.loading.addClass("d-none");
        }
    }

    showAlert(message, status) {
        setAlert({
            element: this.elements.warning,
            message: message,
            color: (status === 'success' ? 'success' : 'warning')
        });
    }

    setGroupDataInElements({ name, image }) {
        this.elements.group.image.attr('src', image);
        this.elements.group.name.text(name);
        this.elements.input.name.val(name);

        this.elements.input.id_category.val(1).change();
    }

    validateLinkGroup = async ({ link }) => {
        const response = await sendRequestToAPI('POST', 'api/validate-link', { link });
        const { status = 'error', group = null } = response.data;

        this.enableButton(true);

        if (status !== "success") {
            this.showAlert(response.data.message, status);
            return;
        }

        this.setGroupDataInElements(group);
        this.isLinkValid = true;

        $('#first-step').addClass('d-none');
        $('#second-step').removeClass('d-none');
        this.elements.btnSubmit.find("#text").text("Enviar Grupo");
    }

    createGroup = async (data) => {
        const response = await sendRequestToAPI('POST', 'api/group', data);
        const { status = 'error' } = response.data;

        this.enableButton(true);

        const message = (status === 'success' ? "Grupo cadastrado com sucesso!" : response.data.message);
        this.showAlert(message, status);

        if (status !== 'success') {
            return;
        }

        this.isLinkValid = false;
        grecaptcha.reset();

        $('#first-step').removeClass('d-none');
        $('#second-step').addClass('d-none');
    }

    setupEventHandlers() {
        this.elements.form.create.on('submit', (e) => {
            e.preventDefault();
            const recaptchaResponse = grecaptcha.getResponse();

            const formData = {
                link:        this.sanitizeLinkGroup(this.elements.input.link.val()),
                id_category: this.elements.input.id_category.find(":selected").val(),
                name:        this.elements.input.name.val(),
                description: this.elements.input.description.val() || "",
                recaptchaResponse: recaptchaResponse
            }

            this.enableButton(false);

            if (!this.isLinkValid) {
                this.validateLinkGroup(formData);
                return;
            }

            if (formData.recaptchaResponse === "") {
                this.showAlert("Por favor, complete o reCAPTCHA");
                this.enableButton(true);
                return;
            }

            this.createGroup(formData);
        });

        this.elements.input.id_category.on('change', () => {
            const nameSelected = this.elements.input.id_category.find('option:selected').text();
            this.elements.group.category.text(nameSelected);
        });

        this.elements.input.name.on('keyup', () => {
            const name = this.elements.input.name.val();
            this.elements.group.name.text(name);
        });

        this.elements.input.description.on('keyup', () => {
            const description = this.elements.input.description.val();
            this.elements.group.description.text(description);
        });
    }
}

// Instantiate the GroupForm class
const GroupPage = new GroupForm();