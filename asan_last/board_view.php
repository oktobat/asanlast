<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta property="og:url" content="http://tsalt.dothome.co.kr">
    <meta property="og:image" content="./images/snsicon.png">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="아산병원">
    <meta property="og:locale" content="ko">
    <meta property="og:title" content="아산병원">
    <meta property="og:description" content="서울아산병원을 소개하는 글">

    <title>게시글제목 :: 아산병원</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="shortcut icon" href="./images/favicon.ico">  <!-- 주소표시줄 : 파비콘(16*16) -->
    <link rel="apple-touch-icon" href="./images/asan.png">  <!-- 스마트폰 바탕화면 : 바로가기 아이콘(144*144)-->
    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
</head>
<body>
    
    <div id="wrap">

        <?php include 'header.php'; ?>

         <section class="boardPage">
            <div class="boardContent">
                <h2>자유게시판 > 상세보기</h2>

                <?php 
                    $num = $_GET["num"];
                    $page = $_GET["page"];

                    $con = mysqli_connect("localhost", "tsalt", "truesalt9!", "tsalt");
                    $sql = "select * from mobboard where num='$num'";
                    $result = mysqli_query($con, $sql);
                
                    $row = mysqli_fetch_array($result);
                    $subject = $row["subject"];
                    $irum = $row["irum"];
                    $regist_day = $row["regist_day"];
                    $content = $row["content"];
                    $id = $row["id"];
                    $nnum = $row["num"];
                    $file_name = $row["file_name"];
                    $file_copied = $row["file_copied"];
                    $file_type = $row["file_type"];
                    
                    $content = str_replace(" ", "&nbsp;", $content);
                    $content = str_replace("\n", "<br>", $content);

                    $hit = $row["hit"];
                    $new_hit = $hit + 1;
                    $sql = "update mobboard set hit='$new_hit' where num='$num'";
                    mysqli_query($con, $sql);

                    $sqlcomm = "select * from comment where onum='$num'";
                    $resultcomm = mysqli_query($con, $sqlcomm);
                    $total_record = mysqli_num_rows($resultcomm);
                    mysqli_close($con);
                ?>
                <ul id="view_content">
                    <li>
                        <p> 제목 : <?=$subject?> </p>
                        <p> <?=$irum?> | <?=$regist_day?> </p>    
                    </li>
                    <li>
                        <p>
                            <?php 
                                if ( $file_name ) {
                                    $file_path = "./data/".$file_copied;
                                    $file_size = filesize($file_path);
                                    // echo "첨부파일 : $file_name ({$file_size} Byte)  &nbsp;&nbsp;&nbsp;&nbsp;
                                    //     <a href='$file_path' download>[다운로드]</a>
                                    // ";
                                    echo "첨부파일 : $file_name ({$file_size} Byte)  &nbsp;&nbsp;&nbsp;&nbsp;
                                        <a href='board_download.php?file_name=$file_name&file_path=$file_path'>[다운로드]</a>
                                    ";
                                } else {
                                    $file_path = "";
                                }
                            ?>
                        </p>
                        <p><?=$content?></p>
                    </li>
                </ul>
                <div class="buttons">
                    <button type="button" onclick="location.href='board_list.php?page=<?=$page?>'">목록</button>

                    <?php 
                        if ($userid) {
                             if ($userid==$id) {
                    ?>
                                    <button type="button" onclick="location.href='board_modify_form.php?num=<?=$nnum?>&page=<?=$page?>'">수정</button>
                                    <button type="button" onclick="location.href='board_delete.php?num=<?=$nnum?>&page=<?=$page?>&file_path=<?=$file_path?>'">삭제</button>
                    <?php
                             } 
                    ?>
                                    <button type="button" onclick="location.href='board_form.php'">글쓰기</button>
                    <?php
                        } else {
                    ?>
                              <button type="button" onclick="alert('로그인후 이용해 주세요!')">글쓰기</button>
                    <?php          
                        }
                    ?>

                </div>

               <?php 
                if ($userid) {
                ?>
                    <!-- 댓글등록 -->
                <div class="commentBox">
                    <h3>댓글</h3>
                    <div class="comment">
                        <form name="comment_form" action="comm_insert.php?userid=<?=$userid?>&username=<?=$username?>&onum=<?=$num?>&page=<?=$page?>" method="post">
                            
                            <?php 
                                for ($i=0; $i<$total_record; $i++) {
                                    mysqli_data_seek($resultcomm, $i);
                                    $rowcomm = mysqli_fetch_array($resultcomm);
                                    $id_comm = $rowcomm["id"];
                                    $irum_comm = $rowcomm["irum"];
                                    $content_comm = $rowcomm["content"];
                                    $regist_day_comm = $rowcomm["regist_day"];
                                    $cnum_comm = $rowcomm["cnum"];
                            ?>
                                    <div class="comment_list">
                                        <?=$irum_comm?><?=$content_comm?><?=$regist_day_comm?>
                                        <?php 
                                            if ($userid==$id_comm) {
                                        ?>
                                            <button type="button" onclick="window.open('comment_modify.php?cnum=<?=$cnum_comm?>&content=<?=$content_comm?>&num=<?=$num?>&page=<?=$page?>', '', 'top=100, left=100, width=300, height=400')">(수정)</button>
                                            <button type="button" onclick="location.href='comment_delete.php?cnum=<?=$cnum_comm?>&num=<?=$num?>&page=<?=$page?>'">(삭제)</button>
                                        <?php        
                                            }
                                        ?>
                                    </div>
                            <?php
                                }
                            ?>

                            <div class="comm_insert">
                                <span style="vertical-align:middle"><?=$username?></span>
                                <textarea name="content" id="comt" style="width:50%; height:40px; vertical-align:middle"></textarea>
                                <button type="button" onclick="comm_check()" style="vertical-align:middle">등록</button>
                            </div>
                        </form>
                    </div>
                </div>

                <?php
                }
               ?>
                


            </div>
        </section>

        <?php include 'footer.php'; ?>

    </div>


    <div id="onlyMobile">
        <p>이 사이트는 768px 이하 화면에서만 보입니다.</p>
    </div>

    <script src="./js/asan.js"></script>
    <script>
        function comm_check() {
            if (!document.comment_form.content.value) {
                alert("댓글을 입력하세요.")
                document.comment_form.content.focus()
                return false
            }
            document.comment_form.submit()
        }
        
    </script>
</body>
</html>