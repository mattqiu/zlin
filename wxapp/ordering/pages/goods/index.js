// pages/goods/index.js
var goodsListUrl = require('../../config').goodsListUrl;
var util = require('../../utils/util.js');
var goodsFilterUrl = require('../../config').goodsFilterUrl;
var goodsUrl = require('../../config').goodsUrl;
var app = new getApp();
Page({
  data:{
    member_id:app.globalData.member_id,
    keyword: '',
    minPrice: 0.00,
    maxPrice: 0.00,
    sort_id: 0,
    has_id: 0,
    showPlaneType: '',  // 筛选面板 ‘’不展开 0排序 1所有 2筛选
    curpage: 1,//当前页
    scrollHeight: 0,//页面高度
    scrollTop: 0,//滚动条位置
    is_top: false,//默认禁止向上滑动
    is_scroll: true,//防止未完全加载就滚动下一页
    // 排序筛选
    sort: {
      currentIdx: 0,
      list: [
        {
          name: '默认排序',
          sort_id: 0
        },{
          name: '订量从高到低',
          sort_id: 1
        },{
          name: '订量从低到高',
          sort_id: 2
        },{
          name: '价格从高到低',
          sort_id: 3
        },{
          name: '价格从低到高',
          sort_id: 4
        }
      ]
    },
    // 是否已选定筛选
    has: {
      currentIdx: 0,
      list: [
        {
          name: '所有',
          has_id: 0
        },{
          name: '已订货',
          has_id:1
        },{
          name: '未订货',
          has_id:2
        }
      ]
    },
    // 筛选
    filter: {
      list: [
        {
          title: '波段',
          opts: [
            {
              name: '所有',
              key: 4,
              order_4:0
            }
          ]
        },
        {
          title: '商品',
          opts: [
            {
              name: '必定款',
              key: 4,
              order_4:1
            },{
              name: '特价款',
              key:4,
              order_4:2
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
              order_4:3
            }
          ]
        },
        {
          title: '类型',
          opts: [
            {
              name: 'T恤',
              key: 4,
              order_4:4
            },{
              name: '针织衫',
              key:4,
              order_4:5
            }
          ]
        }
      ]
    },
    display: 1, //商品列表陈列类型
    goodsListType: 1, //1小图 2大图
    goods_list: [],
    isShowList: false,  //是否展示陈列
    // 陈列列表
    showList: []
  },
  //触发关键字
  changeKeyword: function (e) {
    // 筛选面板开关
    let keyword = e.detail.value;
    this.setData({
      keyword: keyword
    });
    this.loadGoodsList();
  },
  //点击展开过滤的条件事件
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
  //选择具体排序事件
  selectPlaneItem: function(e){

    var that = this;
    let sort = this.data.sort,//排序
      has = this.data.has,//是否已订货
      filter = this.data.filter;//筛选
    let dataSet = e.currentTarget.dataset,
     filter_id = dataSet.id,  //过滤类型ID
     index = dataSet.index,  //选择类型序号
     filter_type = dataSet.type; //过滤类型
    if (filter_type == 'sort'){//排序
      sort.currentIdx = index;//选中的排序
      that.setData({
        sort_id: filter_id,
        filter_type: filter_type,
        sort: sort
      });
    } else if (filter_type == 'has'){//是否订货
      has.currentIdx = index;//选中的排序
      that.setData({
        has_id: filter_id,
        filter_type: filter_type,
        has: has
      });
    }
    that.loadGoodsList();
  },
  //输入最低价时触发
  minPrice:function(e){
    this.setData({
      minPrice: e.detail.value
    }); 
  },
  //离开输入最高价时
  blurMaxPrice: function(e){
    let maxPrice = e.detail.value,
      minPrice = this.data.minPrice;
    if (maxPrice < minPrice){
      app.showErrMsg('最高价不得小于最低价，请重新输入！');
      this.setData({
        maxPrice: ''
      });
      return false;
    }
  },
  //点击陈列
  toggleShow: function(){
    // 陈列开关
    this.setData({
      isShowList: !this.data.isShowList,
      minPrice:function(e){
        console.log('测试2', e.detail.value, );
      }
    })
  },
  //商品列表展示形式切换
  switchGoodsType: function(){
    // 商品列表大小图切换
    let goodsType = this.data.goodsListType;
    goodsType = goodsType == 1 ? 2 : 1;
    this.setData({
      goodsListType: goodsType
    })
  },
  //展开筛选的选项
  toggleOpt: function(e){
    // 筛选面板 选项开关
    let index = e.currentTarget.dataset.index,
      filter = this.data.filter;
    let isShow = filter.list[index].show;
    filter.list[index].show = !isShow;
    
    this.setData({
      filter: filter
    })
  },
  
  //确定选择筛选的信息
  formFilterSubmit: function(e){
    let val = e.detail.value;
    var that = this;
    that.setData({
      minPrice: val.minPrice,
      maxPrice: val.maxPrice,
      showPlaneType: '',
    })
    //console.log('form发生了submit事件，携带数据为：', e.detail.value)
    that.loadGoodsList();
  },
  //重置选择筛选的信息
  formFilterReset: function (e) {
    this.setData({
      sort_id: 0,
      has_id: 0,
      minPrice: 0.00,
      maxPrice: 0.00,
      showPlaneType: '',
    })
    this.loadGoodsList();
  },
  //滑动到顶部触发
  bindTopLoad: function () {
    let curpage = this.data.curpage,
      is_top = this.data.is_top;
    if (curpage > 1 && is_top){
      //console.log('向上滚动：', is_top);
      this.setData({
        //scrollTop: this.data.scrollHeight,//向上滚动时
        curpage: curpage - 1,
      })
      this.loadGoodsList();
    }
  },
  //滚动时触发
  bindScroll: function (event) {

    let curpage = this.data.curpage,
      scrollCur = event.detail.scrollTop, //当前高度
      scrollHeight = this.data.scrollHeight;
    if (curpage > 1 && scrollCur>0){
      this.setData({
        is_top: true,
      });
      //console.log('可以向上滚动：', scrollTop);
    }
    //console.log('当前高度：', scrollCur);
  },
  //滑动到底部触发
  bindDownLoad: function () {
    let curpage = this.data.curpage,
      is_scroll = this.data.is_scroll;
    if (is_scroll){
      this.setData({
        is_scroll: false,//禁止再滚动
        curpage: curpage + 1,
      })
      this.loadGoodsList();
    }else{
      //app.showErrMsg('还未来得及加载请耐心等待一下');
    }
  },
  //加载商品列表
  loadGoodsList: function() {
    var that = this;
    //console.log('当前data：', that.data);
    var data = {
      token: app.globalData.token,
      member_id: app.globalData.member_id,
      keyword: that.data.keyword,
      filter_type: that.data.filter_type,
      sort_id: that.data.sort_id,
      has_id: that.data.has_id,
      minPrice: that.data.minPrice,
      maxPrice: that.data.maxPrice,
      curpage: that.data.curpage,
    };
    util.Ajax("ordering_goods/index", data, function (res) {
      that.setData({
        scrollTop: 0,//滚动条位置
        showPlaneType: '',
        is_top: false,
        is_scroll: true,//可以再滚动
        goods_list: res.datas,
      })
      console.log('返回商品排序', res)
    });
  },
  onLoad: function (options) {
    // 页面初始化 options为页面跳转所带来的参数
    var that = this;
    wx.getSystemInfo({
      success: function (res) {
        //console.log('页面高度：',res.windowHeight);
        that.setData({
          scrollHeight: res.windowHeight
        });
      }
    });
  },
  onReady:function(){
    // 页面渲染完成
  },
  onShow:function(){
    // 页面显示
    this.loadGoodsList();
  },
  onHide:function(){
    // 页面隐藏
  },
  onUnload:function(){
    // 页面关闭
  }
})
