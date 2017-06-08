var HOST_URI = 'https://demo.hzlwo.com/wxapp/';

var GET_TOPICS = '/topics';
var GET_TOPIC_BY_ID = '/topic/';

function obj2uri (obj) {
    return Object.keys(obj).map(function (k) {
        return encodeURIComponent(k) + '=' + encodeURIComponent(obj[k]);
    }).join('&');
}
//初始值为空对象
module.exports = {
    // 获取内容页数据
    getTopicByID: function (id, obj) {
        return HOST_URI + GET_TOPIC_BY_ID + id + '?' + obj2uri(obj);
    }
}