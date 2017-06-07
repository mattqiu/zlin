if (getQueryString("key") != "") {
    var key = getQueryString("key")
} else {
    var key = getCookie("key")
}
var nodeSiteUrl = "";
var memberInfo = {};
var resourceSiteUrl = "";
var smilies_array = new Array;
smilies_array[1] = [["1", ":smile:", "smile.gif", "28", "28", "28", "微笑"], ["2", ":sad:", "sad.gif", "28", "28", "28", "难过"], ["3", ":biggrin:", "biggrin.gif", "28", "28", "28", "呲牙"], ["4", ":cry:", "cry.gif", "28", "28", "28", "大哭"], ["5", ":huffy:", "huffy.gif", "28", "28", "28", "发怒"], ["6", ":shocked:", "shocked.gif", "28", "28", "28", "惊讶"], ["7", ":tongue:", "tongue.gif", "28", "28", "28", "调皮"], ["8", ":shy:", "shy.gif", "28", "28", "28", "害羞"], ["9", ":titter:", "titter.gif", "28", "28", "28", "偷笑"], ["10", ":sweat:", "sweat.gif", "28", "28", "28", "流汗"], ["11", ":mad:", "mad.gif", "28", "28", "28", "抓狂"], ["12", ":lol:", "lol.gif", "28", "28", "28", "阴险"], ["13", ":loveliness:", "loveliness.gif", "28", "28", "28", "可爱"], ["14", ":funk:", "funk.gif", "28", "28", "28", "惊恐"], ["15", ":curse:", "curse.gif", "28", "28", "28", "咒骂"], ["16", ":dizzy:", "dizzy.gif", "28", "28", "28", "晕"], ["17", ":shutup:", "shutup.gif", "28", "28", "28", "闭嘴"], ["18", ":sleepy:", "sleepy.gif", "28", "28", "28", "睡"], ["19", ":hug:", "hug.gif", "28", "28", "28", "拥抱"], ["20", ":victory:", "victory.gif", "28", "28", "28", "胜利"], ["21", ":sun:", "sun.gif", "28", "28", "28", "太阳"], ["22", ":moon:", "moon.gif", "28", "28", "28", "月亮"], ["23", ":kiss:", "kiss.gif", "28", "28", "28", "示爱"], ["24", ":handshake:", "handshake.gif", "28", "28", "28", "握手"]];
var t_id = getQueryString("t_id");
var chat_goods_id = getQueryString("goods_id");
$(function() {
    $.getJSON(ApiUrl + "/index.php?act=member_chat&op=get_node_info", {key: key, u_id: t_id, chat_goods_id: chat_goods_id}, function(result) {
        if (!checkLogin(result.login)){return false;}
		
        imchat(result.datas);
        if (!$.isEmptyObject(result.datas.chat_goods)) {
            var goods_info=result.datas.chat_goods;
            var goods_html = '<div class="qm-chat-product"> <a href="' + WapSiteUrl + "/tmpl/product_detail.html?goods_id=" + goods_info.goods_id + '" target="_blank"><div class="goods-pic"><img src="' + goods_info.pic + '" alt=""/></div><div class="goods-info"><div class="goods-name">' + goods_info.goods_name + '</div><div class="goods-price">￥' + goods_info.goods_promotion_price + "</div></div></a> </div>";
            $("#chat_msg_html").append(goods_html)
        }
    });
    var imchat=function(c_datas){	
        nodeSiteUrl = c_datas.node_site_url;
        memberInfo = c_datas.member_info;
        userInfo = c_datas.user_info;
        $("h1").html(userInfo.store_name != "" ? userInfo.store_name: userInfo.member_name);
        resourceSiteUrl = c_datas.resource_site_url;
		
        if (!c_datas.node_chat) {
            $.sDialog({
                skin: "red",
                content: "在线聊天系统暂时未启用",
                okBtn: false,
                cancelBtn: false
            });
            return false
        }
        var script = document.createElement("script");
        script.type = "text/javascript";
        script.src = nodeSiteUrl + "/resource/socket.io.js";
        document.body.appendChild(script);
		
        connect();		
		function connect(){
            setTimeout(function() {
                if (typeof io === "object") {
                    startchat();
                } else {
                    connect();
                }
            },
            500)
        }
        function startchat(){
            var connect_url = nodeSiteUrl;
            var connect = 0;
            var u_info = {};
            u_info["u_id"] = memberInfo.member_id;
            u_info["u_name"] = memberInfo.member_name;
            u_info["avatar"] = memberInfo.member_avatar;
            u_info["s_id"] = memberInfo.store_id;
            u_info["s_name"] = memberInfo.store_name;
            u_info["s_avatar"] = memberInfo.store_avatar;
			
			socket = io.connect(connect_url, { 'resource': 'resource', 'reconnect': false });
            socket.on("connect", function() {
                connect = 1;
                socket.emit("update_user", u_info);
                socket.on("get_msg", function(msg_list) {
                    get_msg(msg_list)
                });
                socket.on("disconnect", function() {
                    connect=0
                })
            });
			
            function send_msg(msg){//发消息	
                if (connect === 1) {
					
                    $.ajax({
                        type: "post",
                        url: ApiUrl + "/index.php?act=member_chat&op=send_msg",
                        data: msg,
                        dataType: "json",
                        success: function(t_msg) {
							
                            if (t_msg.code == 200) {
                                var s_msg=t_msg.datas.msg;
                                socket.emit("send_msg", s_msg);
                                s_msg.avatar = memberInfo.member_avatar;
                                s_msg.class = "msg-me";
                                show_msg(s_msg)
                            } else {
                                $.sDialog({
                                    skin: "red",
                                    content: t_msg.error,
                                    okBtn: false,
                                    cancelBtn: false
                                });
                                return false
                            }
                        }
                    })
                }
            }
            function del_msg(max_id,f_id){//已读消息处理 max_id:最大的消息编号 f_id:消息发送人
                if (connect === 1) {
                    socket.emit("del_msg",{max_id:max_id,f_id:f_id})
                }
            }
            function get_msg(list){//接收消息
                var max_id;
                for(var k in list){
                    var msg=list[k];
					if(list[k].f_id!=t_id){continue}
					max_id=k;
					msg.avatar=!$.isEmptyObject(userInfo.store_id)?userInfo.store_avatar:userInfo.member_avatar;
					msg.class="msg-other";
					show_msg(msg)
                }
                if (typeof max_id != "undefined") {
                    del_msg(max_id,t_id)
                }
            }
            $("#submit").click(function() {
                var msg=$("#msg").val();
                $("#msg").val("");
				
                if(msg==""){
                    $.sDialog({
                        skin: "red",
                        content: "请填写内容",
                        okBtn: false,
                        cancelBtn: false
                    });
                    return false
                }
                send_msg({key:key,t_id:t_id,t_name:userInfo.member_name,t_msg:msg,chat_goods_id:chat_goods_id});
                $("#chat_smile").addClass("hide");
                $(".qm-chat-con").css("bottom", "2rem")
            })
        }
        for (var i in smilies_array[1]) {
            var smilie = smilies_array[1][i];
            var html = '<img title="' + smilie[6] + '" alt="' + smilie[6] + '" data-sign="' + smilie[1] + '" src="' + resourceSiteUrl + "/js/smilies/images/" + smilie[2] + '">';
            $("#chat_smile > ul").append("<li>" + html + "</li>")
        }
        $("#open_smile").click(function() {
            if ($("#chat_smile").hasClass("hide")) {
                $("#chat_smile").removeClass("hide");
                $(".qm-chat-con").css("bottom", "7rem")
            } else {
                $("#chat_smile").addClass("hide");
                $(".qm-chat-con").css("bottom", "2rem")
            }
        });
        $("#chat_smile").on("click", "img", function() {
            var e = $(this).attr("data-sign");
            var t = $("#msg")[0];
            var a = t.selectionStart;
            var s = t.selectionEnd;
            var i = t.scrollTop;
            t.value = t.value.substring(0, a) + e + t.value.substring(s, t.value.length);
            t.setSelectionRange(a + e.length, s + e.length)
        });
        $("#chat_msg_log").click(function() {
            $.ajax({
                type: "post",
                url: ApiUrl + "/index.php?act=member_chat&op=get_chat_log&page=50",
                data: {
                    key: key,
                    t_id: t_id,
                    t: 30
                },
                dataType: "json",
                success: function(result) {
                    if (result.code == 200) {
                        if (result.datas.list.length == 0) {
                            $.sDialog({
                                skin: "block",
                                content: "暂无聊天记录",
                                okBtn: false,
                                cancelBtn: false
                            });
                            return false
                        }
                        result.datas.list.reverse();
                        $("#chat_msg_html").html("");
                        for (var t = 0; t < result.datas.list.length; t++) {
                            var a = result.datas.list[t];
                            if (a.f_id != t_id) {
                                var s = {};
                                s.class = "msg-me";
                                s.avatar = memberInfo.member_avatar;
                                s.t_msg = a.t_msg;
                                show_msg(s)
                            } else {
                                var s = {};
                                s.class = "msg-other";
                                s.avatar = userInfo.store_avatar == "" ? userInfo.member_avatar: userInfo.store_avatar;
                                s.t_msg = a.t_msg;
                                show_msg(s)
                            }
                        }
                    } else {
                        $.sDialog({
                            skin: "red",
                            content: result.error,
                            okBtn: false,
                            cancelBtn: false
                        });
                        return false
                    }
                }
            })
        });
		
        function show_msg(msg){
            msg.t_msg=replace_smilie(msg.t_msg);
            var html = '<dl class="' + msg.class + '"><dt><img src="' + msg.avatar + '" alt=""/><i></i></dt><dd>' + msg.t_msg + "</dd></dl>";
            $("#chat_msg_html").append(html);
            if (!$.isEmptyObject(html.goods_info)) {
                var good_info=msg.goods_info;
                var g_html = '<div class="qm-chat-product"> <a href="' + WapSiteUrl + "/tmpl/product_detail.html?goods_id=" + good_info.goods_id + '" target="_blank"><div class="goods-pic"><img src="' + good_info.pic + '" alt=""/></div><div class="goods-info"><div class="goods-name">' + good_info.goods_name + '</div><div class="goods-price">￥' + good_info.goods_promotion_price + "</div></div></a> </div>";
                $("#chat_msg_html").append(g_html)
            }
            $("#anchor-bottom")[0].scrollIntoView()
        }
        function replace_smilie(msg){
            if (typeof smilies_array !== "undefined") {
                re_str=""+msg;
                for (var t in smilies_array[1]) {
                    var smilie = smilies_array[1][t];
                    var s = new RegExp("" + smilie[1], "g");
                    var i = '<img title="' + smilie[6] + '" alt="' + smilie[6] + '" src="' + resourceSiteUrl + "/js/smilies/images/" + smilie[2] + '">';
                    msg=msg.replace(s,i)
                }
            }
            return msg
        }
    }
});