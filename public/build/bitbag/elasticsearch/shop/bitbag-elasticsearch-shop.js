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
//# sourceMappingURL=data:application/json;charset=utf-8;base64,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QVUsQ0FBQ2hELE9BQVgsQ0FBbUIsVUFBQ2tELEtBQUQsRUFBVztBQUMxQkEsUUFBQUEsS0FBSyxDQUFDbkQsZ0JBQU4sQ0FBdUIsT0FBdkIsRUFBZ0MsWUFBTTtBQUNsQ29ELFVBQUFBLFlBQVksQ0FBQ0YsT0FBRCxDQUFaO0FBQ0FBLFVBQUFBLE9BQU8sR0FBR0csVUFBVSxDQUFDLFlBQU07QUFDdkIsa0JBQUksQ0FBQ0MsWUFBTCxDQUFrQkgsS0FBbEI7QUFDSCxXQUZtQixFQUVqQixHQUZpQixDQUFwQjtBQUdILFNBTEQ7QUFNSCxPQVBEO0FBUUg7OztXQUVELGdCQUFPO0FBQ0gsVUFBSSxLQUFLakUsWUFBTCxDQUFrQjJCLE1BQWxCLEtBQTZCLENBQWpDLEVBQW9DO0FBQ2hDO0FBQ0g7O0FBRUQsV0FBSzBDLFNBQUw7QUFDSDs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7O0FFbklMO0FBRUEsSUFBSXZFLG1FQUFKLEdBQWdDd0UsSUFBaEM7Ozs7Ozs7Ozs7O0FDRkE7Ozs7Ozs7VUNBQTtVQUNBOztVQUVBO1VBQ0E7VUFDQTtVQUNBO1VBQ0E7VUFDQTtVQUNBO1VBQ0E7VUFDQTtVQUNBO1VBQ0E7VUFDQTtVQUNBOztVQUVBO1VBQ0E7O1VBRUE7VUFDQTtVQUNBOzs7OztXQ3RCQTtXQUNBO1dBQ0E7V0FDQTtXQUNBLHlDQUF5Qyx3Q0FBd0M7V0FDakY7V0FDQTtXQUNBOzs7OztXQ1BBOzs7OztXQ0FBO1dBQ0E7V0FDQTtXQUNBLHVEQUF1RCxpQkFBaUI7V0FDeEU7V0FDQSxnREFBZ0QsYUFBYTtXQUM3RDs7Ozs7Ozs7Ozs7OztBQ05BIiwic291cmNlcyI6WyJ3ZWJwYWNrOi8vQGJpdGJhZy9lbGFzdGljc2VhcmNoLXBsdWdpbi8uL3NyYy9SZXNvdXJjZXMvYXNzZXRzL3Nob3AvanMvZWxhc3RpY3RTZWFyY2hBdXRvY29tcGxldGUuanMiLCJ3ZWJwYWNrOi8vQGJpdGJhZy9lbGFzdGljc2VhcmNoLXBsdWdpbi8uL3NyYy9SZXNvdXJjZXMvYXNzZXRzL3Nob3AvanMvaW5kZXguanMiLCJ3ZWJwYWNrOi8vQGJpdGJhZy9lbGFzdGljc2VhcmNoLXBsdWdpbi8uL3NyYy9SZXNvdXJjZXMvYXNzZXRzL3Nob3AvanMvaW5pdEF1dG9jb21sZWF0ZS5qcyIsIndlYnBhY2s6Ly9AYml0YmFnL2VsYXN0aWNzZWFyY2gtcGx1Z2luLy4vc3JjL1Jlc291cmNlcy9hc3NldHMvc2hvcC9zY3NzL21haW4uc2NzcyIsIndlYnBhY2s6Ly9AYml0YmFnL2VsYXN0aWNzZWFyY2gtcGx1Z2luL3dlYnBhY2svYm9vdHN0cmFwIiwid2VicGFjazovL0BiaXRiYWcvZWxhc3RpY3NlYXJjaC1wbHVnaW4vd2VicGFjay9ydW50aW1lL2RlZmluZSBwcm9wZXJ0eSBnZXR0ZXJzIiwid2VicGFjazovL0BiaXRiYWcvZWxhc3RpY3NlYXJjaC1wbHVnaW4vd2VicGFjay9ydW50aW1lL2hhc093blByb3BlcnR5IHNob3J0aGFuZCIsIndlYnBhY2s6Ly9AYml0YmFnL2VsYXN0aWNzZWFyY2gtcGx1Z2luL3dlYnBhY2svcnVudGltZS9tYWtlIG5hbWVzcGFjZSBvYmplY3QiLCJ3ZWJwYWNrOi8vQGJpdGJhZy9lbGFzdGljc2VhcmNoLXBsdWdpbi8uL3NyYy9SZXNvdXJjZXMvYXNzZXRzL3Nob3AvZW50cnkuanMiXSwic291cmNlc0NvbnRlbnQiOlsiZXhwb3J0IGRlZmF1bHQgY2xhc3MgRWxhc3RpY1NlYXJjaEF1dG9jb21wbGV0ZSB7XG4gICAgY29uc3RydWN0b3IoXG4gICAgICAgIGNvbmZpZyA9IHtcbiAgICAgICAgICAgIHNlYXJjaEZpZWxkczogJy5zZWFyY2hkaXYnLFxuICAgICAgICAgICAgYmFzZUF1dG9jb21wbGV0ZVZhcmlhbnRVcmw6ICdbZGF0YS1iYi1lbGFzdGljLXVybF0nLFxuICAgICAgICAgICAgc2VhcmNoSW5wdXQ6ICcuYXBwLXF1aWNrLWFkZC1jb2RlLWlucHV0JyxcbiAgICAgICAgICAgIHJlc3VsdHNUYXJnZXQ6ICcucmVzdWx0cycsXG4gICAgICAgICAgICByZXN1bHRDb250YWluZXJDbGFzc2VzQXJyYXk6IFsncmVzdWx0J10sXG4gICAgICAgICAgICByZXN1bHRJbWFnZUNsYXNzOiAnaW1hZ2UnLFxuICAgICAgICAgICAgcmVzdWx0Q29udGVudENsYXNzOiAncmVzdWx0X19jb250ZW50JyxcbiAgICAgICAgICAgIHJlc3VsdFByaWNlQ2xhc3M6ICdyZXN1bHRfX3ByaWNlJyxcbiAgICAgICAgICAgIHJlc3VsdFRpdGxlQ2xhc3M6ICdqcy10aXRsZScsXG4gICAgICAgICAgICByZXN1bHREZXNjcmlwdGlvbkNsYXNzOiAncmVzdWx0X19kZXNjcmlwdGlvbicsXG4gICAgICAgIH1cbiAgICApIHtcbiAgICAgICAgdGhpcy5zZWFyY2hGaWVsZHNTZWxlY3RvciA9IGNvbmZpZy5zZWFyY2hGaWVsZHM7XG4gICAgICAgIHRoaXMuc2VhcmNoRmllbGRzID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbChjb25maWcuc2VhcmNoRmllbGRzKTtcbiAgICAgICAgdGhpcy5iYXNlQXV0b2NvbXBsZXRlVmFyaWFudFVybCA9IGNvbmZpZy5iYXNlQXV0b2NvbXBsZXRlVmFyaWFudFVybDtcbiAgICAgICAgdGhpcy5zZWFyY2hJbnB1dCA9IGNvbmZpZy5zZWFyY2hJbnB1dDtcbiAgICAgICAgdGhpcy5yZXN1bHRzVGFyZ2V0ID0gY29uZmlnLnJlc3VsdHNUYXJnZXQ7XG4gICAgICAgIHRoaXMucmVzdWx0Q29udGFpbmVyQ2xhc3Nlc0FycmF5ID0gY29uZmlnLnJlc3VsdENvbnRhaW5lckNsYXNzZXNBcnJheTtcbiAgICAgICAgdGhpcy5yZXN1bHRJbWFnZUNsYXNzID0gY29uZmlnLnJlc3VsdEltYWdlQ2xhc3M7XG4gICAgICAgIHRoaXMucmVzdWx0Q29udGVudENsYXNzID0gY29uZmlnLnJlc3VsdENvbnRlbnRDbGFzcztcbiAgICAgICAgdGhpcy5yZXN1bHRQcmljZUNsYXNzID0gY29uZmlnLnJlc3VsdFByaWNlQ2xhc3M7XG4gICAgICAgIHRoaXMucmVzdWx0VGl0bGVDbGFzcyA9IGNvbmZpZy5yZXN1bHRUaXRsZUNsYXNzO1xuICAgICAgICB0aGlzLnJlc3VsdERlc2NyaXB0aW9uQ2xhc3MgPSBjb25maWcucmVzdWx0RGVzY3JpcHRpb25DbGFzcztcbiAgICB9XG5cbiAgICBfdG9nZ2xlTW9kYWxWaXNpYmlsaXR5KGVsZW1lbnRzKSB7XG4gICAgICAgIGRvY3VtZW50LmFkZEV2ZW50TGlzdGVuZXIoJ3ZhcmlhbnRzVmlzaWJsZScsICgpID0+IHtcbiAgICAgICAgICAgIGRvY3VtZW50LmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgKCkgPT4ge1xuICAgICAgICAgICAgICAgIGVsZW1lbnRzLmZvckVhY2goKGVsZW1lbnQpID0+IHtcbiAgICAgICAgICAgICAgICAgICAgZWxlbWVudC5pbm5lckhUTUwgPSAnJztcbiAgICAgICAgICAgICAgICAgICAgZWxlbWVudC5zdHlsZS5kaXNwbGF5ID0gJ25vbmUnO1xuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgIH0pO1xuICAgIH1cblxuICAgIF9hc3NpZ25FbGVtZW50cyhlbnRyeSwgZGF0YSkge1xuICAgICAgICBjb25zdCBjdXJyZW50UmVzdWx0cyA9IGVudHJ5LmNsb3Nlc3QodGhpcy5zZWFyY2hGaWVsZHNTZWxlY3RvcikucXVlcnlTZWxlY3Rvcih0aGlzLnJlc3VsdHNUYXJnZXQpO1xuICAgICAgICBjdXJyZW50UmVzdWx0cy5pbm5lckhUTUwgPSAnJ1xuICAgICAgICBjdXJyZW50UmVzdWx0cy5zdHlsZSA9ICd2aXNpYmlsaXR5OiB2aXNpYmxlJztcblxuICAgICAgICBjb25zdCBhbGxSZXN1bHRzID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbCh0aGlzLnJlc3VsdHNUYXJnZXQpO1xuICAgICAgICAgXG4gICAgICAgIGlmIChkYXRhLml0ZW1zLmxlbmd0aCA9PT0gMCkge1xuICAgICAgICAgICAgY3VycmVudFJlc3VsdHMuaW5uZXJIVE1MID0gJzxjZW50ZXIgY2xhc3M9XCJyZXN1bHRcIj5ubyBtYXRjaGluZyByZXN1bHRzPC9jZW50ZXI+JztcbiAgICAgICAgfVxuXG4gICAgICAgIGRhdGEuaXRlbXMgPSBkYXRhLml0ZW1zLnNvcnQoKGEsYikgPT4ge1xuICAgICAgICAgICAgaWYgKGIudGF4b25fbmFtZSA8IGEudGF4b25fbmFtZSkgcmV0dXJuIDE7XG4gICAgICAgICAgICBpZiAoYi50YXhvbl9uYW1lID4gYS50YXhvbl9uYW1lKSByZXR1cm4gLTE7XG4gICAgICAgICAgICByZXR1cm4gMDtcbiAgICAgICAgfSk7XG4gICAgICAgICAgICBjb25zb2xlLmxvZyhkYXRhLml0ZW1zKTtcbiAgICAgICAgbGV0IGl0ZW1UZW1wO1xuICAgICAgICBkYXRhLml0ZW1zLmZvckVhY2goKGl0ZW0pID0+IHtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgbGV0IGNhdGVnb3J5ID0gaXRlbS50YXhvbl9uYW1lO1xuICAgICAgICAgICAgbGV0IGNhdGVnb3J5U3R5bGUgPSBcInZpc2liaWxpdHk6IHZpc2libGVcIlxuICAgICAgICAgICAgaWYgKGl0ZW1UZW1wID09IGl0ZW0udGF4b25fbmFtZSkge1xuICAgICAgICAgICAgICAgIGNhdGVnb3J5U3R5bGUgPSBcInZpc2liaWxpdHk6IGhpZGRlblwiO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICBjb25zdCByZXN1bHQgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdhJyk7XG4gICAgICAgICAgICByZXN1bHQuY2xhc3NMaXN0LmFkZCguLi50aGlzLnJlc3VsdENvbnRhaW5lckNsYXNzZXNBcnJheSwgJ2pzLXJlc3VsdCcpO1xuICAgICAgICAgICAgcmVzdWx0LmlubmVySFRNTCA9IGBcbiAgICAgICAgICAgIDxoMyBjbGFzcz1cInJlc3VsdF9fY2F0ZWdvcnlcIiBzdHlsZT0ke2NhdGVnb3J5U3R5bGV9PiR7Y2F0ZWdvcnl9PC9oMz4gXG4gICAgICAgICAgICAgICAgPGEgaHJlZj0ke2l0ZW0uc2x1Z30gY2xhc3M9XCJyZXN1bHRfX2xpbmtcIj5cbiAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cInJlc3VsdF9fY29udGFpbmVyXCI+XG4gICAgICAgICAgICAgICAgICAgICAgICA8aW1nIGNsYXNzPVwicmVzdWx0X19pbWFnZVwiIHNyYz0ke2l0ZW0uaW1hZ2V9PlxuICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz0ke3RoaXMucmVzdWx0Q29udGVudENsYXNzfT5cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPSR7dGhpcy5yZXN1bHRUaXRsZUNsYXNzfT4ke2l0ZW0ubmFtZX08L2Rpdj5cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPSR7dGhpcy5yZXN1bHRQcmljZUNsYXNzfT4ke2l0ZW0ucHJpY2V9PC9kaXY+XG4gICAgICAgICAgICAgICAgICAgICAgICA8L2Rpdj5cbiAgICAgICAgICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgICAgICA8L2Rpdj5cbiAgICAgICAgICAgICAgICA8L2E+IFxuICAgICAgICAgICAgYDtcblxuICAgICAgICAgICAgaXRlbVRlbXAgPSBpdGVtLnRheG9uX25hbWU7XG4gICAgICAgICAgICBjdXJyZW50UmVzdWx0cy5hcHBlbmRDaGlsZChyZXN1bHQpO1xuICAgICAgICB9KTtcblxuICAgICAgICBjdXJyZW50UmVzdWx0cy5zdHlsZS5kaXNwbGF5ID0gJ2Jsb2NrJztcbiAgICAgICAgdGhpcy5fdG9nZ2xlTW9kYWxWaXNpYmlsaXR5KGFsbFJlc3VsdHMpO1xuXG4gICAgICAgIGNvbnN0IGN1c3RvbUV2ZW50ID0gbmV3IEN1c3RvbUV2ZW50KCd2YXJpYW50c1Zpc2libGUnKTtcbiAgICAgICAgZG9jdW1lbnQuZGlzcGF0Y2hFdmVudChjdXN0b21FdmVudCk7XG4gICAgfVxuXG4gICAgYXN5bmMgX2dldFByb2R1Y3RzKGVudHJ5KSB7XG4gICAgICAgIGNvbnN0IHZhcmlhbnRVcmwgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKHRoaXMuYmFzZUF1dG9jb21wbGV0ZVZhcmlhbnRVcmwpLmRhdGFzZXQuYmJFbGFzdGljVXJsO1xuICAgICAgICBjb25zdCB1cmwgPSBgJHt2YXJpYW50VXJsfT9xdWVyeT0ke2VudHJ5LnZhbHVlfWA7XG4gICAgICAgICAgICBcbiAgICAgICAgZW50cnkucGFyZW50Tm9kZS5jbGFzc0xpc3QuYWRkKCdsb2FkaW5nJyk7XG5cbiAgICAgICAgdHJ5IHtcbiAgICAgICAgICAgIGNvbnN0IHJlc3BvbnNlID0gYXdhaXQgZmV0Y2godXJsKTtcbiAgICAgICAgICAgIGNvbnN0IGRhdGEgPSBhd2FpdCByZXNwb25zZS5qc29uKCk7XG4gICAgICAgICAgICAgXG4gICAgICAgICAgICB0aGlzLl9hc3NpZ25FbGVtZW50cyhlbnRyeSwgZGF0YSk7XG4gICAgICAgIH0gY2F0Y2ggKGVycm9yKSB7XG4gICAgICAgICAgICBjb25zb2xlLmVycm9yKGVycm9yKTtcbiAgICAgICAgfSBmaW5hbGx5IHtcbiAgICAgICAgICAgIGVudHJ5LnBhcmVudE5vZGUuY2xhc3NMaXN0LnJlbW92ZSgnbG9hZGluZycpO1xuICAgICAgICB9XG4gICAgfVxuXG4gICAgX2RlYm91bmNlKCkge1xuICAgICAgICBjb25zdCBjb2RlSW5wdXRzID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbCh0aGlzLnNlYXJjaElucHV0KTtcbiAgICAgICAgXG4gICAgICAgIGxldCB0aW1lb3V0O1xuICAgICAgICBcbiAgICAgICAgY29kZUlucHV0cy5mb3JFYWNoKChpbnB1dCkgPT4ge1xuICAgICAgICAgICAgaW5wdXQuYWRkRXZlbnRMaXN0ZW5lcignaW5wdXQnLCAoKSA9PiB7XG4gICAgICAgICAgICAgICAgY2xlYXJUaW1lb3V0KHRpbWVvdXQpO1xuICAgICAgICAgICAgICAgIHRpbWVvdXQgPSBzZXRUaW1lb3V0KCgpID0+IHtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5fZ2V0UHJvZHVjdHMoaW5wdXQpO1xuICAgICAgICAgICAgICAgIH0sIDQwMCk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfSk7XG4gICAgfVxuXG4gICAgaW5pdCgpIHtcbiAgICAgICAgaWYgKHRoaXMuc2VhcmNoRmllbGRzLmxlbmd0aCA9PT0gMCkge1xuICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICB9XG5cbiAgICAgICAgdGhpcy5fZGVib3VuY2UoKTsgICAgICAgIFxuICAgIH1cbn1cbiIsImltcG9ydCAnLi9pbml0QXV0b2NvbWxlYXRlJzsiLCJpbXBvcnQgRWxhc3RpY1NlYXJjaEF1dG9jb21wbGV0ZSBmcm9tICcuL2VsYXN0aWN0U2VhcmNoQXV0b2NvbXBsZXRlJztcblxubmV3IEVsYXN0aWNTZWFyY2hBdXRvY29tcGxldGUoKS5pbml0KCk7IiwiLy8gZXh0cmFjdGVkIGJ5IG1pbmktY3NzLWV4dHJhY3QtcGx1Z2luXG5leHBvcnQge307IiwiLy8gVGhlIG1vZHVsZSBjYWNoZVxudmFyIF9fd2VicGFja19tb2R1bGVfY2FjaGVfXyA9IHt9O1xuXG4vLyBUaGUgcmVxdWlyZSBmdW5jdGlvblxuZnVuY3Rpb24gX193ZWJwYWNrX3JlcXVpcmVfXyhtb2R1bGVJZCkge1xuXHQvLyBDaGVjayBpZiBtb2R1bGUgaXMgaW4gY2FjaGVcblx0dmFyIGNhY2hlZE1vZHVsZSA9IF9fd2VicGFja19tb2R1bGVfY2FjaGVfX1ttb2R1bGVJZF07XG5cdGlmIChjYWNoZWRNb2R1bGUgIT09IHVuZGVmaW5lZCkge1xuXHRcdHJldHVybiBjYWNoZWRNb2R1bGUuZXhwb3J0cztcblx0fVxuXHQvLyBDcmVhdGUgYSBuZXcgbW9kdWxlIChhbmQgcHV0IGl0IGludG8gdGhlIGNhY2hlKVxuXHR2YXIgbW9kdWxlID0gX193ZWJwYWNrX21vZHVsZV9jYWNoZV9fW21vZHVsZUlkXSA9IHtcblx0XHQvLyBubyBtb2R1bGUuaWQgbmVlZGVkXG5cdFx0Ly8gbm8gbW9kdWxlLmxvYWRlZCBuZWVkZWRcblx0XHRleHBvcnRzOiB7fVxuXHR9O1xuXG5cdC8vIEV4ZWN1dGUgdGhlIG1vZHVsZSBmdW5jdGlvblxuXHRfX3dlYnBhY2tfbW9kdWxlc19fW21vZHVsZUlkXShtb2R1bGUsIG1vZHVsZS5leHBvcnRzLCBfX3dlYnBhY2tfcmVxdWlyZV9fKTtcblxuXHQvLyBSZXR1cm4gdGhlIGV4cG9ydHMgb2YgdGhlIG1vZHVsZVxuXHRyZXR1cm4gbW9kdWxlLmV4cG9ydHM7XG59XG5cbiIsIi8vIGRlZmluZSBnZXR0ZXIgZnVuY3Rpb25zIGZvciBoYXJtb255IGV4cG9ydHNcbl9fd2VicGFja19yZXF1aXJlX18uZCA9IChleHBvcnRzLCBkZWZpbml0aW9uKSA9PiB7XG5cdGZvcih2YXIga2V5IGluIGRlZmluaXRpb24pIHtcblx0XHRpZihfX3dlYnBhY2tfcmVxdWlyZV9fLm8oZGVmaW5pdGlvbiwga2V5KSAmJiAhX193ZWJwYWNrX3JlcXVpcmVfXy5vKGV4cG9ydHMsIGtleSkpIHtcblx0XHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShleHBvcnRzLCBrZXksIHsgZW51bWVyYWJsZTogdHJ1ZSwgZ2V0OiBkZWZpbml0aW9uW2tleV0gfSk7XG5cdFx0fVxuXHR9XG59OyIsIl9fd2VicGFja19yZXF1aXJlX18ubyA9IChvYmosIHByb3ApID0+IChPYmplY3QucHJvdG90eXBlLmhhc093blByb3BlcnR5LmNhbGwob2JqLCBwcm9wKSkiLCIvLyBkZWZpbmUgX19lc01vZHVsZSBvbiBleHBvcnRzXG5fX3dlYnBhY2tfcmVxdWlyZV9fLnIgPSAoZXhwb3J0cykgPT4ge1xuXHRpZih0eXBlb2YgU3ltYm9sICE9PSAndW5kZWZpbmVkJyAmJiBTeW1ib2wudG9TdHJpbmdUYWcpIHtcblx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkoZXhwb3J0cywgU3ltYm9sLnRvU3RyaW5nVGFnLCB7IHZhbHVlOiAnTW9kdWxlJyB9KTtcblx0fVxuXHRPYmplY3QuZGVmaW5lUHJvcGVydHkoZXhwb3J0cywgJ19fZXNNb2R1bGUnLCB7IHZhbHVlOiB0cnVlIH0pO1xufTsiLCJpbXBvcnQgJy4vc2Nzcy9tYWluLnNjc3MnXG5pbXBvcnQgJy4vanMvJyJdLCJuYW1lcyI6WyJFbGFzdGljU2VhcmNoQXV0b2NvbXBsZXRlIiwiY29uZmlnIiwic2VhcmNoRmllbGRzIiwiYmFzZUF1dG9jb21wbGV0ZVZhcmlhbnRVcmwiLCJzZWFyY2hJbnB1dCIsInJlc3VsdHNUYXJnZXQiLCJyZXN1bHRDb250YWluZXJDbGFzc2VzQXJyYXkiLCJyZXN1bHRJbWFnZUNsYXNzIiwicmVzdWx0Q29udGVudENsYXNzIiwicmVzdWx0UHJpY2VDbGFzcyIsInJlc3VsdFRpdGxlQ2xhc3MiLCJyZXN1bHREZXNjcmlwdGlvbkNsYXNzIiwic2VhcmNoRmllbGRzU2VsZWN0b3IiLCJkb2N1bWVudCIsInF1ZXJ5U2VsZWN0b3JBbGwiLCJlbGVtZW50cyIsImFkZEV2ZW50TGlzdGVuZXIiLCJmb3JFYWNoIiwiZWxlbWVudCIsImlubmVySFRNTCIsInN0eWxlIiwiZGlzcGxheSIsImVudHJ5IiwiZGF0YSIsImN1cnJlbnRSZXN1bHRzIiwiY2xvc2VzdCIsInF1ZXJ5U2VsZWN0b3IiLCJhbGxSZXN1bHRzIiwiaXRlbXMiLCJsZW5ndGgiLCJzb3J0IiwiYSIsImIiLCJ0YXhvbl9uYW1lIiwiY29uc29sZSIsImxvZyIsIml0ZW1UZW1wIiwiaXRlbSIsImNhdGVnb3J5IiwiY2F0ZWdvcnlTdHlsZSIsInJlc3VsdCIsImNyZWF0ZUVsZW1lbnQiLCJjbGFzc0xpc3QiLCJhZGQiLCJzbHVnIiwiaW1hZ2UiLCJuYW1lIiwicHJpY2UiLCJhcHBlbmRDaGlsZCIsIl90b2dnbGVNb2RhbFZpc2liaWxpdHkiLCJjdXN0b21FdmVudCIsIkN1c3RvbUV2ZW50IiwiZGlzcGF0Y2hFdmVudCIsInZhcmlhbnRVcmwiLCJkYXRhc2V0IiwiYmJFbGFzdGljVXJsIiwidXJsIiwidmFsdWUiLCJwYXJlbnROb2RlIiwiZmV0Y2giLCJyZXNwb25zZSIsImpzb24iLCJfYXNzaWduRWxlbWVudHMiLCJlcnJvciIsInJlbW92ZSIsImNvZGVJbnB1dHMiLCJ0aW1lb3V0IiwiaW5wdXQiLCJjbGVhclRpbWVvdXQiLCJzZXRUaW1lb3V0IiwiX2dldFByb2R1Y3RzIiwiX2RlYm91bmNlIiwiaW5pdCJdLCJzb3VyY2VSb290IjoiIn0=