<?php include_once "../../include/header.php";
include_once "../../db/connect.php";?><?php

	$employNo            = $_GET['eno'];

	$sql	 = " SELECT 	no 			";
	$sql 	.= " 		,	title 		";
	$sql 	.= " 		,	business	";
	$sql 	.= " 		,	manager		";
	$sql 	.= " 		,	address 	";
	$sql 	.= " 		,	keyword 	";
	$sql 	.= " 		,	sex 		";
	$sql 	.= " 		,	age_1st 	";
	$sql 	.= "		,	age_2nd 	";
	$sql 	.= "		,	career 		";
	$sql 	.= "		,	fn 			";
	$sql 	.= "		,	A.phone		";
	$sql 	.= "		,	time 		";
	$sql 	.= "		,	pay 		";
	$sql 	.= "		,	A.content 	";
	$sql 	.= "		,	lat 		";
	$sql 	.= "		,	lng 		";
	$sql 	.= "		,	A.uid 		";
	$sql 	.= "		,	B.img_url		";
		
	$sql 	.= "		FROM work_employ_data A JOIN company B ";
	$sql 	.= "		WHERE 1=1 			  ";
	$sql 	.= "		AND no = '$employNo' AND	A.member_no = B.member_no  ";


    $result = mysql_query($sql, $ilbang_con);
        
    while($row = mysql_fetch_array($result)){
             $no 		= $row["no"];
             $title 	= $row["title"];
             $business 	= $row["business"];
             $manager 	= $row["manager"];
             $address 	= $row["address"];
             $euid	 	= $row["uid"];
             //$keyword 	= $row["keyword"];

	      	 $keyword  = explode(",", $row["keyword"]);
	    	 $area_1st_nm = $keyword[0];
		     $area_2nd_nm = $keyword[1];
		     $area_3rd_nm = $keyword[2];
		     $work_1st_nm = $keyword[3];
		     $work_2nd_nm = $keyword[4];

		     $img_url = $row["img_url"];

             //$sex 		= $row["sex"];
             $age_1st 	= $row["age_1st"];
             $age_2nd 	= $row["age_2nd"];
             //$career 	= $row["career"];


		           if ($row["career"] == "-1")  $career = "무관";
		      else if ($row["career"] == "0")   $career = "신입";
		      else if ($row["career"] == "1")   $career = "1년미만";  
		      else if ($row["career"] == "3")   $career = "3년미만";  
		      else if ($row["career"] == "5")   $career = "5년미만";  
		      else if ($row["career"] == "6")   $career = "5년이상";  


		           if ($row["sex"] == "man")     $sex = "남자";
		      else if ($row["sex"] == "woman")   $sex = "여자";
		      else if ($row["sex"] == "nothing") $sex = "무관";  

	             //$fn 		= $row["fn"];

		           if ($row["fn"] == "0")   	$fn = "X";
		      else if ($row["fn"] == "1")   	$fn = "O";  


             $phone 	= $row["phone"];
             $time 		= $row["time"];
             $pay 		= $row["pay"];
             $content 	= $row["content"];
             $lat 		= $row["lat"];
             $lng 		= $row["lng"];
    }

    // 근무일자 조회, 지원한 근무일자는 체크되게
	$sql  = " SELECT A.date as employWorkDate, B.work_date as myWorkDate, IF(A.date=B.work_date,'지원한날짜임','지원안한날짜임') as myWorkDateYn FROM employ_date A LEFT OUTER JOIN  (SELECT * FROM work_join_list WHERE ruid = '$uid') B";
	$sql .= " ON  A.date = B.work_date WHERE 1=1 AND A.work_employ_data_no = '$employNo'";

   $result = mysql_query($sql, $ilbang_con);
    
    $employWorkDate 	= array();
    $myWorkDate 		= array();
    $myWorkDateYn 	 	= array();

    while($row = mysql_fetch_array($result)){
             $employWorkDate[] 	= $row["employWorkDate"];
             $myWorkDate[] 		= $row["myWorkDate"];
             $myWorkDateYn[] 	= $row["myWorkDateYn"];

    }


