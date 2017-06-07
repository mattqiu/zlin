/*
 * jQuery Easing v1.3 - http://gsgd.co.uk/sandbox/jquery/easing/
 *
 * Uses the built in easing capabilities added In jQuery 1.1
 * to offer multiple easing options
 *
 * TERMS OF USE - jQuery Easing
 *
 * Open source under the BSD License.
 *
 * Copyright © 2008 George McGinley Smith
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 *
 * Redistributions of source code must retain the above copyright notice, this list of
 * conditions and the following disclaimer.
 * Redistributions in binary form must reproduce the above copyright notice, this list
 * of conditions and the following disclaimer in the documentation and/or other materials
 * provided with the distribution.
 *
 * Neither the name of the author nor the names of contributors may be used to endorse
 * or promote products derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 *  COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 *  EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE
 *  GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED
 * AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 *  NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED
 * OF THE POSSIBILITY OF SUCH DAMAGE.
 *
*/

// t: current time, b: begInnIng value, c: change In value, d: duration
jQuery.easing['jswing'] = jQuery.easing['swing'];

jQuery.extend( jQuery.easing,
{
	def: 'easeOutQuad',
	swing: function (x, t, b, c, d) {
		//alert(jQuery.easing.default);
		return jQuery.easing[jQuery.easing.def](x, t, b, c, d);
	},
	easeInQuad: function (x, t, b, c, d) {
		return c*(t/=d)*t + b;
	},
	easeOutQuad: function (x, t, b, c, d) {
		return -c *(t/=d)*(t-2) + b;
	},
	easeInOutQuad: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t + b;
		return -c/2 * ((--t)*(t-2) - 1) + b;
	},
	easeInCubic: function (x, t, b, c, d) {
		return c*(t/=d)*t*t + b;
	},
	easeOutCubic: function (x, t, b, c, d) {
		return c*((t=t/d-1)*t*t + 1) + b;
	},
	easeInOutCubic: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t*t + b;
		return c/2*((t-=2)*t*t + 2) + b;
	},
	easeInQuart: function (x, t, b, c, d) {
		return c*(t/=d)*t*t*t + b;
	},
	easeOutQuart: function (x, t, b, c, d) {
		return -c * ((t=t/d-1)*t*t*t - 1) + b;
	},
	easeInOutQuart: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t*t*t + b;
		return -c/2 * ((t-=2)*t*t*t - 2) + b;
	},
	easeInQuint: function (x, t, b, c, d) {
		return c*(t/=d)*t*t*t*t + b;
	},
	easeOutQuint: function (x, t, b, c, d) {
		return c*((t=t/d-1)*t*t*t*t + 1) + b;
	},
	easeInOutQuint: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t*t*t*t + b;
		return c/2*((t-=2)*t*t*t*t + 2) + b;
	},
	easeInSine: function (x, t, b, c, d) {
		return -c * Math.cos(t/d * (Math.PI/2)) + c + b;
	},
	easeOutSine: function (x, t, b, c, d) {
		return c * Math.sin(t/d * (Math.PI/2)) + b;
	},
	easeInOutSine: function (x, t, b, c, d) {
		return -c/2 * (Math.cos(Math.PI*t/d) - 1) + b;
	},
	easeInExpo: function (x, t, b, c, d) {
		return (t==0) ? b : c * Math.pow(2, 10 * (t/d - 1)) + b;
	},
	easeOutExpo: function (x, t, b, c, d) {
		return (t==d) ? b+c : c * (-Math.pow(2, -10 * t/d) + 1) + b;
	},
	easeInOutExpo: function (x, t, b, c, d) {
		if (t==0) return b;
		if (t==d) return b+c;
		if ((t/=d/2) < 1) return c/2 * Math.pow(2, 10 * (t - 1)) + b;
		return c/2 * (-Math.pow(2, -10 * --t) + 2) + b;
	},
	easeInCirc: function (x, t, b, c, d) {
		return -c * (Math.sqrt(1 - (t/=d)*t) - 1) + b;
	},
	easeOutCirc: function (x, t, b, c, d) {
		return c * Math.sqrt(1 - (t=t/d-1)*t) + b;
	},
	easeInOutCirc: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return -c/2 * (Math.sqrt(1 - t*t) - 1) + b;
		return c/2 * (Math.sqrt(1 - (t-=2)*t) + 1) + b;
	},
	easeInElastic: function (x, t, b, c, d) {
		var s=1.70158;var p=0;var a=c;
		if (t==0) return b;  if ((t/=d)==1) return b+c;  if (!p) p=d*.3;
		if (a < Math.abs(c)) { a=c; var s=p/4; }
		else var s = p/(2*Math.PI) * Math.asin (c/a);
		return -(a*Math.pow(2,10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )) + b;
	},
	easeOutElastic: function (x, t, b, c, d) {
		var s=1.70158;var p=0;var a=c;
		if (t==0) return b;  if ((t/=d)==1) return b+c;  if (!p) p=d*.3;
		if (a < Math.abs(c)) { a=c; var s=p/4; }
		else var s = p/(2*Math.PI) * Math.asin (c/a);
		return a*Math.pow(2,-10*t) * Math.sin( (t*d-s)*(2*Math.PI)/p ) + c + b;
	},
	easeInOutElastic: function (x, t, b, c, d) {
		var s=1.70158;var p=0;var a=c;
		if (t==0) return b;  if ((t/=d/2)==2) return b+c;  if (!p) p=d*(.3*1.5);
		if (a < Math.abs(c)) { a=c; var s=p/4; }
		else var s = p/(2*Math.PI) * Math.asin (c/a);
		if (t < 1) return -.5*(a*Math.pow(2,10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )) + b;
		return a*Math.pow(2,-10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )*.5 + c + b;
	},
	easeInBack: function (x, t, b, c, d, s) {
		if (s == undefined) s = 1.70158;
		return c*(t/=d)*t*((s+1)*t - s) + b;
	},
	easeOutBack: function (x, t, b, c, d, s) {
		if (s == undefined) s = 1.70158;
		return c*((t=t/d-1)*t*((s+1)*t + s) + 1) + b;
	},
	easeInOutBack: function (x, t, b, c, d, s) {
		if (s == undefined) s = 1.70158;
		if ((t/=d/2) < 1) return c/2*(t*t*(((s*=(1.525))+1)*t - s)) + b;
		return c/2*((t-=2)*t*(((s*=(1.525))+1)*t + s) + 2) + b;
	},
	easeInBounce: function (x, t, b, c, d) {
		return c - jQuery.easing.easeOutBounce (x, d-t, 0, c, d) + b;
	},
	easeOutBounce: function (x, t, b, c, d) {
		if ((t/=d) < (1/2.75)) {
			return c*(7.5625*t*t) + b;
		} else if (t < (2/2.75)) {
			return c*(7.5625*(t-=(1.5/2.75))*t + .75) + b;
		} else if (t < (2.5/2.75)) {
			return c*(7.5625*(t-=(2.25/2.75))*t + .9375) + b;
		} else {
			return c*(7.5625*(t-=(2.625/2.75))*t + .984375) + b;
		}
	},
	easeInOutBounce: function (x, t, b, c, d) {
		if (t < d/2) return jQuery.easing.easeInBounce (x, t*2, 0, c, d) * .5 + b;
		return jQuery.easing.easeOutBounce (x, t*2-d, 0, c, d) * .5 + c*.5 + b;
	}
});

