/**
 * 移动端富文本编辑器
 * @author ganzw@gmail.com
 * @url    https://github.com/baixuexiyang/artEditor
 */
$.fn.extend({
    _opt: {placeholader: "<p>请输入文章正文内容</p>", validHtml: [], limitSize: 3, showServer: !1},
    artEditor: function (e) {
        var t = this, a = {
            "-webkit-user-select": "text",
            "user-select": "text",
            "overflow-y": "auto",
            "text-break": "brak-all",
            outline: "none"
        };
        $(this).css(a).attr("contenteditable", !0), t._opt = $.extend(t._opt, e);
        try {

            if (t._opt.showServer){
            $(t._opt.imgTar).fileupload({

                dataType: 'json',
                url:t._opt.uploadUrl,
                formData: {name: 'add_album'},
                add: function (e,data) {
                    data.submit();
                },
                success:function (e) {
                    var a = t._opt.uploadSuccess(e);
                    if(e){
                        var o = '<img src="' +e.thumb_name+ '" style="max-width:100%;" />';
                        t.insertImage(o)
                    }else{t._opt.uploadError(e)}
                }

            }), t.placeholderHandler(), t.pasteHandler()
            }else{
                $(t._opt.imgTar).on("change", function (e) {
                    var a = e.target.files[0];
                    if (Math.ceil(a.size / 1024 / 1024) > t._opt.limitSize)return void console.error("文件太大");
                    var reader = new FileReader();
                    reader.readAsDataURL(a);
                    reader.onload = function (e) {
                        if (t._opt.showServer)return void t.upload(this.result);
                        var a = '<img src="' + this.result + '" style="max-width:100%;" />';
                        t.insertImage(a)
                    }
                }), t.placeholderHandler(), t.pasteHandler()
            }
        } catch (o) {
            console.log(o)
        }
        t._opt.formInputId && $("#" + t._opt.formInputId)[0] && $(t).on("input", function () {
            $("#" + t._opt.formInputId).val(t.getValue())
        })
    },

    upload: function (e) {


    },
    insertImage: function (e) {
        $(this).focus();
        var t = window.getSelection ? window.getSelection() : document.selection, a = t.createRange ? t.createRange() : t.getRangeAt(0);
        if (window.getSelection) {
            a.collapse(!1);
            for (var o = a.createContextualFragment(e), r = o.lastChild; r && "br" == r.nodeName.toLowerCase() && r.previousSibling && "br" == r.previousSibling.nodeName.toLowerCase();) {
                var l = r;
                r = r.previousSibling, o.removeChild(l)
            }
            a.insertNode(a.createContextualFragment("<br/>")), a.insertNode(o), r && (a.setEndAfter(r), a.setStartAfter(r)), t.removeAllRanges(), t.addRange(a)
        } else a.pasteHTML(e), a.collapse(!1), a.select();
        this._opt.formInputId && $("#" + this._opt.formInputId)[0] && $("#" + this._opt.formInputId).val(this.getValue())
    },
    pasteHandler: function () {
        var e = this;
        $(this).on("paste", function (t) {
            console.log(t.clipboardData.items);
            var a = $(this).html();
            console.log(a), valiHTML = e._opt.validHtml, a = a.replace(/_moz_dirty=""/gi, "").replace(/\[/g, "[[-").replace(/\]/g, "-]]").replace(/<\/ ?tr[^>]*>/gi, "[br]").replace(/<\/ ?td[^>]*>/gi, "&nbsp;&nbsp;").replace(/<(ul|dl|ol)[^>]*>/gi, "[br]").replace(/<(li|dd)[^>]*>/gi, "[br]").replace(/<p [^>]*>/gi, "[br]").replace(new RegExp("<(/?(?:" + valiHTML.join("|") + ")[^>]*)>", "gi"), "[$1]").replace(new RegExp('<span([^>]*class="?at"?[^>]*)>', "gi"), "[span$1]").replace(/<[^>]*>/g, "").replace(/\[\[\-/g, "[").replace(/\-\]\]/g, "]").replace(new RegExp("\\[(/?(?:" + valiHTML.join("|") + "|img|span)[^\\]]*)\\]", "gi"), "<$1>"), /firefox/.test(navigator.userAgent.toLowerCase()) || (a = a.replace(/\r?\n/gi, "<br>")), $(this).html(a)
        })
    },
    placeholderHandler: function () {
        var e = this;
        $(this).on("focus", function () {
            $.trim($(this).html()) === e._opt.placeholader && $(this).html("")
        }).on("blur", function () {
            $(this).html() || $(this).html(e._opt.placeholader)
        }), $.trim($(this).html()) || $(this).html(e._opt.placeholader)
    },
    getValue: function () {
        return $(this).html()
    },
    setValue: function (e) {
        $(this).html(e)
    }
});