?>

<script>
	$( document ).ready(function() {
		getNotice();
		getLogoImage();
	getGuinCounter();

    	var onePage               = '<?php echo $_GET["HguinOnePage"];?>';
      var guinArea1st           = '<?php echo $_GET["HguinArea1st"];?>';
      var guinArea2nd           = '<?php echo $_GET["HguinArea2nd"];?>';
      var guinArea3rd           = '<?php echo $_GET["HguinArea3rd"];?>';
      var guinWork1st           = '<?php echo $_GET["HguinWork1st"];?>';
      var guinWork2nd           = '<?php echo $_GET["HguinWork2nd"];?>';
      var guinAge               = '<?php echo $_GET["HguinAge"];?>';
      var guinPay               = '<?php echo $_GET["HguinPay"];?>';
      //var guinPayIsUnrelated    = '<?php echo $_GET["HguinPayIsUnrelated"];?>';
      var guinTime              = '<?php echo $_GET["HguinTime"];?>';
      var guinGender            = '<?php echo $_GET["HguinGender"];?>';
      var guinCareer            = '<?php echo $_GET["HguinCareer"];?>';
      //var guinCareerIsUnrelated = '<?php echo $_GET["HguinCareerIsUnrelated"];?>';

   

      $("#HguinArea1st"           ).val(guinArea1st);
      $("#HguinArea2nd"           ).val(guinArea2nd);     
      $("#HguinArea3rd"           ).val(guinArea3rd);        
      $("#HguinWork1st"           ).val(guinWork1st);        
      $("#HguinWork2nd"           ).val(guinWork2nd);   
      $("#HguinAge"               ).val(guinAge);       
      $("#HguinPay"               ).val(guinPay);            
      //$("#HguinPayIsUnrelated"    ).val(guinPayIsUnrelated); 
      $("#HguinTime"              ).val(guinTime);  
      $("#HguinGender"            ).val(guinGender);
      $("#HguinCareer"            ).val(guinCareer); 
 	 });

	function getNotice() {
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: '../../ajax/getNoticeList.php',
            success: function(data) {
                for(var i=0; i<1; i++) {
                    document.getElementById("adNotice").innerHTML
                    = '<a href="../../notice/view.php?no=' + data.noticeList[i].no + '" class="c999">' + data.noticeList[i].title + '</a>';
                }
            }
        });
    }

	function goPage(addr,tab){
	  $("#tab").val(tab);
  	  $("#viewForm").attr("action", addr);
      document.getElementById("viewForm").submit();
	}

	function getGuinCounter(){
	    $.ajax({
	             type: 'post',
	             dataType: 'json',
	             url: '../../ajax/getGuinCounter.php',
	             data: {},
	             success: function (data) {
	               new numberCounter("newEmploy"    , data.listData[0].newEmploy);
	               new numberCounter("allEmploy"    , data.listData[0].allEmploy);
	               new numberCounter("newMatching"  , data.listData[0].newMatching);
	               new numberCounter("allMatching"  , data.listData[0].allMatching);
	            }
	     })
  }

 function numberCounter(target_frame, target_number) {
    this.count = 0; this.diff = 0;
    this.target_count = parseInt(target_number);
    this.target_frame = document.getElementById(target_frame);
    this.timer = null;
    this.counter();
  };
  
  numberCounter.prototype.counter = function() {
      var self = this;
      this.diff = this.target_count - this.count;
  
      if(this.diff > 0) {
          self.count += Math.ceil(this.diff / 5);
      }
  
      this.target_frame.innerHTML = this.count.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
  
      if(this.count < this.target_count) {
          this.timer = setTimeout(function() { self.counter(); }, 20);
      } else {
          clearTimeout(this.timer);
      }
  };  

  function apply(){

  	var euid 	 = '<?php echo $euid ?>';
  	var ruid 	 = '<?php echo $uid ?>';
	var work_date = [];
  	var work_employ_data_no = '<?php echo $employNo ?>';
  	
  	$("input[name=workDate]:checked").each(function() {
		work_date.push($(this).val());
	});
 
    $.ajax({
	             type: 'post',
	             dataType: 'json',
	             url: '../../ajax/gujik/apply.php',
	             data: {euid:euid, ruid:ruid, work_date:work_date, work_employ_data_no:work_employ_data_no },
	             success: function (data) {
	             	if(data.result=="0"){
	             		alert(data.msg);
	             	} else if (data.result=="1"){
	             		alert(data.msg);
	             		//alert(data.sql);
	             	}
	            }
	     })
	//alert("["+work_date.join()+"]");
	//alert("["+ruid+"]");
	//alert("["+euid+"]");
	//alert(work_employ_data_no);
  }
   function apply_remove(){

  	var euid 	 = '<?php echo $euid ?>';
  	var ruid 	 = '<?php echo $uid ?>';
	var work_date = [];
  	var work_employ_data_no = '<?php echo $employNo ?>';
  	
  	$("input[name=workDate]:checked").each(function() {
		work_date.push($(this).val());
	});
 
    $.ajax({
	             type: 'post',
	             dataType: 'json',
	             url: '../../ajax/gujik/applyRemove.php',
	             data: {euid:euid, ruid:ruid, work_date:work_date, work_employ_data_no:work_employ_data_no },
	             success: function (data) {
	             	console.log(data);

				for(var i=0; i<data.work_date.length; i++){
            		
            		var work_date = data.work_date[i];
            		// var chked_date =  chked_val.split(',');//선택한날
            		var date = document.getElementsByName("workDate");

            		for(var j=0; j<date.length; j++){
            		 if(date[j].value == work_date){
	            			date[j].checked=false;
	            		}
	            	 }//if(chked_date) 
            		}



	             	if(data.result=="0"){
	             		alert(data.msg);
	             	} else if (data.result=="1"){
	             		alert(data.msg);
	             		//alert(data.sql);
	             	}
	            
	            }
	     })
	
  }
     function getLogoImage() {


        var img_url='<?php echo $img_url;?>';

         if(img_url != null){
              console.log("이미지 있을경우");
              var company_logo= document.getElementById("company_logo");
                  company_logo.src="http://il-bang.com/pc_renewal/guinImage/"+img_url;
           }
 
    }

