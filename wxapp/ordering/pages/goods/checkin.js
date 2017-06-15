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
      goods_commonid: options.goods_commonid,
      goods_name: options.goods_name,
      goods_price: options.goods_price
    };
    util.Ajax("goods/goods_detail", data, function (res) {
      //console.log("a结果：", res);
      that.setData({
        goods_info: res.datas
      })
    });
  },
  btn_submit:function(e){
    var that = this;
    var data ={
      goods_info:[
        {
          goods_id: '55',
          quantity: '15',
        },
        {
          goods_id: '56',
          quantity: '15',
        }
      ],
      goods_commonid: '100008',
      goods_name: 'goods_name',
      goods_price: '600',
      /*member_id: app.globalData.member_id,
      store_id: app.globalData.store_id*/
      buyer_id: '0001',
      store_id: '1'
    };
    util.Ajax("member_cart/cart_add",data,function(options){
      console.log("购物城返回结果：",options);
    })
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