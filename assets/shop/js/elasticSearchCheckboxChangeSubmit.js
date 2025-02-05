export default class ElasticSearchCheckboxChangeSubmit {
    constructor(config = {}) {
        this.config = config;
        this.defaultConfig = {
            checkboxSelector: '.bitbag-sylius-elasticsearch-plugin-facets-form input[type="checkbox"]',
            formSelector: 'form',
        };
        this.finalConfig = {...this.defaultConfig, ...config};
    }

    init() {
        const checkboxes = document.querySelectorAll(this.finalConfig.checkboxSelector);
        checkboxes.forEach((checkbox) => {
            checkbox.addEventListener('change', () => {
                this._submitForm(checkbox);
            });
        });
    }

    _submitForm(checkbox) {
        const form = checkbox.closest(this.finalConfig.formSelector);
        if (form) {
            form.submit();
        }
    }
}