/*
 *
 * TERMS OF USE - EASING EQUATIONS
 *
 * Open source under the BSD License.
 *
 * Copyright © 2001 Robert Penner
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 *
 * Redistributions of source code must retain the above copyright notice, this list of
 * conditions and the following disclaimer.
 * Redistributions in binary form must reproduce the above copyright notice, this list
 * of conditions and the following disclaimer in the documentation and/or other materials
 * provided with the distribution.
 *
 * Neither the name of the author nor the names of contributors may be used to endorse
 * or promote products derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 *  COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 *  EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE
 *  GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED
 * AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 *  NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED
 * OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 */

/*
 * LoadManager by Yasunobu Ikeda. April 20, 2010
 * Visit http://clockmaker.jp/ for documentation, updates and examples.
 *
 *
 * Copyright (c) 2010 Yasunobu Ikeda
 *
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation
 * files (the "Software"), to deal in the Software without
 * restriction, including without limitation the rights to use,
 * copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following
 * conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
 */

if(this.lmlib == undefined) {
	/**
	 * @namespace LoadManagerの内部でしかつかわないクラスのための名前空間です。
	 */
	lmlib = {};
}

/**
 * 読み込みアイテムのバリューオブジェクトです。
 * @param {String} url  URLです。
 * @param {Ojbect} extra 任意のObjectデータです。
 * @constructor
 * @author Yasunobu Ikeda ( http://clockmaker.jp )
 */
