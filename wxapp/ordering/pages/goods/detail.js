// pages/goods/detail.js
var util = require('../../utils/util.js');
var app = new getApp();
Page({
  data:{
    tabIndex: 0 //0:商品属性 1：商品买点 2：相关推荐
  },
  switchTab: function(e){
    let idx = e.currentTarget.dataset.idx;

    this.setData({
      tabIndex: idx
    })
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
    var that = this;
    var data = {
      token: app.globalData.token,
      goods_commonid: options.goods_commonid,
    };
    util.Ajax("ordering_goods/goods_detail", data, function (res) {
      //console.log("a结果：", res);
      that.setData({
        goods_info: res.datas,
        store_info: res.datas.store_info
      });
      console.log("a结果：", res.datas);
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