webpackJsonp([62],{"0DN+":function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var i=a("Cz8s"),s=a("deIj"),r={data:function(){return{records:{page:1,psize:15,loading:!1,finished:!1,empty:!1,data:[]},type:"0",isRefresh:!1,showPreLoading:!0,filter:{items:{type:"0"}}}},components:{publicHeader:i.a},methods:{onLoad:function(t){Object(s.b)({vue:this,force:t,url:"plateform/wheel/record/order"})},onPullDownRefresh:function(){this.onLoad(!0)},onChangeStatus:function(t,e){var a=this;a.util.jspost({url:"plateform/wheel/record/status",data:{id:t.id},confirm:"确定发放赠品吗?",success:function(){a.records.data[e].status=1}})},onToggleStatus:function(t){this.filter.items.type!=t&&(this.filter.items.type=t)}},mounted:function(){this.onLoad()},watch:{filter:{handler:function(t,e){this.onLoad(!0)},deep:!0}}},d={render:function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{attrs:{id:"wheel-record"}},[a("public-header",{attrs:{title:"幸运大转盘参与记录"}}),t._v(" "),a("div",{staticClass:"content"},[a("div",{staticClass:"wrap-search wrap-search-input"},[a("div",{staticClass:"tab-group flex-lr border-1px-b"},[a("div",{staticClass:"tab-item",class:{active:"0"==t.filter.items.type},on:{click:function(e){t.onToggleStatus("0")}}},[t._v("全部")]),t._v(" "),a("div",{staticClass:"tab-item",class:{active:"one"==t.filter.items.type},on:{click:function(e){t.onToggleStatus("one")}}},[t._v("一等奖")]),t._v(" "),a("div",{staticClass:"tab-item",class:{active:"two"==t.filter.items.type},on:{click:function(e){t.onToggleStatus("two")}}},[t._v("二等奖")]),t._v(" "),a("div",{staticClass:"tab-item",class:{active:"three"==t.filter.items.type},on:{click:function(e){t.onToggleStatus("three")}}},[t._v("三等奖")]),t._v(" "),a("div",{staticClass:"tab-item",class:{active:"noaward"==t.filter.items.type},on:{click:function(e){t.onToggleStatus("noaward")}}},[t._v("未中奖")])]),t._v(" "),a("van-search",{attrs:{placeholder:"请输入用户姓名或UID"},model:{value:t.filter.items.keyword,callback:function(e){t.$set(t.filter.items,"keyword",e)},expression:"filter.items.keyword"}})],1),t._v(" "),a("van-pull-refresh",{on:{refresh:function(e){t.onPullDownRefresh()}},model:{value:t.isRefresh,callback:function(e){t.isRefresh=e},expression:"isRefresh"}},[t.records.empty?a("div",{staticClass:"no-data"},[a("img",{attrs:{src:"static/img/order_no_con.png",alt:""}}),t._v(" "),a("p",[t._v("没有符合条件的数据!")])]):a("van-list",{staticClass:"record-list",attrs:{finished:t.records.finished,offset:100,"immediate-check":!1},on:{load:t.onLoad},model:{value:t.records.loading,callback:function(e){t.$set(t.records,"loading",e)},expression:"records.loading"}},[t._l(t.records.data,function(e,i){return a("div",{key:e.id,staticClass:"record-item"},[a("div",{staticClass:"record-title flex-lr"},[a("div",{staticClass:"title flex"},[a("div",{staticClass:"title-img"},[a("img",{attrs:{src:e.avatar,alt:""}})]),t._v(" "),a("div",{staticClass:"title-name"},[t._v(t._s(e.nickname))]),t._v(" "),a("div",{staticClass:"font-14 c-gray padding-10-r"},[t._v("UID："+t._s(e.uid))])]),t._v(" "),"one"==e.award.type?a("div",{staticClass:"itag itag-primary"},[t._v("一等奖")]):"two"==e.award.type?a("div",{staticClass:"itag itag-info"},[t._v("二等奖")]):"three"==e.award.type?a("div",{staticClass:"itag itag-danger"},[t._v("三等奖")]):"noaward"==e.award.type?a("div",{staticClass:"itag itag-disabled"},[t._v("未中奖")]):t._e()]),t._v(" "),a("div",{staticClass:"record-detail border-1px-t"},[a("div",{staticClass:"record-info flex"},[a("div",[t._v("活动名称：")]),t._v(" "),a("div",[t._v(t._s(e.activity_title))])]),t._v(" "),"noaward"!==e.type?a("div",{staticClass:"record-info flex"},[a("div",[t._v("奖项详情：")]),t._v(" "),e.award_type?["redpacket"!==e.award_type.name?a("div",[t._v("\n\t\t\t\t\t\t\t\t\t"+t._s(e.award_value)+"\n\t\t\t\t\t\t\t\t\t"),"credit1"==e.award_type.name?[t._v("积分")]:"credit2"==e.award_type.name?[t._v("元")]:t._e(),t._v(" "),a("div",{staticClass:"gift-type itag itag-danger"},[t._v(t._s(e.award_type.text))])],2):a("div",[t._v("\n\t\t\t\t\t\t\t\t\t满减红包\n\t\t\t\t\t\t\t\t\t"),a("div",{staticClass:"gift-type itag itag-danger"},[t._v(t._s(e.award_type.text))])])]:t._e()],2):a("div",{staticClass:"record-info flex"},[a("div",[t._v("奖项详情：")]),t._v(" "),a("div",[t._v("未中奖")])]),t._v(" "),a("div",{staticClass:"record-info flex"},[a("div",[t._v("抽奖时间：")]),t._v(" "),a("div",[t._v(t._s(e.addtime_cn))])])]),t._v(" "),0==e.status?a("div",{staticClass:"border-1px-t btn-group"},[a("div",{staticClass:"btn-item bg-default",on:{click:function(a){t.onChangeStatus(e,i)}}},[t._v("设为已处理")])]):t._e()])}),t._v(" "),t.records.finished?a("div",{staticClass:"loaded"},[a("div",{staticClass:"loaded-tips"},[t._v("没有更多了")])]):t._e()],2)],1)],1),t._v(" "),t.showPreLoading?a("iloading"):t._e()],1)},staticRenderFns:[]};var n=a("VU/8")(r,d,!1,function(t){a("6E4E")},null,null);e.default=n.exports},"6E4E":function(t,e){}});