lmlib.LoadingData = function(url, extra) {
	/**
	 * URLです。
	 * @type String
	 */
	this.url = url;
	/**
	 * エキストラオブジェクト(何でも保持可能)です。
	 * @type Object
	 */
	this.extra = extra;

	/**
	 * Image オブジェクトです。
	 * @type Image
	 */
	this.image = null;
}
/**
 * LoadManager の完了イベントを定義したイベントクラスです。
 * ネイティブのEventクラスの継承ではありませんので注意ください。
 * @author Yasunobu Ikeda ( http://clockmaker.jp )
 * @constructor
 */
lmlib.CompleteEvent = function() {
}

lmlib.CompleteEvent.prototype = {
	/**
	 * イベントターゲットです。
	 * @type LoadManager
	 */
	target : null
};

/**
 * LoadManager のプログレスイベントを定義したイベントクラスです。
 * ネイティブのEventクラスの継承ではありませんので注意ください。
 * @constructor
 * @author Yasunobu Ikeda ( http://clockmaker.jp )
 */
lmlib.ProgressEvent = function() {
	/**
	 * Number of items already loaded
	 * @type Number
	 */
	this.itemsLoaded = 0;
	/**
	 * Number of items to be loaded
	 * @type Number
	 */
	this.itemsTotal = 0;
	/**
	 * The ratio (0-1) loaded (number of items loaded / number of items total)
	 * @type Number
	 */
	this.percent = 0;
	/**
	 * 読み込み処理に関するデータオブジェクトです。
	 * @type lmlib.LoadingData
	 */
	this.data = null;
	/**
	 * イベントターゲットです。
	 * @type LoadManager
	 */
	this.target = null;
}
/**
 * LoadManager のエラーイベントを定義したイベントクラスです。
 * ネイティブのEventクラスの継承ではありませんので注意ください。
 * @constructor
 * @author Yasunobu Ikeda ( http://clockmaker.jp )
 */
lmlib.ErrorEvent = function() {
	/**
	 * 読み込み処理に関するデータオブジェクトです。
	 * @type lmlib.LoadingData
	 */
	this.data = null;
	/**
	 * イベントターゲットです。
	 * @type LoadManager
	 */
	this.target = null;
}
/**
 * LoadManager は Image オブジェクトの読み込みを管理するクラスです。.
 * @version 2.0.0 alpha
 * @author Yasunobu Ikeda ( http://clockmaker.jp )
 * @constructor
 */
function LoadManager() {
	/** URLリスト */
	this._queueArr = [];
	this._registerArr = [];
	this._successArr = [];
	this._errorArr = [];
	this._isStarted = false;
	this._isRunning = false;
	this._isFinished = false;
	this._currentQueues = [];
	this._queueCount = 0;
	/**
	 * 同時接続数です。デフォルトは6です。
	 * @type Number
	 * @default 6
	 */
	this.numConnections = 6;

	/**
	 * プログレスイベント発生時に呼ばれるコールバックです。
	 * @params {lmlib.ProgressEvent} event ProgressEvent オブジェクトが第一引数で渡されます。
	 * @function
	 */
	this.onProgress = null;
	/**
	 * 処理完了時に呼ばれるコールバックです。
	 * @params {lmlib.CompleteEvent} event CompleteEvent オブジェクトが第一引数で渡されます。
	 * @function
	 */
	this.onComplete = null;
	/**
	 * エラー発生時に呼ばれるコールバックです。
	 * @params {lmlib.ErrorEvent} event  ErrorEvent オブジェクトが第一引数で渡されます。
	 * @function
	 */
	this.onError = null;
}

