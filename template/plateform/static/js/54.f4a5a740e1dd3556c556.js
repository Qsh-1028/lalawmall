webpackJsonp([54],{AZ3W:function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var s=a("Dd8w"),l=a.n(s),r=a("NYxO"),n=a("Cz8s"),i=a("deIj"),o={components:{publicHeader:n.a},data:function(){return{showPreLoading:!0,stat:{},store:{}}},methods:l()({},Object(r.b)(["setSearch"]),{onLoad:function(){var t=this;Object(i.a)({vue:t,data:{filter:t.filter?t.filter.items:{}},url:"plateform/statcenter/takeoutOrderChannel/index",success:function(e){t.stat=e.stat,t.store=e.store}})}}),computed:l()({},Object(r.c)(["filter"])),mounted:function(){this.onLoad()}},c={render:function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{attrs:{id:"statcenter-takeout"}},[a("public-header",{attrs:{title:"订单来源统计"}}),t._v(" "),a("div",{staticClass:"content"},[a("div",{staticClass:"padding-15"},[a("span",{staticClass:"font-16"},[t._v(t._s(t.store.title?t.store.title:"全部店铺"))]),t._v(" "),a("span",{staticClass:"c-gray font-14"},[t._v(t._s(t.filter.items&&t.filter.items.stat_day?t.filter.items.stat_day.start+"-"+t.filter.items.stat_day.end:"今天"))])]),t._v(" "),a("van-cell-group",[a("van-cell",{attrs:{title:"总订单",value:t.stat.total_success_order}}),t._v(" "),a("van-cell",{attrs:{title:"总饿了么订单数",value:t.stat.total_eleme_order}}),t._v(" "),a("van-cell",{attrs:{title:"总美团订单数",value:t.stat.total_meituan_order}}),t._v(" "),a("van-cell",{attrs:{title:"总小程序订单数",value:t.stat.total_wxapp_order}}),t._v(" "),a("van-cell",{attrs:{title:"总H5订单数",value:t.stat.total_wap_order}}),t._v(" "),a("van-cell",{attrs:{title:"总顾客APP订单数",value:t.stat.total_h5app_order}}),t._v(" "),a("van-cell",{attrs:{title:"总后台创建订单数",value:t.stat.total_plateformCreate_order}})],1),t._v(" "),a("div",{staticClass:"padding-10"},[a("van-button",{staticClass:"font-16",attrs:{type:"primary",size:"normal",block:""},on:{click:function(e){t.util.jsUrl("pages/statcenter/takeoutOrderChannelDetail")}}},[t._v("查看详情")])],1)],1),t._v(" "),t.showPreLoading?a("iloading"):t._e()],1)},staticRenderFns:[]};var d=a("VU/8")(o,c,!1,function(t){a("J95R")},null,null);e.default=d.exports},J95R:function(t,e){}});