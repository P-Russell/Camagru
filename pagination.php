<?php

if(!isset($_GET['page'])){
    $_GET['page'] = 0;
}else{
    $_GET['page'] = (int)$_GET['page'];
}
$articlesPerPage = 2;
$totalArticles = count_images();
$totalPages = ceil($totalArticles / $articlesPerPage);

if($_GET['page'] < 1){
    $_GET['page'] = 1;
}else if($_GET['page'] > $totalPages){
    $_GET['page'] = $totalPages;
}
?>
<div class="pagination">
<?php
if ($totalPages) {
foreach(range(1, $totalPages) as $page) {
    echo '<a href="?page=' . $page . '">' . $page . '</a>';
    /*   if($page == $_GET['page']) {
           echo '<span class="currentpage">' . $page . '</span>';
       }
       if($page == 1 || $page == $totalPages || ($page >= $_GET['page'] - 2 && $page <= $_GET['page'] + 2)){
           echo '<a href="?page=' . $page . '">' . $page . '</a>';
       }*/
}
}
?>
</div>