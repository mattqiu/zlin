/******************************************************************************* 
* KindEditor - WYSIWYG HTML Editor for Internet 
* Copyright (C) 2006-2011 kindsoft.net 
* 
* @author Roddy <luolonghao@gmail.com> 
* @site http://www.kindsoft.net/ 
* @licence http://www.kindsoft.net/license.php 
* 再次修改：by tmouse 2014.4.7 
*******************************************************************************/  
   
KindEditor.plugin('flv', function(K) {  
    var self = this, name = 'flv', lang = self.lang(name + '.'),  
        allowFlashUpload = K.undef(self.allowFlashUpload, true),  
        allowFileManager = K.undef(self.allowFileManager, false),  
        formatUploadUrl = K.undef(self.formatUploadUrl, true),  
        extraParams = K.undef(self.extraFileUploadParams, {}),  
        filePostName = K.undef(self.filePostName, 'imgFile'),  
        uploadJson = K.undef(self.uploadJson, self.basePath + 'php/upload_json.php');  
        self.plugin.flv = {  
        edit : function() {  
            var html = [  
                '<div style="padding:20px;">',  
                //url  
                '<div class="ke-dialog-row">',  
                '<label for="keUrl" style="width:60px;">' + lang.url + '</label>',  
                '<input class="ke-input-text" type="text" id="keUrl" name="url" value="" style="width:160px;" />  ',  
                '<input type="button" class="ke-upload-button" value="' + lang.upload + '" />  ',  
                '<span class="ke-button-common ke-button-outer">',  
                '<input type="button" class="ke-button-common ke-button" name="viewServer" value="' + lang.viewServer + '" />',  
                '</span>',  
                '</div>',  
                //width  
                '<div class="ke-dialog-row">',  
                '<label for="keWidth" style="width:60px;">' + lang.width + '</label>',  
                '<input type="text" id="keWidth" class="ke-input-text ke-input-number" name="width" value="550" maxlength="4" /> ',  
                '</div>',  
                //height  
                '<div class="ke-dialog-row">',  
                '<label for="keHeight" style="width:60px;">' + lang.height + '</label>',  
                '<input type="text" id="keHeight" class="ke-input-text ke-input-number" name="height" value="400" maxlength="4" /> ',  
                '</div>',  
                '</div>'  
            ].join('');  
            var dialog = self.createDialog({  
                name : name,  
                width : 450,  
                title : self.lang(name),  
                body : html,  
                yesBtn : {  
                    name : self.lang('yes'),  
                    click : function(e) {  
                        var url = K.trim(urlBox.val()),  
                            width = widthBox.val(),  
                            height = heightBox.val();  
                        if (url == 'http://' || K.invalidUrl(url)) {  
                            alert(self.lang('invalidUrl'));  
                            urlBox[0].focus();  
                            return;  
                        }  
                        if (!/^\d*$/.test(width)) {  
                            alert(self.lang('invalidWidth'));  
                            widthBox[0].focus();  
                            return;  
                        }  
                        if (!/^\d*$/.test(height)) {  
                            alert(self.lang('invalidHeight'));  
                            heightBox[0].focus();  
                            return;  
                        }  
                        var html = K.mediaImg(self.themesPath + 'common/blank.gif', {  
                              src : '/data/resource/videoplayer/jwplayer.swf',  
                              type : 'application/x-shockwave-flv',  
                              width : width,  
                              height : height,  
                              quality : 'high',
							  allowfullscreen: 'true',
							  allowscriptaccess: 'always',
							  autostart: 'true',
							  bgcolor : '#000000',
							  flvfile : url,
							  flashvars : 'autostart=true&file='+url   //config=/data/resource/videoplayer/config.xml&
					   }); 
                        //var html='<script src="/data/resource/videoplayer/flowplayer-3.2.12.min.js">< /script>';  
                        //html += '<a href="'+url+'" style="display:block;width:'+width+'px;height:'+height+'px;margin-left:auto;margin-right:auto" id="player"></a>';  
					    //html+='<script language="JavaScript">flowplayer("player", "/data/resource/videoplayer/flowplayer-3.2.15.swf");< /script>';                   
                        //html+='<script language="JavaScript">var player=flowplayer("player","/data/resource/videoplayer/flowplayer-3.2.16.swf",{plugins:{pseudo:{url:"/data/resource/videoplayer/flowplayer.pseudostreaming-3.2.12.swf"},controls:{autoHide:{fullscreenOnly:true,hideDelay:2000},height:30,scrubber:true,buttonColor:"rgba(0, 0, 0, 0.9)",buttonOverColor:"#000000",backgroundGradient:"medium",backgroundColor:"#D7D7D7",sliderColor:"#2c2c2c",bufferColor:"#606060",progressColor:"#056e9f",sliderBorder:"1px solid #808080",sliderHeight:20,volumeSliderColor:"#FFFFFF",volumeBorder:"1px solid #808080",timeColor:"#000000",durationColor:"#535353",tooltips:{buttons:true,play:"播放",fullscreen:"全屏",fullscreenExit:"退出全屏",pause:"暂停",mute:"静音",unmute:"取消静音"}}},onStart:function(clip){animate(this,{bottom:31})},onFullscreen:function(){},onFullscreenExit:function(){},onBegin:function(){}});< /script>'; 
						//var html = '<p style="text-align: center"><embed allowfullscreen="true" allowscriptaccess="always" autostart="true" bgcolor="#ffffff" type="application/x-shockwave-flash" flashvars="file='+url+'&amp;autostart=true" height="'+height+'" src="/data/resource/videoplayer/jwplayer.swf" width="'+width+'"></embed></p>';
                        self.insertHtml(html).hideDialog().focus();  
                    }  
                }  
            }),  
            div = dialog.div,  
            urlBox = K('[name="url"]', div),  
            viewServerBtn = K('[name="viewServer"]', div),  
            widthBox = K('[name="width"]', div),  
            heightBox = K('[name="height"]', div);  
            urlBox.val('http://');  
   
            if (allowFlashUpload) {  
                var uploadbutton = K.uploadbutton({  
                    button : K('.ke-upload-button', div)[0],  
                    fieldName : filePostName,  
                    extraParams : extraParams,  
                    url : K.addParam(uploadJson, 'dir=flv'),  
                    afterUpload : function(data) {  
                        dialog.hideLoading();  
                        if (data.error === 0) {  
                            var url = data.url;  
                            if (formatUploadUrl) {  
                                url = K.formatUrl(url, 'absolute');  
                            }  
                            urlBox.val(url);  
                            if (self.afterUpload) {  
                                self.afterUpload.call(self, url, data, name);  
                            }  
                            alert(self.lang('uploadSuccess'));  
                        } else {  
                            alert(data.message);  
                        }  
                    },  
                    afterError : function(html) {  
                        dialog.hideLoading();  
                        self.errorDialog(html);  
                    }  
                });  
                uploadbutton.fileBox.change(function(e) {  
                    dialog.showLoading(self.lang('uploadLoading'));  
                    uploadbutton.submit();  
                });  
            } else {  
                K('.ke-upload-button', div).hide();  
            }  
   
            if (allowFileManager) {  
                viewServerBtn.click(function(e) {  
                    self.loadPlugin('filemanager', function() {  
                        self.plugin.filemanagerDialog({  
                            viewType : 'LIST',  
                            dirName : 'flv',  
                            clickFn : function(url, title) {  
                                if (self.dialogs.length > 1) {  
                                    K('[name="url"]', div).val(url);  
                                    if (self.afterSelectFile) {  
                                        self.afterSelectFile.call(self, url);  
                                    }  
                                    self.hideDialog();  
                                }  
                            }  
                        });  
                    });  
                });  
            } else {  
                viewServerBtn.hide();  
            }  
   
            var img = self.plugin.getSelectedFlv();  
            if (img) {  
                var attrs = K.mediaAttrs(img.attr('data-ke-tag'));  
                urlBox.val(attrs.flvfile);  
                widthBox.val(K.removeUnit(img.css('width')) || attrs.width || 0);  
                heightBox.val(K.removeUnit(img.css('height')) || attrs.height || 0);  
            }  
            urlBox[0].focus();  
            urlBox[0].select();  
        },  
        'delete' : function() { 
            self.plugin.getSelectedFlv().remove();
			// [IE] 删除图片后立即点击图片按钮出错
			self.addBookmark();  
        }  
    };  
    self.clickToolbar(name, self.plugin.flv.edit);  
});  