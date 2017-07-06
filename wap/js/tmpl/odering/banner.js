 $(function() {

            var index = 0;
            var timer = null;
            function dolunbo(){

            timer = setInterval(function() {
                index++;
                if (index == 3) {
                    index = 0;
                }
                show();
            }, 2000);
            };
            dolunbo();


            function show() {
                $('.box .zhangshiul li').eq(index).fadeIn(1000).siblings().fadeOut();
                $('.box .gundongul li').eq(index).addClass('active').siblings().removeClass('active');


                $('.box .zhangshiul li').eq(index).find('.img1').animate({"top": "0px"}, 1000, function() {
                    $('.box .zhangshiul li').eq(index).find('.img2').animate({
                        "top": "0px"
                    });
                });
            }

            // 停掉定时器
            $('.box .gundongul li').mouseover(function(){
                index = $(this).index();
                show();
                clearInterval(timer);
            })
            $('.box .gundongul li').mouseout(function(){
                index = $(this).index();
                dolunbo();
            })
            



        })