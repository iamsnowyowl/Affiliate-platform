webpackJsonp([45],{1490:function(e,t,a){"use strict";function n(e,t,a){return t in e?Object.defineProperty(e,t,{value:a,enumerable:!0,configurable:!0,writable:!0}):e[t]=a,e}function l(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function o(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!==typeof t&&"function"!==typeof t?e:t}function s(e,t){if("function"!==typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,enumerable:!1,writable:!0,configurable:!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}Object.defineProperty(t,"__esModule",{value:!0});var r=a(0),i=a.n(r),c=a(75),m=a(46),u=a(545),d=a(172),p=a(99),h=a(124),f=a.n(h),y=a(296),E=a(547),b=(a.n(E),a(549)),g=a.n(b),v=function(){function e(e,t){for(var a=0;a<t.length;a++){var n=t[a];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,n.key,n)}}return function(t,a,n){return a&&e(t.prototype,a),n&&e(t,n),t}}(),S=u.m.Option,C=function(e){function t(e){l(this,t);var a=o(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e));return a.handleChange=function(e){var t;a.setState((t={},n(t,e.target.name,e.target.value),n(t,"fetching",!0),t))},a.handleSubmit=function(e){e.preventDefault(),a.setState({overlay:!0});var t=a.props.match.params.assessment_id,n={},l=Object(d.a)(p.B+"/"+t+"/plenos","POST");n.pleno_id=a.state.pleno_id,n.position=a.state.position;var o={method:l.method,headers:{Authorization:l.digest,"X-Lsp-Date":l.date,"Content-Type":"multipart/form-data"},url:""+p.c+p.B+"/"+t+"/plenos",data:n};f()(o).then(function(e){201===e.status&&a.setState({assignPleno:!0})}).catch(function(e){var t=e.response;switch(a.setState({response:t.data.error.code}),a.state.response){case 400:a.setState({hidden:!1,message:y.a.alertInput,overlay:!1});break;case 409:a.setState({hidden:!1,overlay:!1,message:y.a.userAlready})}})},a.onChange=function(e){a.setState({value:e,payload:[],fetching:!1,pleno_id:e.key})},a.onSearch=function(e){if(a.setState({fetching:!0}),""!==e){var t=Object(d.a)(p.Z,"GET"),n={method:t.method,headers:{Authorization:t.digest,"X-Lsp-Date":t.date},url:""+p.c+p.Z+"?limit=100&&role_code=ACS,ADM,SUP&search="+e,data:null};f()(n).then(function(e){a.setState({payload:e.data.data,fetching:!1})})}else a.setState({payload:[],fetching:!1})},a.state={data:{pleno_id:"",position:"",pleno_date:""},assignPleno:!1,hidden:!0,message:"",overlay:!1,payloadAssessment:[],payload:[],value:[],fetching:!1},a}return s(t,e),v(t,[{key:"Get",value:function(e,t){var a=this;f()(e).then(function(e){a.setState(n({},t,e.data.data))})}},{key:"componentDidMount",value:function(){var e=this.props.match.params.assessment_id,t=Object(d.a)(p.B+"/"+e,"GET"),a=Object(d.a)(p.Z,"GET"),n={method:t.method,headers:{Authorization:t.digest,"X-Lsp-Date":t.date},url:p.c+p.B+"/"+e,data:null},l={method:a.method,headers:{Authorization:a.digest,"X-Lsp-Date":a.date},url:""+p.c+p.Z+"?limit=100&role_code=ACS,ADM,SUP",data:null};this.Get(n,"payloadAssessment"),this.Get(l,"payload")}},{key:"render",value:function(){console.log("cari",this.state.value);var e=this.props.match.params,t=e.run,a=e.assessment_id,n=this.state.payloadAssessment.title,l=this.state,o=l.fetching,s=l.payload;return l.assignPleno?i.a.createElement(c.d,{to:{pathname:p.B+"/"+a+"/assign",state:{runs:t}}}):(console.log("payload",s),i.a.createElement("div",null,i.a.createElement(g.a,{active:this.state.overlay,spinner:!0,text:"Loading"},i.a.createElement(m.Card,null,i.a.createElement(m.CardBody,null,i.a.createElement("form",{onSubmit:this.handleSubmit,name:"test-form"},i.a.createElement(m.Row,null,i.a.createElement(m.Col,{md:"3"},i.a.createElement(m.Label,{htmlFor:"assessment_id"},y.a.name+" Assessment")),i.a.createElement(m.Col,{xs:"5",md:"4"},i.a.createElement(m.Input,{type:"text",name:"assessment_id",defaultValue:n,readOnly:!0}))),i.a.createElement("p",null),i.a.createElement("p",null),i.a.createElement(m.Row,null,i.a.createElement(m.Col,{md:"3"},i.a.createElement(m.Label,null,y.a.name)),i.a.createElement(m.Col,{xs:"5",md:"4"},i.a.createElement(u.m,{showSearch:!0,labelInValue:!0,placeholder:y.a.select+" Staff",notFoundContent:o?i.a.createElement(u.o,{size:"small"}):null,filterOption:!1,onSearch:this.onSearch,onChange:this.onChange,style:{width:"100%"}},s.map(function(e){return i.a.createElement(S,{key:e.user_id},e.first_name)})))),i.a.createElement("p",null),i.a.createElement(m.Row,{style:{marginBottom:"15px"}},i.a.createElement(m.Col,{md:"3"},i.a.createElement(m.Label,null,y.a.positionPleno)),i.a.createElement(m.Col,{xs:"5",md:"4"},i.a.createElement(m.Input,{style:{borderColor:"black"},type:"select",name:"position",onChange:this.handleChange},i.a.createElement("option",{value:""},y.a.select),i.a.createElement("option",{value:"ketua"},"Ketua Pleno"),i.a.createElement("option",{value:"anggota"},"Anggota Pleno")))),i.a.createElement(m.Row,null,i.a.createElement(m.Col,null,i.a.createElement(m.Alert,{color:"danger",hidden:this.state.hidden,className:"text-center"},this.state.message))),i.a.createElement(m.Row,null,i.a.createElement(m.Col,{md:"1.5"},i.a.createElement(c.b,{to:{pathname:p.B+"/"+a+"/assign",state:{runs:t}}},i.a.createElement(m.Button,{className:"btn-danger",type:"submit",size:"md"},i.a.createElement("i",{className:"fa fa-chevron-left"})," ",y.a.back))),i.a.createElement(m.Col,{md:"1.5",className:"Btn-Submit"},i.a.createElement(m.Button,{color:"success",type:"submit",value:"Submit",size:"md",onClick:this.handleSubmit},i.a.createElement("i",{className:"fa fa-check"})," ",y.a.submit)))))))))}}]),t}(r.Component);t.default=C}});
//# sourceMappingURL=45.b3518f2a.chunk.js.map