webpackJsonp([28],{Vfz2:function(e,t){},oGr5:function(e,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var n=a("Cz8s"),i=a("deIj"),o=a("Fd2+"),r={components:{publicHeader:n.a},data:function(){return{showPreLoading:!0,type:"recommendHome",advertise:{recommendHome:{},recommendOther:{}},amount:0,day:{recommendHome:0,recommendOther:0},pay_type:"credit"}},methods:{onLoad:function(){var e=this;Object(i.a)({vue:e,url:"manage/advertise/index/recommend",data:{type:e.type},success:function(t){if(e.advertise=t.advertise,e.amount=t.amount,e.advertise.recommendHome.leave>0)for(var a in e.advertise.recommendHome.prices){e.day.recommendHome=a;break}else if(e.advertise.recommendOther.leave>0)for(var a in e.type="recommendOther",e.advertise.recommendOther.prices){e.data.day.recommendOther=a;break}}})},onSelectPosition:function(e,t){"day"==t?this.advertise[this.type].leave>0&&(this.day[this.type]=e):this.type=t},onSubmit:function(){var e=this;return e.type?0==e.day[e.type]?(e.util.$toast("请选择购买天数","",1e3),!1):void o.a.confirm({title:"提示",message:"确定购买该推广活动吗?"}).then(function(){Object(i.c)({vue:e,url:"manage/advertise/index/recommend",data:{type:e.type,day:e.day[e.type],pay_type:e.pay_type},success:function(t){e.util.pay({pay_type:e.pay_type,order_type:"advertise",order_id:t.id,sid:t.sid,vue:e})}})}).catch(function(){}):(e.util.$toast("请选择购买位置","",1e3),!1)}},mounted:function(){this.onLoad()}},s={render:function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{attrs:{id:"advertise-stick"}},[a("public-header",{attrs:{title:"商家推广"}}),e._v(" "),a("div",{staticClass:"content"},[a("div",{staticClass:"content-inner"},[a("div",{staticClass:"padding-15-lr padding-10-tb c-gray font-14"},[e._v("选择广告位置")]),e._v(" "),a("van-radio-group",{model:{value:e.type,callback:function(t){e.type=t},expression:"type"}},[a("van-cell-group",[a("van-cell",{attrs:{clickable:""},on:{click:function(t){e.onSelectPosition(-1,"recommendHome")}}},[a("div",{attrs:{slot:"title"},slot:"title"},[e._v("为您优选首页 ")]),e._v(" "),a("van-radio",{attrs:{name:"recommendHome"}})],1),e._v(" "),a("van-cell",{attrs:{clickable:""},on:{click:function(t){e.onSelectPosition(-1,"recommendOther")}}},[a("div",{attrs:{slot:"title"},slot:"title"},[e._v("为您优选更多页 ")]),e._v(" "),a("van-radio",{attrs:{name:"recommendOther"}})],1)],1)],1),e._v(" "),a("van-radio-group",{staticClass:"margin-10-t",model:{value:e.day[e.type],callback:function(t){e.$set(e.day,e.type,t)},expression:"day[type]"}},[a("van-cell-group",[a("van-cell",[a("div",{attrs:{slot:"title"},slot:"title"},[e._v("\n\t\t\t\t\t\t\t广告位总数"+e._s(e.advertise[e.type].total)+"个 "+e._s(e.advertise[e.type].leave>0?"剩余"+e.advertise[e.type].leave+"个位置":"（已售罄）")+" 价目:\n\t\t\t\t\t\t")])]),e._v(" "),e._l(e.advertise[e.type].prices,function(t,n){return a("van-cell",{key:n,attrs:{title:"购买广告"+t.day+"天"+t.fee+e.Lang.dollarSignCn,clickable:""},on:{click:function(t){e.onSelectPosition(n,"day")}}},[e.advertise[e.type].leave>0?a("van-radio",{attrs:{name:n}}):e._e()],1)})],2)],1),e._v(" "),a("div",{staticClass:"padding-15-lr padding-10-tb c-gray font-14"},[e._v("选择支付方式")]),e._v(" "),a("van-radio-group",{model:{value:e.pay_type,callback:function(t){e.pay_type=t},expression:"pay_type"}},[a("van-cell-group",[a("van-cell",{staticClass:"no-fix",attrs:{clickable:""},on:{click:function(t){e.pay_type="credit"}}},[a("div",{attrs:{slot:"title"},slot:"title"},[a("span",{staticClass:"margin-5-r"},[e._v("余额支付")]),e._v(" "),a("span",[e._v("账户余额: "+e._s(e.Lang.dollarSign)+e._s(e.amount))])]),e._v(" "),a("van-radio",{attrs:{name:"credit"}})],1)],1)],1)],1),e._v(" "),a("div",{staticClass:"fix-bottom van-hairline--top"},[e.advertise[e.type].leave?a("van-button",{staticClass:"bg-info",attrs:{size:"normal",block:""},on:{click:function(t){e.onSubmit()}}},[e._v("立即购买")]):a("van-button",{staticClass:"van-button--disabled",attrs:{size:"normal",block:""}},[e._v("此广告位已售罄，去看看其他广告位")])],1)]),e._v(" "),e.showPreLoading?a("iloading"):e._e()],1)},staticRenderFns:[]};var c=a("VU/8")(r,s,!1,function(e){a("Vfz2")},null,null);t.default=c.exports}});
//# sourceMappingURL=28.56eb372e30bd6e8df354.js.map