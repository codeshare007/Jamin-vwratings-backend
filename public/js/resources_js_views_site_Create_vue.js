"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["resources_js_views_site_Create_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/site/Create.vue?vue&type=script&lang=js&":
/*!*************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/site/Create.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************************************************************************************************************************************/
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
//
//
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  data: function data() {
    return {
      form: {
        domain: null,
        path: null,
        template_id: 1,
        reference_url: null
      },
      templates: []
    };
  },
  mounted: function mounted() {
    var _this = this;

    this.$api.template.fetch().then(function (response) {
      _this.templates = response.data;
    });
  },
  methods: {
    createSite: function createSite(e) {
      var _this2 = this;

      e.preventDefault();
      this.$api.site.create(this.form).then(function (response) {
        if (response.data.status === 'success') {
          _this2.$router.push({
            name: 'reviewer.site.list'
          });
        }
      });
    }
  }
});

/***/ }),

/***/ "./resources/js/views/site/Create.vue":
/*!********************************************!*\
  !*** ./resources/js/views/site/Create.vue ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Create_vue_vue_type_template_id_150b76b5___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Create.vue?vue&type=template&id=150b76b5& */ "./resources/js/views/site/Create.vue?vue&type=template&id=150b76b5&");
/* harmony import */ var _Create_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Create.vue?vue&type=script&lang=js& */ "./resources/js/views/site/Create.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Create_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Create_vue_vue_type_template_id_150b76b5___WEBPACK_IMPORTED_MODULE_0__.render,
  _Create_vue_vue_type_template_id_150b76b5___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/views/site/Create.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/views/site/Create.vue?vue&type=script&lang=js&":
/*!*********************************************************************!*\
  !*** ./resources/js/views/site/Create.vue?vue&type=script&lang=js& ***!
  \*********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Create_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./Create.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/site/Create.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Create_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/views/site/Create.vue?vue&type=template&id=150b76b5&":
/*!***************************************************************************!*\
  !*** ./resources/js/views/site/Create.vue?vue&type=template&id=150b76b5& ***!
  \***************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Create_vue_vue_type_template_id_150b76b5___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Create_vue_vue_type_template_id_150b76b5___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Create_vue_vue_type_template_id_150b76b5___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./Create.vue?vue&type=template&id=150b76b5& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/site/Create.vue?vue&type=template&id=150b76b5&");


/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/site/Create.vue?vue&type=template&id=150b76b5&":
/*!******************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/site/Create.vue?vue&type=template&id=150b76b5& ***!
  \******************************************************************************************************************************************************************************************************************/
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
    { staticClass: "site-create" },
    [
      _c(
        "div",
        { staticClass: "mb-2" },
        [
          _c(
            "b-button",
            {
              attrs: { variant: "primary", to: { name: "reviewer.site.list" } },
            },
            [_vm._v("Back")]
          ),
        ],
        1
      ),
      _vm._v(" "),
      _c(
        "b-row",
        [
          _c(
            "b-col",
            { attrs: { cols: "6" } },
            [
              _c(
                "b-form",
                { staticClass: "mt-3" },
                [
                  _c(
                    "b-form-group",
                    { attrs: { label: "Domain" } },
                    [
                      _c("b-form-input", {
                        attrs: { placeholder: "example.com" },
                        model: {
                          value: _vm.form.domain,
                          callback: function ($$v) {
                            _vm.$set(_vm.form, "domain", $$v)
                          },
                          expression: "form.domain",
                        },
                      }),
                    ],
                    1
                  ),
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
                  _c(
                    "b-form-group",
                    { attrs: { label: "Reference Url" } },
                    [
                      _c("b-form-input", {
                        attrs: {
                          placeholder:
                            "https://www.trustpilot.com/review/idealagent.com",
                        },
                        model: {
                          value: _vm.form.reference_url,
                          callback: function ($$v) {
                            _vm.$set(_vm.form, "reference_url", $$v)
                          },
                          expression: "form.reference_url",
                        },
                      }),
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-form-group",
                    { attrs: { label: "Template Id" } },
                    [
                      _c(
                        "b-form-select",
                        {
                          staticClass: "mb-3",
                          model: {
                            value: _vm.form.template_id,
                            callback: function ($$v) {
                              _vm.$set(_vm.form, "template_id", $$v)
                            },
                            expression: "form.template_id",
                          },
                        },
                        _vm._l(_vm.templates, function (item, key) {
                          return _c(
                            "b-form-select-option",
                            { key: key, attrs: { value: item.id } },
                            [_vm._v(_vm._s(item.name))]
                          )
                        }),
                        1
                      ),
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-button",
                    {
                      attrs: { type: "submit", variant: "success" },
                      on: { click: _vm.createSite },
                    },
                    [_vm._v("Create")]
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