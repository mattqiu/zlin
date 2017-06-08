// pages/index/index.js
var indexUrl = require('../../config').indexUrl;
var saveMemberUrl = require('../../config').saveMemberUrl;
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
  onLoad: function (options) {
    var that = this;
    console.log("页面加载获取token: ",app.globalData.token);
    if (app.globalData.userInfo) {
      let userInfo = app.globalData.userInfo;
      console.log("页面加载获取userInfo: ",app.globalData.userInfo);
      //更新数据
      that.setData({
        userInfo: userInfo
      });
      
    } else {
      //调用应用实例的方法获取全局数据
      app.getUserInfo(function (userInfo) {
        //更新数据
        that.setData({
          userInfo: userInfo
        });
        //app.showErrMsg("登录结果是："+wx.getStorageSync('token'));
        console.log("页面加载获取token: ",wx.getStorageSync('token'));
        if (app.globalData.token == '' && wx.getStorageSync('token')) {
          app.globalData.token = wx.getStorageSync('token');//更新全局数据     
          that.onShow();
        }
      });
    }
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
    //console.log("页面加载缓存token: ",wx.getStorageSync('token'));
    if (app.globalData.token == '' && wx.getStorageSync('token') == '') {
      //console.log("页面加载--全局token: ",app.globalData.token);
      app.globalData.token = wx.getStorageSync('token');//更新全局数据
      that.onLoad();
    } else {
      //console.log('店铺ID：',app.globalData.store_id);  
      //调用应用实例的方法获取全局数据
      wx.request({
        url: indexUrl,
        data: {
          token: app.globalData.token,
          store_id: app.globalData.store_id,
          member_id: app.globalData.member_id,
          seller_id: app.globalData.seller_id
        },
        method: 'POST',
        header: {
          'content-type': 'application/x-www-form-urlencoded'
        }, // 设置请求的 header
        success: function (res) {
          //console.log('请求首页时加载',res);
          let data = res.data;
          if (data.error_code) {
            app.showErrMsg(data.errMsg);
            //console.log(data)
            if (data.error_code == 100) {
              wx.showModal({
                title: '温馨提示',
                content: data.errMsg,
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
            }
          } else {

            that.setData(data.datas);
            let salenamearr = data.datas.saleman.saleman_name;
            let saleidarr = data.datas.saleman.saleman_id;
            let salemidarr = data.datas.saleman.saleman_member_id;
            that.setData({
              salenamearr: salenamearr,//导购名称组
              saleidarr: saleidarr//导购ID组
            });
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
            //app.showSucMsg('加载成功！');
          }
        },
        fail: function (err) {
          app.showErrMsg('加载失败！');
          that.onShow();
          console.log(err)
        }
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