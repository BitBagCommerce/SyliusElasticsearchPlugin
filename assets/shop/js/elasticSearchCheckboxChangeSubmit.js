export default class ElasticSearchCheckboxChangeSubmit {
    constructor(config = {}) {
        this.config = config;
        this.defaultConfig = {
            checkboxSelector: '.bitbag-sylius-elasticsearch-plugin-facets-form input[type="checkbox"]',
            priceFilterSelector: '.bitbag-sylius-elasticsearch-plugin-price-filter',
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

        const priceFilters = document.querySelectorAll(this.finalConfig.priceFilterSelector);
        priceFilters.forEach((priceFilter) => {
            this._initPriceFilter(priceFilter);
        });
    }

    _initPriceFilter(priceFilter) {
        const valuesBeforeEditing = this._readValues(priceFilter);

        priceFilter.addEventListener('keydown', (event) => {
            if ('Enter' !== event.key) {
                return;
            }

            event.preventDefault();
            this._submitForm(priceFilter);
        });

        priceFilter.addEventListener('focusout', () => {
            window.setTimeout(() => {
                if (priceFilter.contains(document.activeElement)) {
                    return;
                }

                if (this._readValues(priceFilter) !== valuesBeforeEditing) {
                    this._submitForm(priceFilter);
                }
            }, 0);
        });
    }

    _readValues(priceFilter) {
        return [...priceFilter.querySelectorAll('input')].map((input) => input.value).join('|');
    }

    _submitForm(element) {
        const form = element.closest(this.finalConfig.formSelector);
        if (form) {
            form.submit();
        }
    }
}
