webpackJsonp([12],{"C6/7":function(t,e,i){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var a=i("Cz8s"),s=i("75NE"),r=i("5Kdi"),n=i("jUC1"),l={name:"pay",components:{PublicHeader:a.a,countDown:s.a},data:function(){return{showPreLoading:!0,order_type:"takeout",slides:[],order:{},payment:[],paytype:"",member:{},submitDisabled:!1}},methods:{onLoad:function(){var t=this,e=this;this.util.request({url:"system/paycenter/pay",method:"POST",data:{sid:this.sid,id:this.order_id,order_type:this.order_type,type:1}}).then(function(i){t.showPreLoading=!1;var a=i.data.message;if(a.errno)return t.$toast(a.message),!1;if(e.util.isMajia())Object(n.a)().then(function(){var t=window.majia.payinfo;mag.pay(t,function(i){e.util.$toast("订单支付成功",t.url_detail)},function(i){e.util.$toast("支付失败,具体原因："+i,t.url_pay)})});else if(e.util.isQianfan()){var s=window.qianfan.payinfo,r={type:parseInt(s.type),item:s.item,send_type:parseInt(s.send_type),address:s.address,allow_pay_type:parseInt(s.allow_pay_type)};QFH5.createOrder(r.type,r.item,r.send_type,r.address,r.allow_pay_type,function(t,i){if(1==t){var a=i.order_id;e.util.request({url:"system/paycenter/sync/qianfan",data:{tid:s.tid,order_id:a}}).then(function(t){QFH5.jumpPayOrder(a,function(t,i){1==t?e.util.$toast("订单支付成功",s.url_detail):e.util.$toast(i.error,s.url_pay)})})}else e.util.$toast("创建交易订单失败:"+i.error,s.url_pay)})}else{if(t.slides=a.message.slides,t.payment=a.message.payment,1!=t.util.getStorage("itime")&&1!=t.util.getStorage("jskey")||(a.message.order.final_fee=100,a.message.order.final_fee=200),t.order=a.message.order,t.member=a.message.member,t.payment.length>0){for(var l=t.payment[0].value,o=0,d=t.payment.length;o<d;o++)"wechat"==t.payment[o].value&&(l="wechat");t.paytype=l}var c=t.util.getUrlParam(window.location.href,"autoPay");c&&(t.paytype=c,t.onSubmit())}})},onSelectPayType:function(t){this.paytype=t,"credit"==this.paytype?(this.member.credit2||(this.member.credit2=0),this.member.credit2-this.order.fee<0&&(this.submitDisabled=!0)):this.submitDisabled=!1},onSubmit:function(){return this.paytype?!this.util.ish5app()||"wechat"!=this.paytype&&"alipay"!=this.paytype?(this.submitDisabled=!0,void this.util.pay({pay_type:this.paytype,order_type:this.order_type,order_id:this.order_id,extra:this.extra,vue:this})):(r.a.init({}),r.a.pay(this.paytype,"",""),!1):(this.$toast("请先选择支付方式"),!1)}},mounted:function(){this.onLoad()},created:function(){this.query=this.$route.query,this.query&&(this.order_id=this.query.order_id,this.order_type=this.query.order_type,this.extra=this.query.extra)}},o={render:function(){var t=this,e=t.$createElement,i=t._self._c||e;return t.util.isMajia()||t.util.isQianfan()?t._e():i("div",{attrs:{id:"pay"}},[i("public-header",{attrs:{title:"支付收银台",bgcolor:"#ff2d4b",textcolor:"#fff"}}),t._v(" "),i("div",{staticClass:"content"},[t.slides&&t.slides.length>0?i("van-swipe",{staticClass:"swiper",attrs:{autoplay:3e3,"indicator-color":"#ff2d4b"}},t._l(t.slides,function(t,e){return i("van-swipe-item",{key:e},[i("img",{attrs:{src:t.thumb,alt:""}})])}),1):t._e(),t._v(" "),t.order.pay_endtime&&t.order.pay_endtime>0?i("div",{staticClass:"remain-time"},[i("div",[t._v("支付剩余时间")]),t._v(" "),i("div",{staticClass:"in-clock"},[i("count-down",{attrs:{endTime:t.order.pay_endtime}})],1)]):t._e(),t._v(" "),i("div",{staticClass:"order-summary flex-lr"},[i("div",{staticClass:"left"},[i("img",{attrs:{src:t.order.logo,alt:""}})]),t._v(" "),i("div",{staticClass:"right"},[i("div",{staticClass:"fee"},[i("span",{staticClass:"underline"},[t._v(t._s(t.Lang.dollarSign)+t._s(t.order.fee))])]),t._v(" "),i("div",{staticClass:"order-info"},[i("span",{staticClass:"underline"},[t._v(t._s(t.order.title))])])])]),t._v(" "),i("div",{staticClass:"bolck-title"},[t._v("选择支付方式")]),t._v(" "),i("div",{staticClass:"pay-list"},[t._l(t.payment,function(e,a){return[i("div",{staticClass:"pay-item van-hairline--bottom flex-lr",on:{click:function(i){return t.onSelectPayType(e.value)}}},[i("div",{staticClass:"item-inner flex"},["wechat"==e.value?i("img",{attrs:{src:"static/img/wx-icon.png",alt:""}}):"alipay"==e.value?i("img",{attrs:{src:"static/img/zfb-icon.png",alt:""}}):"credit"==e.value?i("img",{attrs:{src:"static/img/money-icon.png",alt:""}}):"delivery"==e.value?i("img",{attrs:{src:"static/img/delivery-icon.png",alt:""}}):"peerpay"==e.value?i("img",{attrs:{src:"static/img/peerpay-icon.png",alt:""}}):"finishMeal"==e.value?i("img",{attrs:{src:"static/img/finishMeal-icon.png",alt:""}}):t._e(),t._v(" "),i("div",{staticClass:"item-box"},[i("div",{staticClass:"item-title"},[t._v(t._s(e.title))]),t._v(" "),"wechat"==e.value?i("div",{staticClass:"item-subtitle flex"},[i("span",{staticClass:"pay-recommend"},[t._v("推荐使用")]),t._v("\n\t\t\t\t\t\t\t\t微信支付,安全快捷\n\t\t\t\t\t\t\t")]):t._e(),t._v(" "),"alipay"==e.value?i("div",{staticClass:"item-subtitle flex"},[i("span",{staticClass:"pay-recommend"},[t._v("推荐使用")]),t._v("\n\t\t\t\t\t\t\t\t简单、安全、快速\n\t\t\t\t\t\t\t")]):t._e(),t._v(" "),"credit"==e.value?i("div",{staticClass:"item-subtitle flex"},[t._v("\n\t\t\t\t\t\t\t\t当前账户余额:\n\t\t\t\t\t\t\t\t"),i("span",{staticClass:"money"},[t._v(" "+t._s(t.Lang.dollarSign)+t._s(t.member.credit2||0))])]):t._e(),t._v(" "),"delivery"==e.value?i("div",{staticClass:"item-subtitle flex"},[t._v("\n\t\t\t\t\t\t\t\t线下当面交易，到店付款，货到付款\n\t\t\t\t\t\t\t")]):t._e()])]),t._v(" "),i("i",{staticClass:"icon",class:{"icon-check":e.value==t.paytype}})])]})],2)],1),t._v(" "),i("div",{staticClass:"save-btn"},[i("van-button",{attrs:{type:"default",size:"large",disabled:t.submitDisabled},on:{click:t.onSubmit}},[t._v("确认支付 "+t._s(t.Lang.dollarSign)+t._s(t.order.fee))])],1),t._v(" "),i("transition",{attrs:{name:"loading"}},[t.showPreLoading?i("iloading"):t._e()],1)],1)},staticRenderFns:[]};var d=i("VU/8")(l,o,!1,function(t){i("sU3S")},null,null);e.default=d.exports},jUC1:function(t,e,i){"use strict";e.a=function(){return new s.a(function(t,e){var i=document.createElement("script");i.type="text/javascript",i.src="//static.app1.magcloud.net/public/static/dest/js/libs/magjs-x.js",i.onerror=e,i.onload=t,document.head.appendChild(i)})};var a=i("//Fk"),s=i.n(a)},sU3S:function(t,e){}});
//# sourceMappingURL=12.1b3b3249afa54fd9d41c.js.map