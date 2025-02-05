export default class ElasticSearchAutocomplete {
    constructor(
        config = {},
    ) {
        this.config = config;
        this.defaultConfig = {
            searchFields: '.searchdiv',
            baseAutocompleteVariantUrl: '[data-bb-elastic-url]',
            searchInput: '.app-quick-add-code-input',
            resultsTarget: '.results',
            resultContainerClassesArray: ['result'],
            resultContentClass: 'result__content',
            resultPriceClass: 'result__price',
            resultTitleClass: 'js-title',
            resultDescriptionClass: 'result__description',
            resultLinkClass: 'result__link',
            resultCategoryClass: 'result__category',
            resultImageClass: 'result__image',
            resultContainerClass: 'result__container',
        };
        this.finalConfig = {...this.defaultConfig, ...config};
        this.searchFieldsSelector = document.querySelector(this.finalConfig.searchFields);
    }

    init() {
        if (this.config && typeof this.config !== 'object') {
            throw new Error('BitBag - CreateConfirmationModal - given config is not valid - expected object');
        }

        this._debounce();        
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

    _modalTemplate(item, categoryStyle) {
        const result = document.createElement('a');
        result.classList.add(...this.finalConfig.resultContainerClassesArray, 'js-result');
        result.innerHTML = `
            <h3 class=${this.finalConfig.resultCategoryClass} style=${categoryStyle}>${item.taxon_name}</h3> 
                <a href=${item.slug} class=${this.finalConfig.resultLinkClass}>
                    <div class=${this.finalConfig.resultContainerClass}>
                        <img class=${this.finalConfig.resultImageClass} src=${item.image}>
                        <div class=${this.finalConfig.resultContentClass}>
                            <div class=${this.finalConfig.resultTitleClass}>${item.name}</div>
                            <div class=${this.finalConfig.resultPriceClass}>${item.price}</div>
                        </div>
                    </div>
                </a> 
        `;

        return result;
    }

    _assignElements(entry, data) {
        const currentResults = this.searchFieldsSelector.querySelector(this.finalConfig.resultsTarget);
       
        currentResults.innerHTML = ''
        currentResults.style = 'visibility: visible';

        const allResults = document.querySelectorAll(this.finalConfig.resultsTarget);
         
        if (data.items.length === 0) {
            currentResults.innerHTML = '<center class="result">no matching results</center>';
        }

        data.items = data.items.sort((a,b) => {
            if (b.taxon_name < a.taxon_name) return 1;
            if (b.taxon_name > a.taxon_name) return -1;
            return 0;
        });
            
        let tempTaxonName;
        data.items.forEach((item) => {
            
            let categoryStyle = "visibility: visible"
            if (tempTaxonName == item.taxon_name) {
                categoryStyle = "visibility: hidden";
            }

            tempTaxonName = item.taxon_name;
            currentResults.appendChild(this._modalTemplate(item, categoryStyle));
        });

        currentResults.style.display = 'block';
        this._toggleModalVisibility(allResults);

        const customEvent = new CustomEvent('variantsVisible');
        document.dispatchEvent(customEvent);
    }

    async _getProducts(entry) {
        const variantUrl = document.querySelector(this.finalConfig.baseAutocompleteVariantUrl).dataset.bbElasticUrl;
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
        const codeInputs = document.querySelectorAll(this.finalConfig.searchInput);    
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

}
