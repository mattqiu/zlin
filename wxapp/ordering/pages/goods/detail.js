// pages/goods/detail.js
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