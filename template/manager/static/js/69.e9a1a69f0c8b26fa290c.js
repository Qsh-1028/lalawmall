webpackJsonp([69],{CleF:function(t,a){},l9xD:function(t,a,i){"use strict";Object.defineProperty(a,"__esModule",{value:!0});var s=i("Cz8s"),n=i("mzkE"),e=i("deIj"),l={data:function(){return{store:{},stat:{},news:{},ads:{},menufooter:{},showPreLoading:!0}},components:{publicHeader:s.a,publicFooter:n.a},methods:{onLoad:function(){var t=this;Object(e.a)({vue:this,url:"manage/shop/index/newindex",data:{_account_perm:1,menufooter:1},success:function(a){t.store=a.store,t.stat=a.stat,t.news=a.news,a.ads&&(t.ads=a.ads)}})}},mounted:function(){this.onLoad()}},o={render:function(){var t=this,a=t.$createElement,i=t._self._c||a;return i("div",{attrs:{id:"store-newhome"}},[i("public-header",{attrs:{title:t.store.title}}),t._v(" "),i("public-footer",{attrs:{menufooter:t.menufooter}}),t._v(" "),i("div",{staticClass:"content"},[i("div",{staticClass:"header-title flex-lr"},[i("div",{staticClass:"title-wrap"},[i("div",{staticClass:"font-18 font-bold"},[t._v(t._s(t.stat.final_fee))]),t._v(" "),i("div",{staticClass:"font-13 padding-5-t"},[t._v("今日总收入")])]),t._v(" "),i("div",{staticClass:"title-wrap"},[i("div",{staticClass:"font-18 font-bold"},[t._v(t._s(t.stat.total_order))]),t._v(" "),i("div",{staticClass:"font-13 padding-5-t"},[t._v("有效订单")]),t._v(" "),i("span",{staticClass:"icon icon-right font-16"})])]),t._v(" "),i("div",{staticClass:"nav-list"},[i("van-row",[i("van-col",{attrs:{span:"6"}},[i("router-link",{attrs:{tag:"div",to:t.util.getUrl({path:"/pages/goods/index"})}},[i("div",{staticClass:"icon icon-goods"}),t._v(" "),i("div",{staticClass:"font-13"},[t._v("商品")])])],1),t._v(" "),i("van-col",{attrs:{span:"6"}},[i("router-link",{attrs:{tag:"div",to:t.util.getUrl({path:"/pages/service/comment"})}},[i("div",{staticClass:"icon icon-survey"}),t._v(" "),i("div",{staticClass:"font-13"},[t._v("评论")])])],1),t._v(" "),i("van-col",{attrs:{span:"6"}},[i("router-link",{attrs:{tag:"div",to:t.util.getUrl({path:"/pages/finance/index"})}},[i("div",{staticClass:"icon icon-recharge"}),t._v(" "),i("div",{staticClass:"font-13"},[t._v("资产")])])],1),t._v(" "),i("van-col",{attrs:{span:"6"}},[i("router-link",{attrs:{tag:"div",to:t.util.getUrl({path:"/pages/shop/setting"})}},[i("div",{staticClass:"icon icon-shop"}),t._v(" "),i("div",{staticClass:"font-13"},[t._v("店铺")])])],1)],1)],1),t._v(" "),i("div",{staticClass:"placeholder"}),t._v(" "),t.ads.length>0?i("div",{staticClass:"swipe-wrap"},[i("van-swipe",{attrs:{autoplay:3e3}},t._l(t.ads,function(a,s){return i("van-swipe-item",{key:a.id},[i("div",{on:{click:function(i){t.util.jsUrl(a.link)}}},[i("img",{attrs:{src:t.util.tomedia(a.thumb)}})])])}))],1):t._e(),t._v(" "),i("div",{staticClass:"make-money"},[i("div",{staticClass:"make-money-title"},[t._v("赚钱技巧")]),t._v(" "),i("div",{staticClass:"skill-list"},[i("div",{staticClass:"skill-wrap"},[i("div",{staticClass:"skill-item flex "},[i("router-link",{staticClass:"item-info flex",attrs:{tag:"div",to:t.util.getUrl({path:"/pages/statcenter/index"})}},[i("div",{staticClass:"img"},[i("img",{attrs:{src:"http://mine/we7/attachment//images/1/2019/01/ocrsIec7NnH1DCD5Rhv8H78IUR2S8N.jpg",alt:""}})]),t._v(" "),i("div",{staticClass:"item-info-left padding-10-l"},[i("div",{staticClass:"font-16 font-bold"},[t._v("销售统计")]),t._v(" "),i("div",{staticClass:"font-14 c-gray padding-5-t"},[t._v("销售精准分析")])])]),t._v(" "),i("router-link",{staticClass:"item-info flex",attrs:{tag:"div",to:t.util.getUrl({path:"/pages/activity/index"})}},[i("div",{staticClass:"img"},[i("img",{attrs:{src:"http://mine/we7/attachment//images/1/2019/01/ocrsIec7NnH1DCD5Rhv8H78IUR2S8N.jpg",alt:""}})]),t._v(" "),i("div",{staticClass:"item-info-left padding-10-l"},[i("div",{staticClass:"font-16 font-bold"},[t._v("钜惠活动")]),t._v(" "),i("div",{staticClass:"font-14 c-gray padding-5-t"},[t._v("实惠让利顾客")])])])],1),t._v(" "),i("div",{staticClass:"skill-item flex van-hairline--top"},[t.util.checkplugin("advertise")?i("router-link",{staticClass:"item-info flex",attrs:{tag:"div",to:t.util.getUrl({path:"/pages/advertise/index"})}},[i("div",{staticClass:"img"},[i("img",{attrs:{src:"http://mine/we7/attachment//images/1/2019/01/ocrsIec7NnH1DCD5Rhv8H78IUR2S8N.jpg",alt:""}})]),t._v(" "),i("div",{staticClass:"item-info-left padding-10-l"},[i("div",{staticClass:"font-16 font-bold"},[t._v("店铺推广")]),t._v(" "),i("div",{staticClass:"font-14 c-gray padding-5-t"},[t._v("获取更多顾客")])])]):t._e(),t._v(" "),i("router-link",{staticClass:"item-info flex",attrs:{tag:"div",to:t.util.getUrl({path:"/pages/service/comment"})}},[i("div",{staticClass:"img"},[i("img",{attrs:{src:"http://mine/we7/attachment//images/1/2019/01/ocrsIec7NnH1DCD5Rhv8H78IUR2S8N.jpg",alt:""}})]),t._v(" "),i("div",{staticClass:"item-info-left padding-10-l"},[i("div",{staticClass:"font-16 font-bold"},[t._v("优质售后")]),t._v(" "),i("div",{staticClass:"font-14 c-gray padding-5-t"},[t._v("评价提升服务")])])])],1)])])]),t._v(" "),i("div",{staticClass:"tool-wrap"},[i("div",{staticClass:"tool-title van-hairline--bottom"},[t._v("实用工具")]),t._v(" "),i("van-row",[i("van-col",{attrs:{span:"6"}},[i("router-link",{attrs:{tag:"div",to:t.util.getUrl({path:"/pages/shop/setting"})}},[i("div",{staticClass:"icon icon-shop c-danger"}),t._v(" "),i("div",{staticClass:"font-14 padding-5-t"},[t._v("店铺管理")])])],1),t._v(" "),i("van-col",{attrs:{span:"6"}},[i("router-link",{attrs:{tag:"div",to:t.util.getUrl({path:"/pages/order/index"})}},[i("div",{staticClass:"icon icon-order c-warning"}),t._v(" "),i("div",{staticClass:"font-14 padding-5-t"},[t._v("外卖订单")])])],1),t._v(" "),i("van-col",{attrs:{span:"6"}},[i("router-link",{attrs:{tag:"div",to:t.util.getUrl({path:"/pages/order/tangshi/index"})}},[i("div",{staticClass:"icon icon-meal c-info"}),t._v(" "),i("div",{staticClass:"font-14 padding-5-t"},[t._v("店内订单")])])],1),t._v(" "),i("van-col",{attrs:{span:"6"}},[i("router-link",{attrs:{tag:"div",to:t.util.getUrl({path:"/pages/goods/index"})}},[i("div",{staticClass:"icon icon-goods c-warning"}),t._v(" "),i("div",{staticClass:"font-14 padding-5-t"},[t._v("商品管理")])])],1),t._v(" "),i("van-col",{attrs:{span:"6"}},[i("router-link",{attrs:{tag:"div",to:t.util.getUrl({path:"/pages/service/comment"})}},[i("div",{staticClass:"icon icon-survey c-info"}),t._v(" "),i("div",{staticClass:"font-14 padding-5-t"},[t._v("评论管理")])])],1),t._v(" "),i("van-col",{attrs:{span:"6"}},[i("router-link",{attrs:{tag:"div",to:t.util.getUrl({path:"/pages/finance/index"})}},[i("div",{staticClass:"icon icon-recharge c-danger"}),t._v(" "),i("div",{staticClass:"font-14 padding-5-t"},[t._v("资产")])])],1),t._v(" "),i("van-col",{attrs:{span:"6"}},[i("router-link",{attrs:{tag:"div",to:t.util.getUrl({path:"/pages/activity/index"})}},[i("div",{staticClass:"icon icon-activity c-warning"}),t._v(" "),i("div",{staticClass:"font-14 padding-5-t"},[t._v("店铺活动")])])],1),t._v(" "),t.util.checkplugin("advertise")?i("van-col",{attrs:{span:"6"}},[i("router-link",{attrs:{tag:"div",to:t.util.getUrl({path:"/pages/advertise/index"})}},[i("div",{staticClass:"icon icon-medal c-info"}),t._v(" "),i("div",{staticClass:"font-14 padding-5-t"},[t._v("店铺推广")])])],1):t._e(),t._v(" "),i("van-col",{attrs:{span:"6"}},[i("router-link",{attrs:{tag:"div",to:t.util.getUrl({path:"/pages/news/notice"})}},[i("div",{staticClass:"icon icon-newshot c-danger"}),t._v(" "),i("div",{staticClass:"font-14 padding-5-t"},[t._v("公告")])])],1),t._v(" "),i("van-col",{attrs:{span:"6"}},[i("router-link",{attrs:{tag:"div",to:t.util.getUrl({path:"/pages/paybill/index"})}},[i("div",{staticClass:"icon icon-signboard c-warning"}),t._v(" "),i("div",{staticClass:"font-14 padding-5-t"},[t._v("买单")])])],1),t._v(" "),i("van-col",{attrs:{span:"6"}},[i("router-link",{attrs:{tag:"div",to:t.util.getUrl({path:"/pages/tangshi/table"})}},[i("div",{staticClass:"icon icon-similar c-info"}),t._v(" "),i("div",{staticClass:"font-14 padding-5-t"},[t._v("桌台管理")])])],1),t._v(" "),i("van-col",{attrs:{span:"6"}},[i("router-link",{attrs:{tag:"div",to:t.util.getUrl({path:"/pages/tangshi/assign"})}},[i("div",{staticClass:"icon icon-friend_light c-danger"}),t._v(" "),i("div",{staticClass:"font-14 padding-5-t"},[t._v("排队")])])],1),t._v(" "),t.util.checkplugin("gohome")?i("van-col",{attrs:{span:"6"}},[i("router-link",{attrs:{tag:"div",to:t.util.getUrl({path:"/pages/gohome/index"})}},[i("div",{staticClass:"icon icon-home c-warning"}),t._v(" "),i("div",{staticClass:"font-14 padding-5-t"},[t._v("生活圈")])])],1):t._e(),t._v(" "),i("van-col",{attrs:{span:"6"}},[i("router-link",{attrs:{tag:"div",to:t.util.getUrl({path:"/pages/statcenter/index"})}},[i("div",{staticClass:"icon icon-rank c-info"}),t._v(" "),i("div",{staticClass:"font-14 padding-5-t"},[t._v("商户统计")])])],1),t._v(" "),i("van-col",{attrs:{span:"6"}},[i("router-link",{attrs:{tag:"div",to:t.util.getUrl({path:"/pages/statcenter/index"})}},[i("div",{staticClass:"icon icon-print c-warning"}),t._v(" "),i("div",{staticClass:"font-14 padding-5-t"},[t._v("打印机")])])],1)],1)],1),t._v(" "),t.news.length>0?i("div",{staticClass:"article-list"},[i("div",{staticClass:"article-head flex-lr van-hairline--bottom"},[i("div",{staticClass:"title"},[t._v("商家学园")]),t._v(" "),i("router-link",{staticClass:"c-gray font-14",attrs:{to:t.util.getUrl({path:"/pages/news/news/index"})}},[t._v("更多"),i("span",{staticClass:"icon icon-right padding-5-l"})])],1),t._v(" "),t._l(t.news,function(a,s){return i("div",{key:a.id,staticClass:"article-item"},[i("router-link",{attrs:{to:t.util.getUrl({path:"/pages/news/news/detail",query:{id:a.id}})}},[i("div",{staticClass:"article-wrap flex-lr"},[i("div",{staticClass:"article-left"},[i("div",{staticClass:"text"},[t._v(t._s(a.title))]),t._v(" "),i("div",{staticClass:"read"},[t._v("阅读 "),i("span",[t._v(t._s(a.click))])])]),t._v(" "),i("div",{staticClass:"article-img"},[i("img",{attrs:{src:a.thumb,alt:""}})])])])],1)})],2):t._e()]),t._v(" "),t.showPreLoading?i("iloading"):t._e()],1)},staticRenderFns:[]};var v=i("VU/8")(l,o,!1,function(t){i("CleF")},null,null);a.default=v.exports}});
//# sourceMappingURL=69.e9a1a69f0c8b26fa290c.js.map