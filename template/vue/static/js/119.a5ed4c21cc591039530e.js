webpackJsonp([119],{"6CQs":function(e,a){},EsyX:function(e,a,t){"use strict";Object.defineProperty(a,"__esModule",{value:!0});var d=t("Gu7T"),r=t.n(d),s={components:{PublicHeader:t("Cz8s").a},data:function(){return{preLoading:!0,redpackorder:{page:1,psize:15,loading:!1,loaded:!1,empty:!1,data:[]}}},methods:{onLoad:function(){var e=this;this.util.request({url:"mealRedpacket/plus/mealorder",data:{page:this.redpackorder.page,psize:this.redpackorder.psize}}).then(function(a){e.preLoading=!1;var t=a.data.message;t.errno?e.$toast(t.message):(e.redpackorder.data=[].concat(r()(e.redpackorder.data),r()(t.message)),e.redpackorder.loading=!1,0==e.redpackorder.data.length&&(e.redpackorder.empty=!0),t.message.length<e.redpackorder.psize&&(e.redpackorder.loaded=!0),e.redpackorder.page++)})}},mounted:function(){this.onLoad()}},i={render:function(){var e=this,a=e.$createElement,t=e._self._c||a;return t("div",{attrs:{id:"mealRedpacket-order"}},[t("public-header",{attrs:{title:"套餐红包购买记录"}}),e._v(" "),t("div",{staticClass:"content"},[e.redpackorder.empty?t("div",{staticClass:"common-no-con"},[t("img",{attrs:{src:"static/img/order_no_con.png",alt:""}}),e._v(" "),t("p",[e._v("您还没有购买记录，赶紧购买吧！")]),e._v(" "),t("div",{staticClass:"btn"},[t("router-link",{attrs:{to:e.util.getUrl({path:"/pages/mealRedpacket/plus"})}},[e._v("现在购买")])],1)]):t("van-list",{attrs:{finished:e.redpackorder.loaded,offset:100,"immediate-check":!1},on:{load:e.onLoad},model:{value:e.redpackorder.loading,callback:function(a){e.$set(e.redpackorder,"loading",a)},expression:"redpackorder.loading"}},[e._l(e.redpackorder.data,function(a){return t("div",{key:a.id,staticClass:"order-item van-hairline--bottom"},[t("div",{staticClass:"order-info"},[t("div",{staticClass:"name-time"},[t("div",{staticClass:"name"},[e._v(e._s(a.data.meal.name))]),e._v(" "),t("div",{staticClass:"time"},[e._v(e._s(a.addtime))])]),e._v(" "),t("div",{staticClass:"price"},[e._v(e._s(e.Lang.dollarSign)+e._s(a.final_fee))])])])}),e._v(" "),e.redpackorder.loaded?t("div",{staticClass:"loaded"},[t("div",{staticClass:"loaded-tips"},[e._v("我是有底线的")])]):e._e()],2)],1),e._v(" "),t("iloading",{directives:[{name:"show",rawName:"v-show",value:e.preLoading,expression:"preLoading"}]})],1)},staticRenderFns:[]};var o=t("VU/8")(s,i,!1,function(e){t("6CQs")},null,null);a.default=o.exports}});
//# sourceMappingURL=119.a5ed4c21cc591039530e.js.map