/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/Resources/assets/shop/js/elastictSearchAutocomplete.js":
/*!********************************************************************!*\
  !*** ./src/Resources/assets/shop/js/elastictSearchAutocomplete.js ***!
  \********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ ElasticSearchAutocomplete)
/* harmony export */ });
function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { Promise.resolve(value).then(_next, _throw); } }

function _asyncToGenerator(fn) { return function () { var self = this, args = arguments; return new Promise(function (resolve, reject) { var gen = fn.apply(self, args); function _next(value) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value); } function _throw(err) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err); } _next(undefined); }); }; }

function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }

function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter); }

function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var ElasticSearchAutocomplete = /*#__PURE__*/function () {
  function ElasticSearchAutocomplete() {
    var config = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {
      searchFields: '.searchdiv',
      baseAutocompleteVariantUrl: '[data-bb-elastic-url]',
      searchInput: '.app-quick-add-code-input',
      resultsTarget: '.results',
      resultContainerClassesArray: ['result'],
      resultImageClass: 'image',
      resultContentClass: 'result__content',
      resultPriceClass: 'result__price',
      resultTitleClass: 'js-title',
      resultDescriptionClass: 'result__description'
    };

    _classCallCheck(this, ElasticSearchAutocomplete);

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

  _createClass(ElasticSearchAutocomplete, [{
    key: "_toggleModalVisibility",
    value: function _toggleModalVisibility(elements) {
      document.addEventListener('variantsVisible', function () {
        document.addEventListener('click', function () {
          elements.forEach(function (element) {
            element.innerHTML = '';
            element.style.display = 'none';
          });
        });
      });
    }
  }, {
    key: "_assignElements",
    value: function _assignElements(entry, data) {
      var _this = this;

      var currentResults = entry.closest(this.searchFieldsSelector).querySelector(this.resultsTarget);
      currentResults.innerHTML = '';
      currentResults.style = 'visibility: visible';
      var allResults = document.querySelectorAll(this.resultsTarget);

      if (data.items.length === 0) {
        currentResults.innerHTML = '<center class="result">no matching results</center>';
      }

      data.items = data.items.sort(function (a, b) {
        if (b.taxon_name < a.taxon_name) return 1;
        if (b.taxon_name > a.taxon_name) return -1;
        return 0;
      });
      console.log(data.items);
      var itemTemp;
      data.items.forEach(function (item) {
        var _result$classList;

        var category = item.taxon_name;
        var categoryStyle = "visibility: visible";

        if (itemTemp == item.taxon_name) {
          categoryStyle = "visibility: hidden";
        }

        var result = document.createElement('a');

        (_result$classList = result.classList).add.apply(_result$classList, _toConsumableArray(_this.resultContainerClassesArray).concat(['js-result']));

        result.innerHTML = "\n            <h3 class=\"result__category\" style=".concat(categoryStyle, ">").concat(category, "</h3> \n                <a href=").concat(item.slug, " class=\"result__link\">\n                    <div class=\"result__container\">\n                        <img class=\"result__image\" src=").concat(item.image, ">\n                        <div class=").concat(_this.resultContentClass, ">\n                            <div class=").concat(_this.resultTitleClass, ">").concat(item.name, "</div>\n                            <div class=").concat(_this.resultPriceClass, ">").concat(item.price, "</div>\n                        </div>\n                        \n                    </div>\n                </a> \n            ");
        itemTemp = item.taxon_name;
        currentResults.appendChild(result);
      });
      currentResults.style.display = 'block';

      this._toggleModalVisibility(allResults);

      var customEvent = new CustomEvent('variantsVisible');
      document.dispatchEvent(customEvent);
    }
  }, {
    key: "_getProducts",
    value: function () {
      var _getProducts2 = _asyncToGenerator( /*#__PURE__*/regeneratorRuntime.mark(function _callee(entry) {
        var variantUrl, url, response, data;
        return regeneratorRuntime.wrap(function _callee$(_context) {
          while (1) {
            switch (_context.prev = _context.next) {
              case 0:
                variantUrl = document.querySelector(this.baseAutocompleteVariantUrl).dataset.bbElasticUrl;
                url = "".concat(variantUrl, "?query=").concat(entry.value);
                entry.parentNode.classList.add('loading');
                _context.prev = 3;
                _context.next = 6;
                return fetch(url);

              case 6:
                response = _context.sent;
                _context.next = 9;
                return response.json();

              case 9:
                data = _context.sent;

                this._assignElements(entry, data);

                _context.next = 16;
                break;

              case 13:
                _context.prev = 13;
                _context.t0 = _context["catch"](3);
                console.error(_context.t0);

              case 16:
                _context.prev = 16;
                entry.parentNode.classList.remove('loading');
                return _context.finish(16);

              case 19:
              case "end":
                return _context.stop();
            }
          }
        }, _callee, this, [[3, 13, 16, 19]]);
      }));

      function _getProducts(_x) {
        return _getProducts2.apply(this, arguments);
      }

      return _getProducts;
    }()
  }, {
    key: "_debounce",
    value: function _debounce() {
      var _this2 = this;

      var codeInputs = document.querySelectorAll(this.searchInput);
      var timeout;
      codeInputs.forEach(function (input) {
        input.addEventListener('input', function () {
          clearTimeout(timeout);
          timeout = setTimeout(function () {
            _this2._getProducts(input);
          }, 400);
        });
      });
    }
  }, {
    key: "init",
    value: function init() {
      if (this.searchFields.length === 0) {
        return;
      }

      this._debounce();
    }
  }]);

  return ElasticSearchAutocomplete;
}();



/***/ }),

/***/ "./src/Resources/assets/shop/js/index.js":
/*!***********************************************!*\
  !*** ./src/Resources/assets/shop/js/index.js ***!
  \***********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _initAutocomleate__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./initAutocomleate */ "./src/Resources/assets/shop/js/initAutocomleate.js");


/***/ }),

/***/ "./src/Resources/assets/shop/js/initAutocomleate.js":
/*!**********************************************************!*\
  !*** ./src/Resources/assets/shop/js/initAutocomleate.js ***!
  \**********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _elastictSearchAutocomplete__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./elastictSearchAutocomplete */ "./src/Resources/assets/shop/js/elastictSearchAutocomplete.js");

new _elastictSearchAutocomplete__WEBPACK_IMPORTED_MODULE_0__["default"]().init();

/***/ }),

/***/ "./src/Resources/assets/shop/scss/main.scss":
/*!**************************************************!*\
  !*** ./src/Resources/assets/shop/scss/main.scss ***!
  \**************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {
/*!********************************************!*\
  !*** ./src/Resources/assets/shop/entry.js ***!
  \********************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _scss_main_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./scss/main.scss */ "./src/Resources/assets/shop/scss/main.scss");
/* harmony import */ var _js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./js/ */ "./src/Resources/assets/shop/js/index.js");


})();

/******/ })()
;