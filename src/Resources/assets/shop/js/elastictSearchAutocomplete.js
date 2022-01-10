export default class ElasticSearchAutocomplete {
    constructor(
        config = {
            searchFields: '.searchdiv',
            baseAutocompleteVariantUrl: '[data-bb-elastic-url]',
            searchInput: '.app-quick-add-code-input',
            resultsTarget: '.results',
            resultContainerClassesArray: ['result'],
            resultImageClass: 'image',
            resultContentClass: 'result__content',
            resultPriceClass: 'result__price',
            resultTitleClass: 'js-title',
            resultDescriptionClass: 'result__description',
        }
    ) {
        this.searchFieldsSelector = config.searchFields;
        this.searchFields = document.querySelectorAll(config.searchFields);
        this.baseAutocompleteVariantUrl = config.baseAutocompleteVariantUrl;
        this.searchInput = config.searchInput;
        this.resultsTarget = config.resultsTarget;
        this.resultContainerClassesArray = config.resultContainerClassesArray;
        this.resultImageClass = config.resultImageClass;
        this.resultContentClass = config.resultContentClass;
        this.resultPriceClass = config.resultPriceClass;
        this.resultTitleClass = config.resultTitleClass;
        this.resultDescriptionClass = config.resultDescriptionClass;
    }

    _toggleModalVisibility(elements) {
        document.addEventListener('variantsVisible', () => {
            document.addEventListener('click', () => {
                elements.forEach((element) => {
                    element.innerHTML = '';
                    element.style.display = 'none';
                });
            });
        });
    }

    _assignElements(entry, data) {
        const currentResults = entry.closest(this.searchFieldsSelector).querySelector(this.resultsTarget);
        currentResults.innerHTML = ''
        currentResults.style = 'visibility: visible';

        const allResults = document.querySelectorAll(this.resultsTarget);
         
        if (data.items.length === 0) {
            currentResults.innerHTML = '<center class="result">no matching results</center>';
        }

        data.items = data.items.sort((a,b) => {
            if (b.taxon_name < a.taxon_name) return 1;
            if (b.taxon_name > a.taxon_name) return -1;
            return 0;
        });
            console.log(data.items);
        let itemTemp;
        data.items.forEach((item) => {
            
            let category = item.taxon_name;
            let categoryStyle = "visibility: visible"
            if (itemTemp == item.taxon_name) {
                categoryStyle = "visibility: hidden";
            }

            const result = document.createElement('a');
            result.classList.add(...this.resultContainerClassesArray, 'js-result');
            result.innerHTML = `
            <h3 class="result__category" style=${categoryStyle}>${category}</h3> 
                <a href=${item.slug} class="result__link">
                    <div class="result__container">
                        <img class="result__image" src=${item.image}>
                        <div class=${this.resultContentClass}>
                            <div class=${this.resultTitleClass}>${item.name}</div>
                            <div class=${this.resultPriceClass}>${item.price}</div>
                        </div>
                        
                    </div>
                </a> 
            `;

            itemTemp = item.taxon_name;
            currentResults.appendChild(result);
        });

        currentResults.style.display = 'block';
        this._toggleModalVisibility(allResults);

        const customEvent = new CustomEvent('variantsVisible');
        document.dispatchEvent(customEvent);
    }

    async _getProducts(entry) {
        const variantUrl = document.querySelector(this.baseAutocompleteVariantUrl).dataset.bbElasticUrl;
        const url = `${variantUrl}?query=${entry.value}`;
            
        entry.parentNode.classList.add('loading');

        try {
            const response = await fetch(url);
            const data = await response.json();
             
            this._assignElements(entry, data);
        } catch (error) {
            console.error(error);
        } finally {
            entry.parentNode.classList.remove('loading');
        }
    }

    _debounce() {
        const codeInputs = document.querySelectorAll(this.searchInput);
        
        let timeout;
        
        codeInputs.forEach((input) => {
            input.addEventListener('input', () => {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    this._getProducts(input);
                }, 400);
            });
        });
    }

    init() {
        if (this.searchFields.length === 0) {
            return;
        }

        this._debounce();        
    }
}
