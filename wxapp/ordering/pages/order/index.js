// pages/order/index.js
Page({
  data:{
    listType: 0, //0:我的订单 1:总榜排行
    orderGoodsList: [
      {
        id: 0,
        name: '百搭针织上衣',
        no: 1630188,
        pic: '../../images/local/pic2.png',
        num: 16,
        price: 600
      },{
        id: 0,
        name: '百搭针织上衣',
        no: 1630188,
        pic: '../../images/local/pic2.png',
        num: 16,
        price: 600
      },{
        id: 0,
        name: '百搭针织上衣',
        no: 1630188,
        pic: '../../images/local/pic2.png',
        num: 16,
        price: 600
      },{
        id: 0,
        name: '百搭针织上衣',
        no: 1630188,
        pic: '../../images/local/pic2.png',
        num: 16,
        price: 600
      },{
        id: 0,
        name: '百搭针织上衣',
        no: 1630188,
        pic: '../../images/local/pic2.png',
        num: 16,
        price: 600
      },{
        id: 0,
        name: '百搭针织上衣',
        no: 1630188,
        pic: '../../images/local/pic2.png',
        num: 16,
        price: 600
      },{
        id: 0,
        name: '百搭针织上衣',
        no: 1630188,
        pic: '../../images/local/pic2.png',
        num: 16,
        price: 600
      }
    ],
    topGoodsList: [
      {
        id: 0,
        name: '百搭针织上衣百搭针织上衣百搭针织上衣百搭针织上衣',
        no: 1630188,
        pic: '../../images/local/pic2.png',
        num: 16,
        price: 600
      },{
        id: 0,
        name: '百搭针织上衣',
        no: 1630188,
        pic: '../../images/local/pic2.png',
        num: 16,
        price: 600
      },{
        id: 0,
        name: '百搭针织上衣',
        no: 1630188,
        pic: '../../images/local/pic2.png',
        num: 16,
        price: 600
      },{
        id: 0,
        name: '百搭针织上衣',
        no: 1630188,
        pic: '../../images/local/pic2.png',
        num: 16,
        price: 600
      },{
        id: 0,
        name: '百搭针织上衣',
        no: 1630188,
        pic: '../../images/local/pic2.png',
        num: 16,
        price: 600
      },{
        id: 0,
        name: '百搭针织上衣',
        no: 1630188,
        pic: '../../images/local/pic2.png',
        num: 16,
        price: 600
      },{
        id: 0,
        name: '百搭针织上衣',
        no: 1630188,
        pic: '../../images/local/pic2.png',
        num: 16,
        price: 600
      }
    ],
    orderFilter: [
      {
        name: '升序'
      },
      {
        name: '降序'
      },
      {
        name: '货号'
      },
      {
        name: '数量'
      },
      {
        name: '零售额'
      },
    ],
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
    let type = e.currentTarget.dataset.idx;
    this.setData({
      listType: type
    })
  },
  orderFilterFn: function(e){
    // 我的订单筛选
    let index = e.currentTarget.dataset.idx;

    this.setData({
      orderFilterIdx: index
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