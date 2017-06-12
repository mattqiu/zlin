// pages/goods/checkin.js
var util = require('../../utils/util.js');
var goodsAdd = require('../../config').goodsAdd;
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
      that.setData({
        goods_commonid: res.datas.goods_commonid,
        goods_price: res.goods_price,
        
      })
      console.log('提交返回数据2', res.datas.goods_name)      
    });
    /*var goods_commonid
    this.setData({
      goods_commonid: options.goods_commonid,
      goods_price:options.goods_price,
      goods_name:options.goods_name
    })
    console.log('获取到goods_commonid', options.goods_commonid)*/
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