</script>
<div class="wdfull">
    <div class="container center wdfull bb mb15">
        <div class="pg_rp"> 
            <div class="c999 fl subTitle">
                <a href="../../index.php" class="c999">HOME</a>
                <span class="plr5">></span>
                <a href="../../guin.php" class="bold">구인자</a>
            </div>
            <div class="c999 fr padding-vertical">
                <span class="mr5 br15 subNotice">공지</span>
                <span id="adNotice"></span>
            </div>
        </div>
    </div>
</div>

<div class="container pl30">
<!-- 상단 카운트 영역 -->
	<div class="guingujikTabWrap fl pr">
	   <div class="quick pa" style="left: -145px;">  
	        <p class="di">
	            <!-- 내 정보 -->
	            <a href="my-page/myInfo-comp.php">
	              <img src="http://il-bang.com/pc_renewal/images/ggQuick1.png" alt="" class="db"> 
	            </a> 

	            <a href="guin.php?tab=2"">
	              <img src="http://il-bang.com/pc_renewal/images/ggQuick2.png" alt="" class="db"> 
	            </a>
	            <a href="javascript:alert('준비중입니다.');">
	              <img src="http://il-bang.com/pc_renewal/images/ggQuick3.png" alt="" class="db"> 
	            </a>
	        </p>   
	    </div>   
	</div>
<script>
	 /* quick menu */
	 $(".quick").animate( { "top": $(document).scrollTop() + 0 +"px" }, 500 ); // 빼도 된다.
	 $(window).scroll(function(){
	  $(".quick").stop();
	  if($(document).scrollTop()==0){
	  $(".quick").animate( { "top": $(document).scrollTop() + 0  + "px" }, 500 );
	}else{
	  $(".quick").animate( { "top": $(document).scrollTop() + -50 + "px" }, 500 );
	}
	 });	        	         	        
