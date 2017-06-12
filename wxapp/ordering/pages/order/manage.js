// pages/order/manage.js
Page({
  data:{
    listType: 0 //0:已完成订单 1：未完成订单
  },
  switchType: function(e){
    let type = e.currentTarget.dataset.type;
    this.setData({
      listType: type
    })
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
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