(self.webpackChunk=self.webpackChunk||[]).push([[498],{63349:(t,e,a)=>{"use strict";function n(t){if(void 0===t)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return t}a.r(e),a.d(e,{default:()=>n})},6610:(t,e,a)=>{"use strict";function n(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}a.r(e),a.d(e,{default:()=>n})},5991:(t,e,a)=>{"use strict";function n(t,e){for(var a=0;a<e.length;a++){var n=e[a];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(t,n.key,n)}}function r(t,e,a){return e&&n(t.prototype,e),a&&n(t,a),t}a.r(e),a.d(e,{default:()=>r})},77608:(t,e,a)=>{"use strict";function n(t){return(n=Object.setPrototypeOf?Object.getPrototypeOf:function(t){return t.__proto__||Object.getPrototypeOf(t)})(t)}a.r(e),a.d(e,{default:()=>n})},10379:(t,e,a)=>{"use strict";a.r(e),a.d(e,{default:()=>r});var n=a(14665);function r(t,e){if("function"!=typeof e&&null!==e)throw new TypeError("Super expression must either be null or a function");t.prototype=Object.create(e&&e.prototype,{constructor:{value:t,writable:!0,configurable:!0}}),e&&(0,n.default)(t,e)}},46070:(t,e,a)=>{"use strict";a.r(e),a.d(e,{default:()=>i});var n=a(90484),r=a(63349);function i(t,e){return!e||"object"!==(0,n.default)(e)&&"function"!=typeof e?(0,r.default)(t):e}},14665:(t,e,a)=>{"use strict";function n(t,e){return(n=Object.setPrototypeOf||function(t,e){return t.__proto__=e,t})(t,e)}a.r(e),a.d(e,{default:()=>n})},75357:(t,e,a)=>{"use strict";a.r(e),a.d(e,{default:()=>c});a(12419);var n=a(6610),r=a(5991),i=a(10379),l=a(46070),s=a(77608),o=a(43753);function u(t){var e=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Boolean.prototype.valueOf.call(Reflect.construct(Boolean,[],(function(){}))),!0}catch(t){return!1}}();return function(){var a,n=(0,s.default)(t);if(e){var r=(0,s.default)(this).constructor;a=Reflect.construct(n,arguments,r)}else a=n.apply(this,arguments);return(0,l.default)(this,a)}}var c=function(t){(0,i.default)(a,t);var e=u(a);function a(){return(0,n.default)(this,a),e.call(this,"generators")}return(0,r.default)(a,[{key:"checkModel",value:function(t){return(0,o.default)({url:"/generators/check-model",method:"get",params:{name:t}})}},{key:"checkColumn",value:function(t,e){return(0,o.default)({url:"/generators/check-column",method:"get",params:{table:t,column:e}})}},{key:"getModels",value:function(t){return(0,o.default)({url:"/generators/get-models",method:"get",params:{model:t}})}},{key:"getAllModels",value:function(){return(0,o.default)({url:"/generators/get-all-models",method:"get"})}},{key:"getColumns",value:function(t){return(0,o.default)({url:"/generators/get-columns",method:"get",params:{table:t}})}},{key:"generateRelationship",value:function(t){return(0,o.default)({url:"/generators/relationship",method:"post",data:t})}},{key:"generateDiagram",value:function(t){return(0,o.default)({url:"/generators/diagram",method:"get",params:{model:t}})}}]),a}(a(27427).default)},27427:(t,e,a)=>{"use strict";a.r(e),a.d(e,{default:()=>l});var n=a(6610),r=a(5991),i=a(43753),l=function(){function t(e){(0,n.default)(this,t),this.uri=e}return(0,r.default)(t,[{key:"list",value:function(t){return(0,i.default)({url:this.uri,method:"get",params:t})}},{key:"get",value:function(t){return(0,i.default)({url:this.uri+"/"+t,method:"get"})}},{key:"store",value:function(t){return(0,i.default)({url:this.uri,method:"post",data:t})}},{key:"update",value:function(t,e){return(0,i.default)({url:this.uri+"/"+t,method:"put",data:e})}},{key:"destroy",value:function(t){return(0,i.default)({url:this.uri+"/"+t,method:"delete"})}}]),t}()},76438:(t,e,a)=>{"use strict";a.r(e),a.d(e,{default:()=>r});a(9653);var n=a(77854);const r={name:"Pagination",props:{total:{required:!0,type:Number},page:{type:Number,default:1},limit:{type:Number,default:25},pageSizes:{type:Array,default:function(){return[10,25,50,100]}},layout:{type:String,default:"total, sizes, prev, pager, next, jumper"},background:{type:Boolean,default:!0},autoScroll:{type:Boolean,default:!0},hidden:{type:Boolean,default:!1},scrollTo:{type:Number,default:0}},computed:{currentPage:{get:function(){return this.page},set:function(t){this.$emit("update:page",t)}},pageSize:{get:function(){return this.limit},set:function(t){this.$emit("update:limit",t)}}},methods:{handleSizeChange:function(t){this.$emit("pagination",{page:this.currentPage,limit:t}),this.autoScroll&&(0,n.scrollTo)(this.scrollTo,800)},handleCurrentChange:function(t){this.$emit("pagination",{page:t,limit:this.pageSize}),this.autoScroll&&(0,n.scrollTo)(this.scrollTo,800)}}}},87750:(t,e,a)=>{"use strict";a.r(e),a.d(e,{default:()=>d});var n=a(92137),r=(a(34553),a(40561),a(87757)),i=a.n(r),l=a(54614),s=a(78524),o=a(75357),u=a(29698),c=new o.default;const d={components:{Pagination:s.default},mixins:[l.default],data:function(){return{diagram:"/images/diagram-erd.png",dialogVisible:!1,table:{listQuery:{search:"",limit:25,ascending:1,page:1,orderBy:"updated_at",updated_at:[]},list:null,total:0,loading:!1}}},watch:{"table.listQuery.search":(0,u.debounce)((function(){this.handleFilter()}),500)},mounted:function(){this.getList()},methods:{getList:function(){var t=this;return(0,n.default)(i().mark((function e(){var a,n;return i().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return t.table.loading=!0,e.next=3,c.list(t.table.listQuery);case 3:a=e.sent,n=a.data,t.table.list=n.data,t.table.total=n.count,t.table.loading=!1;case 8:case"end":return e.stop()}}),e)})))()},handleFilter:function(){this.table.listQuery.page=1,this.getList()},changeDateRangePicker:function(t){if(t){var e=this.parseTime(t[0]),a=this.parseTime(t[1]);this.table.listQuery.updated_at=[e,a]}else this.table.listQuery.updated_at=[];this.handleFilter()},sortChange:function(t){var e=t.prop,a=t.order;this.table.listQuery.orderBy=e,this.table.listQuery.ascending=+("ascending"===a),this.getList()},remove:function(t){var e=this;this.$confirm(this.$t("messages.delete_confirm",{attribute:this.$t("table.user.id")+"#"+t}),this.$t("messages.warning"),{confirmButtonText:this.$t("button.ok"),cancelButtonClass:this.$t("button.cancel"),type:"warning",center:!0}).then((0,n.default)(i().mark((function a(){var n;return i().wrap((function(a){for(;;)switch(a.prev=a.next){case 0:return e.table.loading=!0,a.next=3,c.destroy(t);case 3:n=e.table.list.findIndex((function(e){return e.id===t})),e.table.list.splice(n,1),e.$message({showClose:!0,message:e.$t("messages.delete"),type:"success"}),e.table.loading=!1;case 7:case"end":return a.stop()}}),a)}))))},parseTime:function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"{y}-{m}-{d}";return this.$options.filters.parseTime(t,e)},numericalOrder:function(t){var e=this.table.listQuery;return(e.page-1)*e.limit+t+1}}}},27065:(t,e,a)=>{"use strict";var n=a(13099),r=a(70111),i=[].slice,l={},s=function(t,e,a){if(!(e in l)){for(var n=[],r=0;r<e;r++)n[r]="a["+r+"]";l[e]=Function("C,a","return new C("+n.join(",")+")")}return l[e](t,a)};t.exports=Function.bind||function(t){var e=n(this),a=i.call(arguments,1),l=function(){var n=a.concat(i.call(arguments));return this instanceof l?s(e,n.length,n):e.apply(t,n)};return r(e.prototype)&&(l.prototype=e.prototype),l}},34553:(t,e,a)=>{"use strict";var n=a(82109),r=a(42092).findIndex,i=a(51223),l="findIndex",s=!0;l in[]&&Array(1).findIndex((function(){s=!1})),n({target:"Array",proto:!0,forced:s},{findIndex:function(t){return r(this,t,arguments.length>1?arguments[1]:void 0)}}),i(l)},12419:(t,e,a)=>{var n=a(82109),r=a(35005),i=a(13099),l=a(19670),s=a(70111),o=a(70030),u=a(27065),c=a(47293),d=r("Reflect","construct"),f=c((function(){function t(){}return!(d((function(){}),[],t)instanceof t)})),p=!c((function(){d((function(){}))})),g=f||p;n({target:"Reflect",stat:!0,forced:g,sham:g},{construct:function(t,e){i(t),l(e);var a=arguments.length<3?t:i(arguments[2]);if(p&&!f)return d(t,e,a);if(t==a){switch(e.length){case 0:return new t;case 1:return new t(e[0]);case 2:return new t(e[0],e[1]);case 3:return new t(e[0],e[1],e[2]);case 4:return new t(e[0],e[1],e[2],e[3])}var n=[null];return n.push.apply(n,e),new(u.apply(t,n))}var r=a.prototype,c=o(s(r)?r:Object.prototype),g=Function.apply.call(t,c,e);return s(g)?g:c}})},62347:(t,e,a)=>{(e=a(23645)(!1)).push([t.id,".pagination-container[data-v-f0c38056]{background:#fff;padding:32px 16px}.pagination-container[data-v-f0c38056]  .el-input{width:120px}.pagination-container.hidden[data-v-f0c38056]{display:none}",""]),t.exports=e},54038:(t,e,a)=>{(e=a(23645)(!1)).push([t.id,".generate-table[data-v-2425a081] thead>tr>th:nth-child(4){width:245px}",""]),t.exports=e},65186:(t,e,a)=>{"use strict";a.r(e),a.d(e,{default:()=>y});var n=a(93379),r=a.n(n),i=a(7795),l=a.n(i),s=a(90569),o=a.n(s),u=a(3565),c=a.n(u),d=a(19216),f=a.n(d),p=a(44589),g=a.n(p),h=a(62347),m=a.n(h),v={};for(const t in h)"default"!==t&&(v[t]=()=>h[t]);a.d(e,v);var b={};b.styleTagTransform=g(),b.setAttributes=c(),b.insert=o().bind(null,"head"),b.domAPI=l(),b.insertStyleElement=f();r()(m(),b);const y=m()&&m().locals?m().locals:void 0},25446:(t,e,a)=>{"use strict";a.r(e),a.d(e,{default:()=>y});var n=a(93379),r=a.n(n),i=a(7795),l=a.n(i),s=a(90569),o=a.n(s),u=a(3565),c=a.n(u),d=a(19216),f=a.n(d),p=a(44589),g=a.n(p),h=a(54038),m=a.n(h),v={};for(const t in h)"default"!==t&&(v[t]=()=>h[t]);a.d(e,v);var b={};b.styleTagTransform=g(),b.setAttributes=c(),b.insert=o().bind(null,"head"),b.domAPI=l(),b.insertStyleElement=f();r()(m(),b);const y=m()&&m().locals?m().locals:void 0},78524:(t,e,a)=>{"use strict";a.r(e),a.d(e,{default:()=>l});var n=a(56660),r=a(61108),i={};for(const t in r)"default"!==t&&(i[t]=()=>r[t]);a.d(e,i);a(46234);const l=(0,a(51900).default)(r.default,n.render,n.staticRenderFns,!1,null,"f0c38056",null).exports},68498:(t,e,a)=>{"use strict";a.r(e),a.d(e,{default:()=>l});var n=a(29260),r=a(21229),i={};for(const t in r)"default"!==t&&(i[t]=()=>r[t]);a.d(e,i);a(78164);const l=(0,a(51900).default)(r.default,n.render,n.staticRenderFns,!1,null,"2425a081",null).exports},61108:(t,e,a)=>{"use strict";a.r(e),a.d(e,{default:()=>i});var n=a(76438),r={};for(const t in n)"default"!==t&&(r[t]=()=>n[t]);a.d(e,r);const i=n.default},21229:(t,e,a)=>{"use strict";a.r(e),a.d(e,{default:()=>i});var n=a(87750),r={};for(const t in n)"default"!==t&&(r[t]=()=>n[t]);a.d(e,r);const i=n.default},46234:(t,e,a)=>{"use strict";a.r(e);var n=a(65186),r={};for(const t in n)"default"!==t&&(r[t]=()=>n[t]);a.d(e,r)},78164:(t,e,a)=>{"use strict";a.r(e);var n=a(25446),r={};for(const t in n)"default"!==t&&(r[t]=()=>n[t]);a.d(e,r)},56660:(t,e,a)=>{"use strict";a.r(e);var n=a(84894),r={};for(const t in n)"default"!==t&&(r[t]=()=>n[t]);a.d(e,r)},29260:(t,e,a)=>{"use strict";a.r(e);var n=a(81414),r={};for(const t in n)"default"!==t&&(r[t]=()=>n[t]);a.d(e,r)},84894:(t,e,a)=>{"use strict";a.r(e),a.d(e,{render:()=>n,staticRenderFns:()=>r});var n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"pagination-container",class:{hidden:t.hidden}},[a("el-pagination",t._b({attrs:{background:t.background,"current-page":t.currentPage,"page-size":t.pageSize,layout:t.layout,"page-sizes":t.pageSizes,total:t.total},on:{"update:currentPage":function(e){t.currentPage=e},"update:current-page":function(e){t.currentPage=e},"update:pageSize":function(e){t.pageSize=e},"update:page-size":function(e){t.pageSize=e},"size-change":t.handleSizeChange,"current-change":t.handleCurrentChange}},"el-pagination",t.$attrs,!1))],1)},r=[]},81414:(t,e,a)=>{"use strict";a.r(e),a.d(e,{render:()=>n,staticRenderFns:()=>r});var n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("el-row",[a("el-col",{attrs:{span:24}},[a("el-card",[a("div",{staticClass:"tw-flex tw-justify-between tw-items-center",attrs:{slot:"header"},slot:"header"},[a("div"),t._v(" "),a("button",{staticClass:"\n            hover:tw-bg-green-600 hover:tw-text-white\n            tw-font-bold tw-border tw-rounded tw-border-green-600 tw-text-green-600 tw-bg-transparent tw-py-3 tw-px-4\n          ",on:{click:function(e){t.dialogVisible=!0}}},[a("svg-icon",{attrs:{"icon-class":"tree-table"}})],1),t._v(" "),a("router-link",{attrs:{to:{name:"GeneratorCreate"},custom:""},scopedSlots:t._u([{key:"default",fn:function(e){var n=e.href,r=e.navigate;return[a("a",{staticClass:"pan-btn blue-btn",attrs:{href:n},on:{click:r}},[a("i",{staticClass:"el-icon-plus tw-mr-2"}),t._v("\n            Create\n          ")])]}}])})],1),t._v(" "),a("div",{staticClass:"tw-flex tw-flex-col"},[a("el-col",{staticClass:"tw-mb-6",attrs:{span:24}},[a("el-col",{attrs:{xs:24,sm:10,md:6}},[a("label",[t._v(t._s(t.$t("table.texts.filter")))]),t._v(" "),a("el-input",{attrs:{placeholder:t.$t("table.texts.filterPlaceholder")},model:{value:t.table.listQuery.search,callback:function(e){t.$set(t.table.listQuery,"search",e)},expression:"table.listQuery.search"}})],1),t._v(" "),a("el-col",{attrs:{xs:24,sm:14,md:18}},[a("br"),t._v(" "),a("el-date-picker",{staticClass:"md:tw-float-right",attrs:{type:"daterange","start-placeholder":t.$t("date.start_date"),"end-placeholder":t.$t("date.end_date"),"picker-options":t.pickerOptions},on:{change:t.changeDateRangePicker},model:{value:t.table.listQuery.updated_at,callback:function(e){t.$set(t.table.listQuery,"updated_at",e)},expression:"table.listQuery.updated_at"}})],1)],1),t._v(" "),a("el-col",{staticClass:"table-responsive",attrs:{span:24}},[a("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.table.loading,expression:"table.loading"}],staticClass:"w-full",attrs:{data:t.table.list,"default-sort":{prop:"updated_at",order:"descending"},border:"",fit:"","highlight-current-row":""},on:{"sort-change":t.sortChange}},[a("el-table-column",{attrs:{align:"center",sortable:"custom",prop:"id",label:"No.",width:"70px"},scopedSlots:t._u([{key:"default",fn:function(e){var a=e.row;return[t._v("\n                "+t._s(a.id)+"\n              ")]}}])}),t._v(" "),a("el-table-column",{attrs:{align:"center",label:"Table"},scopedSlots:t._u([{key:"default",fn:function(e){var a=e.row;return[t._v("\n                "+t._s(a.table)+"\n              ")]}}])}),t._v(" "),a("el-table-column",{attrs:{"data-generator":"updated_at",prop:"updated_at",label:t.$t("date.updated_at"),sortable:"custom",align:"center","header-align":"center"},scopedSlots:t._u([{key:"default",fn:function(e){var a=e.row;return[t._v("\n                "+t._s(t._f("parseTime")(a.updated_at,"{y}-{m}-{d}"))+"\n              ")]}}])}),t._v(" "),a("el-table-column",{attrs:{label:t.$t("table.actions"),align:"center","class-name":"small-padding fixed-width"},scopedSlots:t._u([{key:"default",fn:function(e){var n=e.row;return[a("router-link",{attrs:{to:{name:"GeneratorEdit",params:{id:n.id}}}},[a("el-tooltip",{attrs:{effect:"dark",content:"Update",placement:"left"}},[a("i",{staticClass:"el-icon-edit el-link el-link--primary tw-mr-4"})])],1),t._v(" "),a("router-link",{attrs:{to:{name:"GeneratorRelationship",params:{id:n.id}}}},[a("el-tooltip",{attrs:{effect:"dark",content:"Relationship",placement:"top"}},[a("svg-icon",{staticClass:"el-link el-link--success tw-mr-4",attrs:{"icon-class":"tree"}})],1)],1),t._v(" "),1!==n.id?a("a",{directives:[{name:"permission",rawName:"v-permission",value:["delete"],expression:"['delete']"}],staticClass:"cursor-pointer",on:{click:function(e){return e.stopPropagation(),function(){return t.remove(n.id)}.apply(null,arguments)}}},[a("el-tooltip",{attrs:{effect:"dark",content:"Remove",placement:"right"}},[a("i",{staticClass:"el-icon-delete el-link el-link--danger"})])],1):t._e()]}}])})],1),t._v(" "),t.table.total>0?a("pagination",{attrs:{total:t.table.total,page:t.table.listQuery.page,limit:t.table.listQuery.limit},on:{"update:page":function(e){return t.$set(t.table.listQuery,"page",e)},"update:limit":function(e){return t.$set(t.table.listQuery,"limit",e)},pagination:t.getList}}):t._e()],1)],1)])],1),t._v(" "),a("div",{staticClass:"container tw-is-fullhd"},[a("el-dialog",{attrs:{visible:t.dialogVisible,fullscreen:!0},on:{"update:visible":function(e){t.dialogVisible=e}}},[a("div",{staticClass:"text-center",attrs:{slot:"title"},slot:"title"},[a("h3",{staticClass:"title"},[t._v("Diagram "+t._s(t.$t("route.generator_relationship")))])]),t._v(" "),a("div",[a("div",{staticClass:"demo-image__preview"},[a("el-image",{attrs:{src:t.diagram,"preview-src-list":[t.diagram]}})],1),t._v(" "),a("svg-icon",{attrs:{"icon-class":"diagram-erd"}})],1)])],1)],1)},r=[]}}]);