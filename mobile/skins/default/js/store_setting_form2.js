              

        function onUploadImgChange(sender){        
		    if( !sender.value.match( /.jpg|.gif|.png|.bmp/i ) ){
				//alert('图片格式无效！');
						          return false;   
								           }     
				  var objPreview = document.getElementById( 'preview' ); 
				 var objPreviewFake = document.getElementById( 'preview_fake' ); 
				  var objPreviewSizeFake = document.getElementById( 'preview_size_fake' );
				       var file=document.getElementById("upload_img");         
					 if( sender.files &&  sender.files[0] ){   
					 objPreview.style.display = 'block';  
					   objPreview.style.width = '100%';        
					objPreview.style.height = '100%';          
			   objPreview.src = window.URL.createObjectURL(file.files[0])  }
			   else if( objPreviewFake.filters ){  
			路径
				sender.select();  
				 var imgSrc = document.selection.createRange().text;  
				 objPreviewFake.filters.item('DXImageTransform.Microsoft.AlphaImageLoader').src = imgSrc;  
				 objPreviewSizeFake.filters.item('DXImageTransform.Microsoft.AlphaImageLoader').src = imgSrc; 
				 autoSizePreview( objPreviewFake,objPreviewSizeFake.offsetWidth, objPreviewSizeFake.offsetHeight );
				 objPreview.style.display = 'none';}}    
				 function onPreviewLoad(sender){
					 autoSizePreview( sender, sender.offsetWidth, sender.offsetHeight );
					        }   
				 function autoSizePreview( objPre, originalWidth, originalHeight ){        
                 var zoomParam = clacImgZoomParam( 150, 150, originalWidth, originalHeight );  
	                   objPre.style.width = zoomParam.width + 'px';     
			           objPre.style.height = zoomParam.height + 'px'; 
					   objPre.style.marginTop = zoomParam.top + 'px';   
					  objPre.style.marginLeft = zoomParam.left + 'px'; 
					  } 
					  function clacImgZoomParam( maxWidth, maxHeight, width, height ){ 
					  var param = { width:width, height:height, top:0, left:0 }; 
					  if( width>maxWidth || height>maxHeight ){
						   rateWidth = width / maxWidth; 
						   rateHeight = height / maxHeight;
						   if( rateWidth > rateHeight ){ 
						   param.width =  maxWidth;  
						   param.height = height / rateWidth; 
						   }else{ 
						      param.width = width / rateHeight;  
							   param.height = maxHeight;
							   }    
							  }     
							   param.left = (maxWidth - param.width) / 2; 
							   param.top = (maxHeight - param.height) / 2;   
				          return param;         }
						                



        function onstoreImgChange(sender){        
		    if( !sender.value.match( /.jpg|.gif|.png|.bmp/i ) ){    
			             //alert('图片格式无效！');
						          return false;   
								           }     
				  var objPreview = document.getElementById( 'store_pic' ); 
				 var objPreviewFake = document.getElementById( 'store_fake' ); 
				  var objPreviewSizeFake = document.getElementById( 'store_size_fake' );
				       var file=document.getElementById("storeBannerPic");         
					 if( sender.files &&  sender.files[0] ){   
					 objPreview.style.display = 'block';  
					   objPreview.style.width = '100%';        
					objPreview.style.height = '158';          
			   objPreview.src = window.URL.createObjectURL(file.files[0])  }
			   else if( objPreviewFake.filters ){  
			路径
				sender.select();  
				 var imgSrc = document.selection.createRange().text;  
				 objPreviewFake.filters.item('DXImageTransform.Microsoft.AlphaImageLoader').src = imgSrc;  
				 objPreviewSizeFake.filters.item('DXImageTransform.Microsoft.AlphaImageLoader').src = imgSrc; 
				 autoSizePreview( objPreviewFake,objPreviewSizeFake.offsetWidth, objPreviewSizeFake.offsetHeight );
				 objPreview.style.display = 'none';}}    
				 function onPreviewLoad(sender){
					 autoSizePreview( sender, sender.offsetWidth, sender.offsetHeight );
					        }   
				 function autoSizePreview( objPre, originalWidth, originalHeight ){        
                 var zoomParam = clacImgZoomParam(200, 150, originalWidth, originalHeight );  
	                   objPre.style.width = zoomParam.width + 'px';     
			           objPre.style.height = zoomParam.height + 'px'; 
					   objPre.style.marginTop = zoomParam.top + 'px';   
					  objPre.style.marginLeft = zoomParam.left + 'px'; 
					  } 
					  function clacImgZoomParam( maxWidth, maxHeight, width, height ){ 
					  var param = { width:width, height:height, top:0, left:0 }; 
					  if( width>maxWidth || height>maxHeight ){
						   rateWidth = width / maxWidth; 
						   rateHeight = height / maxHeight;
						   if( rateWidth > rateHeight ){ 
						   param.width =  maxWidth;  
						   param.height = height / rateWidth; 
						   }else{ 
						      param.width = width / rateHeight;  
							   param.height = maxHeight;
							   }    
							  }     
							   param.left = (maxWidth - param.width) / 2; 
							   param.top = (maxHeight - param.height) / 2;   
				          return param;         }
						  
						  
						  
						   function onheaderImgChange(sender){        
		    if( !sender.value.match( /.jpg|.gif|.png|.bmp/i ) ){    
			             alert('图片格式无效！');   
						          return false;   
								           }     
				  var objPreview = document.getElementById( 'store_header' ); 
				 var objPreviewFake = document.getElementById( 'store_header_r' ); 
				  var objPreviewSizeFake = document.getElementById( 'store_size_header' );
				       var file=document.getElementById("store_avatarPic");         
					 if( sender.files &&  sender.files[0] ){   
					 objPreview.style.display = 'block';  
					   objPreview.style.width = '100%';        
					objPreview.style.height = '158';          
			   objPreview.src = window.URL.createObjectURL(file.files[0])  }
			   else if( objPreviewFake.filters ){  
			路径
				sender.select();  
				 var imgSrc = document.selection.createRange().text;  
				 objPreviewFake.filters.item('DXImageTransform.Microsoft.AlphaImageLoader').src = imgSrc;  
				 objPreviewSizeFake.filters.item('DXImageTransform.Microsoft.AlphaImageLoader').src = imgSrc; 
				 autoSizePreview( objPreviewFake,objPreviewSizeFake.offsetWidth, objPreviewSizeFake.offsetHeight );
				 objPreview.style.display = 'none';}}    
				 function onPreviewLoad(sender){
					 autoSizePreview( sender, sender.offsetWidth, sender.offsetHeight );
					        }   
				 function autoSizePreview( objPre, originalWidth, originalHeight ){        
                 var zoomParam = clacImgZoomParam(200, 150, originalWidth, originalHeight );  
	                   objPre.style.width = zoomParam.width + 'px';     
			           objPre.style.height = zoomParam.height + 'px'; 
					   objPre.style.marginTop = zoomParam.top + 'px';   
					  objPre.style.marginLeft = zoomParam.left + 'px'; 
					  } 
					  function clacImgZoomParam( maxWidth, maxHeight, width, height ){ 
					  var param = { width:width, height:height, top:0, left:0 }; 
					  if( width>maxWidth || height>maxHeight ){
						   rateWidth = width / maxWidth; 
						   rateHeight = height / maxHeight;
						   if( rateWidth > rateHeight ){ 
						   param.width =  maxWidth;  
						   param.height = height / rateWidth; 
						   }else{ 
						      param.width = width / rateHeight;  
							   param.height = maxHeight;
							   }    
							  }     
							   param.left = (maxWidth - param.width) / 2; 
							   param.top = (maxHeight - param.height) / 2;   
				          return param;         }
						         
	

	