/**
 *  LogLevel: すべてのログを出力します。
 *  @type Number
 *  @constant
 */
LoadManager.LOG_VERBOSE = 0;
/**
 * LogLevel: ログは一切出力しません。
 * @type Number
 * @constant
 */
LoadManager.LOG_SILENT = 10;
/**
 * LogLevel: エラーのときだけログを出力します。
 * @type Number
 * @constant
 */
LoadManager.LOG_ERRORS = 4;

/**
 * スコープを移譲した関数を作成します。
 * @param {Function} func 実行したい関数
 * @param {Object} thisObj 移譲したいスコープ
 * @return {Function} 移譲済みの関数
 * @private
 */
LoadManager._delegate = function(func, thisObj) {
	var del = function() {
		return func.apply(thisObj, arguments);
	};
	//情報は関数のプロパティとして定義する
	del.func = func;
	del.thisObj = thisObj;
	return del;
};

LoadManager.prototype = {
	/**
	 * ログのレベルを取得または設定します。デフォルトは LOG_SILENT (出力なし) です。
	 * @type Number
	 */
	logLevel : LoadManager.LOG_SILENT,

	/**
	 * 処理が走っているかどうかを取得します。
	 * @return {Boolean}
	 */
	getIsRunning : function() {
		return this._isRunning;
	},
	/**
	 * 処理が完了しているかどうかを取得します。
	 * @return {Boolean}
	 */
	getIsFinished : function() {
		return this._isFinished;
	},
	/**
	 * 読み込みに成功したアイテムを配列として取得します。
	 * @return {Array} Array of LoadingData
	 */
	getSuccessItems : function() {
		return this._successArr;
	},
	/**
	 * 読み込みに失敗したアイテムを配列として取得します。
	 * @return {Array} Array of LoadingData
	 */
	getFailedItems : function() {
		return this._errorArr;
	},
	/**
	 * 処理の進行度を0～1の値で取得します。 (either IO Error).
	 * @return {Number}
	 */
	getPercent : function() {
		var percent = (this._successArr.length + this._errorArr.length) / this._registerArr.length;
		percent = Math.min(1, Math.max(0, percent));
		return percent;
	},
	/**
	 * 読み込みたいアセットを登録します。
	 * @params {String} url	URLです。
	 * @params {Object} extra	任意の Object データです。
	 * @return {lmlib.LoadingData} 読み込みアイテムの情報です。
	 */
	add : function(url, extra) {
		if(this._isStarted) {
			this._log("既にLoadManagerインスタンスがstartしているため、add()することができません。", LoadManager.LOG_ERRORS);
		}

		var item = new lmlib.LoadingData(url, extra);
		this._registerArr.push(item);
		return item;
	},
	/**
	 * URLをキーとして読み込み済みアセットを取得します。
	 * なるべく読み込み完了後(onCompete発生後)に使ってください。
	 * @param {String} url	URLです。このURLと一致するアセットを返します。
	 * @return {lmlib.LoadingData}	読み込み情報を保持したデータです。
	 */
	get : function(url) {
		for(var i = 0; i < this._registerArr.length; i++) {
			if(this._registerArr[i].url == url) {
				return this._registerArr[i];
			}
		}
		return null;
	},
	/**
	 * 読み込み処理を開始します。
	 * @param {Number} withConnections (default = null) — [optional] 同時読み込み数です。
	 */
	start : function(withConnections) {
		if(this._isStarted) {
			throw "既にLoadManagerインスタンスがstartしているため、start()することができません。";
		}

		if( typeof (withConnections) == "number") {
			this.numConnections = withConnections;
		}

		this._queueArr = this._registerArr.concat();
		this._execute(this._queueArr[0]);
		this._isStarted = true;
	},
	_loadNextImage : function() {
		while(this.numConnections > this._queueCount) {
			if(this._queueArr.length == 0)
				break;

			var nextQueue = this._queueArr.shift();

			// Retrieve next filename in queue
			this._currentQueues.push(nextQueue);

			this._execute(nextQueue);

			this._queueCount++;
		}

		// Busy loading
		this._isRunning = true;
	},
	_execute : function(loadingData) {
		if(loadingData.image == null)
			loadingData.image = new Image();

		var self = this;

		// Already loaded
		loadingData.image.onload = function() {
			self._log("[LM - Success] " + loadingData.url, LoadManager.LOG_VERBOSE);

			self._successArr.push(loadingData);

			if( typeof (loadingData.callbackFunc) == "function") {
				loadingData.callbackFunc(loadingData.image);
			}

			// Remove from queue
			self._queueCount--;

			// dispatch Event
			if( typeof (self.onProgress) == "function") {
				var event = new lmlib.ProgressEvent();
				event.target = self;
				event.itemsLoaded = self._successArr.length;
				event.itemTotal = self._registerArr.length;
				event.percent = self.getPercent();
				event.data = loadingData;
				self.onProgress(event);
			}

			// Continue loading
			if(!self._checkFinished())
				self._delayFunc();
		};

		loadingData.image.onerror = function() {
			self._log("[LM - Error] " + loadingData.url, LoadManager.LOG_VERBOSE);

			// Remove from queue
			self._errorArr.push(loadingData);

			// Remove from queue
			self._queueCount--;

			// dispatch Event
			if( typeof (self.onError) == "function") {
				var event = new lmlib.ErrorEvent();
				event.target = this;
				event.data = loadingData;
				self.onError(event);
			}

			// Continue loading
			if(!self._checkFinished())
				self._delayFunc();
		};

		loadingData.image.src = loadingData.url;
	},
	_checkFinished : function() {
		// Queue finished?
		if(this._successArr.length + this._errorArr.length == this._registerArr.length) {
			// Loading finished
			this._isRunning = false;
			this._isFinished = true;

			// dispatch Event
			if( typeof (this.onComplete) == "function") {
				var event = new lmlib.CompleteEvent();
				event.target = this;
				this.onComplete(event);
			}

			return true;
		}
		return false;
	},
	/** delay loading for stack over flow error of IE6 */
	_delayFunc : function() {
		setTimeout(LoadManager._delegate(this._loadNextImage, this), 16);
	},
	_log : function(object, level) {
		// if(this.logLevel <= level)
			// console.log(object);
	}
};
window.LoadManager = LoadManager;

