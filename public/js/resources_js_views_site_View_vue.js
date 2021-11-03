"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["resources_js_views_site_View_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/site/View.vue?vue&type=script&lang=js&":
/*!***********************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/site/View.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  props: ['id'],
  data: function data() {
    return {
      site: [],
      form: {
        id: null,
        path: null,
        url: null
      },
      reference_url: null
    };
  },
  mounted: function mounted() {
    this.fetchSite();
  },
  methods: {
    fetchSite: function fetchSite() {
      var _this = this;

      this.$api.site.get(this.id).then(function (response) {
        _this.site = response.data;
        _this.form = {
          id: _this.site.id,
          path: _this.site.path,
          url: _this.site.url
        };
      });
    },
    parse: function parse(e) {
      var _this2 = this;

      e.preventDefault();
      var payload = {
        site_id: this.id,
        reference_url: this.reference_url
      };
      this.$api.site.parse(payload).then(function () {
        _this2.fetchSite();
      });
    }
  }
});

/***/ }),

/***/ "./resources/js/views/site/View.vue":
/*!******************************************!*\
  !*** ./resources/js/views/site/View.vue ***!
  \******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _View_vue_vue_type_template_id_d9542fc4___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./View.vue?vue&type=template&id=d9542fc4& */ "./resources/js/views/site/View.vue?vue&type=template&id=d9542fc4&");
/* harmony import */ var _View_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./View.vue?vue&type=script&lang=js& */ "./resources/js/views/site/View.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _View_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _View_vue_vue_type_template_id_d9542fc4___WEBPACK_IMPORTED_MODULE_0__.render,
  _View_vue_vue_type_template_id_d9542fc4___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/views/site/View.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/views/site/View.vue?vue&type=script&lang=js&":
/*!*******************************************************************!*\
  !*** ./resources/js/views/site/View.vue?vue&type=script&lang=js& ***!
  \*******************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_View_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./View.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/site/View.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_View_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/views/site/View.vue?vue&type=template&id=d9542fc4&":
/*!*************************************************************************!*\
  !*** ./resources/js/views/site/View.vue?vue&type=template&id=d9542fc4& ***!
  \*************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_View_vue_vue_type_template_id_d9542fc4___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_View_vue_vue_type_template_id_d9542fc4___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_View_vue_vue_type_template_id_d9542fc4___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./View.vue?vue&type=template&id=d9542fc4& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/site/View.vue?vue&type=template&id=d9542fc4&");


/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/site/View.vue?vue&type=template&id=d9542fc4&":
/*!****************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/site/View.vue?vue&type=template&id=d9542fc4& ***!
  \****************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function () {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    { staticClass: "site-view" },
    [
      _c(
        "b-row",
        [
          _c(
            "b-col",
            { attrs: { cols: "6" } },
            [
              _c(
                "b-form",
                [
                  _c("b-form-group", { attrs: { label: "Domain" } }, [
                    _vm._v(
                      "\n          " + _vm._s(_vm.site.domain) + "\n        "
                    ),
                  ]),
                  _vm._v(" "),
                  _c(
                    "b-form-group",
                    { attrs: { label: "Path" } },
                    [
                      _c("b-form-input", {
                        attrs: { placeholder: "reviews or 2551928371" },
                        model: {
                          value: _vm.form.path,
                          callback: function ($$v) {
                            _vm.$set(_vm.form, "path", $$v)
                          },
                          expression: "form.path",
                        },
                      }),
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-form-group",
                    { attrs: { label: "Url" } },
                    [
                      _c("b-form-input", {
                        attrs: { placeholder: "ntc-investment-com.html" },
                        model: {
                          value: _vm.form.url,
                          callback: function ($$v) {
                            _vm.$set(_vm.form, "url", $$v)
                          },
                          expression: "form.url",
                        },
                      }),
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c("b-form-group", { attrs: { label: "Template Id" } }, [
                    _vm._v(
                      "\n          " +
                        _vm._s(_vm.site.template.name) +
                        "\n        "
                    ),
                  ]),
                ],
                1
              ),
            ],
            1
          ),
          _vm._v(" "),
          _c(
            "b-col",
            { attrs: { cols: "6" } },
            [
              _c("b-button", { attrs: { variant: "success" } }, [
                _vm._v("Create SSL Certificate"),
              ]),
              _vm._v(" "),
              _c("b-button", { attrs: { variant: "danger" } }, [
                _vm._v("Delete Site"),
              ]),
              _vm._v(" "),
              _c(
                "b-form",
                { staticClass: "mt-5" },
                [
                  _c("b-form-input", {
                    staticClass: "mb-3",
                    attrs: { placeholder: "reference url" },
                    model: {
                      value: _vm.reference_url,
                      callback: function ($$v) {
                        _vm.reference_url = $$v
                      },
                      expression: "reference_url",
                    },
                  }),
                  _vm._v(" "),
                  _c(
                    "b-button",
                    { attrs: { variant: "primary" }, on: { click: _vm.parse } },
                    [_vm._v("Parse")]
                  ),
                ],
                1
              ),
            ],
            1
          ),
        ],
        1
      ),
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ })

}]);