let host = "https://demo.hzlwo.com/wxapp/";//小程序调用接口
const config = {
  API_HOST: "https://demo.hzlwo.com/wxapp/index.php",//小程序调用接口
  indexUrl: `${host}index.php?act=index&op=index`,
  loginUrl: `${host}login.php?act=login&op=member`,
  loginsellerUrl: `${host}index.php?act=login&op=seller`,
  goodsListUrl: `${host}index.php?act=seller_goods&op=goods_list`, //首页商品列表
  goodsFilterUrl: `${host}index.php?act=search&op=index`, //首页商品筛选排序
  goodsUrl: `${host}index.php?act=goods&op=index`,//商品信息页面
  //goodsFilterUrl: `${host}index.php?act=seller_filter_goods&op=goods_list`, //首页商品排序
  //saveMemberUrl: `${host}index.php?act=login&op=member`,

}
//初始值为空对象
module.exports = config;