// pages/order/index.js
var util = require('../../utils/util.js');
var app = new getApp();
Page({
  data:{
    sc:'asc',
    filter:'goods_serial',
    size:'',
    page:'10',
    listType: 0, //0:我的订单 1:总榜排行
    sortFilter: [
      {
        name: '升序',
        index:0,
        sc:'asc'
      },
      {
        name: '降序',
        index: 1,
        sc: 'desc'
      }
    ],
    orderFilter: [
      {
        name: '货号',
        index: 0,
        filter: 'goods_serial'
      },
      {
        name: '数量',
        index: 1,
        filter: 'num'
      },
      {
        name: '零售额',
        index: 2,
        filter: 'total_price'
      },
    ],
    sortFilterIdx: 0,
    orderFilterIdx: 0,
    planeData: [
      {
        name: '小明'
      },
      {
        name: '小葱'
      }
    ],
    isShowPlane: false
  },
  listTypeFn: function(e){
    // 列表切换
    var that =this;
    let type = e.currentTarget.dataset.idx;
    if(type == 1){
      var data = {
        token: app.globalData.token,
        member_id: app.globalData.member_id,
        state_type: '',
        size:'total',
        sc: 'desc',
        order: 'num',
        curpage: '1',
        page: '10'
      };
      util.Ajax("store_ordering/ordering_list", data, function (res) {
        that.setData({
          scrollTop: 0,//滚动条位置
          showPlaneType: '',
          is_top: false,
          is_scroll: true,//可以再滚动
          topGoodsList: res.datas.ordering_list,
        })
        console.log('返回商品排序', res)
      });
    }
    console.log('表头选择',type);
    that.setData({
      listType: type
    })
  },
  sortFilterFn: function(e){
    // 我的订单筛选
    var that = this;
    let sc = e.currentTarget.dataset.sc,
      index = e.currentTarget.dataset.index,
      filter = that.data.filter;
    console.log('订单排序方式', sc);
    console.log('订单排序方式2', filter);
    var data = {
      token: app.globalData.token,
      member_id: app.globalData.member_id,
      state_type: 'state_new',
      sc: sc,
      order:filter,
      size:'',
      page:'10'
    };
    util.Ajax("store_ordering/ordering_list", data, function (res) {
      that.setData({
        scrollTop: 0,//滚动条位置
        showPlaneType: '',
        is_top: false,
        is_scroll: true,//可以再滚动
        orderGoodsList: res.datas.ordering_list,
      })
      console.log('返回商品排序', res)
    });
    that.setData({
      sortFilterIdx: index,
      sc:sc,
      filter:filter
    })
  },
  orderFilterFn: function (e) {
    // 我的订单筛选
    var that = this;
    let sc = that.data.sc,
      index = e.currentTarget.dataset.index,
      filter = e.currentTarget.dataset.filter;
    console.log('订单排序方式3', sc);
    console.log('订单排序方式4', filter);
    var data = {
      token: app.globalData.token,
      member_id: app.globalData.member_id,
      state_type: 'state_new',
      sc: sc,
      order: filter,
      size: '',
      page: '10'
    };
    util.Ajax("store_ordering/ordering_list", data, function (res) {
      that.setData({
        scrollTop: 0,//滚动条位置
        showPlaneType: '',
        is_top: false,
        is_scroll: true,//可以再滚动
        orderGoodsList: res.datas.ordering_list,
      })
      console.log('返回商品排序', res)
    });
    that.setData({
      orderFilterIdx: index,
      sc: sc,
      filter: filter
    })
  },
  showPlane: function(){
    this.setData({
      isShowPlane: true
    })
  },
  closePlane: function(){
    this.setData({
      isShowPlane: false
    })
  },
  resetPlane: function(){
    let planeData = this.data.planeData;

    planeData = planeData.map(item => {
      item.active = false;
      return item;
    })
    this.setData({
      planeData: planeData
    });
    this.closePlane();
  },
  chioceOpt: function(e){
    // 我的订单筛选
    let index = e.currentTarget.dataset.idx;
    let planeData = this.data.planeData;

    planeData[index].active = !planeData[index].active

    this.setData({
      planeData: planeData
    })
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
    var that = this;
    var data = {
      token: app.globalData.token,
      member_id: app.globalData.member_id,
      state_type:'state_new',
      sc: 'asc',
      order: 'goods_serial',
      size: '',
      page: '10'
    };
    util.Ajax("store_ordering/ordering_list", data, function (res) {
      that.setData({
        scrollTop: 0,//滚动条位置
        showPlaneType: '',
        is_top: false,
        is_scroll: true,//可以再滚动
        orderGoodsList: res.datas.ordering_list,
      })
      console.log('返回商品排序', res.datas)
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