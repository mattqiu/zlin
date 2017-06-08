// pages/index/index.js
var saveMemberUrl = require('../../config').saveMemberUrl;
var util = require('../../utils/util.js');
var app = new getApp();
Page({
  data: {
    motto:"欢迎进去订货会系统",
    count: 60,
    font: "../../images/login/font.png",
    logo: "../../images/login/logo.png",
    iphono: "../../images/login/iphono.png",
    password: "../../images/login/password.png",
    iphono5: "../../images/login/iphono5.png",
    iphono4: "../../images/login/iphono4.png",
    iphono3: "../../images/login/iphono3.png",
  },

  /**
   * 生命周期函数--监听页面加载
   */
  // 获取手机号码
  savePhoneNumber: function (e) {
    console.log(e.detail.value)
    this.setData({
      phoneNumber: e.detail.value
    });
  },
  // 获取密码
  savePassword: function (e) {
    console.log(e.detail.value)
    this.setData({
      savePassword: e.detail.value
    });
  },
  // 获取验证码
  getValidCode: function () {
    if (this.data.phoneNumber && this.data.count == 60) {
      this.tick()
    } else if (!this.data.phoneNumber) {
      wx.showToast({
        title: '请填写电话号码',
        icon: 'loading',
        duration: 1000
      })
    }
  },
  tick: function () {
    var vm = this
    if (vm.data.count > 0) {
      vm.setData({
        count: vm.data.count - 1
      });
      setTimeout(function () {
        return vm.tick()
      }, 1000)
    } else {
      vm.setData({
        count: 60
      });
    }
  },
  // 填写验证码
  identCode:function(e){
    //console.log(e.detail.value)
    this.setData({
      identCode: e.detail.value
    });
  },
  // 获取验证码结束
  // 获取邀请码
  saveInviteNumber: function (e) {
    //console.log(e.detail.value)
    this.setData({
      saveInviteNumber: e.detail.value
    });
  },
  formSubmit: function (data) {
    //console.log(data.detail.value);
    const self = this;
    wx.request({
      url: saveMemberUrl,
      data:e.detail.value,
      method:'POST',
      header: {
        'content-type': 'application/x-www-form-urlencoded'
      },
      success:function(data){
        //console.log("添加个人注册信息",res)
        app.showSucMsg("注册成功",1000);
        wx.redirectTo({
          url: 'list',
        })
      },
      fail:function(err){
        app.showErrMsg(err);
        console.log(err);
      }
    })
  },
  //加载首页信息
  loadIndex: function (options) {

    var that = this;
    let token = app.globalData.token;//获取到token值
    if (token) {
      //console.log('店铺ID：',app.globalData.store_id);
      var data = {
        token: token,
      };
      // 请求商品信息
      util.Ajax("ordering_index/index", data, function (res) {
        if (res.error_code) {
          app.showErrMsg(res.errMsg);
          wx.showModal({
            title: '温馨提示',
            content: res.errMsg,
            success: function (res) {
              if (res.confirm) {
                app.globalData.userInfo = '{}';//重新登录
                app.globalData.token = '';
                wx.removeStorageSync('token');//从本地缓存中同步移除指定 key 。
                wx.clearStorage();//清理本地数据缓存。
                wx.clearStorageSync();//同步清理本地数据缓存
              }
              that.onLoad();//重新初始化页面
            }
          })
        } else {
          that.setData(res.datas);
          //console.log("获取成功", res.datas);
          app.globalData.member_id = that.data.member_id;
          app.globalData.seller_id = that.data.seller_id;
          app.globalData.seller_name = that.data.seller_name;
          //没有默认的店铺则需要更新，有则无需更新
          if (app.globalData.store_id == '') {
            app.globalData.store_id = that.data.store_id;
          }
          //没有默认的导购则需要更新，有则无需更新
          if (app.globalData.saleman_id == '') {
            app.globalData.saleman_id = that.data.seller_id;
            app.globalData.saleman_member_id = that.data.member_id;
          }
          //跳转商品列表页
          wx.navigateTo({
            url: '../goods/index'
          })
        }
      });
    } else {
      that.onLoad();//没有获取就重新去获取
    }
  },
  //初始化页面
  onLoad: function (options) {
    
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {
    var that = this;
    //用户第一次进去页面时，先查询是否已经获取了该会员的微信信息
    let token = app.globalData.token;
    if (token) {//token不为空时
      //没有授权时是无法获取到会员信息，
      that.loadIndex();
    }else{
      //调用应用实例的方法获取全局数据
      app.getUserInfo(function (userInfo) {
        app.globalData.token = wx.getStorageSync('token');
        //console.log("页面加载缓存token: ", wx.getStorageSync('token'));
        that.loadIndex();
      });
      
    }
  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {

  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {

  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {

  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {

  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {

  }
})