</script>				
			<div class="col-xs-5 col-sm-5 col-md-5 col-lg-5 oh">
				<a href="../../ad/adMoney.php" class="linkImg di w100" style="height: 105px;"></a>
			</div>
			<div class="col-xs-7 col-sm-7 col-md-7 col-lg-7 border-grey border-box gujikTopBox oh">
				<div class="gujikCount padding-vertical count1 fl tc padding-vertical; margin-vertical">
					<h4 class="margin-vertical"><span id="newEmploy"></span>건</h4>
					<p class="margin-vertical">신규신청서</p>	
				</div>
				<div class="gujikCount padding-vertical count2 fl tc padding-vertical; margin-vertical">
					<h4 class="margin-vertical"><span id="allEmploy"></span>건</h4>
					<p class="margin-vertical">전체 신청서</p>	
				</div>
				<div class="gujikCount padding-vertical count3 fl tc padding-vertical; margin-vertical">
					<h4 class="margin-vertical"><span id="newMatching"></span>건</h4>
					<p class="margin-vertical">신규매칭</p>	
				</div>
				<div class="gujikCount padding-vertical count4 fl tc padding-vertical; margin-vertical">
					<h4 class="margin-vertical"><span id="allMatching"></span>건</h4>
					<p class="margin-vertical">전체매칭</p>	
				</div>
				<a href="#" class="gujikCount count5 fl tc f14" style="background-color: black">
				<span class="glyphicon glyphicon-plus f22"></span>
					<p class="margin-vertical">신청서 작성</p>
				</a>
			</div>
		
	
