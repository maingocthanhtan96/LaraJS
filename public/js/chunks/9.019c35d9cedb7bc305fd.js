(window.webpackJsonp=window.webpackJsonp||[]).push([[9],{"+Awk":function(t,e,n){"use strict";n.d(e,"a",(function(){return l}));var o=n("lwsE"),r=n.n(o),a=n("W8MJ"),i=n.n(a),s=n("rywy"),l=function(){function t(e){r()(this,t),this.uri=e}return i()(t,[{key:"list",value:function(t){return Object(s.a)({url:"/"+this.uri,method:"get",params:t})}},{key:"get",value:function(t){return Object(s.a)({url:"/"+this.uri+"/"+t,method:"get"})}},{key:"store",value:function(t){return Object(s.a)({url:"/"+this.uri,method:"post",data:t})}},{key:"update",value:function(t,e){return Object(s.a)({url:"/"+this.uri+"/"+t,method:"put",data:e})}},{key:"destroy",value:function(t){return Object(s.a)({url:"/"+this.uri+"/"+t,method:"delete"})}}]),t}()},"4d3n":function(t,e,n){var o=n("BgMW");"string"==typeof o&&(o=[[t.i,o,""]]);var r={hmr:!0,transform:void 0,insertInto:void 0};n("aET+")(o,r);o.locals&&(t.exports=o.locals)},"7W2i":function(t,e,n){var o=n("SksO");t.exports=function(t,e){if("function"!=typeof e&&null!==e)throw new TypeError("Super expression must either be null or a function");t.prototype=Object.create(e&&e.prototype,{constructor:{value:t,writable:!0,configurable:!0}}),e&&o(t,e)}},BfuM:function(t,e,n){var o=n("z3Kf");"string"==typeof o&&(o=[[t.i,o,""]]);var r={hmr:!0,transform:void 0,insertInto:void 0};n("aET+")(o,r);o.locals&&(t.exports=o.locals)},BgMW:function(t,e,n){(t.exports=n("I1BE")(!1)).push([t.i,'/* CSS Simple Pre Code */\npre[data-v-75378ab4] {\n  font-size: 1.5rem;\n  border: 1px solid grey;\n  width: 60vw;\n  max-width: 700px;\n  border-left: 12px solid #67C23A;\n  border-radius: 5px;\n  padding: 14px;\n  white-space: pre;\n  word-wrap: break-word;\n  overflow: auto;\n}\npre code[data-v-75378ab4] {\n  font-family: "Inconsolata", "Monaco", "Consolas", "Andale Mono", "Bitstream Vera Sans Mono", "Courier New", Courier, monospace;\n  color: #dd6b20;\n}',""])},Nsbk:function(t,e){function n(e){return t.exports=n=Object.setPrototypeOf?Object.getPrototypeOf:function(t){return t.__proto__||Object.getPrototypeOf(t)},n(e)}t.exports=n},O48x:function(t,e,n){"use strict";n.d(e,"a",(function(){return f}));var o=n("lwsE"),r=n.n(o),a=n("W8MJ"),i=n.n(a),s=n("a1gu"),l=n.n(s),c=n("Nsbk"),d=n.n(c),u=n("7W2i"),p=n.n(u),h=n("rywy"),f=function(t){function e(){return r()(this,e),l()(this,d()(e).call(this,"generators"))}return p()(e,t),i()(e,[{key:"checkModel",value:function(t){return Object(h.a)({url:"/generators/check-model",method:"get",params:{name:t}})}},{key:"checkColumn",value:function(t,e){return Object(h.a)({url:"/generators/check-column",method:"get",params:{table:t,column:e}})}},{key:"getModels",value:function(t){return Object(h.a)({url:"/generators/get-models",method:"get",params:{model:t}})}},{key:"getColumns",value:function(t){return Object(h.a)({url:"/generators/get-columns",method:"get",params:{table:t}})}},{key:"generateRelationship",value:function(t){return Object(h.a)({url:"/generators/relationship",method:"post",data:t})}},{key:"generateDiagram",value:function(t){return Object(h.a)({url:"/generators/diagram",method:"get",params:{model:t}})}}]),e}(n("+Awk").a)},PJYZ:function(t,e){t.exports=function(t){if(void 0===t)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return t}},SksO:function(t,e){function n(e,o){return t.exports=n=Object.setPrototypeOf||function(t,e){return t.__proto__=e,t},n(e,o)}t.exports=n},W8MJ:function(t,e){function n(t,e){for(var n=0;n<e.length;n++){var o=e[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(t,o.key,o)}}t.exports=function(t,e,o){return e&&n(t.prototype,e),o&&n(t,o),t}},a1gu:function(t,e,n){var o=n("cDf5"),r=n("PJYZ");t.exports=function(t,e){return!e||"object"!==o(e)&&"function"!=typeof e?r(t):e}},eUNx:function(t,e,n){"use strict";var o=n("BfuM");n.n(o).a},gXgE:function(t,e,n){"use strict";n.r(e);var o={name:"PreCodeTag",props:{content:{type:String,required:!1,default:""}}},r=(n("mHXD"),n("KHd+")),a=Object(r.a)(o,(function(){var t=this.$createElement,e=this._self._c||t;return e("pre",{staticClass:"code code-php"},[this._v("  "),e("code",{domProps:{innerHTML:this._s(this.content)}}),this._v("\n")])}),[],!1,null,"75378ab4",null).exports,i=new(n("O48x").a),s={components:{PreCodeTag:a},data:function(){return{loading:!1,loadingModel:!1,loadingDisplay:!1,dialogVisible:!1,options:["Search","Sort","Show"],relationshipOptions:["hasOne","hasMany","belongsToMany"],modelOptions:[],markdown:"# Relationship",displayColumns:[],displayColumns2:[],form:{model_current:"",relationship:"",model:"",column:"",column2:"",options:["Search","Sort","Show"]},drawDiagram:[]}},mounted:function(){var t=this,e=this.$route.params.id;e&&i.get(e).then((function(e){var n=e.data.data.model,o=JSON.parse(n);t.form.model_current=o.name,t.loadingModel=!0,i.getModels(n).then((function(e){t.loadingModel=!1;var n=e.data.data;t.modelOptions=n})).catch((function(){t.loadingModel=!1}))}))},methods:{diagram:function(){var t=this;i.generateDiagram(this.form.model_current).then((function(e){var n=e.data.data;t.drawDiagram=n}))},changeOptions:function(t){t.includes("Show")?"belongsToMany"===this.form.relationship?this.options=["Show","Search"]:this.options=["Search","Sort","Show"]:(this.form.options=[],this.options=["Show"])},createRelationship:function(){var t=this;this.loading=!0,i.generateRelationship(this.form).then((function(e){t.$message({showClose:!0,message:t.$t("messages.success"),type:"success"}),t.loading=!1,location.reload()})).catch((function(){t.loading=!1,location.reload()}))},replaceTemplate:function(t){var e=this,n="# Model ".concat(this.form.model_current,"\n      public function ").concat(this.$_.camelCase(this.form.model),"() {\n        return $this->").concat(this.form.relationship,"(").concat(this.form.model,"::class);\n      }"),o="<br/>  # Model ".concat(this.form.model,"\n      public function ").concat(this.$_.camelCase(this.form.model_current),"() {\n        return $this->belongsTo(").concat(this.form.model_current,"::class);\n      }");"belongsToMany"===this.form.relationship&&(o="<br/>  # Model ".concat(this.form.model,"\n      public function ").concat(this.$_.camelCase(this.form.model_current),"() {\n        return $this->belongsToMany(").concat(this.form.model_current,"::class);\n      }"),o+="<br/>  # I will create a table ".concat(this.$_.snakeCase(this.form.model_current),"_").concat(this.$_.snakeCase(this.form.model))),this.form.relationship&&this.form.model&&(this.markdown=n.concat(o)),"belongsToMany"===this.form.relationship?(this.options=["Show","Search"],this.form.options=["Show","Search"]):(this.options=["Search","Sort","Show"],this.form.options=["Search","Sort","Show"]),t&&(this.loadingDisplay=!0,i.getColumns(this.form.model_current).then((function(t){e.loadingDisplay=!1;var n=t.data.data;e.displayColumns=n})).catch((function(){e.loadingDisplay=!1})),i.getColumns(this.form.model).then((function(t){e.loadingDisplay=!1;var n=t.data.data;e.displayColumns2=n})).catch((function(){e.loadingDisplay=!1})))}}},l=(n("eUNx"),Object(r.a)(s,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("el-row",[n("el-col",{attrs:{span:24}},[n("el-card",[n("div",{staticClass:"text-center",attrs:{slot:"header"},slot:"header"},[n("button",{staticClass:"hover:bg-green-600 hover:text-white font-bold border rounded border-green-600 text-green-600 bg-transparent py-3 px-4",on:{click:function(e){t.dialogVisible=!0}}},[n("svg-icon",{attrs:{"icon-class":"tree"}})],1)]),t._v(" "),n("section",{staticClass:"section"},[n("div",{staticClass:"flex flex-col items-center"},[n("el-tag",{attrs:{type:"success",effect:"dark"}},[t._v(t._s(t.form.model_current))]),t._v(" "),n("div",{staticClass:"w-04-rem h-24 bg-indigo-600 draw-arrow-down one"}),t._v(" "),n("el-select",{class:{"error-danger":t.errors.relationship},attrs:{placeholder:"Relationship"},on:{change:function(e){return t.replaceTemplate("")}},model:{value:t.form.relationship,callback:function(e){t.$set(t.form,"relationship",e)},expression:"form.relationship"}},t._l(t.relationshipOptions,(function(t,e){return n("el-option",{key:"relationship_"+e,attrs:{label:t,value:t}})})),1),t._v(" "),t.errors.relationship?n("p",{staticClass:"help is-danger text-lg"},[t._v(t._s(t.errors.relationship[0]))]):t._e(),t._v(" "),n("div",{staticClass:"w-04-rem h-24 bg-indigo-600 draw-arrow-down two"}),t._v(" "),n("el-select",{class:{"error-danger":t.errors.model},attrs:{loading:t.loadingModel,filterable:"",placeholder:"Model"},on:{change:function(e){return t.replaceTemplate("display")}},model:{value:t.form.model,callback:function(e){t.$set(t.form,"model",e)},expression:"form.model"}},t._l(t.modelOptions,(function(t,e){return n("el-option",{key:"model_"+e,attrs:{label:t,value:t}})})),1),t._v(" "),t.errors.model?n("p",{staticClass:"help is-danger text-lg"},[t._v(t._s(t.errors.model[0]))]):t._e(),t._v(" "),n("div",{staticClass:"w-04-rem h-24 bg-indigo-600 draw-arrow-down three"}),t._v(" "),n("div",{staticClass:"z-10"},[n("pre-code-tag",{attrs:{content:t.markdown}})],1),t._v(" "),n("div",{staticClass:"w-04-rem h-24 bg-indigo-600 draw-arrow-down four"}),t._v(" "),n("el-select",{staticClass:"z-10",class:{"error-danger":t.errors.column},attrs:{loading:t.loadingDisplay,filterable:"",placeholder:"Display Column"},model:{value:t.form.column,callback:function(e){t.$set(t.form,"column",e)},expression:"form.column"}},t._l(t.displayColumns,(function(t,e){return n("el-option",{key:"col_"+e,attrs:{label:t,value:t}})})),1),t._v(" "),"belongsToMany"===t.form.relationship?[n("div",{staticClass:"w-04-rem h-24 bg-indigo-600 draw-arrow-down equivalent"}),t._v(" "),n("el-select",{class:{"error-danger":t.errors.column2},attrs:{loading:t.loadingDisplay,filterable:"",placeholder:"Display Column 2"},model:{value:t.form.column2,callback:function(e){t.$set(t.form,"column2",e)},expression:"form.column2"}},t._l(t.displayColumns2,(function(t,e){return n("el-option",{key:"col_"+e,attrs:{label:t,value:t}})})),1)]:t._e(),t._v(" "),t.errors.column?n("p",{staticClass:"help is-danger text-lg"},[t._v(t._s(t.errors.column[0]))]):t._e(),t._v(" "),n("div",{staticClass:"w-04-rem h-24 bg-indigo-600 draw-arrow-down five"}),t._v(" "),n("el-select",{staticClass:"options",attrs:{multiple:"",placeholder:"Options"},on:{change:function(e){return t.changeOptions(t.form.options)}},model:{value:t.form.options,callback:function(e){t.$set(t.form,"options",e)},expression:"form.options"}},t._l(t.options,(function(t,e){return n("el-option",{key:"option_"+e,attrs:{label:t,value:t}})})),1),t._v(" "),n("div",{staticClass:"w-04-rem h-24 bg-indigo-600 draw-arrow-down six"}),t._v(" "),n("el-tooltip",{attrs:{effect:"dark",content:t.$t("route.generator_relationship"),placement:"bottom"}},[n("el-button",{directives:[{name:"loading",rawName:"v-loading.fullscreen.lock",value:t.loading,expression:"loading",modifiers:{fullscreen:!0,lock:!0}}],staticClass:"z-10",attrs:{type:"success",icon:"el-icon-check",circle:""},on:{click:function(e){return e.preventDefault(),t.createRelationship()}}})],1)],2),t._v(" "),n("div",{staticClass:"container is-fullhd"},[n("el-dialog",{attrs:{visible:t.dialogVisible,fullscreen:!0},on:{"update:visible":function(e){t.dialogVisible=e},open:t.diagram}},[n("div",{staticClass:"text-center",attrs:{slot:"title"},slot:"title"},[n("h3",{staticClass:"title"},[t._v("Diagram "+t._s(t.$t("route.generator_relationship")))])]),t._v(" "),n("div",[n("div",{staticClass:"tree text-center"},[n("ul",{staticClass:"inline-block"},t._l(t.drawDiagram,(function(e,o){return n("li",{key:"diagram_"+o},[n("a",[t._v(t._s(e.model))]),t._v(" "),n("ul",{staticClass:"flex"},t._l(e.data,(function(e,o){return n("li",{key:"itemDiagram_"+o},[n("a",[t._v(t._s(e.type))]),t._v(" "),n("ul",[n("li",[n("a",{staticClass:"w-64"},[t._v(t._s(e.model))]),t._v(" "),e.table?n("ul",{class:{"has-mtm-parent":e.table}},[n("a",{staticClass:"w-64"},[t._v(t._s(e.table))])]):t._e(),t._v(" "),n("ul",[n("li",[n("a",{class:{"has-mtm":e.table}},[t._v(t._s(e.foreign_key))])]),t._v(" "),n("li",[n("a",{class:{"has-mtm":e.table}},[t._v(t._s(e.local_key))])])])])])])})),0)])})),0)])])])],1)])])],1)],1)}),[],!1,null,"2d2ea0b0",null));e.default=l.exports},lwsE:function(t,e){t.exports=function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}},mHXD:function(t,e,n){"use strict";var o=n("4d3n");n.n(o).a},z3Kf:function(t,e,n){(t.exports=n("I1BE")(!1)).push([t.i,'.w-04-rem[data-v-2d2ea0b0] {\n  width: 0.4rem;\n}\n.draw-arrow-down[data-v-2d2ea0b0] {\n  position: relative;\n}\n.draw-arrow-down[data-v-2d2ea0b0]:before {\n  content: "";\n  position: absolute;\n  bottom: -1rem;\n  right: -0.3rem;\n  width: 0;\n  height: 0;\n  border-left: 0.5rem solid transparent;\n  border-right: 0.5rem solid transparent;\n  border-top: 1.9rem solid #5a67d8;\n}\n.error-danger[data-v-2d2ea0b0] input {\n  border-color: #ff3860 !important;\n}\n.options[data-v-2d2ea0b0] {\n  width: 260px;\n}\n.one[data-v-2d2ea0b0]:after {\n  content: "1";\n  background-color: #5a67d8;\n  position: absolute;\n  top: 14px;\n  left: -8px;\n  color: #fff;\n  width: 20px;\n  height: 20px;\n  border-radius: 100%;\n  text-align: center;\n  line-height: 20px;\n}\n.two[data-v-2d2ea0b0]:after {\n  content: "2";\n  background-color: #5a67d8;\n  position: absolute;\n  top: 14px;\n  left: -8px;\n  color: #fff;\n  width: 20px;\n  height: 20px;\n  border-radius: 100%;\n  text-align: center;\n  line-height: 20px;\n}\n.three[data-v-2d2ea0b0]:after {\n  content: "3";\n  background-color: #5a67d8;\n  position: absolute;\n  top: 14px;\n  left: -8px;\n  color: #fff;\n  width: 20px;\n  height: 20px;\n  border-radius: 100%;\n  text-align: center;\n  line-height: 20px;\n}\n.four[data-v-2d2ea0b0]:after {\n  content: "4";\n  background-color: #5a67d8;\n  position: absolute;\n  top: 14px;\n  left: -8px;\n  color: #fff;\n  width: 20px;\n  height: 20px;\n  border-radius: 100%;\n  text-align: center;\n  line-height: 20px;\n}\n.five[data-v-2d2ea0b0]:after {\n  content: "5";\n  background-color: #5a67d8;\n  position: absolute;\n  top: 14px;\n  left: -8px;\n  color: #fff;\n  width: 20px;\n  height: 20px;\n  border-radius: 100%;\n  text-align: center;\n  line-height: 20px;\n}\n.six[data-v-2d2ea0b0]:after {\n  content: "6";\n  background-color: #5a67d8;\n  position: absolute;\n  top: 14px;\n  left: -8px;\n  color: #fff;\n  width: 20px;\n  height: 20px;\n  border-radius: 100%;\n  text-align: center;\n  line-height: 20px;\n}\n.equivalent[data-v-2d2ea0b0]:after {\n  content: "";\n  position: absolute;\n  top: -1rem;\n  right: -0.3rem;\n  width: 0;\n  height: 0;\n  border-left: 0.5rem solid transparent;\n  border-right: 0.5rem solid transparent;\n  border-bottom: 1.9rem solid #5a67d8;\n}',""])}}]);