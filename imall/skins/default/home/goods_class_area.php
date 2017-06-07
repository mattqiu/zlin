
<div class="filter-detailc" id="addressDraw">
  <dl class="location-hots">
    <dt>常用地区</dt>
    <dd><a href="<?php echo replaceParam(array('area_id' => '1','area_id2' =>0));?>">北京</a></dd>
    <dd><a href="<?php echo replaceParam(array('area_id' => '9','area_id2' =>0));?>">上海</a></dd>
    <dd><a href="<?php echo replaceParam(array('area_id' => '19','area_id2' =>289));?>" <?php if ($_GET['area_id2'] == 289) {?>class="current"<?php }?>>广州</a></dd>
    <dd><a href="<?php echo replaceParam(array('area_id' => '19','area_id2' =>291));?>" <?php if ($_GET['area_id2'] == 291) {?>class="current"<?php }?>>深圳</a></dd>
	<dd><a href="<?php echo replaceParam(array('area_id' => '2','area_id2' =>0));?>">天津</a></dd>    
    <dd><a href="<?php echo replaceParam(array('area_id' => '22','area_id2' =>0));?>">重庆</a></dd>
    <dd><a href="<?php echo replaceParam(array('area_id' => '35','area_id2' =>0));?>">海外</a></dd>
  </dl>
  <dl class="location-all">    
    <dt>省份</dt>
    <dd>
      <ul>
        <li>
          <p class="cities <?php if ($_GET['area_id'] != 19){?>hide<?php }?>" id='cities_19'>
            <span><a href="<?php echo replaceParam(array('area_id' => '19','area_id2' =>289));?>" <?php if ($_GET['area_id2'] == 289) {?>class="current"<?php }?>>广州</a></span>
            <span><a href="<?php echo replaceParam(array('area_id' => '19','area_id2' =>290));?>" <?php if ($_GET['area_id2'] == 290) {?>class="current"<?php }?>>韶关</a></span>
            <span><a href="<?php echo replaceParam(array('area_id' => '19','area_id2' =>291));?>" <?php if ($_GET['area_id2'] == 291) {?>class="current"<?php }?>>深圳</a></span>
            <span><a href="<?php echo replaceParam(array('area_id' => '19','area_id2' =>292));?>" <?php if ($_GET['area_id2'] == 292) {?>class="current"<?php }?>>珠海</a></span>
            <span><a href="<?php echo replaceParam(array('area_id' => '19','area_id2' =>293));?>" <?php if ($_GET['area_id2'] == 293) {?>class="current"<?php }?>>汕头</a></span>
            <span><a href="<?php echo replaceParam(array('area_id' => '19','area_id2' =>294));?>" <?php if ($_GET['area_id2'] == 294) {?>class="current"<?php }?>>佛山</a></span>
            <span><a href="<?php echo replaceParam(array('area_id' => '19','area_id2' =>295));?>" <?php if ($_GET['area_id2'] == 295) {?>class="current"<?php }?>>江门</a></span>
            <span><a href="<?php echo replaceParam(array('area_id' => '19','area_id2' =>296));?>" <?php if ($_GET['area_id2'] == 296) {?>class="current"<?php }?>>湛江</a></span>
            <span><a href="<?php echo replaceParam(array('area_id' => '19','area_id2' =>297));?>" <?php if ($_GET['area_id2'] == 297) {?>class="current"<?php }?>>茂名</a></span>
            <span><a href="<?php echo replaceParam(array('area_id' => '19','area_id2' =>298));?>" <?php if ($_GET['area_id2'] == 298) {?>class="current"<?php }?>>肇庆</a></span>
            <span><a href="<?php echo replaceParam(array('area_id' => '19','area_id2' =>299));?>" <?php if ($_GET['area_id2'] == 299) {?>class="current"<?php }?>>惠州</a></span>
            <span><a href="<?php echo replaceParam(array('area_id' => '19','area_id2' =>300));?>" <?php if ($_GET['area_id2'] == 300) {?>class="current"<?php }?>>梅州</a></span>
            <span><a href="<?php echo replaceParam(array('area_id' => '19','area_id2' =>301));?>" <?php if ($_GET['area_id2'] == 301) {?>class="current"<?php }?>>汕尾</a></span>
            <span><a href="<?php echo replaceParam(array('area_id' => '19','area_id2' =>302));?>" <?php if ($_GET['area_id2'] == 302) {?>class="current"<?php }?>>河源</a></span>
            <span><a href="<?php echo replaceParam(array('area_id' => '19','area_id2' =>303));?>" <?php if ($_GET['area_id2'] == 303) {?>class="current"<?php }?>>阳江</a></span>
            <span><a href="<?php echo replaceParam(array('area_id' => '19','area_id2' =>304));?>" <?php if ($_GET['area_id2'] == 304) {?>class="current"<?php }?>>清远</a></span>
            <span><a href="<?php echo replaceParam(array('area_id' => '19','area_id2' =>305));?>" <?php if ($_GET['area_id2'] == 305) {?>class="current"<?php }?>>东莞</a></span>
            <span><a href="<?php echo replaceParam(array('area_id' => '19','area_id2' =>306));?>" <?php if ($_GET['area_id2'] == 306) {?>class="current"<?php }?>>中山</a></span>
            <span><a href="<?php echo replaceParam(array('area_id' => '19','area_id2' =>307));?>" <?php if ($_GET['area_id2'] == 307) {?>class="current"<?php }?>>潮州</a></span>
            <span><a href="<?php echo replaceParam(array('area_id' => '19','area_id2' =>308));?>" <?php if ($_GET['area_id2'] == 308) {?>class="current"<?php }?>>揭阳</a></span>
            <span><a href="<?php echo replaceParam(array('area_id' => '19','area_id2' =>309));?>" <?php if ($_GET['area_id2'] == 309) {?>class="current"<?php }?>>云浮</a></span>     
          </p>
          <p class="cities <?php if ($_GET['area_id'] != 13){?>hide<?php }?>" id='cities_13'>
            <span><a href="<?php echo replaceParam(array('area_id' => '13','area_id2' =>203));?>" <?php if ($_GET['area_id2'] == 203) {?>class="current"<?php }?>>福州</a></span>
            <span><a href="<?php echo replaceParam(array('area_id' => '13','area_id2' =>204));?>" <?php if ($_GET['area_id2'] == 204) {?>class="current"<?php }?>>厦门</a></span>
            <span><a href="<?php echo replaceParam(array('area_id' => '13','area_id2' =>205));?>" <?php if ($_GET['area_id2'] == 205) {?>class="current"<?php }?>>莆田</a></span>
            <span><a href="<?php echo replaceParam(array('area_id' => '13','area_id2' =>206));?>" <?php if ($_GET['area_id2'] == 206) {?>class="current"<?php }?>>三明</a></span>
            <span><a href="<?php echo replaceParam(array('area_id' => '13','area_id2' =>207));?>" <?php if ($_GET['area_id2'] == 207) {?>class="current"<?php }?>>泉州</a></span>
            <span><a href="<?php echo replaceParam(array('area_id' => '13','area_id2' =>208));?>" <?php if ($_GET['area_id2'] == 208) {?>class="current"<?php }?>>漳州</a></span>
            <span><a href="<?php echo replaceParam(array('area_id' => '13','area_id2' =>209));?>" <?php if ($_GET['area_id2'] == 209) {?>class="current"<?php }?>>南平</a></span>
            <span><a href="<?php echo replaceParam(array('area_id' => '13','area_id2' =>210));?>" <?php if ($_GET['area_id2'] == 210) {?>class="current"<?php }?>>龙岩</a></span>
            <span><a href="<?php echo replaceParam(array('area_id' => '13','area_id2' =>211));?>" <?php if ($_GET['area_id2'] == 211) {?>class="current"<?php }?>>宁德</a></span>
          </p>
          <p class="cities <?php if ($_GET['area_id'] != 11){?>hide<?php }?>" id='cities_11'>
            <span><a href="<?php echo replaceParam(array('area_id' => '11','area_id2' =>175));?>" <?php if ($_GET['area_id2'] == 175) {?>class="current"<?php }?>>杭州</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '11','area_id2' =>176));?>" <?php if ($_GET['area_id2'] == 176) {?>class="current"<?php }?>>宁波</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '11','area_id2' =>177));?>" <?php if ($_GET['area_id2'] == 177) {?>class="current"<?php }?>>温州</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '11','area_id2' =>178));?>" <?php if ($_GET['area_id2'] == 178) {?>class="current"<?php }?>>嘉兴</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '11','area_id2' =>179));?>" <?php if ($_GET['area_id2'] == 179) {?>class="current"<?php }?>>湖州</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '11','area_id2' =>180));?>" <?php if ($_GET['area_id2'] == 180) {?>class="current"<?php }?>>绍兴</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '11','area_id2' =>181));?>" <?php if ($_GET['area_id2'] == 181) {?>class="current"<?php }?>>舟山</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '11','area_id2' =>182));?>" <?php if ($_GET['area_id2'] == 182) {?>class="current"<?php }?>>衢州</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '11','area_id2' =>183));?>" <?php if ($_GET['area_id2'] == 183) {?>class="current"<?php }?>>金华</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '11','area_id2' =>184));?>" <?php if ($_GET['area_id2'] == 184) {?>class="current"<?php }?>>台州</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '11','area_id2' =>185));?>" <?php if ($_GET['area_id2'] == 185) {?>class="current"<?php }?>>丽水</a></span> 
          </p>
          <p class="cities <?php if ($_GET['area_id'] != 10){?>hide<?php }?>" id='cities_10'>                      
            <span><a href="<?php echo replaceParam(array('area_id' => '10','area_id2' =>162));?>" <?php if ($_GET['area_id2'] == 162) {?>class="current"<?php }?>>南京</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '10','area_id2' =>163));?>" <?php if ($_GET['area_id2'] == 163) {?>class="current"<?php }?>>无锡</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '10','area_id2' =>164));?>" <?php if ($_GET['area_id2'] == 164) {?>class="current"<?php }?>>徐州</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '10','area_id2' =>165));?>" <?php if ($_GET['area_id2'] == 165) {?>class="current"<?php }?>>常州</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '10','area_id2' =>166));?>" <?php if ($_GET['area_id2'] == 166) {?>class="current"<?php }?>>苏州</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '10','area_id2' =>167));?>" <?php if ($_GET['area_id2'] == 167) {?>class="current"<?php }?>>南通</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '10','area_id2' =>168));?>" <?php if ($_GET['area_id2'] == 168) {?>class="current"<?php }?>>连云港</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '10','area_id2' =>169));?>" <?php if ($_GET['area_id2'] == 169) {?>class="current"<?php }?>>淮安</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '10','area_id2' =>170));?>" <?php if ($_GET['area_id2'] == 170) {?>class="current"<?php }?>>盐城</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '10','area_id2' =>171));?>" <?php if ($_GET['area_id2'] == 171) {?>class="current"<?php }?>>扬州</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '10','area_id2' =>172));?>" <?php if ($_GET['area_id2'] == 172) {?>class="current"<?php }?>>镇江</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '10','area_id2' =>173));?>" <?php if ($_GET['area_id2'] == 173) {?>class="current"<?php }?>>泰州</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '10','area_id2' =>174));?>" <?php if ($_GET['area_id2'] == 174) {?>class="current"<?php }?>>宿迁</a></span> 
          </p>
          <p class="cities <?php if ($_GET['area_id'] != 15){?>hide<?php }?>" id='cities_15'>                      
            <span><a href="<?php echo replaceParam(array('area_id' => '15','area_id2' =>223));?>" <?php if ($_GET['area_id2'] == 223) {?>class="current"<?php }?>>济南</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '15','area_id2' =>224));?>" <?php if ($_GET['area_id2'] == 224) {?>class="current"<?php }?>>青岛</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '15','area_id2' =>225));?>" <?php if ($_GET['area_id2'] == 225) {?>class="current"<?php }?>>淄博</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '15','area_id2' =>226));?>" <?php if ($_GET['area_id2'] == 226) {?>class="current"<?php }?>>枣庄</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '15','area_id2' =>227));?>" <?php if ($_GET['area_id2'] == 227) {?>class="current"<?php }?>>东营</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '15','area_id2' =>228));?>" <?php if ($_GET['area_id2'] == 228) {?>class="current"<?php }?>>烟台</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '15','area_id2' =>229));?>" <?php if ($_GET['area_id2'] == 229) {?>class="current"<?php }?>>潍坊</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '15','area_id2' =>230));?>" <?php if ($_GET['area_id2'] == 230) {?>class="current"<?php }?>>济宁</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '15','area_id2' =>231));?>" <?php if ($_GET['area_id2'] == 231) {?>class="current"<?php }?>>泰安</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '15','area_id2' =>232));?>" <?php if ($_GET['area_id2'] == 232) {?>class="current"<?php }?>>威海</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '15','area_id2' =>233));?>" <?php if ($_GET['area_id2'] == 233) {?>class="current"<?php }?>>日照</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '15','area_id2' =>234));?>" <?php if ($_GET['area_id2'] == 234) {?>class="current"<?php }?>>莱芜</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '15','area_id2' =>235));?>" <?php if ($_GET['area_id2'] == 235) {?>class="current"<?php }?>>临沂</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '15','area_id2' =>236));?>" <?php if ($_GET['area_id2'] == 236) {?>class="current"<?php }?>>德州</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '15','area_id2' =>237));?>" <?php if ($_GET['area_id2'] == 237) {?>class="current"<?php }?>>聊城</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '15','area_id2' =>238));?>" <?php if ($_GET['area_id2'] == 238) {?>class="current"<?php }?>>滨州</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '15','area_id2' =>239));?>" <?php if ($_GET['area_id2'] == 239) {?>class="current"<?php }?>>菏泽</a></span>  
          </p>
          <p class="cities <?php if ($_GET['area_id'] != 6){?>hide<?php }?>" id='cities_6'>
            <span><a href="<?php echo replaceParam(array('area_id' => '6','area_id2' =>107));?>" <?php if ($_GET['area_id2'] == 107) {?>class="current"<?php }?>>沈阳</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '6','area_id2' =>108));?>" <?php if ($_GET['area_id2'] == 108) {?>class="current"<?php }?>>大连</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '6','area_id2' =>109));?>" <?php if ($_GET['area_id2'] == 109) {?>class="current"<?php }?>>鞍山</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '6','area_id2' =>110));?>" <?php if ($_GET['area_id2'] == 110) {?>class="current"<?php }?>>抚顺</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '6','area_id2' =>111));?>" <?php if ($_GET['area_id2'] == 111) {?>class="current"<?php }?>>本溪</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '6','area_id2' =>112));?>" <?php if ($_GET['area_id2'] == 112) {?>class="current"<?php }?>>丹东</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '6','area_id2' =>113));?>" <?php if ($_GET['area_id2'] == 113) {?>class="current"<?php }?>>锦州</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '6','area_id2' =>114));?>" <?php if ($_GET['area_id2'] == 114) {?>class="current"<?php }?>>营口</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '6','area_id2' =>115));?>" <?php if ($_GET['area_id2'] == 115) {?>class="current"<?php }?>>阜新</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '6','area_id2' =>116));?>" <?php if ($_GET['area_id2'] == 116) {?>class="current"<?php }?>>辽阳</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '6','area_id2' =>117));?>" <?php if ($_GET['area_id2'] == 117) {?>class="current"<?php }?>>盘锦</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '6','area_id2' =>118));?>" <?php if ($_GET['area_id2'] == 118) {?>class="current"<?php }?>>铁岭</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '6','area_id2' =>119));?>" <?php if ($_GET['area_id2'] == 119) {?>class="current"<?php }?>>朝阳</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '6','area_id2' =>120));?>" <?php if ($_GET['area_id2'] == 120) {?>class="current"<?php }?>>葫芦岛</a></span> 
          </p>
          <p class="area">
            <span><a onmouseover="showcities(19)" href="<?php echo replaceParam(array('area_id' => '19','area_id2' =>0));?>" <?php if ($_GET['area_id'] == 19) {?>class="current"<?php }?>>广东</a></span>
            <span><a onmouseover="showcities(13)" href="<?php echo replaceParam(array('area_id' => '13','area_id2' =>0));?>" <?php if ($_GET['area_id'] == 13) {?>class="current"<?php }?>>福建</a></span>
            <span><a onmouseover="showcities(11)"  href="<?php echo replaceParam(array('area_id' => '11','area_id2' =>0));?>" <?php if ($_GET['area_id'] == 11) {?>class="current"<?php }?>>浙江</a></span>
            <span><a onmouseover="showcities(10)"  href="<?php echo replaceParam(array('area_id' => '10','area_id2' =>0));?>" <?php if ($_GET['area_id'] == 10) {?>class="current"<?php }?>>江苏</a></span>
            <span><a onmouseover="showcities(15)"  href="<?php echo replaceParam(array('area_id' => '15','area_id2' =>0));?>" <?php if ($_GET['area_id'] == 15) {?>class="current"<?php }?>>山东</a></span>
            <span><a onmouseover="showcities(6)"   href="<?php echo replaceParam(array('area_id' => '6', 'area_id2' =>0));?>" <?php if ($_GET['area_id'] == 6)  {?>class="current"<?php }?>>辽宁</a></span>            
          </p>  
        </li>
        <li>    
          <p class="cities <?php if ($_GET['area_id'] != 17){?>hide<?php }?>" id='cities_17'>                       
            <span><a href="<?php echo replaceParam(array('area_id' => '17','area_id2' =>258));?>" <?php if ($_GET['area_id2'] == 258) {?>class="current"<?php }?>>武汉</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '17','area_id2' =>259));?>" <?php if ($_GET['area_id2'] == 259) {?>class="current"<?php }?>>黄石</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '17','area_id2' =>260));?>" <?php if ($_GET['area_id2'] == 260) {?>class="current"<?php }?>>十堰</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '17','area_id2' =>261));?>" <?php if ($_GET['area_id2'] == 261) {?>class="current"<?php }?>>宜昌</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '17','area_id2' =>262));?>" <?php if ($_GET['area_id2'] == 262) {?>class="current"<?php }?>>襄樊</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '17','area_id2' =>263));?>" <?php if ($_GET['area_id2'] == 263) {?>class="current"<?php }?>>鄂州</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '17','area_id2' =>264));?>" <?php if ($_GET['area_id2'] == 264) {?>class="current"<?php }?>>荆门</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '17','area_id2' =>265));?>" <?php if ($_GET['area_id2'] == 265) {?>class="current"<?php }?>>孝感</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '17','area_id2' =>266));?>" <?php if ($_GET['area_id2'] == 266) {?>class="current"<?php }?>>荆州</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '17','area_id2' =>267));?>" <?php if ($_GET['area_id2'] == 267) {?>class="current"<?php }?>>黄冈</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '17','area_id2' =>268));?>" <?php if ($_GET['area_id2'] == 268) {?>class="current"<?php }?>>咸宁</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '17','area_id2' =>269));?>" <?php if ($_GET['area_id2'] == 269) {?>class="current"<?php }?>>随州</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '17','area_id2' =>270));?>" <?php if ($_GET['area_id2'] == 270) {?>class="current"<?php }?>>恩施</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '17','area_id2' =>271));?>" <?php if ($_GET['area_id2'] == 271) {?>class="current"<?php }?>>仙桃</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '17','area_id2' =>272));?>" <?php if ($_GET['area_id2'] == 272) {?>class="current"<?php }?>>潜江</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '17','area_id2' =>273));?>" <?php if ($_GET['area_id2'] == 273) {?>class="current"<?php }?>>天门</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '17','area_id2' =>274));?>" <?php if ($_GET['area_id2'] == 274) {?>class="current"<?php }?>>神农架</a></span>  
          </p>
          <p class="cities <?php if ($_GET['area_id'] != 18){?>hide<?php }?>" id='cities_18'>                      
            <span><a href="<?php echo replaceParam(array('area_id' => '18','area_id2' =>275));?>" <?php if ($_GET['area_id2'] == 275) {?>class="current"<?php }?>>长沙</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '18','area_id2' =>276));?>" <?php if ($_GET['area_id2'] == 276) {?>class="current"<?php }?>>株洲</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '18','area_id2' =>277));?>" <?php if ($_GET['area_id2'] == 277) {?>class="current"<?php }?>>湘潭</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '18','area_id2' =>278));?>" <?php if ($_GET['area_id2'] == 278) {?>class="current"<?php }?>>衡阳</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '18','area_id2' =>279));?>" <?php if ($_GET['area_id2'] == 279) {?>class="current"<?php }?>>邵阳</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '18','area_id2' =>280));?>" <?php if ($_GET['area_id2'] == 280) {?>class="current"<?php }?>>岳阳</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '18','area_id2' =>281));?>" <?php if ($_GET['area_id2'] == 281) {?>class="current"<?php }?>>常德</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '18','area_id2' =>282));?>" <?php if ($_GET['area_id2'] == 282) {?>class="current"<?php }?>>张家界</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '18','area_id2' =>283));?>" <?php if ($_GET['area_id2'] == 283) {?>class="current"<?php }?>>益阳</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '18','area_id2' =>284));?>" <?php if ($_GET['area_id2'] == 284) {?>class="current"<?php }?>>郴州</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '18','area_id2' =>285));?>" <?php if ($_GET['area_id2'] == 285) {?>class="current"<?php }?>>永州</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '18','area_id2' =>286));?>" <?php if ($_GET['area_id2'] == 286) {?>class="current"<?php }?>>怀化</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '18','area_id2' =>287));?>" <?php if ($_GET['area_id2'] == 287) {?>class="current"<?php }?>>娄底</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '18','area_id2' =>288));?>" <?php if ($_GET['area_id2'] == 288) {?>class="current"<?php }?>>湘西</a></span>                      
          </p> 
          <p class="cities <?php if ($_GET['area_id'] != 14){?>hide<?php }?>" id='cities_14'>                       
            <span><a href="<?php echo replaceParam(array('area_id' => '14','area_id2' =>212));?>" <?php if ($_GET['area_id2'] == 212) {?>class="current"<?php }?>>南昌</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '14','area_id2' =>213));?>" <?php if ($_GET['area_id2'] == 213) {?>class="current"<?php }?>>景德镇</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '14','area_id2' =>214));?>" <?php if ($_GET['area_id2'] == 214) {?>class="current"<?php }?>>萍乡</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '14','area_id2' =>215));?>" <?php if ($_GET['area_id2'] == 215) {?>class="current"<?php }?>>九江</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '14','area_id2' =>216));?>" <?php if ($_GET['area_id2'] == 216) {?>class="current"<?php }?>>新余</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '14','area_id2' =>217));?>" <?php if ($_GET['area_id2'] == 217) {?>class="current"<?php }?>>鹰潭</a></span>                       
            <span><a href="<?php echo replaceParam(array('area_id' => '14','area_id2' =>218));?>" <?php if ($_GET['area_id2'] == 218) {?>class="current"<?php }?>>赣州</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '14','area_id2' =>219));?>" <?php if ($_GET['area_id2'] == 219) {?>class="current"<?php }?>>吉安</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '14','area_id2' =>220));?>" <?php if ($_GET['area_id2'] == 220) {?>class="current"<?php }?>>宜春</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '14','area_id2' =>221));?>" <?php if ($_GET['area_id2'] == 221) {?>class="current"<?php }?>>抚州</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '14','area_id2' =>222));?>" <?php if ($_GET['area_id2'] == 222) {?>class="current"<?php }?>>上饶</a></span> 
          </p>   
          <p class="cities <?php if ($_GET['area_id'] != 12){?>hide<?php }?>" id='cities_12'>
            <span><a href="<?php echo replaceParam(array('area_id' => '12','area_id2' =>186));?>" <?php if ($_GET['area_id2'] == 186) {?>class="current"<?php }?>>合肥</a></span>          
            <span><a href="<?php echo replaceParam(array('area_id' => '12','area_id2' =>187));?>" <?php if ($_GET['area_id2'] == 187) {?>class="current"<?php }?>>芜湖</a></span>          
            <span><a href="<?php echo replaceParam(array('area_id' => '12','area_id2' =>188));?>" <?php if ($_GET['area_id2'] == 188) {?>class="current"<?php }?>>蚌埠</a></span>
            <span><a href="<?php echo replaceParam(array('area_id' => '12','area_id2' =>189));?>" <?php if ($_GET['area_id2'] == 189) {?>class="current"<?php }?>>淮南</a></span>
            <span><a href="<?php echo replaceParam(array('area_id' => '12','area_id2' =>190));?>" <?php if ($_GET['area_id2'] == 190) {?>class="current"<?php }?>>马鞍山</a></span>
            <span><a href="<?php echo replaceParam(array('area_id' => '12','area_id2' =>191));?>" <?php if ($_GET['area_id2'] == 191) {?>class="current"<?php }?>>淮北</a></span>
            <span><a href="<?php echo replaceParam(array('area_id' => '12','area_id2' =>192));?>" <?php if ($_GET['area_id2'] == 192) {?>class="current"<?php }?>>铜陵</a></span>
            <span><a href="<?php echo replaceParam(array('area_id' => '12','area_id2' =>193));?>" <?php if ($_GET['area_id2'] == 193) {?>class="current"<?php }?>>安庆</a></span>
            <span><a href="<?php echo replaceParam(array('area_id' => '12','area_id2' =>194));?>" <?php if ($_GET['area_id2'] == 194) {?>class="current"<?php }?>>黄山</a></span>
            <span><a href="<?php echo replaceParam(array('area_id' => '12','area_id2' =>195));?>" <?php if ($_GET['area_id2'] == 195) {?>class="current"<?php }?>>滁州</a></span>
            <span><a href="<?php echo replaceParam(array('area_id' => '12','area_id2' =>196));?>" <?php if ($_GET['area_id2'] == 196) {?>class="current"<?php }?>>阜阳</a></span>
            <span><a href="<?php echo replaceParam(array('area_id' => '12','area_id2' =>197));?>" <?php if ($_GET['area_id2'] == 197) {?>class="current"<?php }?>>宿州</a></span>
            <span><a href="<?php echo replaceParam(array('area_id' => '12','area_id2' =>198));?>" <?php if ($_GET['area_id2'] == 198) {?>class="current"<?php }?>>巢湖</a></span>
            <span><a href="<?php echo replaceParam(array('area_id' => '12','area_id2' =>199));?>" <?php if ($_GET['area_id2'] == 199) {?>class="current"<?php }?>>六安</a></span>
            <span><a href="<?php echo replaceParam(array('area_id' => '12','area_id2' =>200));?>" <?php if ($_GET['area_id2'] == 200) {?>class="current"<?php }?>>亳州</a></span>
            <span><a href="<?php echo replaceParam(array('area_id' => '12','area_id2' =>201));?>" <?php if ($_GET['area_id2'] == 201) {?>class="current"<?php }?>>池州</a></span>
            <span><a href="<?php echo replaceParam(array('area_id' => '12','area_id2' =>202));?>" <?php if ($_GET['area_id2'] == 202) {?>class="current"<?php }?>>宣城</a></span>
          </p> 
          <p class="cities <?php if ($_GET['area_id'] != 4){?>hide<?php }?>" id='cities_4'>
            <span><a href="<?php echo replaceParam(array('area_id' => '4','area_id2' =>84));?>" <?php if ($_GET['area_id2'] == 84) {?>class="current"<?php }?>>太原</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '4','area_id2' =>85));?>" <?php if ($_GET['area_id2'] == 85) {?>class="current"<?php }?>>大同</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '4','area_id2' =>86));?>" <?php if ($_GET['area_id2'] == 86) {?>class="current"<?php }?>>阳泉</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '4','area_id2' =>87));?>" <?php if ($_GET['area_id2'] == 87) {?>class="current"<?php }?>>长治</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '4','area_id2' =>88));?>" <?php if ($_GET['area_id2'] == 88) {?>class="current"<?php }?>>晋城</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '4','area_id2' =>89));?>" <?php if ($_GET['area_id2'] == 89) {?>class="current"<?php }?>>朔州</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '4','area_id2' =>90));?>" <?php if ($_GET['area_id2'] == 90) {?>class="current"<?php }?>>晋中</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '4','area_id2' =>91));?>" <?php if ($_GET['area_id2'] == 91) {?>class="current"<?php }?>>运城</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '4','area_id2' =>92));?>" <?php if ($_GET['area_id2'] == 92) {?>class="current"<?php }?>>忻州</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '4','area_id2' =>93));?>" <?php if ($_GET['area_id2'] == 93) {?>class="current"<?php }?>>临汾</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '4','area_id2' =>94));?>" <?php if ($_GET['area_id2'] == 94) {?>class="current"<?php }?>>吕梁</a></span> 
          </p>
          <p class="cities <?php if ($_GET['area_id'] != 27){?>hide<?php }?>" id='cities_27'>                      
            <span><a href="<?php echo replaceParam(array('area_id' => '27','area_id2' =>438));?>" <?php if ($_GET['area_id2'] == 438) {?>class="current"<?php }?>>西安</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '27','area_id2' =>439));?>" <?php if ($_GET['area_id2'] == 439) {?>class="current"<?php }?>>铜川</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '27','area_id2' =>440));?>" <?php if ($_GET['area_id2'] == 440) {?>class="current"<?php }?>>宝鸡</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '27','area_id2' =>441));?>" <?php if ($_GET['area_id2'] == 441) {?>class="current"<?php }?>>咸阳</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '27','area_id2' =>442));?>" <?php if ($_GET['area_id2'] == 442) {?>class="current"<?php }?>>渭南</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '27','area_id2' =>443));?>" <?php if ($_GET['area_id2'] == 443) {?>class="current"<?php }?>>延安</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '27','area_id2' =>444));?>" <?php if ($_GET['area_id2'] == 444) {?>class="current"<?php }?>>汉中</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '27','area_id2' =>445));?>" <?php if ($_GET['area_id2'] == 445) {?>class="current"<?php }?>>榆林</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '27','area_id2' =>446));?>" <?php if ($_GET['area_id2'] == 446) {?>class="current"<?php }?>>安康</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '27','area_id2' =>447));?>" <?php if ($_GET['area_id2'] == 447) {?>class="current"<?php }?>>商洛</a></span>
          </p>
          <p class="area"> 
            <span><a onmouseover="showcities(17)"  href="<?php echo replaceParam(array('area_id' => '17','area_id2' =>0));?>" <?php if ($_GET['area_id'] == 17) {?>class="current"<?php }?>>湖北</a></span> 
            <span><a onmouseover="showcities(18)"  href="<?php echo replaceParam(array('area_id' => '18','area_id2' =>0));?>" <?php if ($_GET['area_id'] == 18) {?>class="current"<?php }?>>湖南</a></span>             
            <span><a onmouseover="showcities(14)"  href="<?php echo replaceParam(array('area_id' => '14','area_id2' =>0));?>" <?php if ($_GET['area_id'] == 14) {?>class="current"<?php }?>>江西</a></span>          
            <span><a onmouseover="showcities(12)" href="<?php echo replaceParam(array('area_id' => '12','area_id2' =>0));?>" <?php if ($_GET['area_id'] == 12) {?>class="current"<?php }?>>安徽</a></span>
            <span><a onmouseover="showcities(4)"   href="<?php echo replaceParam(array('area_id' => '4','area_id2' =>0));?>" <?php if ($_GET['area_id'] == 4)   {?>class="current"<?php }?>>山西</a></span> 
            <span><a onmouseover="showcities(27)"  href="<?php echo replaceParam(array('area_id' => '27','area_id2' =>0));?>" <?php if ($_GET['area_id'] == 27) {?>class="current"<?php }?>>陕西</a></span> 
          </p>
        </li>
        <li>
          <p class="cities <?php if ($_GET['area_id'] != 23){?>hide<?php }?>" id='cities_23'>                       
            <span><a href="<?php echo replaceParam(array('area_id' => '23','area_id2' =>385));?>" <?php if ($_GET['area_id2'] == 385) {?>class="current"<?php }?>>成都</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '23','area_id2' =>386));?>" <?php if ($_GET['area_id2'] == 386) {?>class="current"<?php }?>>自贡</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '23','area_id2' =>387));?>" <?php if ($_GET['area_id2'] == 387) {?>class="current"<?php }?>>攀枝花</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '23','area_id2' =>388));?>" <?php if ($_GET['area_id2'] == 388) {?>class="current"<?php }?>>泸州</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '23','area_id2' =>389));?>" <?php if ($_GET['area_id2'] == 389) {?>class="current"<?php }?>>德阳</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '23','area_id2' =>390));?>" <?php if ($_GET['area_id2'] == 390) {?>class="current"<?php }?>>绵阳</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '23','area_id2' =>391));?>" <?php if ($_GET['area_id2'] == 391) {?>class="current"<?php }?>>广元</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '23','area_id2' =>392));?>" <?php if ($_GET['area_id2'] == 392) {?>class="current"<?php }?>>遂宁</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '23','area_id2' =>393));?>" <?php if ($_GET['area_id2'] == 393) {?>class="current"<?php }?>>内江</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '23','area_id2' =>394));?>" <?php if ($_GET['area_id2'] == 394) {?>class="current"<?php }?>>乐山</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '23','area_id2' =>395));?>" <?php if ($_GET['area_id2'] == 395) {?>class="current"<?php }?>>南充</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '23','area_id2' =>396));?>" <?php if ($_GET['area_id2'] == 396) {?>class="current"<?php }?>>眉山</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '23','area_id2' =>397));?>" <?php if ($_GET['area_id2'] == 397) {?>class="current"<?php }?>>宜宾</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '23','area_id2' =>398));?>" <?php if ($_GET['area_id2'] == 398) {?>class="current"<?php }?>>广安</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '23','area_id2' =>399));?>" <?php if ($_GET['area_id2'] == 399) {?>class="current"<?php }?>>达州</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '23','area_id2' =>400));?>" <?php if ($_GET['area_id2'] == 400) {?>class="current"<?php }?>>雅安</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '23','area_id2' =>401));?>" <?php if ($_GET['area_id2'] == 401) {?>class="current"<?php }?>>巴中</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '23','area_id2' =>402));?>" <?php if ($_GET['area_id2'] == 402) {?>class="current"<?php }?>>资阳</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '23','area_id2' =>403));?>" <?php if ($_GET['area_id2'] == 403) {?>class="current"<?php }?>>阿坝州</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '23','area_id2' =>404));?>" <?php if ($_GET['area_id2'] == 404) {?>class="current"<?php }?>>甘孜</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '23','area_id2' =>405));?>" <?php if ($_GET['area_id2'] == 405) {?>class="current"<?php }?>>凉山</a></span> 
          </p>
          <p class="cities <?php if ($_GET['area_id'] != 24){?>hide<?php }?>" id='cities_24'>                       
            <span><a href="<?php echo replaceParam(array('area_id' => '24','area_id2' =>406));?>" <?php if ($_GET['area_id2'] == 406) {?>class="current"<?php }?>>贵阳</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '24','area_id2' =>407));?>" <?php if ($_GET['area_id2'] == 407) {?>class="current"<?php }?>>六盘水</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '24','area_id2' =>408));?>" <?php if ($_GET['area_id2'] == 408) {?>class="current"<?php }?>>遵义</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '24','area_id2' =>409));?>" <?php if ($_GET['area_id2'] == 409) {?>class="current"<?php }?>>安顺</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '24','area_id2' =>410));?>" <?php if ($_GET['area_id2'] == 410) {?>class="current"<?php }?>>铜仁</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '24','area_id2' =>411));?>" <?php if ($_GET['area_id2'] == 411) {?>class="current"<?php }?>>黔西南</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '24','area_id2' =>412));?>" <?php if ($_GET['area_id2'] == 412) {?>class="current"<?php }?>>毕节</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '24','area_id2' =>413));?>" <?php if ($_GET['area_id2'] == 413) {?>class="current"<?php }?>>黔东南州</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '24','area_id2' =>414));?>" <?php if ($_GET['area_id2'] == 414) {?>class="current"<?php }?>>黔南州</a></span>                      
          </p> 
          <p class="cities <?php if ($_GET['area_id'] != 30){?>hide<?php }?>" id='cities_30'>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '30','area_id2' =>470));?>" <?php if ($_GET['area_id2'] == 470) {?>class="current"<?php }?>>银川</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '30','area_id2' =>471));?>" <?php if ($_GET['area_id2'] == 471) {?>class="current"<?php }?>>石嘴山</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '30','area_id2' =>472));?>" <?php if ($_GET['area_id2'] == 472) {?>class="current"<?php }?>>吴忠</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '30','area_id2' =>473));?>" <?php if ($_GET['area_id2'] == 473) {?>class="current"<?php }?>>固原</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '30','area_id2' =>474));?>" <?php if ($_GET['area_id2'] == 474) {?>class="current"<?php }?>>中卫</a></span>                      
          </p> 
          <p class="cities <?php if ($_GET['area_id'] != 26){?>hide<?php }?>" id='cities_26'>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '26','area_id2' =>431));?>" <?php if ($_GET['area_id2'] == 431) {?>class="current"<?php }?>>拉萨</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '26','area_id2' =>432));?>" <?php if ($_GET['area_id2'] == 432) {?>class="current"<?php }?>>昌都</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '26','area_id2' =>433));?>" <?php if ($_GET['area_id2'] == 433) {?>class="current"<?php }?>>山南</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '26','area_id2' =>434));?>" <?php if ($_GET['area_id2'] == 434) {?>class="current"<?php }?>>日喀则</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '26','area_id2' =>435));?>" <?php if ($_GET['area_id2'] == 435) {?>class="current"<?php }?>>那曲</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '26','area_id2' =>436));?>" <?php if ($_GET['area_id2'] == 436) {?>class="current"<?php }?>>阿里</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '26','area_id2' =>437));?>" <?php if ($_GET['area_id2'] == 437) {?>class="current"<?php }?>>林芝</a></span>
          </p>
          <p class="cities <?php if ($_GET['area_id'] != 31){?>hide<?php }?>" id='cities_31'>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '31','area_id2' =>475));?>" <?php if ($_GET['area_id2'] == 475) {?>class="current"<?php }?>>乌鲁木齐</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '31','area_id2' =>476));?>" <?php if ($_GET['area_id2'] == 476) {?>class="current"<?php }?>>克拉玛依</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '31','area_id2' =>477));?>" <?php if ($_GET['area_id2'] == 477) {?>class="current"<?php }?>>吐鲁番</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '31','area_id2' =>478));?>" <?php if ($_GET['area_id2'] == 478) {?>class="current"<?php }?>>哈密地区</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '31','area_id2' =>479));?>" <?php if ($_GET['area_id2'] == 479) {?>class="current"<?php }?>>昌吉</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '31','area_id2' =>480));?>" <?php if ($_GET['area_id2'] == 480) {?>class="current"<?php }?>>博尔塔拉</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '31','area_id2' =>481));?>" <?php if ($_GET['area_id2'] == 481) {?>class="current"<?php }?>>巴音郭楞</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '31','area_id2' =>482));?>" <?php if ($_GET['area_id2'] == 482) {?>class="current"<?php }?>>阿克苏</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '31','area_id2' =>483));?>" <?php if ($_GET['area_id2'] == 483) {?>class="current"<?php }?>>克孜勒</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '31','area_id2' =>484));?>" <?php if ($_GET['area_id2'] == 484) {?>class="current"<?php }?>>喀什地区</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '31','area_id2' =>485));?>" <?php if ($_GET['area_id2'] == 485) {?>class="current"<?php }?>>和田地区</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '31','area_id2' =>486));?>" <?php if ($_GET['area_id2'] == 486) {?>class="current"<?php }?>>伊犁州</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '31','area_id2' =>487));?>" <?php if ($_GET['area_id2'] == 487) {?>class="current"<?php }?>>塔城地区</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '31','area_id2' =>488));?>" <?php if ($_GET['area_id2'] == 488) {?>class="current"<?php }?>>阿勒泰</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '31','area_id2' =>489));?>" <?php if ($_GET['area_id2'] == 489) {?>class="current"<?php }?>>石河子</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '31','area_id2' =>490));?>" <?php if ($_GET['area_id2'] == 490) {?>class="current"<?php }?>>阿拉尔</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '31','area_id2' =>491));?>" <?php if ($_GET['area_id2'] == 491) {?>class="current"<?php }?>>图木舒克</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '31','area_id2' =>492));?>" <?php if ($_GET['area_id2'] == 492) {?>class="current"<?php }?>>五家渠</a></span>
          </p>
          <p class="cities <?php if ($_GET['area_id'] != 5){?>hide<?php }?>" id='cities_5'>                       
            <span><a href="<?php echo replaceParam(array('area_id' => '5','area_id2' =>95));?>" <?php if ($_GET['area_id2'] == 95) {?>class="current"<?php }?>>呼和浩特</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '5','area_id2' =>96));?>" <?php if ($_GET['area_id2'] == 96) {?>class="current"<?php }?>>包头</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '5','area_id2' =>97));?>" <?php if ($_GET['area_id2'] == 97) {?>class="current"<?php }?>>乌海</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '5','area_id2' =>98));?>" <?php if ($_GET['area_id2'] == 98) {?>class="current"<?php }?>>赤峰</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '5','area_id2' =>99));?>" <?php if ($_GET['area_id2'] == 99) {?>class="current"<?php }?>>通辽</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '5','area_id2' =>100));?>" <?php if ($_GET['area_id2'] == 100) {?>class="current"<?php }?>>鄂尔多斯</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '5','area_id2' =>101));?>" <?php if ($_GET['area_id2'] == 101) {?>class="current"<?php }?>>呼伦贝尔</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '5','area_id2' =>102));?>" <?php if ($_GET['area_id2'] == 102) {?>class="current"<?php }?>>巴彦淖尔</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '5','area_id2' =>103));?>" <?php if ($_GET['area_id2'] == 103) {?>class="current"<?php }?>>乌兰察布</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '5','area_id2' =>104));?>" <?php if ($_GET['area_id2'] == 104) {?>class="current"<?php }?>>兴安盟</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '5','area_id2' =>105));?>" <?php if ($_GET['area_id2'] == 105) {?>class="current"<?php }?>>锡林郭勒</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '5','area_id2' =>106));?>" <?php if ($_GET['area_id2'] == 106) {?>class="current"<?php }?>>阿拉善盟</a></span>  
          </p>
          <p class="area">            
            <span><a onmouseover="showcities(23)"  href="<?php echo replaceParam(array('area_id' => '23','area_id2' =>0));?>" <?php if ($_GET['area_id'] == 23) {?>class="current"<?php }?>>四川</a></span>
            <span><a onmouseover="showcities(24)" href="<?php echo replaceParam(array('area_id' => '24','area_id2' =>0));?>" <?php if ($_GET['area_id'] == 24) {?>class="current"<?php }?>>贵州</a></span>
            <span><a onmouseover="showcities(30)"  href="<?php echo replaceParam(array('area_id' => '30','area_id2' =>0));?>" <?php if ($_GET['area_id'] == 30) {?>class="current"<?php }?>>宁夏</a></span>
            <span><a onmouseover="showcities(26)"  href="<?php echo replaceParam(array('area_id' => '26','area_id2' =>0));?>" <?php if ($_GET['area_id'] == 26) {?>class="current"<?php }?>>西藏</a></span>
            <span><a onmouseover="showcities(31)"  href="<?php echo replaceParam(array('area_id' => '31','area_id2' =>0));?>" <?php if ($_GET['area_id'] == 31) {?>class="current"<?php }?>>新疆</a></span>
            <span><a onmouseover="showcities(5)"   href="<?php echo replaceParam(array('area_id' => '5','area_id2' =>0));?>" <?php if ($_GET['area_id'] == 5)   {?>class="current"<?php }?>>内蒙古</a></span>            
          </p>
        </li>
        <li>
          <p class="cities <?php if ($_GET['area_id'] != 8){?>hide<?php }?>" id='cities_8'>                       
            <span><a href="<?php echo replaceParam(array('area_id' => '8','area_id2' =>130));?>" <?php if ($_GET['area_id2'] == 130) {?>class="current"<?php }?>>哈尔滨</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '8','area_id2' =>131));?>" <?php if ($_GET['area_id2'] == 131) {?>class="current"<?php }?>>齐齐哈尔</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '8','area_id2' =>132));?>" <?php if ($_GET['area_id2'] == 132) {?>class="current"<?php }?>>鸡西</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '8','area_id2' =>133));?>" <?php if ($_GET['area_id2'] == 133) {?>class="current"<?php }?>>鹤岗</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '8','area_id2' =>134));?>" <?php if ($_GET['area_id2'] == 134) {?>class="current"<?php }?>>双鸭山</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '8','area_id2' =>135));?>" <?php if ($_GET['area_id2'] == 135) {?>class="current"<?php }?>>大庆</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '8','area_id2' =>136));?>" <?php if ($_GET['area_id2'] == 136) {?>class="current"<?php }?>>伊春</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '8','area_id2' =>137));?>" <?php if ($_GET['area_id2'] == 137) {?>class="current"<?php }?>>佳木斯</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '8','area_id2' =>138));?>" <?php if ($_GET['area_id2'] == 138) {?>class="current"<?php }?>>七台河</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '8','area_id2' =>139));?>" <?php if ($_GET['area_id2'] == 139) {?>class="current"<?php }?>>牡丹江</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '8','area_id2' =>140));?>" <?php if ($_GET['area_id2'] == 140) {?>class="current"<?php }?>>黑河</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '8','area_id2' =>141));?>" <?php if ($_GET['area_id2'] == 141) {?>class="current"<?php }?>>绥化</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '8','area_id2' =>142));?>" <?php if ($_GET['area_id2'] == 142) {?>class="current"<?php }?>>大兴安岭</a></span>  
          </p>
          <p class="cities <?php if ($_GET['area_id'] != 7){?>hide<?php }?>" id='cities_7'>                       
            <span><a href="<?php echo replaceParam(array('area_id' => '7','area_id2' =>121));?>" <?php if ($_GET['area_id2'] == 121) {?>class="current"<?php }?>>长春</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '7','area_id2' =>122));?>" <?php if ($_GET['area_id2'] == 122) {?>class="current"<?php }?>>吉林</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '7','area_id2' =>123));?>" <?php if ($_GET['area_id2'] == 123) {?>class="current"<?php }?>>四平</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '7','area_id2' =>124));?>" <?php if ($_GET['area_id2'] == 124) {?>class="current"<?php }?>>辽源</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '7','area_id2' =>125));?>" <?php if ($_GET['area_id2'] == 125) {?>class="current"<?php }?>>通化</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '7','area_id2' =>126));?>" <?php if ($_GET['area_id2'] == 126) {?>class="current"<?php }?>>白山</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '7','area_id2' =>127));?>" <?php if ($_GET['area_id2'] == 127) {?>class="current"<?php }?>>松原</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '7','area_id2' =>128));?>" <?php if ($_GET['area_id2'] == 128) {?>class="current"<?php }?>>白城</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '7','area_id2' =>129));?>" <?php if ($_GET['area_id2'] == 129) {?>class="current"<?php }?>>延边</a></span>  
          </p>
          <p class="cities <?php if ($_GET['area_id'] != 3){?>hide<?php }?>" id='cities_3'>
            <span><a href="<?php echo replaceParam(array('area_id' => '3','area_id2' =>73));?>" <?php if ($_GET['area_id2'] == 73) {?>class="current"<?php }?>>石家庄</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '3','area_id2' =>74));?>" <?php if ($_GET['area_id2'] == 74) {?>class="current"<?php }?>>唐山</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '3','area_id2' =>75));?>" <?php if ($_GET['area_id2'] == 75) {?>class="current"<?php }?>>秦皇岛</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '3','area_id2' =>76));?>" <?php if ($_GET['area_id2'] == 76) {?>class="current"<?php }?>>邯郸</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '3','area_id2' =>77));?>" <?php if ($_GET['area_id2'] == 77) {?>class="current"<?php }?>>邢台</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '3','area_id2' =>78));?>" <?php if ($_GET['area_id2'] == 78) {?>class="current"<?php }?>>保定</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '3','area_id2' =>79));?>" <?php if ($_GET['area_id2'] == 79) {?>class="current"<?php }?>>张家口</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '3','area_id2' =>80));?>" <?php if ($_GET['area_id2'] == 80) {?>class="current"<?php }?>>承德</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '3','area_id2' =>81));?>" <?php if ($_GET['area_id2'] == 81) {?>class="current"<?php }?>>衡水</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '3','area_id2' =>82));?>" <?php if ($_GET['area_id2'] == 82) {?>class="current"<?php }?>>廊坊</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '3','area_id2' =>83));?>" <?php if ($_GET['area_id2'] == 83) {?>class="current"<?php }?>>沧州</a></span> 
          </p>          
          <p class="cities <?php if ($_GET['area_id'] != 16){?>hide<?php }?>" id='cities_16'>                      
            <span><a href="<?php echo replaceParam(array('area_id' => '16','area_id2' =>240));?>" <?php if ($_GET['area_id2'] == 240) {?>class="current"<?php }?>>郑州</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '16','area_id2' =>241));?>" <?php if ($_GET['area_id2'] == 241) {?>class="current"<?php }?>>开封</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '16','area_id2' =>242));?>" <?php if ($_GET['area_id2'] == 242) {?>class="current"<?php }?>>洛阳</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '16','area_id2' =>243));?>" <?php if ($_GET['area_id2'] == 243) {?>class="current"<?php }?>>平顶山</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '16','area_id2' =>244));?>" <?php if ($_GET['area_id2'] == 244) {?>class="current"<?php }?>>安阳</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '16','area_id2' =>245));?>" <?php if ($_GET['area_id2'] == 245) {?>class="current"<?php }?>>鹤壁</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '16','area_id2' =>246));?>" <?php if ($_GET['area_id2'] == 246) {?>class="current"<?php }?>>新乡</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '16','area_id2' =>247));?>" <?php if ($_GET['area_id2'] == 247) {?>class="current"<?php }?>>焦作</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '16','area_id2' =>248));?>" <?php if ($_GET['area_id2'] == 248) {?>class="current"<?php }?>>濮阳</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '16','area_id2' =>249));?>" <?php if ($_GET['area_id2'] == 249) {?>class="current"<?php }?>>许昌</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '16','area_id2' =>250));?>" <?php if ($_GET['area_id2'] == 250) {?>class="current"<?php }?>>漯河</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '16','area_id2' =>251));?>" <?php if ($_GET['area_id2'] == 251) {?>class="current"<?php }?>>三门峡</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '16','area_id2' =>252));?>" <?php if ($_GET['area_id2'] == 252) {?>class="current"<?php }?>>南阳</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '16','area_id2' =>253));?>" <?php if ($_GET['area_id2'] == 253) {?>class="current"<?php }?>>商丘</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '16','area_id2' =>254));?>" <?php if ($_GET['area_id2'] == 254) {?>class="current"<?php }?>>信阳</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '16','area_id2' =>255));?>" <?php if ($_GET['area_id2'] == 255) {?>class="current"<?php }?>>周口</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '16','area_id2' =>256));?>" <?php if ($_GET['area_id2'] == 256) {?>class="current"<?php }?>>驻马店</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '16','area_id2' =>257));?>" <?php if ($_GET['area_id2'] == 257) {?>class="current"<?php }?>>济源</a></span> 
          </p>
          <p class="cities <?php if ($_GET['area_id'] != 29){?>hide<?php }?>" id='cities_29'>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '29','area_id2' =>462));?>" <?php if ($_GET['area_id2'] == 462) {?>class="current"<?php }?>>西宁</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '29','area_id2' =>463));?>" <?php if ($_GET['area_id2'] == 463) {?>class="current"<?php }?>>海东地区</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '29','area_id2' =>464));?>" <?php if ($_GET['area_id2'] == 464) {?>class="current"<?php }?>>海北</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '29','area_id2' =>465));?>" <?php if ($_GET['area_id2'] == 465) {?>class="current"<?php }?>>黄南</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '29','area_id2' =>466));?>" <?php if ($_GET['area_id2'] == 466) {?>class="current"<?php }?>>海南</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '29','area_id2' =>467));?>" <?php if ($_GET['area_id2'] == 467) {?>class="current"<?php }?>>果洛</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '29','area_id2' =>468));?>" <?php if ($_GET['area_id2'] == 468) {?>class="current"<?php }?>>玉树</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '29','area_id2' =>469));?>" <?php if ($_GET['area_id2'] == 469) {?>class="current"<?php }?>>海西</a></span>
          </p>            
          <p class="cities <?php if ($_GET['area_id'] != 28){?>hide<?php }?>" id='cities_28'>
            <span><a href="<?php echo replaceParam(array('area_id' => '28','area_id2' =>448));?>" <?php if ($_GET['area_id2'] == 448) {?>class="current"<?php }?>>兰州</a></span>
            <span><a href="<?php echo replaceParam(array('area_id' => '28','area_id2' =>449));?>" <?php if ($_GET['area_id2'] == 449) {?>class="current"<?php }?>>嘉峪关</a></span>
            <span><a href="<?php echo replaceParam(array('area_id' => '28','area_id2' =>450));?>" <?php if ($_GET['area_id2'] == 450) {?>class="current"<?php }?>>金昌</a></span>
            <span><a href="<?php echo replaceParam(array('area_id' => '28','area_id2' =>451));?>" <?php if ($_GET['area_id2'] == 451) {?>class="current"<?php }?>>白银</a></span>
            <span><a href="<?php echo replaceParam(array('area_id' => '28','area_id2' =>452));?>" <?php if ($_GET['area_id2'] == 452) {?>class="current"<?php }?>>天水</a></span>
            <span><a href="<?php echo replaceParam(array('area_id' => '28','area_id2' =>453));?>" <?php if ($_GET['area_id2'] == 453) {?>class="current"<?php }?>>武威</a></span>
            <span><a href="<?php echo replaceParam(array('area_id' => '28','area_id2' =>454));?>" <?php if ($_GET['area_id2'] == 454) {?>class="current"<?php }?>>张掖</a></span>
            <span><a href="<?php echo replaceParam(array('area_id' => '28','area_id2' =>455));?>" <?php if ($_GET['area_id2'] == 455) {?>class="current"<?php }?>>平凉</a></span>
            <span><a href="<?php echo replaceParam(array('area_id' => '28','area_id2' =>456));?>" <?php if ($_GET['area_id2'] == 456) {?>class="current"<?php }?>>酒泉</a></span>
            <span><a href="<?php echo replaceParam(array('area_id' => '28','area_id2' =>457));?>" <?php if ($_GET['area_id2'] == 457) {?>class="current"<?php }?>>庆阳</a></span>
            <span><a href="<?php echo replaceParam(array('area_id' => '28','area_id2' =>458));?>" <?php if ($_GET['area_id2'] == 458) {?>class="current"<?php }?>>定西</a></span>
            <span><a href="<?php echo replaceParam(array('area_id' => '28','area_id2' =>459));?>" <?php if ($_GET['area_id2'] == 459) {?>class="current"<?php }?>>陇南</a></span>
            <span><a href="<?php echo replaceParam(array('area_id' => '28','area_id2' =>460));?>" <?php if ($_GET['area_id2'] == 460) {?>class="current"<?php }?>>临夏</a></span>
            <span><a href="<?php echo replaceParam(array('area_id' => '28','area_id2' =>461));?>" <?php if ($_GET['area_id2'] == 461) {?>class="current"<?php }?>>甘南</a></span>
          </p>
          <p class="area">
            <span><a onmouseover="showcities(8)"  href="<?php echo replaceParam(array('area_id' => '8', 'area_id2' =>0));?>" <?php if ($_GET['area_id'] == 8)  {?>class="current"<?php }?>>黑龙江</a></span>
            <span><a onmouseover="showcities(7)"   href="<?php echo replaceParam(array('area_id' => '7', 'area_id2' =>0));?>" <?php if ($_GET['area_id'] == 7)  {?>class="current"<?php }?>>吉林</a></span>
            <span><a onmouseover="showcities(3)"  href="<?php echo replaceParam(array('area_id' => '3', 'area_id2' =>0));?>" <?php if ($_GET['area_id'] == 3)  {?>class="current"<?php }?>>河北</a></span> 
            <span><a onmouseover="showcities(16)" href="<?php echo replaceParam(array('area_id' => '16','area_id2' =>0));?>" <?php if ($_GET['area_id'] == 16) {?>class="current"<?php }?>>河南</a></span>            
            <span><a onmouseover="showcities(29)"  href="<?php echo replaceParam(array('area_id' => '29','area_id2' =>0));?>" <?php if ($_GET['area_id'] == 29) {?>class="current"<?php }?>>青海</a></span>            
            <span><a onmouseover="showcities(28)" href="<?php echo replaceParam(array('area_id' => '28','area_id2' =>0));?>" <?php if ($_GET['area_id'] == 28) {?>class="current"<?php }?>>甘肃</a></span>
          </p>
        </li>
        <li>          
          <p class="cities <?php if ($_GET['area_id'] != 25){?>hide<?php }?>" id='cities_25'>                       
            <span><a href="<?php echo replaceParam(array('area_id' => '25','area_id2' =>415));?>" <?php if ($_GET['area_id2'] == 415) {?>class="current"<?php }?>>昆明</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '25','area_id2' =>416));?>" <?php if ($_GET['area_id2'] == 416) {?>class="current"<?php }?>>曲靖</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '25','area_id2' =>417));?>" <?php if ($_GET['area_id2'] == 417) {?>class="current"<?php }?>>玉溪</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '25','area_id2' =>418));?>" <?php if ($_GET['area_id2'] == 418) {?>class="current"<?php }?>>保山</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '25','area_id2' =>419));?>" <?php if ($_GET['area_id2'] == 419) {?>class="current"<?php }?>>昭通</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '25','area_id2' =>420));?>" <?php if ($_GET['area_id2'] == 420) {?>class="current"<?php }?>>丽江</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '25','area_id2' =>421));?>" <?php if ($_GET['area_id2'] == 421) {?>class="current"<?php }?>>思茅</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '25','area_id2' =>422));?>" <?php if ($_GET['area_id2'] == 422) {?>class="current"<?php }?>>临沧</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '25','area_id2' =>423));?>" <?php if ($_GET['area_id2'] == 423) {?>class="current"<?php }?>>楚雄州</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '25','area_id2' =>424));?>" <?php if ($_GET['area_id2'] == 424) {?>class="current"<?php }?>>红河州</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '25','area_id2' =>425));?>" <?php if ($_GET['area_id2'] == 425) {?>class="current"<?php }?>>文山</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '25','area_id2' =>426));?>" <?php if ($_GET['area_id2'] == 426) {?>class="current"<?php }?>>西双版纳</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '25','area_id2' =>427));?>" <?php if ($_GET['area_id2'] == 427) {?>class="current"<?php }?>>大理州</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '25','area_id2' =>428));?>" <?php if ($_GET['area_id2'] == 428) {?>class="current"<?php }?>>德宏州</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '25','area_id2' =>429));?>" <?php if ($_GET['area_id2'] == 429) {?>class="current"<?php }?>>怒江州</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '25','area_id2' =>430));?>" <?php if ($_GET['area_id2'] == 430) {?>class="current"<?php }?>>迪庆州</a></span>
          </p>
          <p class="cities <?php if ($_GET['area_id'] != 20){?>hide<?php }?>" id='cities_20'>                       
            <span><a href="<?php echo replaceParam(array('area_id' => '20','area_id2' =>310));?>" <?php if ($_GET['area_id2'] == 310) {?>class="current"<?php }?>>南宁</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '20','area_id2' =>311));?>" <?php if ($_GET['area_id2'] == 311) {?>class="current"<?php }?>>柳州</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '20','area_id2' =>312));?>" <?php if ($_GET['area_id2'] == 312) {?>class="current"<?php }?>>桂林</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '20','area_id2' =>313));?>" <?php if ($_GET['area_id2'] == 313) {?>class="current"<?php }?>>梧州</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '20','area_id2' =>314));?>" <?php if ($_GET['area_id2'] == 314) {?>class="current"<?php }?>>北海</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '20','area_id2' =>315));?>" <?php if ($_GET['area_id2'] == 315) {?>class="current"<?php }?>>防城港</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '20','area_id2' =>316));?>" <?php if ($_GET['area_id2'] == 316) {?>class="current"<?php }?>>钦州</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '20','area_id2' =>317));?>" <?php if ($_GET['area_id2'] == 317) {?>class="current"<?php }?>>贵港</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '20','area_id2' =>318));?>" <?php if ($_GET['area_id2'] == 318) {?>class="current"<?php }?>>玉林</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '20','area_id2' =>319));?>" <?php if ($_GET['area_id2'] == 319) {?>class="current"<?php }?>>百色</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '20','area_id2' =>320));?>" <?php if ($_GET['area_id2'] == 320) {?>class="current"<?php }?>>贺州</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '20','area_id2' =>321));?>" <?php if ($_GET['area_id2'] == 321) {?>class="current"<?php }?>>河池</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '20','area_id2' =>322));?>" <?php if ($_GET['area_id2'] == 322) {?>class="current"<?php }?>>来宾</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '20','area_id2' =>323));?>" <?php if ($_GET['area_id2'] == 323) {?>class="current"<?php }?>>崇左</a></span> 
          </p>  
          <p class="cities <?php if ($_GET['area_id'] != 21){?>hide<?php }?>" id='cities_21'>                       
            <span><a href="<?php echo replaceParam(array('area_id' => '21','area_id2' =>324));?>" <?php if ($_GET['area_id2'] == 324) {?>class="current"<?php }?>>海口</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '21','area_id2' =>325));?>" <?php if ($_GET['area_id2'] == 325) {?>class="current"<?php }?>>三亚</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '21','area_id2' =>326));?>" <?php if ($_GET['area_id2'] == 326) {?>class="current"<?php }?>>五指山</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '21','area_id2' =>327));?>" <?php if ($_GET['area_id2'] == 327) {?>class="current"<?php }?>>琼海</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '21','area_id2' =>328));?>" <?php if ($_GET['area_id2'] == 328) {?>class="current"<?php }?>>儋州</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '21','area_id2' =>329));?>" <?php if ($_GET['area_id2'] == 329) {?>class="current"<?php }?>>文昌</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '21','area_id2' =>330));?>" <?php if ($_GET['area_id2'] == 330) {?>class="current"<?php }?>>万宁</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '21','area_id2' =>331));?>" <?php if ($_GET['area_id2'] == 331) {?>class="current"<?php }?>>东方</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '21','area_id2' =>332));?>" <?php if ($_GET['area_id2'] == 332) {?>class="current"<?php }?>>定安县</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '21','area_id2' =>333));?>" <?php if ($_GET['area_id2'] == 333) {?>class="current"<?php }?>>屯昌县</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '21','area_id2' =>334));?>" <?php if ($_GET['area_id2'] == 334) {?>class="current"<?php }?>>澄迈县</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '21','area_id2' =>335));?>" <?php if ($_GET['area_id2'] == 335) {?>class="current"<?php }?>>临高县</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '21','area_id2' =>336));?>" <?php if ($_GET['area_id2'] == 336) {?>class="current"<?php }?>>白沙县</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '21','area_id2' =>337));?>" <?php if ($_GET['area_id2'] == 337) {?>class="current"<?php }?>>昌江县</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '21','area_id2' =>338));?>" <?php if ($_GET['area_id2'] == 338) {?>class="current"<?php }?>>乐东县</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '21','area_id2' =>339));?>" <?php if ($_GET['area_id2'] == 339) {?>class="current"<?php }?>>陵水县</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '21','area_id2' =>340));?>" <?php if ($_GET['area_id2'] == 340) {?>class="current"<?php }?>>保亭县</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '21','area_id2' =>341));?>" <?php if ($_GET['area_id2'] == 341) {?>class="current"<?php }?>>琼中县</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '21','area_id2' =>342));?>" <?php if ($_GET['area_id2'] == 342) {?>class="current"<?php }?>>西沙群岛</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '21','area_id2' =>343));?>" <?php if ($_GET['area_id2'] == 343) {?>class="current"<?php }?>>南沙群岛</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '21','area_id2' =>344));?>" <?php if ($_GET['area_id2'] == 344) {?>class="current"<?php }?>>中沙群岛</a></span> 
          </p>
          <p class="cities <?php if ($_GET['area_id'] != 33){?>hide<?php }?>" id='cities_33'>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '33','area_id2' =>516));?>" <?php if ($_GET['area_id2'] == 516) {?>class="current"<?php }?>>中西区</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '33','area_id2' =>517));?>" <?php if ($_GET['area_id2'] == 517) {?>class="current"<?php }?>>东区</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '33','area_id2' =>518));?>" <?php if ($_GET['area_id2'] == 518) {?>class="current"<?php }?>>九龙城区</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '33','area_id2' =>519));?>" <?php if ($_GET['area_id2'] == 519) {?>class="current"<?php }?>>观塘区</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '33','area_id2' =>520));?>" <?php if ($_GET['area_id2'] == 520) {?>class="current"<?php }?>>南区</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '33','area_id2' =>521));?>" <?php if ($_GET['area_id2'] == 521) {?>class="current"<?php }?>>深水埗区</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '33','area_id2' =>522));?>" <?php if ($_GET['area_id2'] == 522) {?>class="current"<?php }?>>黄大仙区</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '33','area_id2' =>523));?>" <?php if ($_GET['area_id2'] == 523) {?>class="current"<?php }?>>湾仔区</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '33','area_id2' =>524));?>" <?php if ($_GET['area_id2'] == 524) {?>class="current"<?php }?>>油尖旺区</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '33','area_id2' =>525));?>" <?php if ($_GET['area_id2'] == 525) {?>class="current"<?php }?>>离岛区</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '33','area_id2' =>526));?>" <?php if ($_GET['area_id2'] == 526) {?>class="current"<?php }?>>葵青区</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '33','area_id2' =>527));?>" <?php if ($_GET['area_id2'] == 527) {?>class="current"<?php }?>>北区</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '33','area_id2' =>528));?>" <?php if ($_GET['area_id2'] == 528) {?>class="current"<?php }?>>西贡区</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '33','area_id2' =>529));?>" <?php if ($_GET['area_id2'] == 529) {?>class="current"<?php }?>>沙田区</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '33','area_id2' =>530));?>" <?php if ($_GET['area_id2'] == 530) {?>class="current"<?php }?>>屯门区</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '33','area_id2' =>531));?>" <?php if ($_GET['area_id2'] == 531) {?>class="current"<?php }?>>大埔区</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '33','area_id2' =>532));?>" <?php if ($_GET['area_id2'] == 532) {?>class="current"<?php }?>>荃湾区</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '33','area_id2' =>533));?>" <?php if ($_GET['area_id2'] == 533) {?>class="current"<?php }?>>元朗区</a></span>                      
          </p>
          <p class="cities <?php if ($_GET['area_id'] != 34){?>hide<?php }?>" id='cities_34'>
            <span><a href="<?php echo replaceParam(array('area_id' => '34','area_id2' =>534));?>" <?php if ($_GET['area_id2'] == 534) {?>class="current"<?php }?>>澳门</a></span>
          </p>
          <p class="cities <?php if ($_GET['area_id'] != 32){?>hide<?php }?>" id='cities_32'>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '32','area_id2' =>493));?>" <?php if ($_GET['area_id2'] == 493) {?>class="current"<?php }?>>台北</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '32','area_id2' =>494));?>" <?php if ($_GET['area_id2'] == 494) {?>class="current"<?php }?>>高雄</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '32','area_id2' =>495));?>" <?php if ($_GET['area_id2'] == 495) {?>class="current"<?php }?>>基隆</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '32','area_id2' =>496));?>" <?php if ($_GET['area_id2'] == 496) {?>class="current"<?php }?>>台中</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '32','area_id2' =>497));?>" <?php if ($_GET['area_id2'] == 497) {?>class="current"<?php }?>>台南</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '32','area_id2' =>498));?>" <?php if ($_GET['area_id2'] == 498) {?>class="current"<?php }?>>新竹</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '32','area_id2' =>499));?>" <?php if ($_GET['area_id2'] == 499) {?>class="current"<?php }?>>嘉义</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '32','area_id2' =>500));?>" <?php if ($_GET['area_id2'] == 500) {?>class="current"<?php }?>>台北县</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '32','area_id2' =>501));?>" <?php if ($_GET['area_id2'] == 501) {?>class="current"<?php }?>>宜兰县</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '32','area_id2' =>502));?>" <?php if ($_GET['area_id2'] == 502) {?>class="current"<?php }?>>桃园县</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '32','area_id2' =>503));?>" <?php if ($_GET['area_id2'] == 503) {?>class="current"<?php }?>>新竹县</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '32','area_id2' =>504));?>" <?php if ($_GET['area_id2'] == 504) {?>class="current"<?php }?>>苗栗县</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '32','area_id2' =>505));?>" <?php if ($_GET['area_id2'] == 505) {?>class="current"<?php }?>>台中县</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '32','area_id2' =>506));?>" <?php if ($_GET['area_id2'] == 506) {?>class="current"<?php }?>>彰化县</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '32','area_id2' =>507));?>" <?php if ($_GET['area_id2'] == 507) {?>class="current"<?php }?>>南投县</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '32','area_id2' =>508));?>" <?php if ($_GET['area_id2'] == 508) {?>class="current"<?php }?>>云林县</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '32','area_id2' =>509));?>" <?php if ($_GET['area_id2'] == 509) {?>class="current"<?php }?>>嘉义县</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '32','area_id2' =>510));?>" <?php if ($_GET['area_id2'] == 510) {?>class="current"<?php }?>>台南县</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '32','area_id2' =>511));?>" <?php if ($_GET['area_id2'] == 511) {?>class="current"<?php }?>>高雄县</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '32','area_id2' =>512));?>" <?php if ($_GET['area_id2'] == 512) {?>class="current"<?php }?>>屏东县</a></span>                        

            <span><a href="<?php echo replaceParam(array('area_id' => '32','area_id2' =>513));?>" <?php if ($_GET['area_id2'] == 513) {?>class="current"<?php }?>>澎湖县</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '32','area_id2' =>514));?>" <?php if ($_GET['area_id2'] == 514) {?>class="current"<?php }?>>台东县</a></span>                        
            <span><a href="<?php echo replaceParam(array('area_id' => '32','area_id2' =>515));?>" <?php if ($_GET['area_id2'] == 515) {?>class="current"<?php }?>>花莲县</a></span>
          </p>
          <p class="area">                         
            <span><a onmouseover="showcities(25)"  href="<?php echo replaceParam(array('area_id' => '25','area_id2' =>0));?>" <?php if ($_GET['area_id'] == 25) {?>class="current"<?php }?>>云南</a></span>
            <span><a onmouseover="showcities(20)" href="<?php echo replaceParam(array('area_id' => '20','area_id2' =>0));?>" <?php if ($_GET['area_id'] == 20) {?>class="current"<?php }?>>广西</a></span>
            <span><a onmouseover="showcities(21)" href="<?php echo replaceParam(array('area_id' => '21','area_id2' =>0));?>" <?php if ($_GET['area_id'] == 21) {?>class="current"<?php }?>>海南</a></span>
            <span><a onmouseover="showcities(33)"  href="<?php echo replaceParam(array('area_id' => '33','area_id2' =>0));?>" <?php if ($_GET['area_id'] == 33) {?>class="current"<?php }?>>香港</a></span>
            <span><a onmouseover="showcities(34)" href="<?php echo replaceParam(array('area_id' => '34','area_id2' =>0));?>" <?php if ($_GET['area_id'] == 34) {?>class="current"<?php }?>>澳门</a></span>
            <span><a onmouseover="showcities(32)"  href="<?php echo replaceParam(array('area_id' => '32','area_id2' =>0));?>" <?php if ($_GET['area_id'] == 32) {?>class="current"<?php }?>>台湾</a></span>
          </p>                             
        </li>        
      </ul>
    </dd>
  </dl>
  <p class="oreder-default"><a href="<?php echo dropParam(array('area_id','area_id2'));?>">不限地区</a></p>
</div>
<script type="text/javascript">

function showcities(id) {
	$('.cities').addClass("hide");
	$('#cities_'+id).removeClass("hide");
}
</script>