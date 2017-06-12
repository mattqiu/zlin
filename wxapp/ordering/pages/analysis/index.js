// pages/analysis/index.js
let wxCharts = require('../../utils/wxcharts.js');
Page({
  data:{
    colors: ['#dfcea5', '#8f690d', '#9e8446', '#dbb864', '#ae7122', '#ce9100', '#f6a526'],  // 图表配色
    titles: ['类型', '颜色', '尺码', '波段', '价格'],
    dataList: [
      {name: '女装-羊剪绒大衣', data: 100, percent: 26},
      {name: '女装-羊剪绒大衣', data: 120, percent: 26},
      {name: '女装-羊剪绒大衣', data: 300, percent: 26},
      {name: '女装-羊剪绒大衣', data: 50, percent: 26},
      {name: '女装-羊剪绒大衣', data: 200, percent: 26},
    ],
    currentType: 0 //当前类型 0：类型 1：颜色 2；尺码 3：波段 4：价格
  },
  switchTye: function(e){
    let type = e.currentTarget.dataset.type;
    this.setData({
      currentType: type
    });
    let typeStr = 'ring'
    if(type == 4){
      typeStr = 'column'
    }
    this.setChart({
      type: typeStr, 
      data: this.data.dataList,
      title: this.data.titles[type]
    })
  },
  setChart: function(opts){
    var windowWidth = 320;
    try {
        var res = wx.getSystemInfoSync();
        windowWidth = res.windowWidth;
    } catch (e) {
        console.error('getSystemInfoSync failed!');
    }
    let that = this;
    if(opts.type == 'ring'){
      new wxCharts({
          colors: opts.colors || that.data.colors,
          animation: true,
          canvasId: 'Canvas',
          type: opts.type,
          extra: {
              ringWidth: 25
          },
          title: {
              name: opts.title + '占比分析',
              color: '#000000',
              fontSize: 10
          },
          // subtitle: {
          //     name: '收益率',
          //     color: '#666666',
          //     fontSize: 15
          // },
          series: opts.data,
          disablePieStroke: true,
          width: windowWidth,
          height: 200,
          dataLabel: false,
          legend: false,
          padding: 0
      });
    }else if(opts.type == 'column'){
      let categories = [],
        seriesData = [];
      opts.data.forEach(item => {
        categories.push(item.name);
        seriesData.push(item.data)
      })
      new wxCharts({
        colors: opts.colors || that.data.colors,
        canvasId: 'Canvas',
        type: 'column',
        animation: true,
        categories: categories,
        series: [{
            name: opts.title + '占比分析',
            data: seriesData,
            format: function (val, name) {
                return val.toFixed(2) + '元';
            }
        }],
        yAxis: {
            format: function (val) {
                return val + '元';
            },
            title: '',
            min: 0
        },
        xAxis: {
            disableGrid: false,
            type: 'calibration'
        },
        extra: {
            column: {
                width: 15
            }
        },
        width: windowWidth,
        height: 200,
      });
    }
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
  },
  onReady: function (e) {
      this.setChart({
        type: 'ring', 
        data: this.data.dataList,
        title: this.data.titles[0]
      })
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