/*! Lazy Load 1.9.3 - MIT license - Copyright 2010-2013 Mika Tuupola */
!function(a,b,c,d){var e=a(b);a.fn.lazyload=function(f){function g(){var b=0;i.each(function(){var c=a(this);if(!j.skip_invisible||c.is(":visible"))if(a.abovethetop(this,j)||a.leftofbegin(this,j));else if(a.belowthefold(this,j)||a.rightoffold(this,j)){if(++b>j.failure_limit)return!1}else c.trigger("appear"),b=0})}var h,i=this,j={threshold:0,failure_limit:0,event:"scroll",effect:"show",container:b,data_attribute:"original",skip_invisible:!0,appear:null,load:null,placeholder:"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC"};return f&&(d!==f.failurelimit&&(f.failure_limit=f.failurelimit,delete f.failurelimit),d!==f.effectspeed&&(f.effect_speed=f.effectspeed,delete f.effectspeed),a.extend(j,f)),h=j.container===d||j.container===b?e:a(j.container),0===j.event.indexOf("scroll")&&h.bind(j.event,function(){return g()}),this.each(function(){var b=this,c=a(b);b.loaded=!1,(c.attr("src")===d||c.attr("src")===!1)&&c.is("img")&&c.attr("src",j.placeholder),c.one("appear",function(){if(!this.loaded){if(j.appear){var d=i.length;j.appear.call(b,d,j)}a("<img />").bind("load",function(){var d=c.attr("data-"+j.data_attribute);c.hide(),c.is("img")?c.attr("src",d):c.css("background-image","url('"+d+"')"),c[j.effect](j.effect_speed),b.loaded=!0;var e=a.grep(i,function(a){return!a.loaded});if(i=a(e),j.load){var f=i.length;j.load.call(b,f,j)}}).attr("src",c.attr("data-"+j.data_attribute))}}),0!==j.event.indexOf("scroll")&&c.bind(j.event,function(){b.loaded||c.trigger("appear")})}),e.bind("resize",function(){g()}),/(?:iphone|ipod|ipad).*os 5/gi.test(navigator.appVersion)&&e.bind("pageshow",function(b){b.originalEvent&&b.originalEvent.persisted&&i.each(function(){a(this).trigger("appear")})}),a(c).ready(function(){g()}),this},a.belowthefold=function(c,f){var g;return g=f.container===d||f.container===b?(b.innerHeight?b.innerHeight:e.height())+e.scrollTop():a(f.container).offset().top+a(f.container).height(),g<=a(c).offset().top-f.threshold},a.rightoffold=function(c,f){var g;return g=f.container===d||f.container===b?e.width()+e.scrollLeft():a(f.container).offset().left+a(f.container).width(),g<=a(c).offset().left-f.threshold},a.abovethetop=function(c,f){var g;return g=f.container===d||f.container===b?e.scrollTop():a(f.container).offset().top,g>=a(c).offset().top+f.threshold+a(c).height()},a.leftofbegin=function(c,f){var g;return g=f.container===d||f.container===b?e.scrollLeft():a(f.container).offset().left,g>=a(c).offset().left+f.threshold+a(c).width()},a.inviewport=function(b,c){return!(a.rightoffold(b,c)||a.leftofbegin(b,c)||a.belowthefold(b,c)||a.abovethetop(b,c))},a.extend(a.expr[":"],{"below-the-fold":function(b){return a.belowthefold(b,{threshold:0})},"above-the-top":function(b){return!a.belowthefold(b,{threshold:0})},"right-of-screen":function(b){return a.rightoffold(b,{threshold:0})},"left-of-screen":function(b){return!a.rightoffold(b,{threshold:0})},"in-viewport":function(b){return a.inviewport(b,{threshold:0})},"above-the-fold":function(b){return!a.belowthefold(b,{threshold:0})},"right-of-fold":function(b){return a.rightoffold(b,{threshold:0})},"left-of-fold":function(b){return!a.rightoffold(b,{threshold:0})}})}(jQuery,window,document);

