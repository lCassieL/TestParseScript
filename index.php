<?php
// Script start
$rustart = getrusage();

$categories = [];
$db = new mysqli('eu-cdbr-west-03.cleardb.net', 'be8093adde50b7', '4bf02618', 'heroku_12488d9ac6849c3');
if($db->connect_errno === 0) {
    $query = "SELECT SQL_NO_CACHE * FROM categoriest2;";
    $categories = $db->query($query);
    if($categories) {
        $categories = $categories->fetch_all(MYSQLI_ASSOC);
    } else {
        echo 'empty';
    }
}
$result = [];
categoryParsing(0, $result, $categories);
function categoryParsing($parent_id, &$category_node, &$categories) {
    foreach($categories as $key=>$category) {
        if($category['parent_id'] == $parent_id) {
            $category_node[$parent_id][$category['categories_id']] = [];
            array_splice($categories, $key, 1);
            categoryParsing($category['categories_id'], $category_node[$parent_id], $categories);
        }
    }
    if(count($category_node[$parent_id]) == 0) $category_node[$parent_id] = $parent_id;
}

// Script end
function rutime($ru, $rus, $index) {
    return ($ru["ru_$index.tv_sec"]*1000 + intval($ru["ru_$index.tv_usec"]/1000))
     -  ($rus["ru_$index.tv_sec"]*1000 + intval($rus["ru_$index.tv_usec"]/1000));
}

$ru = getrusage();
echo "This process used " . rutime($ru, $rustart, "utime") .
    " ms for its computations\n";
echo "It spent " . rutime($ru, $rustart, "stime") .
    " ms in system calls\n";
?>
<pre>
<?php print_r($result); ?>
</pre>