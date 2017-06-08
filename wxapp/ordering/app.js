//app.js
import common from 'utils/index';//导入公共常用函数
var indexUrl = require('config').indexUrl; //导购登录地址
var loginsellerUrl = require('config').loginsellerUrl; //导购登录地址
var token = wx.getStorageSync('token');//登录令牌
var store_id = wx.getStorageSync('store_id');//店铺ID
var member_id = wx.getStorageSync('member_id');//会员ID
var seller_id = wx.getStorageSync('seller_id');//登录者导购ID
App({
  //生命周期函数--监听小程序初始化
  onLaunch: function () {
    this.common = new common();//加载常用函数
    //调用API从本地缓存中获取数据
    //var logs = wx.getStorageSync('logs') || []
    //logs.unshift(Date.now())
    //wx.setStorageSync('logs', logs)
  },
  //定义整个小程序的全局变量
  globalData: {
    token: '',
    store_id: store_id,
    member_id: member_id,
    seller_id: seller_id,
    seller_name: '店长',
    saleman_id: seller_id,//收银导购ID，默认是当前登录者
    saleman_member_id: member_id,//收银导购member_id，默认是当前登录者
    error_code: null,//服务器返回失败的结果
    userInfo: null
  },
  getUserInfo: function (cb) {
    if (this.globalData.userInfo) {
      typeof cb == "function" && cb(this.globalData.userInfo)
    } else {
      var that = this;
      let token = that.globalData.token;
      //调用登录接口
      wx.login({
        success: function (res) {
          //console.log('获取微信code：', res)
          if (res.code) {
            var code = res.code;
            wx.getUserInfo({
              success: function (res2) {
                //console.log('获取微信信息2：', res2)
                //缓存是否失效
                if (!token) {
                  var encryptedData = encodeURIComponent(res2.encryptedData);//一定要把加密串转成URI编码
                  var iv = res2.iv;
                  //请求自己的服务器
                  that.login({
                    code: code,
                    encryptedData: encryptedData,
                    iv: iv
                  }, res2.userInfo, cb);
                } else {
                  that.globalData.userInfo = res2.userInfo;
                  typeof cb == "function" && cb(that.globalData.userInfo)
                }
              },
              fail: function (data) {
                console.log('获取信息失败！', data)
                // fail
                this.showErrMsg('获取信息失败，原因：' + err);
              }
            })
          } else {
            console.log('获取用户登录态失败！', res.errMsg)
          }
        }
      })
    }
  },
  //调用登录接口
  login: function (param, userInfo, cb) {
    //创建一个dialog
    wx.showToast({
      title: '正在登录...',
      icon: 'loading',
      duration: 3000
    });
    var that = this;
    //请求服务器
    wx.request({
      url: loginsellerUrl,
      data: param,
      method: 'POST',
      header: {
        'content-type': 'application/x-www-form-urlencoded'
      }, // 设置请求的 header
      dataType: 'txt',
      success: function (res) {
        // success
        wx.hideToast();
        //console.log('获取用户登录信息！', res)
        if (res.error_code) {
          that.showErrMsg(res.errMsg);
          that.getUserInfo();//失败后重新去获取
          return false;
        } else {
          var data = res.data.trim();
          wx.setStorageSync('token', data);
          //console.log('获取用户登录成功！', data)
          that.globalData.token = data;//获取成功后给全局token赋值
          typeof cb == "function" && cb(userInfo)
        }
      },
      fail: function () {
        // fail
        wx.hideToast();
        that.showErrMsg('登录失败');
      }
    })
  },
  //调用提示成功
  showSucMsg: function (sucmsg) {
    wx.showToast({
      title: sucmsg,
      icon: 'success',
      duration: 3000
    })
  },
  //调用提示失败
  showErrMsg: function (errmsg, duration = 3000) {
    wx.showToast({
      title: errmsg,
      icon: 'loading',
      duration: duration
    })
  }
})