let host = "https://demo.hzlwo.com/wxapp/";//小程序调用接口
const config = {
  API_HOST: "https://demo.hzlwo.com/wxapp/index.php",//小程序调用接口
  loginUrl: `${host}login.php?act=login&op=member`,
  loginsellerUrl: `${host}index.php?act=login&op=seller`,
  goodsListUrl: `${host}index.php?act=search&op=index`, //首页商品列表
  goodsFilterUrl: `${host}index.php?act=search&op=index`, //首页商品筛选排序
  goodsUrl: `${host}index.php?act=goods&op=index`,//商品信息页面
  goodsAdd: `${host}index.php?act=cart&op=index`, //商品下单页面
}
//初始值为空对象
module.exports = config;