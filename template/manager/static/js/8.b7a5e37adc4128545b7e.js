webpackJsonp([8],{NWrh:function(e,t){},yQu4:function(e,t,s){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var o=s("mvHQ"),i=s.n(o),n=s("Cz8s"),a=s("+TmT"),l=s("deIj"),r={data:function(){return{timePickerShow:!1,type:"title",store:{title:"",logo:"",content:"",business_hours:[],telephone:"",address:"",thumbs:[],category_arr:[],notice:"",tips:""},categorys:[],businessHoursSelected:{index:0,type:"s"},logo:[],thumbs:[],islegal:!1,showPreLoading:!0}},components:{publicHeader:n.a,uploader:a.a},methods:{onToggleTimePicker:function(e,t){this.timePickerShow=!this.timePickerShow,e>-1&&("s"==t||"e"==t)&&(this.businessHoursSelected.index=e,this.businessHoursSelected.type=t)},onConfirmTime:function(e){if(e){var t=this.businessHoursSelected.index,s=this.businessHoursSelected.type;this.store.business_hours[t][s]=e,this.onToggleTimePicker()}},onAddTime:function(){return"business_hours"==this.type&&(this.store.business_hours.length>=3?(this.util.$toast("最多可添加三个营业时间段"),!1):void this.store.business_hours.push({s:"08:00",e:"21:00"}))},onRemoveTime:function(e){if("business_hours"!=this.type)return!1;this.store.business_hours.splice(e,1)},onToggleCategory:function(e,t){this.$refs.checkboxes[t].toggle()},onChangeLogo:function(e){var t="";e&&1==e.length&&e[0].filename&&(t=e[0].filename),this.store.logo=t},onChangeThumbs:function(e){var t=[];if(e&&e.length>0)for(var s in e)t.push({image:e[s].url,url:""});this.store.thumbs=t},onLoad:function(){var e=this;Object(l.a)({url:"manage/shop/index/info",vue:e,autoAssign:!0,variable:"store",data:{id:e.id,type:e.type},success:function(t){if(e.islegal=!0,"logo"==e.type)e.store.logo&&e.logo.push({url:e.store.logo});else if("thumbs"==e.type&&e.store.thumbs&&e.store.thumbs.length>0)for(var s in e.store.thumbs)e.thumbs.push({url:e.store.thumbs[s].image})}})},onSubmit:function(){var e=this;if(!e.islegal)return!1;e.islegal=!1;var t=e.store[e.type],s="pages/shop/basic";"notice"!=e.type&&"tips"!=e.type||(s="pages/shop/index"),Object(l.c)({url:"manage/shop/index/setting",vue:e,data:{type:e.type,value:i()(t)},redirect:e.util.getUrl({path:s}),fail:function(t){e.util.$toast(t),e.islegal=!0}})}},created:function(){this.$route.query&&this.$route.query.type&&(this.type=this.$route.query.type)},mounted:function(){this.onLoad()}},c={render:function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("div",{attrs:{id:"store-update"}},[s("public-header",{attrs:{title:"基本信息设置"}}),e._v(" "),s("div",{staticClass:"content"},["title"==e.type?s("van-cell-group",[s("van-field",{attrs:{label:"门店名称",placeholder:"请输入门店名称","input-align":"right"},model:{value:e.store.title,callback:function(t){e.$set(e.store,"title",t)},expression:"store.title"}})],1):"telephone"==e.type?s("van-cell-group",[s("van-field",{attrs:{label:"联系电话",placeholder:"请输入门店联系电话","input-align":"right"},model:{value:e.store.telephone,callback:function(t){e.$set(e.store,"telephone",t)},expression:"store.telephone"}})],1):"address"==e.type?s("van-cell-group",[s("van-field",{attrs:{label:"门店地址",placeholder:"请输入门店地址","input-align":"right"},model:{value:e.store.address,callback:function(t){e.$set(e.store,"address",t)},expression:"store.address"}})],1):"content"==e.type?[s("div",{staticClass:"font-14 padding-15-lr padding-10-tb c-gray"},[e._v("门店描述")]),e._v(" "),s("van-cell-group",[s("van-field",{attrs:{type:"textarea",rows:"3",autosize:"",placeholder:"请输入门店描述"},model:{value:e.store.content,callback:function(t){e.$set(e.store,"content",t)},expression:"store.content"}})],1)]:"notice"==e.type?[s("div",{staticClass:"font-14 padding-15-lr padding-10-tb c-gray"},[e._v("商户公告")]),e._v(" "),s("van-cell-group",[s("van-field",{attrs:{type:"textarea",rows:"3",autosize:"",placeholder:"请输入商户公告"},model:{value:e.store.notice,callback:function(t){e.$set(e.store,"notice",t)},expression:"store.notice"}})],1)]:"tips"==e.type?[s("div",{staticClass:"font-14 padding-15-lr padding-10-tb c-gray"},[e._v("商品列表页提示")]),e._v(" "),s("van-cell-group",[s("van-field",{attrs:{type:"textarea",rows:"3",autosize:"",placeholder:"用户进入页面后, 将弹框提示设置的内容"},model:{value:e.store.tips,callback:function(t){e.$set(e.store,"tips",t)},expression:"store.tips"}})],1)]:"business_hours"==e.type?[s("div",{staticClass:"font-14 padding-15-lr padding-10-tb c-gray"},[e._v("营业时间")]),e._v(" "),s("van-cell-group",e._l(e.store.business_hours,function(t,o){return s("van-cell",{key:o},[s("div",{attrs:{slot:"title"},slot:"title"},[s("span",{on:{click:function(t){e.onToggleTimePicker(o,"s")}}},[e._v(e._s(t.s))]),e._v(" "),s("span",{staticClass:"padding-10-lr"},[e._v("至")]),e._v(" "),s("span",{on:{click:function(t){e.onToggleTimePicker(o,"e")}}},[e._v(e._s(t.e))])]),e._v(" "),s("div",{staticClass:"c-info",attrs:{slot:"right-icon"},on:{click:function(t){e.onRemoveTime(o)}},slot:"right-icon"},[e._v("移除")])])})),e._v(" "),s("van-cell-group",{class:{"margin-10-t":e.store.business_hours.length>0}},[s("van-cell",[s("div",{staticClass:"c-info",attrs:{slot:"title"},on:{click:e.onAddTime},slot:"title"},[s("i",{staticClass:"icon icon-roundadd font-16"}),e._v("添加时间段\n\t\t\t\t\t")])])],1),e._v(" "),s("van-popup",{attrs:{position:"bottom"},model:{value:e.timePickerShow,callback:function(t){e.timePickerShow=t},expression:"timePickerShow"}},[s("van-datetime-picker",{attrs:{type:"time"},on:{confirm:e.onConfirmTime,cancel:e.onToggleTimePicker}})],1)]:"logo"==e.type?[s("div",{staticClass:"font-14 padding-15-lr padding-10-tb c-gray"},[e._v("门店Logo")]),e._v(" "),s("uploader",{attrs:{max:"1",value:e.logo},on:{onGetUrl:e.onChangeLogo,delete:e.onChangeLogo}})]:"thumbs"==e.type?[s("div",{staticClass:"font-14 padding-15-lr padding-10-tb c-gray"},[e._v("门店实景")]),e._v(" "),s("uploader",{attrs:{max:"7",value:e.thumbs},on:{onGetUrl:e.onChangeThumbs,delete:e.onChangeThumbs}})]:e._e(),e._v(" "),s("div",{staticClass:"padding-15"},[s("van-button",{staticClass:"bg-info",attrs:{size:"normal",disabled:!e.islegal,block:""},on:{click:e.onSubmit}},[e._v("保存修改")])],1)],2),e._v(" "),e.showPreLoading?s("iloading"):e._e()],1)},staticRenderFns:[]};var u=s("VU/8")(r,c,!1,function(e){s("NWrh")},"data-v-be8552ec",null);t.default=u.exports}});
//# sourceMappingURL=8.b7a5e37adc4128545b7e.js.map