</div>
<div class="wdfull">
	<div class="guingujikTabWrap">
	  <ul class="nav nav-tabs mt10" role="tablist" id="myTab">
	    <li role="presentation" class="noPadding guingujikTabLi text-center active"><a href="#gujikTab1" aria-controls="gujikTab1" role="tab" data-toggle="tab">전체</a></li>
	    <li class="noPadding guingujikTabLi text-center" role="presentation"><a href="javascript:goPage('../../gujik.php',2)" >구인 신청서</a></li>
	    <li class="noPadding guingujikTabLi text-center" role="presentation"><a href="javascript:goPage('../../gujik.php',3)" >매칭 리스트</a></li>
	    <li class="noPadding guingujikTabLi text-center" role="presentation"><a href="javascript:goPage('../../gujik.php',4)" >매칭 완료</a></li>
	  </ul>
	  <div class="tab-content">
	  <form method="get" id="viewForm">

	         <input type="hidden" name="tab"                 id="tab"              value="">
            <input type="hidden" name="HguinPage"              id="HguinPage"              value="<?php echo $page?>">
            <input type="hidden" name="HguinOnePage"           id="HguinOnePage"           value="<?php echo $onePage?>">
            <input type="hidden" name="HguinArea1st"           id="HguinArea1st"           value="">
            <input type="hidden" name="HguinArea2nd"           id="HguinArea2nd"           value="">
            <input type="hidden" name="HguinArea3rd"           id="HguinArea3rd"           value="">
            <input type="hidden" name="HguinWork1st"           id="HguinWork1st"           value="">
            <input type="hidden" name="HguinWork2nd"           id="HguinWork2nd"           value="">
            <input type="hidden" name="HguinAge"               id="HguinAge"               value="">
            
            <input type="hidden" name="HguinPay"               id="HguinPay"               value="">
            <!--<input type="hidden" name="HguinPayIsUnrelated"    id="HguinPayIsUnrelated"    value="">-->
            <input type="hidden" name="HguinTime"              id="HguinTime"              value="">
            <input type="hidden" name="HguinGender"            id="HguinGender"            value="">
            <input type="hidden" name="HguinCareer"            id="HguinCareer"            value="">
       </form>
	  <!-- 첫번째 탭 내용 -->
	    <div role="tabpanel" class="tab-pane active mt10 container pl30" id="gujikTab1">
	      
			<div class="gujikDetSect1 mt50">
				<div class="border-navy">
					<h4 class="bg_navy f_white padding-vertical tc noMargin"><?php echo  $title ?></h4>
					<ul class="detailInfoUl_guin pr">
						<li class="bb padding">
							<span class="di w10">간단소개</span><span class=""><?php echo  $business ?></span>
						</li>
						<li class="bb padding">
							<span class="di w10">담당자</span><span class=""><?php echo  $manager ?></span>
						</li>
						<li class="padding">
							<span class="di w10">회사위치</span><span class=""><?php echo  $address ?></span>
						</li>
						<li class="pa tc" style="left:20px; top:35px;">

							<img src="http://il-bang.com/pc_renewal/images/144x38.png" id="company_logo" alt="프로필" style="width:205px; height:54px;">
						</li>
					</ul>
				</div>
			</div>
				
			<h4 class="mt30 f16 mb10">
			  
			  근무조건 및 모집정보
			</h4>

			<div class="gujikDetSect2 oh">
				<div class="oh">
					<p class="fl w20 noMargin sect2head di bg_grey lh40 f14 tc">모집부문</p>
					<p class="fl w20 noMargin sect2head di bg_grey lh40 f14 tc">성별</p>
					<p class="fl w20 noMargin sect2head di bg_grey lh40 f14 tc">나이</p>
					<p class="fl w20 noMargin sect2head di bg_grey lh40 f14 tc">경력</p>
					<p class="fl w20 noMargin sect2head di bg_grey lh40 f14 tc">외국인 고용 특례</p>
				</div>
				<div class="oh">
					<p class="w20 fl sect2Table di lh70 f14 tc"><?php echo  $work_2nd_nm ?></p>
					<p class="w20 fl sect2Table di lh70 f14 tc"><?php echo  $sex ?></p>
					<p class="w20 fl sect2Table di lh70 f14 tc"><?php echo  $age_1st ?>세 ~ <?php echo  $age_2nd ?>세</p>
					<p class="w20 fl sect2Table di lh70 f14 tc"><?php echo  $career ?></p>
					<p class="w20 fl sect2Table di lh70 f14 tc"><?php echo  $fn ?></p>
				</div>
			</div>

			<h4 class="mt30 f16 mb10">
				  
				  모집내용
				</h4>
			<div class="gujikDetSect3 oh mb30">

				<div class="oh">
					<div class="oh cont"><p class="fl w20 noMargin sect2head di bg_grey lh40 f14 pl15 tl">담당업무</p><p class="fl sect2Table di lh40 f12 tc noMargin pdl20"><?php echo  $business ?></p></div>
					<div class="oh cont"><p class="fl w20 noMargin sect2head di bg_grey lh40 f14 pl15 tl">담당자 연락처</p><p class="fl sect2Table di lh40 f12 tc noMargin pdl20"><?php echo  $phone ?></p></div>
					<div class="oh cont bg_grey"><p class="fl w20 noMargin sect2head di bg_grey lh40 f14 pl15 tl">근무일자</p><p class="fl sect2Table di lh40 f12 tc noMargin pl10 workDate bg_white">
						

						<?php
							for($i=0; $i<count($employWorkDate); $i++){
						?>	
							<input type="checkbox" class="mr5 ml10 vm" name="workDate" <?php if($myWorkDateYn[$i]=="지원한날짜임") { ?> checked <?php } ?>  value="<?php echo $employWorkDate[$i] ?>" /><?php echo $employWorkDate[$i] ?>
						<?php
						}
						?>
					</p></div>
					<div class="oh cont"><p class="fl w20 noMargin sect2head di bg_grey lh40 f14 pl15 tl">근무시간</p><p class="fl sect2Table di lh40 f12 tc noMargin pdl20"><?php echo  $time ?></p></div>
					<div class="oh cont"><p class="fl w20 noMargin sect2head di bg_grey lh40 f14 pl15 tl">금액(일당)</p><p class="fl sect2Table di lh40 f12 tc noMargin pdl20"><b class="fc"><?php echo  number_format($pay) ?></b></p></div>
					<div class="oh"><p class="fl w100 noMargin sect2head di bg_grey lh40 f14 pl15 tl">상세모집요강</p></div>
					<div class="pb20 sect3Inner">
						<p class="tl p15">
						<?php echo  $content ?>
						</p>
					</div>
				</div>
			</div>
			
			<div class="tc">
				<a href="../review.php?employNo=<?php echo $employNo ?>" class="info-review-btn">평가보기</a>
				<a href="javascript:apply()" class="info-guin-btn">지원하기</a>
				<a href="javascript:apply_remove()" class="info-guin-btn">취소하기</a>
			</div>

			<h4 class="mt30 f16 mb10">
				위치정보
			</h4>
			<div class="gujikDetSect4">
				<div class="oh cont bb"><p class="fl w20 noMargin sect2head di bg_grey lh40 f14 tc">위치정보</p><p class="fl sect2Table di lh40 f12 tc noMargin pdl20"><?php echo  $area_1st_nm ?> <?php echo  $area_2nd_nm ?> <?php echo  $area_3rd_nm ?> <?php echo  $address ?></p></div>
				<div class="oh cont"><p class="fl w20 noMargin sect2head di bg_grey lh40 f14 tc mapSecTlt">지도보기</p><p class="fl sect2Table di lh40 f12 tc noMargin pdl20">
					<div id="map" style="width:849px;height:495px;"></div>
					<script>
							// var container = document.getElementById('map');
							// var options = {
							// 	center: new daum.maps.LatLng(33.450701, 126.570667),
							// 	level: 3
							// };

							// var map = new daum.maps.Map(container, options);
							  var lat ='<?php echo  $lat ?>';
							  var lng = '<?php echo  $lng ?>';

							  if (lat == '' || lng == '' ){
							  	lat = "37.565742";
							  	lng = "126.977966";
							  }

							var mapContainer = document.getElementById('map'), // 지도를 표시할 div 
							    mapOption = { 
							        center: new daum.maps.LatLng(lat, lng), // 지도의 중심좌표
							        level: 3 // 지도의 확대 레벨
							    };

							var map = new daum.maps.Map(mapContainer, mapOption); // 지도를 생성합니다

							// 마커가 표시될 위치입니다 
							var markerPosition  = new daum.maps.LatLng(lat, lng); 

							// 마커를 생성합니다
							var marker = new daum.maps.Marker({
							    position: markerPosition
							});

							// 마커가 지도 위에 표시되도록 설정합니다
							marker.setMap(map);

						</script>
				</p></div>
				
			</div>
	      	<div class="botWarn mt30 mb60">
				<div class="botWarnImg fl"><img src="http://il-bang.com/pc_renewal/images/top_left_logo.png" alt="로고" class="wh95"></div>
	      		<div class="botWarnTxt ml25">
	      			본 구인정보는 게시자가 제공한 자료이며, (주)엠엠씨피플 일방은 기재된 내용에 대한 오류와 지연에 사용자가 이를 신뢰하여 취한 조치에 대해<br>
	      			책임을 지지 않습니다. 본 정보의 저작권은 (주)엠엠씨피플 일방에 있으며, 동의없이 무단개제 및 재배포 할 수 없습니다. 
	      		</div>
	      	</div><div class="clear"></div>

	    </div>
	    <!-- 두번째 탭 내용 -->
	    <div role="tabpanel" class="tab-pane mt10 container pl30" id="gujikTab2">
	        asidjaskasldkjasdkasdkj2
	    </div>
	    <div role="tabpanel" class="tab-pane mt10 container pl30" id="gujikTab3">
	        asidjaskasldkjasdkasdkj3
	    </div>
	    <div role="tabpanel" class="tab-pane mt10 container pl30" id="gujikTab4">
	        asidjaskasldkjasdkasdkj4
	    </div>
	  </div>
	</div>
</div>

<?php include_once "../../include/footer.php"; ?>