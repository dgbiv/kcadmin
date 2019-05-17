(function(e){function t(t){for(var i,r,s=t[0],l=t[1],u=t[2],p=0,d=[];p<s.length;p++)r=s[p],o[r]&&d.push(o[r][0]),o[r]=0;for(i in l)Object.prototype.hasOwnProperty.call(l,i)&&(e[i]=l[i]);c&&c(t);while(d.length)d.shift()();return a.push.apply(a,u||[]),n()}function n(){for(var e,t=0;t<a.length;t++){for(var n=a[t],i=!0,s=1;s<n.length;s++){var l=n[s];0!==o[l]&&(i=!1)}i&&(a.splice(t--,1),e=r(r.s=n[0]))}return e}var i={},o={1:0},a=[];function r(t){if(i[t])return i[t].exports;var n=i[t]={i:t,l:!1,exports:{}};return e[t].call(n.exports,n,n.exports,r),n.l=!0,n.exports}r.m=e,r.c=i,r.d=function(e,t,n){r.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:n})},r.r=function(e){"undefined"!==typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},r.t=function(e,t){if(1&t&&(e=r(e)),8&t)return e;if(4&t&&"object"===typeof e&&e&&e.__esModule)return e;var n=Object.create(null);if(r.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var i in e)r.d(n,i,function(t){return e[t]}.bind(null,i));return n},r.n=function(e){var t=e&&e.__esModule?function(){return e["default"]}:function(){return e};return r.d(t,"a",t),t},r.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},r.p="/";var s=window["webpackJsonp"]=window["webpackJsonp"]||[],l=s.push.bind(s);s.push=t,s=s.slice();for(var u=0;u<s.length;u++)t(s[u]);var c=l;a.push([1,0]),n()})({1:function(e,t,n){e.exports=n("Vtdi")},Vtdi:function(e,t,n){"use strict";n.r(t);n("VRzm");var i=n("Kw5r"),o=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",[n("form",{attrs:{action:"",method:"post",id:"app"},on:{submit:e.onSubmit}},[n("h4",[e._v("问卷标题")]),n("input",{directives:[{name:"model",rawName:"v-model",value:e.title,expression:"title"},{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],staticClass:"title",attrs:{type:"text",name:"questionnaire[title]",required:""},domProps:{value:e.title},on:{input:function(t){t.target.composing||(e.title=t.target.value)}}}),n("h4",[e._v("问卷简介")]),n("textarea",{directives:[{name:"model",rawName:"v-model",value:e.brief,expression:"brief"},{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],staticClass:"brief",attrs:{name:"questionnaire[brief]",required:""},domProps:{value:e.brief},on:{input:function(t){t.target.composing||(e.brief=t.target.value)}}}),e._l(e.blockList,function(t,i){return n("div",{key:i,staticClass:"block"},[n("div",{staticClass:"block-header"},[n("label",[e._v("块名")]),0===i?n("span",[e._v(e._s(t.name))]):n("input",{directives:[{name:"model",rawName:"v-model",value:t.name,expression:"block.name"},{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],attrs:{type:"text",required:""},domProps:{value:t.name},on:{input:function(n){n.target.composing||e.$set(t,"name",n.target.value)}}}),i>0?n("div",{staticClass:"right"},[n("label",{attrs:{for:""}},[e._v("关联：")]),e.canRelatedList?n("select",{directives:[{name:"model",rawName:"v-model",value:t.byQuestion,expression:"block.byQuestion"}],on:{change:function(n){var i=Array.prototype.filter.call(n.target.options,function(e){return e.selected}).map(function(e){var t="_value"in e?e._value:e.value;return t});e.$set(t,"byQuestion",n.target.multiple?i:i[0])}}},e._l(e.canRelatedList,function(t){return n("option",{domProps:{value:t.label}},[e._v(e._s(t.label)+"\n            ")])})):e._e(),n("select",{directives:[{name:"model",rawName:"v-model",value:t.byOption,expression:"block.byOption"}],on:{change:function(n){var i=Array.prototype.filter.call(n.target.options,function(e){return e.selected}).map(function(e){var t="_value"in e?e._value:e.value;return t});e.$set(t,"byOption",n.target.multiple?i:i[0])}}},e._l(e.getOptions(t),function(t){return n("option",{domProps:{value:t}},[e._v(e._s(t)+"\n            ")])}))]):e._e()]),n("table",{staticClass:"table table-hover"},[e._m(0,!0),n("draggable",{attrs:{element:"tbody",options:{filter:"input, textarea",preventOnFilter:!1}},model:{value:t.content,callback:function(n){e.$set(t,"content",n)},expression:"block.content"}},e._l(t.content,function(i,o){return n("tr",{key:o},[n("td",[n("select",{directives:[{name:"model",rawName:"v-model",value:i.type,expression:"question.type"}],attrs:{name:"type",id:"",required:""},on:{change:function(t){var n=Array.prototype.filter.call(t.target.options,function(e){return e.selected}).map(function(e){var t="_value"in e?e._value:e.value;return t});e.$set(i,"type",t.target.multiple?n:n[0])}}},e._l(e.types,function(t,i){return n("option",{domProps:{value:i}},[e._v(e._s(t))])}))]),n("td",[n("input",{directives:[{name:"model",rawName:"v-model",value:i.label,expression:"question.label"},{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],attrs:{type:"text",placeholder:"请输入标题",required:""},domProps:{value:i.label},on:{input:function(t){t.target.composing||e.$set(i,"label",t.target.value)}}})]),n("td",[1==i.type?n("input",{directives:[{name:"model",rawName:"v-model",value:i.options,expression:"question.options"}],attrs:{type:"text",placeholder:"输入单位（可选）"},domProps:{value:i.options},on:{input:function(t){t.target.composing||e.$set(i,"options",t.target.value)}}}):4==i.type?n("select",{directives:[{name:"model",rawName:"v-model",value:i.dateType,expression:"question.dateType"},{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],staticClass:"date-type",attrs:{name:"data-type",required:""},on:{change:function(t){var n=Array.prototype.filter.call(t.target.options,function(e){return e.selected}).map(function(e){var t="_value"in e?e._value:e.value;return t});e.$set(i,"dateType",t.target.multiple?n:n[0])}}},e._l(e.dateTypes,function(t,i){return n("option",{domProps:{value:i,selected:i>2}},[e._v(e._s(t))])})):n("textarea",{directives:[{name:"model",rawName:"v-model",value:i.options,expression:"question.options"},{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],attrs:{type:"text",minlength:"1",required:"",placeholder:"请输入选项，以中文逗号分隔"},domProps:{value:i.options},on:{input:function(t){t.target.composing||e.$set(i,"options",t.target.value)}}})]),n("td",[n("toggle-button",{attrs:{labels:{checked:"是",unchecked:"否"}},model:{value:i.required,callback:function(t){e.$set(i,"required",t)},expression:"question.required"}})],1),n("td",[n("div",{staticClass:"btn-group",attrs:{role:"group","aria-label":"..."}},[t.content.length>1?n("button",{staticClass:"btn btn-default glyphicon glyphicon-trash",attrs:{type:"button"},on:{click:function(n){t.content.splice(e.index,1)}}}):e._e(),n("button",{staticClass:"btn btn-default glyphicon glyphicon-plus",attrs:{type:"button"},on:{click:function(n){t.content.splice(e.index+1,0,JSON.parse(JSON.stringify(e.defaultConfig)))}}})])]),n("td",[e._v("=")])])}))],1),n("p",[n("button",{staticClass:"btn btn-primary",attrs:{type:"button"},on:{click:function(n){t.content.push(JSON.parse(JSON.stringify(e.defaultConfig)))}}},[e._v("新增项 ")]),i>0?n("button",{staticClass:"btn btn-primary",attrs:{type:"button"},on:{click:function(t){e.removeBlock(i)}}},[e._v("删除块")]):e._e()])])}),n("p",[n("button",{staticClass:"btn btn-info",attrs:{type:"button"},on:{click:e.createBlock}},[e._v("新增块")])]),n("p",[n("button",{staticClass:"btn btn-success",on:{click:e.submit}},[e._v("提交")])])],2)])},a=[function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("thead",[n("tr",[n("td",[n("h4",[e._v("类型")])]),n("td",[n("h4",[e._v("标题")])]),n("td",[n("h4",[e._v("选项/单位\n            "),n("span",{staticClass:"hint"},[e._v("（输入为单位，显示在输入框后；单选和复选框为选项，以中文逗号分隔，例如：选项一，选项二，选项三）")])])]),n("td",[n("h4",[e._v("必填")])]),n("td",[n("h4",[e._v("操作")])]),n("td",[n("h4",[e._v("拖动排序")])])])])}],r=(n("INYr"),n("rGqo"),n("KKXr"),n("dRSK"),n("vDqi")),s=n.n(r),l=n("FRYs"),u=n.n(l),c={name:"app",components:{draggable:u.a},data:function(){return{title:"",brief:"",blockList:[{id:0,name:"主体",byBlock:"",byQuestion:"",byOption:"",content:[{type:1,label:"",options:"",dateType:"",required:!0}]}],types:{1:"输入",2:"单选",3:"多选",4:"日期"},dateTypes:{1:"年",2:"年月",3:"年月日"},defaultConfig:{type:1,label:"",options:"",dateType:"",required:!0},id:1}},computed:{canRelatedList:function(){return this.blockList[0].content.filter(function(e){return 2==e.type})}},methods:{createBlock:function(){this.blockList.push({id:this.id,name:"块"+this.id++,byBlock:"",byQuestion:"",byOption:"",content:[JSON.parse(JSON.stringify(this.defaultConfig))]})},getOtherBlockList:function(e){var t=JSON.parse(JSON.stringify(this.blockList));return t.splice(e,1),t},getOptions:function(e){if(!this.canRelatedList)return[];var t=this.canRelatedList.find(function(t){return t.label===e.byQuestion});if(t){var n=t.options.split("，");if(n)return n}},addQuestion:function(e){"number"===typeof e?this.content.splice(e+1,0,defaultValue):this.content.push(defaultValue)},onSubmit:function(e){e.preventDefault()},removeBlock:function(e){confirm("确认删除这个块以及这个块下的所有问答吗？")&&this.blockList.splice(e,1)},submit:function(){var e=this;this.$validator.validate().then(function(t){if(!t)return alert("请填写正确、完整的信息"),!1;var n=JSON.parse(JSON.stringify(e.blockList[0].content)),i={};n=n.map(function(e){return 2!=e.type&&3!=e.type||(e.options=e.options.split("，")),e}),e.blockList.forEach(function(e){if(e.byQuestion&&e.byOption){var t=n.findIndex(function(t){return t.label===e.byQuestion});if(t>=0){var i=n[t].options.findIndex(function(t){return t===e.byOption});i>=0&&(e.content=e.content.map(function(e){return 2!=e.type&&3!=e.type||(e.options=e.options.split("，")),e})),n[t].options[i]={name:n[t].options[i],child:e.content}}}}),i={title:e.title,brief:e.brief,content:n},n&&(console.log(i),s.a.post("/questionnaire/questions/create-questionnaire",i).then(function(e){e.data&&1===e.data.status&&(location.href="/questionnaire/questionnaire")}))})}},created:function(){window.onbeforeunload=function(){return"你确定要舍弃所有修改并离开此页面吗？此页面的数据将不会被保留。"}}},p=c,d=(n("nNx0"),n("KHd+")),v=Object(d["a"])(p,o,a,!1,null,null,null),f=v.exports,m=n("e7F3"),b=n("8gbZ"),y=n.n(b);i["default"].use(m["a"],{events:""}),i["default"].config.productionTip=!1,i["default"].use(y.a),new i["default"]({render:function(e){return e(f)}}).$mount("#app")},boi5:function(e,t,n){},nNx0:function(e,t,n){"use strict";var i=n("boi5"),o=n.n(i);o.a}});
//# sourceMappingURL=app.79a25b5e.js.map