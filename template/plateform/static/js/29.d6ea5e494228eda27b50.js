webpackJsonp([29],{Ez5F:function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var i=a("woOf"),s=a.n(i),r=a("Cz8s"),n=a("mzkE"),l=a("deIj"),o={components:{publicHeader:r.a,publicFooter:n.a},data:function(){return{plateformer:{username:"",role_type:""},showPreLoading:!0,menufooter:{}}},methods:{onLoad:function(){var t=this;Object(l.a)({vue:t,url:"plateform/member/mine",data:{menufooter:1},success:function(e){t.plateformer=s()(t.plateformer,e.plateformer),e.user&&t.util.setStorage("ipuser",e.user)}})}},mounted:function(){this.onLoad(),this.util.imap(),this.util.icloudapi()}},c={render:function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{attrs:{id:"member-mine"}},[a("public-header",{attrs:{title:"我的"}}),t._v(" "),a("public-footer",{attrs:{menufooter:t.menufooter,showPreLoading:t.showPreLoading}}),t._v(" "),a("div",{staticClass:"content"},[a("router-link",{staticClass:"padding-15-lr padding-10-tb margin-10-t bg-default flex-lr",attrs:{tag:"div",to:t.util.getUrl({path:"pages/member/profile"})}},[a("div",{staticClass:"userinfo flex"},[a("img",{staticClass:"avatar margin-15-r",attrs:{src:"static/img/zfb-icon.png",alt:""}}),t._v(" "),a("div",[a("div",[t._v(t._s(t.plateformer.username))]),t._v(" "),a("div",{staticClass:"c-gray font-14 margin-10-t"},[t._v(t._s(t.plateformer.role_type))])])]),t._v(" "),a("van-icon",{attrs:{name:"arrow"}})],1),t._v(" "),"agenter"==t.util.getUser().role?[a("van-cell-group",{staticClass:"margin-10-t"},[a("van-cell",{attrs:{"is-link":"",to:t.util.getUrl({path:"pages/plugin/agent/config/index"})}},[a("div",{staticClass:"flex",attrs:{slot:"title"},slot:"title"},[a("i",{staticClass:"icon icon-settings margin-5-r font-18 c-primary"}),t._v("代理设置\n\t\t\t\t\t")])])],1),t._v(" "),a("van-cell-group",{staticClass:"margin-10-t"},[a("van-cell",{attrs:{"is-link":"",to:t.util.getUrl({path:"pages/plugin/agent/finance/getcash"})}},[a("div",{staticClass:"flex",attrs:{slot:"title"},slot:"title"},[a("i",{staticClass:"icon icon-settings margin-5-r font-18 c-primary"}),t._v("申请提现\n\t\t\t\t\t")])])],1)]:[a("van-cell-group",{staticClass:"margin-10-t"},[a("van-cell",{attrs:{"is-link":"",to:t.util.getUrl({path:"pages/config/index"})}},[a("div",{staticClass:"flex",attrs:{slot:"title"},slot:"title"},[a("i",{staticClass:"icon icon-settings margin-5-r font-18 c-primary"}),t._v("平台设置\n\t\t\t\t\t")])])],1)],t._v(" "),a("van-cell-group",{staticClass:"margin-10-t hide"},[a("van-cell",{attrs:{"is-link":"",to:t.util.getUrl({path:"pages/member/phonic"})}},[a("div",{staticClass:"flex",attrs:{slot:"title"},slot:"title"},[a("i",{staticClass:"icon icon-settings margin-5-r font-18 c-primary"}),t._v("语音设置\n\t\t\t\t")])])],1)],2)],1)},staticRenderFns:[]};var u=a("VU/8")(o,c,!1,function(t){a("WzZb")},null,null);e.default=u.exports},WzZb:function(t,e){}});