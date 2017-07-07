// pages/goods/checkin.js
var util = require('../../utils/util.js');
var app = new getApp();
Page({
  data:{
    goods_commonid:'',
    goodsNum: 0,//定义商品数量
    goodsTotal: 0,//商品总数
    totalAmount: 0.00,//总金额
    optRadio: {},//单选框
    optSpec: {},//选中商品规格
    specsku: {},//组合数据
  },
  //单选或多选规格
  tapRadioSpec: function(e){
    let curData = e.currentTarget.dataset,
      spid = curData.spid,
      radioType = curData.type,
      checked = curData.checked ? curData.checked : '',
      _optRadio = this.data.optRadio;
    _optRadio[spid] = radioType;
    let _optSpec = this.data.optSpec,
      goods_spec = this.data.goods_spec,
      _objSpec = goods_spec[spid];
    let _specKey = Object.keys(_objSpec);
    for (let i = 0; i < _specKey.length; i++) {
      let _obj_sp_v = _objSpec[_specKey[i]];
      //如果该规格时全选模式
      if (radioType == 'all') {
        if (checked){
          _optRadio[spid] = 'more';
          goods_spec[spid][_obj_sp_v.sp_v_id]['checked'] = '';
          _optSpec[spid][_obj_sp_v.sp_v_id] = { checked: '' };
        }else{
          goods_spec[spid][_obj_sp_v.sp_v_id]['checked'] = 'true';
          _optSpec[spid][_obj_sp_v.sp_v_id] = { checked: 'true' };
        }
      } else if (radioType == 'one' || radioType == '') {
        _optSpec[spid][_obj_sp_v.sp_v_id]['checked'] = '';
        goods_spec[spid][_obj_sp_v.sp_v_id]['checked'] = ''//先取消当前规格下，其他选中的规格
      }
    }
    
    this.setData({
      goodsNum: 0,
      goods_spec: goods_spec,
      optSpec: _optSpec,
      optRadio: _optRadio
    });
    this.setSpecSku();
  },
  //选择商品具体规格
  tapOptSpec: function (e) {
    var that = this;
    let curData = e.currentTarget.dataset,
      spid = curData.spid,
      svid = curData.svid,
      sp_v_name = curData.svname,
      checked = curData.checked ? curData.checked : '',
      _optRadio = that.data.optRadio;
    var radioType = _optRadio[spid];
    let _optSpec = that.data.optSpec,
      goods_spec = that.data.goods_spec,
      _objSpec = goods_spec[spid];
    //console.log('获取当前信息: ', curData);
    let _optSpecKey = Object.keys(_optSpec),//选中商品规格数
      _optSpecLen = _optSpecKey.length,
      _specKey = Object.keys(_objSpec);
    for (let i = 0; i < _specKey.length; i++) {
      let _obj_sp_v = _objSpec[_specKey[i]];
      //console.log('规格子集: ', _obj_sp_v);
      if (_obj_sp_v.sp_v_id == svid) {
        goods_spec[spid][svid]['checked'] = checked == '' ? 'true' : ''
        if (radioType == 'all'){
          _optRadio[spid] = 'more';
        }
      }else{
        //如果该规格时单选模式
        if (radioType == 'one' || radioType == '') {
          _optSpec[spid][_obj_sp_v.sp_v_id] = { checked: ''}; //单选时取消选项
          goods_spec[spid][_obj_sp_v.sp_v_id]['checked'] = ''//先取消当前规格下，其他选中的规格
        }
      }
    }
    //记录已经勾选的数据
    if (checked) {
      _optSpec[spid][svid] = { checked: '' }; //单选时取消选项
      goods_spec[spid][svid]['checked'] = '';
    } else {
      _optSpec[spid][svid] = { sp_v_id: svid, sp_v_name: sp_v_name, checked: 'true'};
    }
    //console.log('重组的规格: ', _optSpec);
    //console.log('重新整合的规格: ', goods_spec);
    that.setData({
      goods_spec: goods_spec,
      optSpec: _optSpec,
      optRadio: _optRadio
    });
    that.setSpecSku();
  },
  //组合规格生成sku
  setSpecSku: function(){
    let _specsku = this.data.specsku,
        _optSpec = this.data.optSpec,
        _selectedKey = Object.keys(_optSpec);
    let isEmptySku = false;
    let keyIndex = 0;
    let _tempNo = [],//取消数组
      _temp = []; // [[id_id, name1, name2, ...]]
    /* 以下大致意思：是根据规格的数组长度有关，如：有颜色、尺码，那么数据就有两次，如果有三个属性值，就会出现id_id2_id3*/
    while(keyIndex<_selectedKey.length && !isEmptySku){
      let curObj = _optSpec[_selectedKey[keyIndex]],
          curObjKeys = Object.keys(curObj),
          curObjKeysLen = curObjKeys.length,
          _tempLen = _temp.length,
          _tempNoLen = _tempNo.length;
      // 当前组没有选择项 则不构成组合
      if(curObjKeysLen == 0){
        isEmptySku = true;
        break;
      }
      // 第一个初始化零时数组数据
      if(keyIndex == 0){
        for(let i=0; i<curObjKeysLen; i++){
          let obj = curObj[curObjKeys[i]]
          if (obj.checked){
            _temp.push([curObjKeys[i], obj.sp_v_name])//组合数组
          }else{
            _tempNo.push([curObjKeys[i], obj.sp_v_name]);//取消组合
          }
        }
        //console.log('第一个初始化零时数组: ', _temp);
      }else{
        // 拼接sku
        // [id_id, name1, name2]
        let arr = [];
        for(let i=0; i<_tempLen; i++){
          let curTemp = _temp[i],
            curTempId = curTemp[0],
            curTempName = curTemp[1];
          let spv_arr = [];
          spv_arr[curTempId] = curTempName;
          //console.log('选中规格拼接sku:', curTemp)
          for(let j=0; j<curObjKeysLen; j++){
            let objKey = curObjKeys[j],
              spvName = curObj[objKey].sp_v_name,
              spvchecked = curObj[objKey].checked;
              spv_arr[objKey] = spvName;
            let _arr = [],
              skukey = '';
            if (curTempId > objKey){
              skukey = objKey + '|' + curTempId;
            }else{
              skukey = curTempId + '|' + objKey;
            }
            _arr.push(skukey);
            _arr.push(spvchecked);
            _arr = _arr.concat(curTemp.slice(1));
            _arr.push(spvName);
            //console.log('凭借_arr:', _arr)
            arr.push(_arr)
          }
        }

        //取消部分
        for (let i = 0; i < _tempNoLen; i++) {
          let curTempNo = _tempNo[i],
            curTempNoId = curTempNo[0],
            curTempNoName = curTempNo[1];
          let spv_arr = [];
          spv_arr[curTempNoId] = curTempNoName;
          //console.log('选中规格拼接sku:', curTemp)
          for (let j = 0; j < curObjKeysLen; j++) {
            let objKey = curObjKeys[j],
              spvName = curObj[objKey].sp_v_name;
            spv_arr[objKey] = spvName;
            let _arrNo = [],
              skukey = '';
            if (curTempNoId > objKey) {
              skukey = objKey + '|' + curTempNoId;
            } else {
              skukey = curTempNoId + '|' + objKey;
            }
            _arrNo.push(skukey);
            _arrNo.push('');
            _arrNo = _arrNo.concat(curTempNo.slice(1));
            _arrNo.push(spvName);
            arr.push(_arrNo)
          }
        }
        _temp = arr;
      }
      keyIndex++;
    }
    //console.log('选中数组: ', _temp);
    //console.log('取消数组: ', _tempNo);
    let _tempSku = _specsku;
    //console.log('原_optSpec:', _optSpec)
    //console.log('原_specsku:', _specsku)
    if(!isEmptySku){ //选项滞空
      _temp.forEach(function(item){
        let _key = item[0],
          _checked = item[1],//是否选中
          spvnArr = item.slice(2),//规格组合名称数组
          spvidstrs = _key.split("|"), //字符分割 ,获取规格ID 
          //color_id = 0,//暂时用不上
          spv_arr = [],
          gnum = 0;
        //console.log('原_skukey:', _key)
        if (_specsku[_key]){
          gnum = _specsku[_key].gnum;//商品数量

        }        
        for (let i = 0; i < spvidstrs.length; i++) {
          //color_id = spvidstrs[0];
          spv_arr.push({
            sp_v_id: spvidstrs[i],
            sp_v_name: spvnArr[i]
          })
        }
        _tempSku[_key] = { gnum: gnum,checked:_checked, sp_value: spv_arr};
      })      
      //console.log('选项滞空: ', _temp);
    }
    
    this.setData({
      goodsNum: 0,
      specsku: _tempSku,
    })
    //console.log('拼接成功后的sku:', this.data.specsku)
  },
  //增加商品数量
  tapPlusGnum:function(e){
    var that = this;
    let  _specsku = that.data.specsku,
      _goodsTotal = Number(that.data.goodsTotal),//商品总数
      _totalAmount = Number(that.data.totalAmount),//商品总金额
      goodsNum = Number(that.data.goodsNum);
    //console.log('sku:', _specsku)
    if (app.common.judgeNull(_specsku)){
      app.showErrMsg('请先选择颜色或尺码！');
      return;
    } else {      
      if (goodsNum < 99999){
        goodsNum = goodsNum + 1;
        let
          _skuList = that.data.sku_list,//获取商品价格
          specskuKeys = Object.keys(_specsku),
          specskuKeysLen = specskuKeys.length;
        for (let i = 0; i < specskuKeysLen; i++) {
          let curObj = _specsku[specskuKeys[i]];
          //console.log('curObj:', curObj)
          if (curObj.checked){//选中的数量添加
            let gnum = Number(curObj.gnum) + 1;
            _specsku[specskuKeys[i]].gnum = gnum;
            _goodsTotal = _goodsTotal + 1;
            //计算金额
            let goods_price = _skuList[specskuKeys[i]].goods_price;
            _totalAmount = _totalAmount + 1 * goods_price;
          }
        }

        that.setData({
          specsku: _specsku,
          goodsNum: goodsNum,
          goodsTotal: _goodsTotal,
          totalAmount: _totalAmount
        });
      } else {
        app.showErrMsg('商品数量不可大于五位数');
        return;
      }
    }
  },
  //减少商品数量
  tapMinusGnum: function (e) {
    var that = this;
    let _goodsTotal = Number(that.data.goodsTotal),//商品总数
      _totalAmount = Number(that.data.totalAmount),//商品总金额
      goodsNum = Number(that.data.goodsNum);
    if (goodsNum > 0){
      goodsNum = goodsNum - 1;
      let _specsku = that.data.specsku,
        _skuList = that.data.sku_list,//获取商品价格
        specskuKeys = Object.keys(_specsku),
        specskuKeysLen = specskuKeys.length;
      for (let i = 0; i < specskuKeysLen; i++) {
        let curObj = _specsku[specskuKeys[i]];
        //console.log('curObj:', curObj)
        if (curObj.checked) {//选中的数量添加
          let gnum = Number(curObj.gnum) - 1;
          _specsku[specskuKeys[i]].gnum = gnum;
          _goodsTotal = _goodsTotal - 1;
          //计算金额
          let goods_price = _skuList[specskuKeys[i]].goods_price;
          _totalAmount = _totalAmount - 1 * goods_price;
        }
      }

      that.setData({
        specsku: _specsku,
        goodsNum: goodsNum,
        goodsTotal: _goodsTotal
      });
    }else{
      app.showErrMsg('商品数量不可为负数');
      return;
    }    
  },
  //修改SKU数量事件
  inputSkuNum:function(e){
    var that = this;
    let _skunum = Number(e.detail.value),
      curData = e.currentTarget.dataset,
      _skuid = curData.skuid,
      _specsku = that.data.specsku, 
      _skuList = that.data.sku_list,//获取商品价格
      _goodsTotal = Number(that.data.goodsTotal),//商品总数
      _totalAmount = Number(that.data.totalAmount),//商品总金额
      goodsNum = Number(that.data.goodsNum);
    
    if (_skunum < 99999) {
      if (app.common.judgeNull(_specsku)) {
        _specsku[_skuid] = { gnum: _skunum, checked :'true'};
        _goodsTotal = _goodsTotal + _skunum;
        //计算金额
        let goods_price = _skuList[_skuid].goods_price;
        _totalAmount = _totalAmount + _skunum * goods_price;
      } else {
        let specskuKeys = Object.keys(_specsku),
          specskuKeysLen = specskuKeys.length;
        console.log('specskuKeys:', specskuKeys);
        for (let i = 0; i < specskuKeysLen; i++) {
          let curObj = _specsku[specskuKeys[i]];
          if (_skuid == specskuKeys[i]) {//选中的数量添加
            let gnum = Number(curObj.gnum);
            _specsku[specskuKeys[i]].gnum = _skunum;
            _specsku[specskuKeys[i]].checked = 'true';
            _goodsTotal = _goodsTotal + (_skunum - gnum);
            //计算金额
            let goods_price = _skuList[specskuKeys[i]].goods_price;
            _totalAmount = _totalAmount + (_skunum - gnum) * goods_price;
          }
        }
      }
      that.setData({
        specsku: _specsku,
        goodsNum: _skunum,
        goodsTotal: _goodsTotal,
        totalAmount: _totalAmount
      });
    } else {
      app.showErrMsg('商品数量不可大于五位数');
      return;
    }
    
  },
  //修改当前选中商品数量事件
  inputGoodsNum: function (e) {
    var that = this;
    let _gnumval = Number(e.detail.value),
      _specsku = that.data.specsku,
      _goodsTotal = Number(that.data.goodsTotal),//商品总数
      _totalAmount = Number(that.data.totalAmount);
    //console.log('sku:', _specsku)
    if (app.common.judgeNull(_specsku)) {
      app.showErrMsg('请先选择颜色或尺码！');
      return;
    } else {
      if (_gnumval < 99999) {
        let _skuList = that.data.sku_list,//获取商品价格
          specskuKeys = Object.keys(_specsku),
          specskuKeysLen = specskuKeys.length;
        for (let i = 0; i < specskuKeysLen; i++) {
          let curObj = _specsku[specskuKeys[i]];
          //console.log('curObj:', curObj)
          if (curObj.checked) {//选中的数量添加
            let gnum = Number(curObj.gnum) + _gnumval;
            _specsku[specskuKeys[i]].gnum = gnum;
            _goodsTotal = _goodsTotal + _gnumval;
            //计算金额
            let goods_price = _skuList[specskuKeys[i]].goods_price;
            _totalAmount = _totalAmount + _gnumval * goods_price;
          }
        }

        that.setData({
          specsku: _specsku,
          goodsNum: _gnumval,
          goodsTotal: _goodsTotal,
          totalAmount: _totalAmount
        });
      } else if (goodsNum > 0){
        app.showErrMsg('商品数量不可为负数');
        return;
      }else{
        app.showErrMsg('商品数量不可大于五位数');
        return;
      }
    }
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
    var that = this;
    var data = {
      token: app.globalData.token,
      goods_commonid: options.goods_commonid,
    };

    util.Ajax("ordering_goods/goods_detail", data, function (res) {
      let spec_name = res.datas.spec_name,
        _sData = {},
        _optRadio = that.data.optRadio;
      let i = 0,
        _specKey = Object.keys(spec_name);
      while (i < _specKey.length) {
        _sData[_specKey[i]] = {};//需要定义
        _optRadio[_specKey[i]] = 'one';//默认单选框
        i++;
      }
      console.log("a结果：", res);
      that.setData({
        goods_info: res.datas,
        goods_spec: res.datas.spec_value, //商品规格
        sku_list: res.datas.spec_list, //商品sku
        optRadio: _optRadio,
        optSpec: _sData
      })
    });
  },
  btn_submit:function(e){
    var that = this;
    var data ={
      list:[
        {
          goods_id: '55',
          quantity: '78',
        },
        {
          goods_id: '56',
          quantity: '105',
        },
        {
          goods_id: '57',
          quantity: '125',
        },
        {
          goods_id: '58',
          quantity: '135',
        }
      ],
      token: app.globalData.token,
      goods_commonid: '100008',
      goods_name: 'goods_name',
      goods_price: '600',
      /*member_id: app.globalData.member_id,
      store_id: app.globalData.store_id*/
      buyer_id: '5',
      store_id: '1',
      store_name: 'E.music',
      gooods_image:'http://demo.hzlwo.com/data/upload/shop/common/default_goods_image.gif'
    };
    util.Ajax("member_cart/cart_add",data,function(options){
      console.log("购物城返回结果：",options);
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