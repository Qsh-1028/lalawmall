webpackJsonp([16],{"+qUl":function(t,a,s){"use strict";Object.defineProperty(a,"__esModule",{value:!0});var e={data:function(){return{}},components:{publicHeader:s("Cz8s").a},methods:{}},i={render:function(){var t=this,a=t.$createElement,s=t._self._c||a;return s("div",{attrs:{id:"agent-index"}},[s("public-header",{attrs:{title:"区域代理管理"}}),t._v(" "),s("div",{staticClass:"content"},[s("div",{staticClass:"group"},[s("van-row",{attrs:{gutter:"20"}},[t.util.checkperm("agent.agent")?s("van-col",{attrs:{span:"8"}},[s("router-link",{staticClass:"merchant-item",attrs:{tag:"div",to:t.util.getUrl({path:"/pages/plugin/agent/agent"})}},[s("div",{staticClass:"top"},[s("i",{staticClass:"icon icon-order c-primary"})]),t._v(" "),s("div",{staticClass:"bottom"},[s("span",[t._v("代理列表")])])])],1):t._e(),t._v(" "),t.util.checkperm("agent.getcash")?s("van-col",{attrs:{span:"8"}},[s("router-link",{staticClass:"merchant-item",attrs:{tag:"div",to:t.util.getUrl({path:"/pages/plugin/agent/getcash"})}},[s("div",{staticClass:"top"},[s("i",{staticClass:"icon icon-money c-danger"})]),t._v(" "),s("div",{staticClass:"bottom"},[s("span",[t._v("提现申请")])])])],1):t._e(),t._v(" "),t.util.checkperm("agent.current")?s("van-col",{attrs:{span:"8"}},[s("router-link",{staticClass:"merchant-item",attrs:{tag:"div",to:t.util.getUrl({path:"/pages/plugin/agent/current"})}},[s("div",{staticClass:"top"},[s("i",{staticClass:"icon icon-sponsor"})]),t._v(" "),s("div",{staticClass:"bottom"},[s("span",[t._v("账户明细")])])])],1):t._e()],1)],1)])],1)},staticRenderFns:[]};var n=s("VU/8")(e,i,!1,function(t){s("iU5V")},null,null);a.default=n.exports},iU5V:function(t,a){}});