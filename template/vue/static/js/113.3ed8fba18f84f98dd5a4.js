webpackJsonp([113],{"2JWE":function(e,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var i={render:function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{attrs:{id:"peerpay-payment"}},[a("div",{staticClass:"content"},[a("div",{staticClass:"head-block"},[a("div",{staticClass:"head-block-bg"},[a("div",{staticClass:"item-text"},[e._v(e._s(e.peerpay.peerpay_message))]),e._v(" "),a("img",{staticClass:"avatar",attrs:{src:e.member.avatar,alt:""}})])]),e._v(" "),a("div",{staticClass:"pay-container van-hairline--top van-hairline--top"},[a("div",{staticClass:"pay-title"},[e._v("代付金额")]),e._v(" "),a("div",{staticClass:"input-container"},[e._v("\n\t\t\t\t"+e._s(e.Lang.dollarSign)+"\n\t\t\t\t"),a("input",{directives:[{name:"model",rawName:"v-model",value:e.fee,expression:"fee"}],attrs:{type:"text",palceholder:"请输入代付金额"},domProps:{value:e.fee},on:{input:function(t){t.target.composing||(e.fee=t.target.value)}}})])]),e._v(" "),a("div",{staticClass:"note-container van-hairline--top van-hairline--top"},[a("div",{staticClass:"note-title"},[e._v("给好友留言")]),e._v(" "),a("textarea",{directives:[{name:"model",rawName:"v-model",value:e.note,expression:"note"}],attrs:{name:"note",placeholder:"请输入留言"},domProps:{value:e.note},on:{input:function(t){t.target.composing||(e.note=t.target.value)}}})]),e._v(" "),a("div",{staticClass:"wx-pay-button",on:{click:function(t){return e.onSubmit()}}},[e._v("微信支付")])]),e._v(" "),a("transition",{attrs:{name:"loading"}},[e.showPreLoading?a("iloading"):e._e()],1)],1)},staticRenderFns:[]};var s=a("VU/8")({name:"peerpayPayment",data:function(){return{showPreLoading:!0,fee:0,peerpay:{},member:{},note:""}},methods:{onLoad:function(){var e=this;this.util.request({url:"system/paycenter/peerpay/payment",data:{id:this.id}}).then(function(t){e.showPreLoading=!1;var a=t.data.message;if(a.errno)return e.util.$toast(a.message,"",1e3),!1;a=a.message,e.peerpay=a.peerpay,e.member=a.member,e.note=a.note,e.util.setWXTitle(a.page_title)})},onSubmit:function(){var e=this;return this.fee<=0?(this.util.$toast("请输入大于0的金额","",1e3),!1):this.fee>this.peerpay.peerpay_price?(this.util.$toast("代付不能超过订单的金额","",1e3),!1):this.fee>this.peerpay.peerpay_selfpay&&this.peerpay.peerpay_selfpay>0?(this.util.$toast("代付不能超过代付最大金额","",1e3),!1):void this.util.request({url:"system/paycenter/peerpay/payment",method:"POST",data:{id:this.id,note:this.note,val:this.fee}}).then(function(t){var a=t.data.message;if(a.errno)return e.util.$toast(a.message,"",1e3),!1;e.util.pay({pay_type:"wechat",order_type:"peerpay",order_id:a.message.id,vue:e})})}},created:function(){this.query=this.$route.query,this.query&&(this.id=this.query.id)},mounted:function(){this.onLoad()}},i,!1,function(e){a("rY2p")},null,null);t.default=s.exports},rY2p:function(e,t){}});
//# sourceMappingURL=113.3ed8fba18f84f98dd5a4.js.map