/* Information
----------------------------------------------
File Name : smoothscroll.js
URL : http://www.atokala.com/
Copyright : (C)atokala
Author : Masahiro Abe
--------------------------------------------*/
var ATScroll = function(vars) {
	//コンストラクタ
	var _self = this;
	var _timer;

	//デフォルトオプション
	var options = {
		noScroll : 'noSmoothScroll',
		setHash : false,
		duration : 800,
		interval : 10,
		animation : 'liner',
		callback : function(){}
	};

	//オプションの上書き設定
	this.config = function(property) {
		for (var i in property) {
			//設定されていない時は上書きしない
			if (!vars.hasOwnProperty(i)) {
				continue;
			}
			options[i] = property[i];
		}
	}

	//ブラウザチェック
	var browser = {
		ua : function() {
			return navigator.userAgent;
		},
		//IE
		ie : function() {
			return browser.ua.indexOf('MSIE') >= 0;
		},
		//IE6
		ie6 : function() {
			return browser.ua.indexOf('MSIE 6') >= 0;
		},
		//標準モード
		ieStandard : function() {
			return (document.compatMode && document.compatMode == 'CSS1Compat');
		}
	};

	//スクロール位置の取得
	var scroll = {
		top : function() {
			return (document.documentElement.scrollTop || document.body.scrollTop);
		},
		left : function() {
			return (document.documentElement.scrollLeft || document.body.scrollLeft);
		},
		width : function() {
			if (browser.ie && !browser.ieStandard) {
				return document.body.scrollWidth;
			}
			//モダンブラウザ
			else {
				return document.documentElement.scrollWidth;
			}
		},
		height : function() {
			if (browser.ie && !browser.ieStandard) {
				return document.body.scrollHeight;
			}
			//モダンブラウザ
			else {
				return document.documentElement.scrollHeight;
			}
		}
	};

	//ウインドウのサイズ取得
	var inner = {
		width : function() {
			//モダン
			if (window.innerWidth) {
				return window.innerWidth;
			}
			//IE
			else if (browser.ie) {
				//IE6 && 標準モード
				if (browser.ie6 && browser.ieStandard) {
					return document.documentElement.clientWidth;
				}
				//IE6互換モード && 他IE
				else {
					//IE6以下
					if (!document.documentElement.clientWidth) {
						return document.body.clientWidth;
					}
					//IE6以上
					else {
						return document.documentElement.clientWidth;
					}
				}
			}
		},
		height : function() {
			//モダン
			if (window.innerHeight) {
				return window.innerHeight;
			}
			//IE
			else if (browser.ie) {
				//IE6 && 標準モード
				if (browser.ie6 && browser.ieStandard) {
					return document.documentElement.clientHeight;
				}
				//IE6互換モード && 他IE
				else {
					//IE6以下
					if (!document.documentElement.clientHeight) {
						return document.body.clientHeight;
					}
					//IE6以上
					else {
						return document.documentElement.clientHeight;
					}
				}
			}
		}
	};

	//オブジェクト位置の取得
	this.getElementPosition = function(ele) {
		var obj = new Object();
		obj.x = 0;
		obj.y = 0;

		while(ele) {
			obj.x += ele.offsetLeft || 0;
			obj.y += ele.offsetTop || 0;
			ele = ele.offsetParent;
		}
		return obj;
	}


	//イベント追加
	this.addEvent = function(eventTarget, eventName, func) {
		// モダンブラウザ
		if(eventTarget.addEventListener) {
			eventTarget.addEventListener(eventName, func, false);
		}
		// IE
		else if(window.attachEvent) {
			eventTarget.attachEvent('on'+eventName, function(){func.apply(eventTarget);});
		}
	}

	//イベントキャンセル
	this.eventCancel = function(e) {
		//for IE
		if (!e) e = window.event;

		if (e.preventDefault) {
			e.preventDefault()
		}
		else{
			e.returnValue = false;
		}
	}

	this.setSmoothScrollY = function(e) {
		_self.eventCancel(e);
		clearTimeout(_timer);
		var hash = this.hash;
		var idName = hash.replace('#', '');
		var targetId = document.getElementById(idName);

		//var toX = _self.getElementPosition(targetId).x;
		var toY = _self.getElementPosition(targetId).y;

		//リンク先が範囲外時
		var limitH = scroll.height() - inner.height();
		if (limitH < toY) {
			toY = limitH;
		}
		if (toY < 0) {
			toY = 0;
		}

		if (options.setHash) {
			options.callback = function(){
				window.location.hash = hash;
			}
		}
		_self.scroll(toY);
	}

	var easing = {
		/*
		time = 現在秒 (現在
		begin = 最初の値
		change = 変動する値
		duration = 何秒かけて動くか
		*/
		liner : function(t, b, c, d) {
			return c * t / d + b;
		},
		quinticIn : function(t, b, c, d) {
			t /= d;
			return c * t * t * t * t * t + b;
		},
		quinticOut : function(t, b, c, d) {
			t /= d;
			t = t - 1;
			return -c * (t * t * t * t - 1) + b;
		}
	};

	this.scroll = function(toY) {
		var now = new Date();
		var fromY = scroll.top();
		var run = function() {
			var time = new Date() - now;
			var next = easing[options.animation](time, fromY, toY - fromY, options.duration);

			if (time < options.duration - options.interval) {
				window.scrollTo(scroll.left(), parseInt(next));
				_timer = setTimeout(function(){run()}, options.interval);
			}
			else {
				clearTimeout(_timer);
				window.scrollTo(scroll.left(), parseInt(toY));
				options.callback();
			}
		}
		run();
	}

	this.load = function() {
		//コンストラクタ
		this.config(vars);

		this.addEvent(window, 'load', function() {
			var allLinks = document.links;

			//ページ内リンク
			for (var i = 0; i < allLinks.length; i++) {
				var a = allLinks[i]
				var hash = a.href.split('#')[1];

				if (a.className.indexOf(options.noScroll) >= 0) {
					continue;
				}
				if (a.href.match('#') && document.getElementById(hash)) {
					_self.addEvent(a, 'click', _self.setSmoothScrollY);
				}
			}
		});
	}
};


