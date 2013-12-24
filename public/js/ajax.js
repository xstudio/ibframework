// JavaScript Document
function ajax(options){
	options={
		type:options.type||"POST",	//http请求类型
		url:options.url||"",		//请求的url
		timeout:options.timeout||5000,
		isAsyn:options.isAsyn||false,	//发送请求类型是否是异步的
		onComplete:options.onComplete||function(){},	//程序执行调用的函数
		onError:options.onError||function(){},		//请求失败
		onSuccess:options.onSuccess||function(){},	//请求成功
		data:options.data||""					//POST提交时用户提交的数据
	};
	//alert(options.url);
	if(typeof(XMLHttpRequest)=='undefined'){
		XMLHttpRequest=function(){
			//IE浏览器用ActiveXObject创建
			//IE5用XMLHTTP创建
			return new ActiveXObject(navigator.userAgent.indexOf("MSIE 5")>=0?'Microsoft.XMLHTTP':'Msxml2.XMLHTTP');
		};
	}
	var xml=new XMLHttpRequest();
	xml.open(options.type,options.url,options.isAsyn);	//初始化请求
	var requestDone=false;	//记录请求是否完成
	setTimeout(function(){
		requestDone=true;	
	},options.timeout);
	xml.onreadystatechange=function(){	//监听文档状态的更新
		if(xml.readyState==4&&!requestDone){	//未超时情况，直到数据完全加载
			if(httpSuccess(xml)){	//检查请求是否成功
				options.onSuccess(httpData(xml,options.type));	//处理返回的数据
			}else{
				options.onError();	
			}
			options.onComplete();
			xml=null;	//清理
		}
	};
	switch(options.type){		//POST提交时需要设置RequestHeader
		case 'GET':	
		case 'get':
					xml.send();
					break;
		default:
				xml.setRequestHeader('content-type','application/x-www-form-urlencoded');
				xml.send(options.data);
	}
	
	function httpSuccess(r){	//检查请求是否成功
		try{
			return !r.status&&location.protocol=="file:"||	//
			(r.status>=200&&r.status<300)||		//200-300之间的状态码认为请求成功
			r.status==304||		//文档为修改也算请求成功
			navigator.userAgent.indexOf("Safari")>=0&&typeof r.status=="undefined";	//Safari处理返回
		}catch(e){}
		return false;
	}
	//解析返回的数据
	function httpData(r,type){
		var ct=r.getResponseHeader("content-type");		//获取content-type的首部
		var data=!type&&ct&&ct.indexOf("xml")>=0;		//若没有提供默认类型 判断是否是XML
		data=(type=="xml"||data)?r.responseXML:r.responseText;	//获取xml文本对象或者文本内容
		if(type=="script"){		//指定类型是javascript
			eval.call(window,data);
		}
		return data;	//返回数据
	};
}

