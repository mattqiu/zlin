// pages/my/collection.js
Page({
  data:{

    goodsListType: 1, //1小图 2大图
    goodsList: [
      {
        id: 0,
        pic: '../../images/local/pic2.png',
        name: '百搭针织上衣 针织毛衣上 衣针织衫',
        no: 1630188,
        price: 999,
        dn: 260
      },{
        id: 0,
        pic: '../../images/local/pic2.png',
        name: '百搭针织上衣 针织毛衣上 衣针织衫',
        no: 1630188,
        price: 999,
        dn: 260
      },{
        id: 0,
        pic: '../../images/local/pic2.png',
        name: '百搭针织上衣 针织毛衣上 衣针织衫',
        no: 1630188,
        price: 999,
        dn: 260
      },{
        id: 0,
        pic: '../../images/local/pic2.png',
        name: '百搭针织上衣 针织毛衣上 衣针织衫',
        no: 1630188,
        price: 999,
        dn: 260
      },{
        id: 0,
        pic: '../../images/local/pic2.png',
        name: '百搭针织上衣 针织毛衣上 衣针织衫',
        no: 1630188,
        price: 999,
        dn: 260
      },{
        id: 0,
        pic: '../../images/local/pic2.png',
        name: '百搭针织上衣 针织毛衣上 衣针织衫',
        no: 1630188,
        price: 999,
        dn: 260
      },{
        id: 0,
        pic: '../../images/local/pic2.png',
        name: '百搭针织上衣 针织毛衣上 衣针织衫',
        no: 1630188,
        price: 999,
        dn: 260
      },{
        id: 0,
        pic: '../../images/local/pic2.png',
        name: '百搭针织上衣 针织毛衣上 衣针织衫',
        no: 1630188,
        price: 999,
        dn: 260
      },{
        id: 0,
        pic: '../../images/local/pic2.png',
        name: '百搭针织上衣 针织毛衣上 衣针织衫',
        no: 1630188,
        price: 999,
        dn: 260
      },{
        id: 0,
        pic: '../../images/local/pic2.png',
        name: '百搭针织上衣 针织毛衣上 衣针织衫',
        no: 1630188,
        price: 999,
        dn: 260
      },{
        id: 0,
        pic: '../../images/local/pic2.png',
        name: '百搭针织上衣 针织毛衣上 衣针织衫',
        no: 1630188,
        price: 999,
        dn: 260
      },{
        id: 0,
        pic: '../../images/local/pic2.png',
        name: '百搭针织上衣 针织毛衣上 衣针织衫',
        no: 1630188,
        price: 999,
        dn: 260
      },{
        id: 0,
        pic: '../../images/local/pic2.png',
        name: '百搭针织上衣 针织毛衣上 衣针织衫',
        no: 1630188,
        price: 999,
        dn: 260
      },{
        id: 0,
        pic: '../../images/local/pic2.png',
        name: '百搭针织上衣 针织毛衣上 衣针织衫',
        no: 1630188,
        price: 999,
        dn: 260
      },{
        id: 0,
        pic: '../../images/local/pic2.png',
        name: '百搭针织上衣 针织毛衣上 衣针织衫',
        no: 1630188,
        price: 999,
        dn: 260
      },{
        id: 0,
        pic: '../../images/local/pic2.png',
        name: '百搭针织上衣 针织毛衣上 衣针织衫',
        no: 1630188,
        price: 999,
        dn: 260
      }
    ]
  },
  switchGoodsType: function(){
    // 商品列表大小图切换
    let goodsType = this.data.goodsListType;
    goodsType = goodsType == 1 ? 2 : 1;
    this.setData({
      goodsListType: goodsType
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