/*!
 * jQuery Cookie Plugin v1.4.0
 * https://github.com/carhartl/jquery-cookie
 *
 * Copyright 2013 Klaus Hartl
 * Released under the MIT license
 */
(function (factory) {
	if (typeof define === 'function' && define.amd) {
		// AMD
		define(['jquery'], factory);
	} else if (typeof exports === 'object') {
		// CommonJS
		factory(require('jquery'));
	} else {
		// Browser globals
		factory(jQuery);
	}
}(function ($) {

	var pluses = /\+/g;

	function encode(s) {
		return config.raw ? s : encodeURIComponent(s);
	}

	function decode(s) {
		return config.raw ? s : decodeURIComponent(s);
	}

	function stringifyCookieValue(value) {
		return encode(config.json ? JSON.stringify(value) : String(value));
	}

	function parseCookieValue(s) {
		if (s.indexOf('"') === 0) {
			// This is a quoted cookie as according to RFC2068, unescape...
			s = s.slice(1, -1).replace(/\\"/g, '"').replace(/\\\\/g, '\\');
		}

		try {
			// Replace server-side written pluses with spaces.
			// If we can't decode the cookie, ignore it, it's unusable.
			// If we can't parse the cookie, ignore it, it's unusable.
			s = decodeURIComponent(s.replace(pluses, ' '));
			return config.json ? JSON.parse(s) : s;
		} catch(e) {}
	}

	function read(s, converter) {
		var value = config.raw ? s : parseCookieValue(s);
		return $.isFunction(converter) ? converter(value) : value;
	}

	var config = $.cookie = function (key, value, options) {

		// Write

		if (value !== undefined && !$.isFunction(value)) {
			options = $.extend({}, config.defaults, options);

			if (typeof options.expires === 'number') {
				var days = options.expires, t = options.expires = new Date();
				t.setTime(+t + days * 864e+5);
			}

			return (document.cookie = [
				encode(key), '=', stringifyCookieValue(value),
				options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
				options.path    ? '; path=' + options.path : '',
				options.domain  ? '; domain=' + options.domain : '',
				options.secure  ? '; secure' : ''
			].join(''));
		}

		// Read

		var result = key ? undefined : {};

		// To prevent the for loop in the first place assign an empty array
		// in case there are no cookies at all. Also prevents odd result when
		// calling $.cookie().
		var cookies = document.cookie ? document.cookie.split('; ') : [];

		for (var i = 0, l = cookies.length; i < l; i++) {
			var parts = cookies[i].split('=');
			var name = decode(parts.shift());
			var cookie = parts.join('=');

			if (key && key === name) {
				// If second argument (value) is a function it's a converter...
				result = read(cookie, value);
				break;
			}

			// Prevent storing a cookie that we couldn't decode.
			if (!key && (cookie = read(cookie)) !== undefined) {
				result[name] = cookie;
			}
		}

		return result;
	};

	config.defaults = {};

	$.removeCookie = function (key, options) {
		if ($.cookie(key) === undefined) {
			return false;
		}

		// Must not alter options, thus extending a fresh object...
		$.cookie(key, '', $.extend({}, options, { expires: -1 }));
		return !$.cookie(key);
	};

}));
