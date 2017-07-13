// pages/order/manage.js
var util = require('../../utils/util.js');
var app = new getApp();
Page({
  data:{
    listType: 0 //0:已完成订单 1：未完成订单
  },
  switchType: function(e){
    let type = e.currentTarget.dataset.type;
    if(type == 1){
      var that = this;
      var data = {
        token: app.globalData.token,
        member_id: app.globalData.member_id,
        state_type: 'state_new',
        sc: 'desc',
        size: 'order',
        order:'num',
        page: '0',
        curpage: '1'
      };
      util.Ajax("store_ordering/ordering_list", data, function (res) {
        that.setData({
          scrollTop: 0,//滚动条位置
          showPlaneType: '',
          is_top: false,
          is_scroll: true,//可以再滚动
          noFinishGoodsList: res.datas.ordering_list,
        })
        console.log('返回商品排序', res)
      });
    }
    this.setData({
      listType: type
    })
  },
  changeboxChange:function(e){
    console.log('选中的checkobox的值',e.detail.value)
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
    var that = this;
    var data = {
      token: app.globalData.token,
      member_id: app.globalData.member_id,
      state_type: 'state_commit',
      sc: 'desc',
      size: 'order',
      page: '0',
      curpage:'1'
    };
    util.Ajax("store_ordering/ordering_list", data, function (res) {
      that.setData({
        scrollTop: 0,//滚动条位置
        showPlaneType: '',
        is_top: false,
        is_scroll: true,//可以再滚动
        finishGoodsList: res.datas.ordering_list,
      })
      console.log('返回商品排序', res)
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