webpackJsonp([35],{n241:function(t,e,i){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o=i("Gu7T"),a=i.n(o),s=i("Cz8s"),r=i("+CBI"),n=i("deIj"),l=i("+TmT"),c={data:function(){return{id:17,goodsCategory:!1,category:[],datePicker:{type:"",maxDate:new Date(2050,12),currentDate:new Date,status:!1},bestsetting:"0",showPreLoading:!0,records:{name:"",total:"",unit:"",cateid:"",category_title:"",price:"",aloneprice:"",oldprice:"",islimittime:0,starttime_cn:"",endtime_cn:"",status:1,usetype:"1",peoplenum:"",grouptime:"",falesailed:"",buylimit:""}}},components:{publicHeader:s.a,iswitch:r.a,Uploader:l.a},methods:{onLoad:function(){var t=this;Object(n.a)({vue:t,url:"manage/pintuan/goods/post",data:{id:t.id},success:function(e){t.records=t.util.extend(t.records,e.records),e.category&&(t.category=[].concat(a()(t.category),a()(e.category)))}})},onGetImgsUrl:function(t){},onTogglePopup:function(){this.goodsCategory=!this.goodsCategory},onConfirmCategory:function(t){this.records.cateid=t.id,this.records.category_title=t.title,this.onTogglePopup()},onToggleDatePicker:function(t){this.datePicker.status=!this.datePicker.status,t&&(this.datePicker.type=t)},onConfirmTime:function(t){var e=this.util.formatDate(t);"start"==this.datePicker.type?this.records.starttime_cn=e:this.records.endtime_cn=e,this.onToggleDatePicker()},jsToggleSwitch:function(t){this.util.jsToggleSwitch({vue:this,key:t.keys,value:t.value})},onSubmit:function(){Object(n.c)({vue:this,url:"manage/pintuan/goods/post",data:{id:this.id,data:this.records},redirect:this.util.getUrl({path:"/pages/pintuan/goods/list"})})}},created:function(){this.$route.query&&this.$route.query.id>0&&(this.id=parseInt(this.$route.query.id))},mounted:function(){this.onLoad()}},d={render:function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{attrs:{id:"pintuangoods-post"}},[i("public-header",{attrs:{title:"添加拼团商品"}}),t._v(" "),i("div",{staticClass:"content"},[i("van-cell-group",[i("van-cell",[i("div",{staticClass:"upload-img"},[i("div",{staticClass:"flex-lr goods-img"},[i("div",[t._v("商品图片")]),t._v(" "),i("div",{staticClass:"c-gray"},[t._v("最多5张,第一张为商品列表页图片")])]),t._v(" "),i("uploader",{attrs:{max:5},on:{onGetUrl:t.onGetImgsUrl}})],1)]),t._v(" "),i("van-field",{attrs:{label:"商品名称",placeholder:"请填写商品名称","input-align":"right"},model:{value:t.records.name,callback:function(e){t.$set(t.records,"name",e)},expression:"records.name"}}),t._v(" "),i("van-field",{attrs:{label:"商品库存",placeholder:"请填写商品库存","input-align":"right"},model:{value:t.records.total,callback:function(e){t.$set(t.records,"total",e)},expression:"records.total"}}),t._v(" "),i("van-field",{attrs:{label:"商品单位",placeholder:"请填写商品单位","input-align":"right"},model:{value:t.records.unit,callback:function(e){t.$set(t.records,"unit",e)},expression:"records.unit"}}),t._v(" "),i("van-cell",{attrs:{title:"商品详情"}},[i("div",{staticClass:"flex",attrs:{slot:"right-icon"},slot:"right-icon"},[i("span",[t._v("未编辑")]),t._v(" "),i("van-icon",{attrs:{name:"arrow"}})],1)])],1),t._v(" "),i("van-cell-group",{staticClass:"margin-10-t"},[i("van-cell",{attrs:{title:"拼团分类"}},[i("div",{staticClass:"flex",attrs:{slot:"right-icon"},on:{click:t.onTogglePopup},slot:"right-icon"},[i("span",[t._v(t._s(t.records.category_title?t.records.category_title:"请选择"))]),t._v(" "),i("van-icon",{attrs:{name:"arrow"}})],1)]),t._v(" "),i("van-field",{attrs:{label:"团购价",type:"number",placeholder:"请输入团购价","input-align":"right"},model:{value:t.records.price,callback:function(e){t.$set(t.records,"price",e)},expression:"records.price"}}),t._v(" "),i("van-field",{attrs:{label:"单购价",type:"number",placeholder:"请输入团购价","input-align":"right"},model:{value:t.records.aloneprice,callback:function(e){t.$set(t.records,"aloneprice",e)},expression:"records.aloneprice"}}),t._v(" "),i("van-field",{attrs:{label:"市场价",type:"number",placeholder:"请输入市场价","input-align":"right"},model:{value:t.records.oldprice,callback:function(e){t.$set(t.records,"oldprice",e)},expression:"records.oldprice"}}),t._v(" "),i("van-cell",[i("div",{attrs:{slot:"title"},slot:"title"},[t._v("定时发售")]),t._v(" "),i("iswitch",{attrs:{slot:"right-icon",value:t.records.islimittime,"condition-open":"1","condition-close":"0","text-open":"ON","text-close":"OFF",keys:"records.islimittime"},on:{change:t.jsToggleSwitch},slot:"right-icon"})],1),t._v(" "),1==t.records.islimittime?[i("van-cell",{attrs:{title:"活动开始时间"}},[i("div",{staticClass:"flex",attrs:{slot:"right-icon"},on:{click:function(e){t.onToggleDatePicker("start")}},slot:"right-icon"},[i("span",[t._v(t._s(this.records.starttime_cn?this.records.starttime_cn:"点击选择"))]),t._v(" "),i("van-icon",{attrs:{name:"arrow"}})],1)]),t._v(" "),i("van-cell",{attrs:{title:"活动结束时间"}},[i("div",{staticClass:"flex",attrs:{slot:"right-icon"},on:{click:function(e){t.onToggleDatePicker("end")}},slot:"right-icon"},[i("span",[t._v(t._s(this.records.endtime_cn?this.records.endtime_cn:"点击选择"))]),t._v(" "),i("van-icon",{attrs:{name:"arrow"}})],1)])]:t._e(),t._v(" "),i("van-cell",[i("div",{attrs:{slot:"title"},slot:"title"},[t._v("活动状态")]),t._v(" "),i("iswitch",{attrs:{slot:"right-icon",value:t.records.status,"condition-open":"1","condition-close":"0","text-open":"ON","text-close":"OFF",keys:"records.status"},on:{change:t.jsToggleSwitch},slot:"right-icon"})],1)],2),t._v(" "),i("van-radio-group",{staticClass:"margin-10-t",model:{value:t.records.usetype,callback:function(e){t.$set(t.records,"usetype",e)},expression:"records.usetype"}},[i("van-cell-group",[i("van-cell",{attrs:{title:"消费方式"}}),t._v(" "),i("van-cell",{staticClass:"border-0px",attrs:{title:"到店消费",clickable:""},on:{click:function(e){t.records.usetype="1"}}},[i("van-radio",{attrs:{name:"1"}})],1),t._v(" "),i("van-cell",{staticClass:"border-0px",attrs:{title:"快递上门",clickable:""},on:{click:function(e){t.records.usetype="2"}}},[i("van-radio",{attrs:{name:"2"}})],1)],1)],1),t._v(" "),i("van-cell-group",{staticClass:"margin-10-t"},[i("van-cell",[i("div",{attrs:{slot:"title"},slot:"title"},[t._v("高级设置")]),t._v(" "),i("iswitch",{attrs:{slot:"right-icon",value:t.bestsetting,"condition-open":"1","condition-close":"0","text-open":"ON","text-close":"OFF",keys:"bestsetting"},on:{change:t.jsToggleSwitch},slot:"right-icon"})],1)],1),t._v(" "),"1"==t.bestsetting?[i("van-cell-group",{staticClass:"margin-10-t"},[i("van-field",{attrs:{label:"开团人数",placeholder:"请输入开团人数","input-align":"right"},model:{value:t.records.peoplenum,callback:function(e){t.$set(t.records,"peoplenum",e)},expression:"records.peoplenum"}}),t._v(" "),i("van-field",{attrs:{label:"组团时间",placeholder:"单位小时","input-align":"right"},model:{value:t.records.grouptime,callback:function(e){t.$set(t.records,"grouptime",e)},expression:"records.grouptime"}}),t._v(" "),i("van-field",{attrs:{label:"虚拟销量",placeholder:"请输入虚拟销量","input-align":"right"},model:{value:t.records.falesailed,callback:function(e){t.$set(t.records,"falesailed",e)},expression:"records.falesailed"}}),t._v(" "),i("van-field",{attrs:{label:"单次限购",placeholder:"请输入单次限购","input-align":"right"},model:{value:t.records.buylimit,callback:function(e){t.$set(t.records,"buylimit",e)},expression:"records.buylimit"}})],1)]:t._e(),t._v(" "),i("div",{staticClass:"padding-15"},[i("van-button",{staticClass:"bg-info",attrs:{size:"normal",block:""},on:{click:t.onSubmit}},[t._v("提交")])],1)],2),t._v(" "),t.showPreLoading?i("iloading"):t._e(),t._v(" "),i("van-popup",{attrs:{position:"bottom"},model:{value:t.goodsCategory,callback:function(e){t.goodsCategory=e},expression:"goodsCategory"}},[i("van-picker",{attrs:{"show-toolbar":"",title:"所属分类",columns:t.category,"value-key":"title"},on:{cancel:t.onTogglePopup,confirm:t.onConfirmCategory}})],1),t._v(" "),i("van-popup",{staticClass:"popup-time",attrs:{position:"bottom"},model:{value:t.datePicker.status,callback:function(e){t.$set(t.datePicker,"status",e)},expression:"datePicker.status"}},[i("van-datetime-picker",{attrs:{type:"datetime","min-data":t.datePicker.currentDate,"max-date":t.datePicker.maxDate},on:{confirm:t.onConfirmTime,cancel:t.onToggleDatePicker},model:{value:t.datePicker.currentDate,callback:function(e){t.$set(t.datePicker,"currentDate",e)},expression:"datePicker.currentDate"}})],1)],1)},staticRenderFns:[]};var u=i("VU/8")(c,d,!1,function(t){i("yD6N")},null,null);e.default=u.exports},yD6N:function(t,e){}});
//# sourceMappingURL=35.fc2f8179e6ab53de34ef.js.map