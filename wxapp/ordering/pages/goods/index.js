// pages/goods/index.js
var goodsListUrl = require('../../config').goodsListUrl;
var util = require('../../utils/util.js');
var goodsFilterUrl = require('../../config').goodsFilterUrl;
var goodsUrl = require('../../config').goodsUrl;
var app = new getApp();
Page({
  data:{
    goods_list:[],
    goods_commonid:'',
    goods_name:'',
    goods_price:'',
    goods_id:'',
    showPlaneType: '',  // 筛选面板 ‘’不展开 0排序 1拥有 2筛选
    filter:{
      // 排序筛选 key  代表排序的类别 1 默认排序 2 所有  3 陈列  4 筛选  order  代表下拉的顺序
      sort: {
        currentIdx: 0,
        list: [
          {
            name: '默认排序',
            key: 1,
            order:0
          },{
            name: '定量从高到低',
            key: 1,
            order:1
          },{
            name: '定量从低到高',
            key: 1,
            order: 2
          },{
            name: '价格从高到低',
            key: 1,
            order:3
          },{
            name: '价格从低到高',
            key: 1,
            order: 4
          }
        ]
      },
      // 是否拥有筛选
      has: {
        currentIdx: 0,
        list: [
          {
            name: '所有',
            key: 2,
            order:0
          },{
            name: '已定',
            key:2,
            order:1
          },{
            name: '未定',
            key:2,
            order:2
          }
        ]
      },
      // 筛选
      ft: {
        list: [
          {
            title: '波段',
            opts: [
              {
                name: '所有',
                key: 4,
                order:0
              }
            ]
          },
          {
            title: '商品',
            opts: [
              {
                name: '必定款',
                key: 4,
                order:1
              },{
                name: '特价款',
                key:4,
                order:2
              }
            ]
          },
          {
            title: '价格区间（元）',
            hasPriceRange: true,
            opts: [
              {
                name: '所有',
                key: 4,
                order:3
              }
            ]
          },
          {
            title: '女装',
            opts: [
              {
                name: 'T恤',
                key: 4,
                order:4
              },{
                name: '针织衫',
                key:4,
                order:5
              }
            ]
          }
        ]
      }
    },
    goodsListType: 1, //1小图 2大图
    goodsList: [
    ],
    isShowList: false,  //是否展示陈列
    // 陈列列表
    showList: [
      {
        id: 0,
        pic: '../../images/local/pic1.png',
        name: '百搭针织上衣 针织毛衣上 衣针织衫',
        dn: 260
      },{
        id: 0,
        pic: '../../images/local/pic1.png',
        name: '百搭针织上衣 针织毛衣上 衣针织衫',
        dn: 260
      },{
        id: 0,
        pic: '../../images/local/pic1.png',
        name: '百搭针织上衣 针织毛衣上 衣针织衫',
        dn: 260
      },{
        id: 0,
        pic: '../../images/local/pic1.png',
        name: '百搭针织上衣 针织毛衣上 衣针织衫',
        dn: 260
      }
    ]
  },
  showPlane: function(e){
    // 筛选面板开关
    let index = e.currentTarget.dataset.index,
      showPlaneType = this.data.showPlaneType;

    if(index == showPlaneType){
      showPlaneType = '';
    }else{
      showPlaneType = index;
    }
    this.setData({
      showPlaneType: showPlaneType
    })
    
  },
  selectPlaneItem: function(e){
    // 排序 拥有 面板选择
    //console.log(e);
    var that = this;
    let dataSet = e.currentTarget.dataset,
     order = dataSet.index,
     key = dataSet.id;
     //console.log('测试key', key);
     //console.log('测试order', order);
     var data = {
       key: key,
       order: order,
     };
     util.Ajax("search/index",data,function(res){
       that.setData({
         goods_list: res.datas,        
       })
       //console.log('提交返回排序数据', that.data.goods_list)      
     });
     
    let filterData = this.data.filter;
    //filterData[type].currentIdx = index;
    this.setData({
      showPlaneType: '',
      filter: filterData
    });
    this.restGoodsList();
  },
  
  restGoodsList: function(){
    // 排序 拥有 筛选改变 重新加载商品列表
    // 展现陈列时不变
    // if(this.data.isShowList) return;
    // doing
  },
  toggleShow: function(){
    // 陈列开关
    this.setData({
      isShowList: !this.data.isShowList,
      minPrice:function(e){
        console.log('测试2', e.detail.value, );
      }
    })
  },
  switchGoodsType: function(){
    // 商品列表大小图切换
    let goodsType = this.data.goodsListType;
    goodsType = goodsType == 1 ? 2 : 1;
    this.setData({
      goodsListType: goodsType
    })
  },
  toggleOpt: function(e){
    // 筛选面板 选项开关
    let index = e.currentTarget.dataset.index,
      filter = this.data.filter;
    let isShow = filter.ft.list[index].show;
    filter.ft.list[index].show = !isShow;
    
    this.setData({
      filter: filter
    })
  },
  onLoad:function(options){
    console.log('跳转成功：', app.globalData);
    // 页面初始化 options为页面跳转所带来的参数
    var that = this;
    //console.log('商品列表：', options);
    wx.request({
      url: goodsListUrl,
      data:{
        token: app.globalData.token,
        store_id: app.globalData.store_id,
        member_id: app.globalData.member_id,
        seller_id: app.globalData.seller_id,
      },
      method:'post',
      header: {
        'content-type': 'application/x-www-form-urlencoded'
      },
      success: function (res){
        //console.log('商品列表：', res.data.datas.goods_list);
        that.setData({
          goods_list:res.data.datas.goods_list
        });
      },
      fail: function (err) {
        app.showErrMsg(err);
        console.log('跳转失败：', err);
      }
    })
  },
  loadMore: function (e) {
    this.showLoading('正在加载图片中');
    var that = this;
    currentPage++;
    wx.request({
      url: baseUrl + 'pictureController/getPicturesByAid',
      data: {
        pictureAid: albumId,
        pageSize: pageSize,
        currentPage: currentPage
      },
      header: {
        'content-type': 'application/json'
      },
      success: function (res) {
        console.log(res);
        if (res.data.result.length != 0) {
          array = array.concat(res.data.result)
          that.setData({
            array: array
          })
        }
        else {
          that.showToast('已加载完全部图片!');
        }
      },
      complete: function (res) {
        that.hideLoading();
      }
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