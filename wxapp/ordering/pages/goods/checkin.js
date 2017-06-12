// pages/goods/checkin.js
var util = require('../../utils/util.js');
var app = new getApp();
Page({
  data:{
    goods_size:'',
    gooods_color:'',
    goods_num:'',
    goods_id:'',
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
    var that = this;
    var data = {
      goods_commonid: options.goods_commonid
    };
    util.Ajax("goods/goods_detail", data, function (res) {
      //console.log("a结果：", res);
      that.setData({
        goods_info: res.datas
      })
    });
  },
  onReady:function(){
    // 页面渲染完成
  },
  onShow:function(){
    // 页面显示
    
  },
  onHide:function(){
    // 页面隐藏
  },
  onUnload:function(){
    // 页面关闭
  }
})