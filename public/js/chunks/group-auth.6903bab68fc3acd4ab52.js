(window.webpackJsonp=window.webpackJsonp||[]).push([[5],{"+9zw":function(e,t,r){var a=r("YnG+");"string"==typeof a&&(a=[[e.i,a,""]]);var s={hmr:!0,transform:void 0,insertInto:void 0};r("aET+")(a,s);a.locals&&(e.exports=a.locals)},"30UI":function(e,t,r){"use strict";r("+9zw")},"8GM5":function(e,t,r){var a=r("vIGA");"string"==typeof a&&(a=[[e.i,a,""]]);var s={hmr:!0,transform:void 0,insertInto:void 0};r("aET+")(a,s);a.locals&&(e.exports=a.locals)},JtRG:function(e,t,r){"use strict";r("8GM5")},Y7df:function(e,t,r){"use strict";r.r(t);r("pNMO"),r("TeQF"),r("QWBl"),r("E9XD"),r("HRxU"),r("eoL8"),r("5DmW"),r("27RR"),r("tkto"),r("rB9j"),r("UxlC"),r("FZtP");var a=r("lSNA"),s=r.n(a),o=r("L2JU"),n=r("nhF1");function i(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var a=Object.getOwnPropertySymbols(e);t&&(a=a.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,a)}return r}var l={components:{LangSelect:r("s7Al").a},data:function(){return{form:{email:"",password:"",remember_me:!1},loading:!1,languages:[{value:"vn",title:"Việt Nam"},{value:"en",title:"English"}],redirect:void 0,otherQuery:{}}},computed:function(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?i(Object(r),!0).forEach((function(t){s()(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):i(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}({rules:function(){return{email:[{required:!0,message:this.$t("auth.error.email"),trigger:["change","blur"]},{type:"email",message:this.$t("auth.error.email_valid"),trigger:["change","blur"]}],password:[{required:!0,message:this.$t("auth.error.password"),trigger:["change","blur"]}]}}},Object(o.mapGetters)(["user","lang"])),watch:{$route:{handler:function(e){var t=e.query;t&&(this.redirect=t.redirect,this.otherQuery=this.getOtherQuery(t))},immediate:!0}},methods:{handleCommand:function(e){"vn"!==e&&"en"!==e||this.$store.dispatch("lang/".concat(n.i),e)},login:function(){var e=this;this.loading=!0,this.$refs.login.validate((function(t){if(!t)return e.loading=!1,!1;e.$store.dispatch("user/".concat(n.f),e.form).then((function(){e.loading=!1,e.$router.replace({path:e.redirect||e.$store.state.settings.redirect,query:e.otherQuery},(function(e){}))})).catch((function(){e.loading=!1}))}))},getOtherQuery:function(e){return Object.keys(e).reduce((function(t,r){return"redirect"!==r&&(t[r]=e[r]),t}),{})}}},c=r("KHd+"),u=Object(c.a)(l,(function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("el-container",{staticClass:"tw-blue-grey tw-lighten-5 tw-h-screen tw-justify-center tw-items-center"},[r("el-row",[r("el-col",{attrs:{span:24}},[r("el-card",{staticClass:"box-card"},[r("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[r("div",{staticClass:"tw-flex tw-justify-center tw-items-center tw-relative"},[r("img",{attrs:{src:e.$store.state.settings.urlLogo,width:"200"}}),e._v(" "),e.$store.state.settings.showTrans?r("lang-select",{staticClass:"tw-absolute tw-right-0 tw-top-0"}):e._e()],1)]),e._v(" "),r("el-form",{ref:"login",attrs:{model:e.form,rules:e.rules,"label-width":"120px","label-position":"left"}},[r("el-form-item",{attrs:{label:e.$t("auth.login.email"),prop:"email"}},[r("el-input",{attrs:{name:"email",type:"text",autocomplete:"on"},model:{value:e.form.email,callback:function(t){e.$set(e.form,"email",t)},expression:"form.email"}})],1),e._v(" "),r("el-form-item",{attrs:{label:e.$t("auth.login.password"),prop:"password"}},[r("el-input",{attrs:{name:"password",type:"password","show-password":"",autocomplete:"off"},nativeOn:{keyup:function(t){return!t.type.indexOf("key")&&e._k(t.keyCode,"enter",13,t.key,"Enter")?null:e.login(t)}},model:{value:e.form.password,callback:function(t){e.$set(e.form,"password",t)},expression:"form.password"}})],1)],1),e._v(" "),r("el-row",[r("el-col",{staticClass:"tw-mb-5",attrs:{span:24}},[r("el-button",{staticClass:"tw-w-full",attrs:{type:"primary",loading:e.loading},on:{click:function(t){return t.preventDefault(),e.login(t)}}},[e._v("\n              "+e._s(e.$t("auth.login.login"))+"\n            ")])],1),e._v(" "),r("el-col",{attrs:{span:12}},[r("el-checkbox",{model:{value:e.form.remember_me,callback:function(t){e.$set(e.form,"remember_me",t)},expression:"form.remember_me"}},[e._v("\n              "+e._s(e.$t("auth.login.remember"))+"\n            ")])],1),e._v(" "),r("el-col",{staticClass:"tw-text-right",attrs:{span:12}},[r("router-link",{staticClass:"text-black",attrs:{to:{name:"reset-password"}}},[e._v("\n              "+e._s(e.$t("auth.login.forgot_password"))+"\n            ")])],1)],1)],1)],1)],1)],1)}),[],!1,null,"b4e9ac10",null);t.default=u.exports},"YnG+":function(e,t,r){(t=r("JPst")(!1)).push([e.i,".reset-password[data-v-554b6d1a] {\n  width: 50rem;\n}",""]),e.exports=t},s7Al:function(e,t,r){"use strict";var a=r("nhF1"),s={computed:{language:function(){return this.$store.getters.lang}},methods:{handleSetLanguage:function(e){this.$store.dispatch("lang/".concat(a.i),e)}}},o=r("KHd+"),n=Object(o.a)(s,(function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("el-dropdown",{staticClass:"international",attrs:{trigger:"click"},on:{command:e.handleSetLanguage}},[r("div",[r("svg-icon",{attrs:{"class-name":"tw-text-4xl","icon-class":"language"}})],1),e._v(" "),r("el-dropdown-menu",{attrs:{slot:"dropdown"},slot:"dropdown"},[r("el-dropdown-item",{attrs:{disabled:"ja"===e.language,command:"ja",icon:"flag-icon flag-icon-jp"}},[e._v("\n      日本語\n    ")]),e._v(" "),r("el-dropdown-item",{attrs:{disabled:"en"===e.language,command:"en",icon:"flag-icon flag-icon-um"}},[e._v("\n      English\n    ")]),e._v(" "),r("el-dropdown-item",{attrs:{disabled:"vi"===e.language,command:"vi",icon:"flag-icon flag-icon-vn"}},[e._v("\n      Tiếng Việt\n    ")])],1)],1)}),[],!1,null,null,null);t.a=n.exports},tjzd:function(e,t,r){"use strict";r.r(t);var a=r("nlWZ"),s=r("l+MC"),o={data:function(){var e=this;return{form:{token:this.$route.params.token,email:"",password:"",password_confirmation:""},loadingResetPassword:!1,rules:{email:[{validator:function(t,r,a){r?Object(s.d)(r)?a():a(new Error(e.$t("validation.email",{attribute:e.$t("table.user.email")}))):a(new Error(e.$t("validation.required",{attribute:e.$t("table.user.email")})))},trigger:["blur","change"]}],password:[{validator:function(t,r,a){""===r?a(new Error(e.$t("validation.required",{attribute:e.$t("table.user.password")}))):(""!==e.form.password_confirmation&&e.$refs.resetForm.validateField("password_confirmation"),a())},trigger:["change","blur"]}],password_confirmation:[{validator:function(t,r,a){""===r?a(new Error(e.$t("validation.required",{attribute:e.$t("table.user.password_confirmation")}))):r!==e.form.password?a(new Error(e.$t("validation.confirmed",{attribute:e.$t("table.user.password_confirmation")}))):a()},trigger:["change","blur"]}]}}},methods:{resetPassword:function(e){var t=this;this.$refs[e].validate((function(e){if(!e)return!1;t.loadingResetPassword=!0,Object(a.a)(t.form).then((function(e){t.$router.push({name:"login"}),t.$message({showClose:!0,message:e.data.message,type:"success"}),t.loadingResetPassword=!1})).catch((function(){t.loadingResetPassword=!1}))}))}}},n=(r("30UI"),r("KHd+")),i=Object(n.a)(o,(function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("el-container",{staticClass:"tw-blue-grey tw-lighten-5 tw-h-screen tw-justify-center tw-items-center"},[r("el-row",{staticClass:"reset-password"},[r("el-col",{attrs:{xs:24,sm:24,lg:24,xl:24}},[r("el-card",[r("div",{staticClass:"text-center",attrs:{slot:"header"},slot:"header"},[e._v("\n          "+e._s(e.$t("auth.reset_password"))+"\n        ")]),e._v(" "),r("div",[r("el-form",{ref:"resetForm",attrs:{model:e.form,rules:e.rules}},[r("el-form-item",{attrs:{label:e.$t("auth.login.email"),prop:"email",required:""}},[r("el-input",{attrs:{type:"text",autocomplete:"off"},model:{value:e.form.email,callback:function(t){e.$set(e.form,"email",t)},expression:"form.email"}})],1),e._v(" "),r("el-form-item",{attrs:{"data-generator":"password",required:"",label:e.$t("table.user.password"),error:e.errors.password&&e.errors.password[0],prop:"password"}},[r("el-input",{attrs:{"show-password":"",type:"password"},model:{value:e.form.password,callback:function(t){e.$set(e.form,"password",t)},expression:"form.password"}})],1),e._v(" "),r("el-form-item",{attrs:{"data-generator":"password_confirmation",required:"",label:e.$t("table.user.password_confirmation"),prop:"password_confirmation"}},[r("el-input",{attrs:{"show-password":"",type:"password"},model:{value:e.form.password_confirmation,callback:function(t){e.$set(e.form,"password_confirmation",t)},expression:"form.password_confirmation"}})],1),e._v(" "),r("el-form-item",{staticClass:"tw-text-center"},[r("el-button",{directives:[{name:"loading",rawName:"v-loading.fullscreen.lock",value:e.loadingResetPassword,expression:"loadingResetPassword",modifiers:{fullscreen:!0,lock:!0}}],attrs:{type:"primary",icon:"el-icon-check",circle:""},on:{click:function(t){return e.resetPassword("resetForm")}}})],1)],1)],1)])],1)],1)],1)}),[],!1,null,"554b6d1a",null);t.default=i.exports},v1B8:function(e,t,r){"use strict";r.r(t);var a=r("nlWZ"),s={data:function(){return{form:{email:""},loadingSendEmail:!1}},computed:{rules:function(){return{email:[{required:!0,message:this.$t("auth.error.email"),trigger:["change","blur"]},{type:"email",message:this.$t("auth.error.email_valid"),trigger:["change","blur"]}]}}},methods:{requestResetPassword:function(e){var t=this;this.$refs[e].validate((function(e){if(!e)return!1;t.loadingSendEmail=!0,Object(a.d)(t.form).then((function(e){t.$message({showClose:!0,message:e.data.message,type:"success"}),t.loadingSendEmail=!1})).catch((function(){t.loadingSendEmail=!1}))}))}}},o=(r("JtRG"),r("KHd+")),n=Object(o.a)(s,(function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("el-container",{staticClass:"tw-blue-grey tw-lighten-5 tw-h-screen tw-justify-center tw-items-center"},[r("el-row",{staticClass:"forgot-password"},[r("el-col",{attrs:{xs:24,sm:24,lg:24,xl:24}},[r("el-card",[r("div",{staticClass:"tw-text-center",attrs:{slot:"header"},slot:"header"},[e._v("\n          "+e._s(e.$t("auth.forgot_password"))+"\n        ")]),e._v(" "),r("el-form",{ref:"forgotForm",attrs:{model:e.form,rules:e.rules},nativeOn:{submit:function(t){return t.preventDefault(),e.requestResetPassword("forgotForm")}}},[r("el-form-item",{attrs:{label:e.$t("auth.login.email"),prop:"email",required:""}},[r("el-input",{attrs:{type:"text",autocomplete:"off"},model:{value:e.form.email,callback:function(t){e.$set(e.form,"email",t)},expression:"form.email"}})],1),e._v(" "),r("el-form-item",{staticClass:"tw-text-center"},[r("el-button",{directives:[{name:"loading",rawName:"v-loading.fullscreen.lock",value:e.loadingSendEmail,expression:"loadingSendEmail",modifiers:{fullscreen:!0,lock:!0}}],attrs:{type:"primary",icon:"el-icon-message"},on:{click:function(t){return e.requestResetPassword("forgotForm")}}})],1)],1)],1)],1)],1)],1)}),[],!1,null,"05039553",null);t.default=n.exports},vIGA:function(e,t,r){(t=r("JPst")(!1)).push([e.i,".forgot-password[data-v-05039553] {\n  width: 50rem;\n}",""]),e.exports=t}}]);