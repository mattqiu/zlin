// 加载配置文件
const config = require( '../config.js' );

function formatTime( date ) {
    var year = date.getFullYear();
    var month = date.getMonth() + 1;
    var day = date.getDate();
    var hour = date.getHours();
    var minute = date.getMinutes();
    var second = date.getSeconds();

    return [year, month, day].map(formatNumber).join('/') + ' ' + [hour, minute, second].map(formatNumber).join(':');
}

function formatNumber(n) {
    n = n.toString();
    return n[1] ? n : '0' + n;
}

// 格式化时间戳
function getTime( timestamp ) {
    var time = arguments[ 0 ] || 0;
    var t, y, m, d, h, i, s;
    t = time ? new Date( time * 1000 ) : new Date();
    y = t.getFullYear();    // 年
    m = t.getMonth() + 1;   // 月
    d = t.getDate();        // 日
    h = t.getHours();       // 时
    i = t.getMinutes();     // 分
    s = t.getSeconds();     // 秒

    return [ y, m, d ].map( formatNumber ).join('-') + ' ' + [ h, i, s ].map( formatNumber ).join(':');    
}

function imageUtil(e) {
  var imageSize = {};
  var originalWidth = e.detail.width;//图片原始宽  
  var originalHeight = e.detail.height;//图片原始高  
  var originalScale = originalHeight / originalWidth;//图片高宽比  
  console.log('originalWidth: ' + originalWidth)
  console.log('originalHeight: ' + originalHeight)
  //获取屏幕宽高  
  wx.getSystemInfo({
    success: function (res) {
      var windowWidth = res.windowWidth;
      var windowHeight = res.windowHeight;
      var windowscale = windowHeight / windowWidth;//屏幕高宽比  
      console.log('windowWidth: ' + windowWidth)
      console.log('windowHeight: ' + windowHeight)
      if (originalScale < windowscale) {//图片高宽比小于屏幕高宽比  
        //图片缩放后的宽为屏幕宽  
        imageSize.imageWidth = windowWidth;
        imageSize.imageHeight = (windowWidth * originalHeight) / originalWidth;
      } else {//图片高宽比大于屏幕高宽比  
        //图片缩放后的高为屏幕高  
        imageSize.imageHeight = windowHeight;
        imageSize.imageWidth = (windowHeight * originalWidth) / originalHeight;
      }

    }
  })
  console.log('缩放后的宽: ' + imageSize.imageWidth)
  console.log('缩放后的高: ' + imageSize.imageHeight)
  return imageSize;
}

//上传文件
function upload_file(url, filePath, name, formData, success, fail) {
    url = '?act=' + url;//替换成商城的路由
    if (url.indexOf("/") >= 0) {//
      url = url.replace('/', '&op=');//替换'/'为空格，目前只考虑一个'/'情况，如果有多个则需要使用re = new RegExp("index/index/id", "t");
    }
      wx.uploadFile({
          url: config.API_HOST  + url,
          filePath: filePath,
    　     name: name,
    　　　　header: {
      　　　　　　'content-type': 'nultipart/form-data'
    　　　　},
    　　　　formData: formData,  //http请求中其他额外的form data
    　　　　success: function (res) {
      　　　　　　console.log(res);
      　　　　　　if (res.statusCode == 200 && !res.data.result_code) {
        　　　　　　　　　typeof success == "function" && success(res.data);
      　　　　　　} else {
        　　　　　　　　typeof fail == "function" && fail(res);
      　　　　　　}
    　　　　},
    　　　　fail: function (res) {
      　　　　　　console.log(res);
                app.showErrMsg(res);
      　　　　　　typeof fail == "function" && fail(res);
    　　　　}
  　　})
}
function ajax(url, data = {}, fn, method = "GET", header = {}, dataType = 'json') {
  url = '?act=' + url;//替换成商城的路由
  if (url.indexOf("/") >= 0) {//
    url = url.replace('/', '&op=');//替换'/'为空格，目前只考虑一个'/'情况，如果有多个则需要使用re = new RegExp("index/index/id", "t");
  }
  //console.log("提交数据", data);
  wx.request({
    url: config.API_HOST + url,
    data: data,
    header: header ? header : { "Content-Type": "application/json" },//或 'content-type': 'application/x-www-form-urlencoded'
    method: method ? method : 'GET',//或'GET',必须大写
    dataType: dataType ? dataType : 'json',
    success: function (res) {
      console.log("ajax结果：",res);
      fn(res.data);
    },
    fail: function (res) {
      console.log(res);
      app.showErrMsg(res);
    }
  });
}
module.exports = {

  formatTime: formatTime,
  imageUtil: imageUtil,
  uploadFile: upload_file,
  getTime : getTime,
  Ajax: ajax,  //传输路由的格式为：index/index&id=100
  /**
   * 获取格式化日期
   * 20161002
   */
  getFormatDate : function( str ) {
          
      // 拆分日期为年 月 日
      var YEAR = str.substring( 0, 4 ),
          MONTH = str.substring( 4, 6 ),
          DATE = str.slice( -2 );

      // 拼接为 2016/10/02 可用于请求日期格式
      var dateDay = YEAR + "/" + MONTH + "/" + DATE;

      // 获取星期几
      var week = ['星期日', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六'],
          day = new Date( dateDay ).getDay();

      // 获取前一天日期 根据今天日期获取前一天的日期
      // var dateBefore = new Date( new Date( dateDay ) - 1000 * 60 * 60 * 24 ).toLocaleDateString();
      // var dateBefore = dateBefore.split('/');
      // if( dateBefore[1] < 10 ) {
      //     dateBefore[1] = '0' + dateBefore[1];
      // }
      // if( dateBefore[2] < 10 ) {
      //     dateBefore[2] = '0' + dateBefore[2];
      // }
      // dateBefore = dateBefore.join('');

      return {
          "dateDay" : MONTH + "月" + DATE + "日 " + week[ day ]
      }
    },

}
