<?php 

// Include database connection file

include('database.php');

 class databaseTaskTwo{

 	private $conn=false;
 	public function __construct($connection){
 		$this->conn=$connection; // Connection check from database file and store $conn property
 	}
 	/*
 		* Data query
 	*/
    public function category_tree(){
		 $sql='select c.Id as id, c.Name as name, case when cr.ParentcategoryId is null then 0 else cr.ParentcategoryId end parent, count(item.Id) as count from category c left join item_category_relations ir on ir.categoryId=c.Id left join item on item.Number=ir.ItemNumber left join catetory_relations cr on cr.categoryId=c.Id group by c.Id';
		 $q = $this->conn->query($sql) or die("failed!");
		 $data= $q->fetchAll(PDO::FETCH_ASSOC);
		 return $this->buildTree($data);
	}
	/*
 		* Database data to tree making function
 	*/
 	function buildTree(&$elements, $parentId = 0) {
	    $branch = [];
	    foreach ($elements as &$element) {
	        if ($element['parent'] == $parentId) {
	            $children = $this->buildTree($elements, $element['id']);
	            if ($children) {
	                $element['children'] = $children;
	                $element['count'] += array_sum(array_column($children, 'count'));
	            }
	            $branch[$element['id']] = $element;
	            unset($element);
	        }
	    }
	    return $branch;
	}
	/*
 		* Recursive loading data
 	*/
	public function recursivePrint($data){
		echo "<ul>";
		foreach ($data as $category) {			
			echo '<li>' . $category['name'] . ' (' . $category['count'] . ')' . '</li>';
			if (isset($category['children'])) {
				$this->recursivePrint($category['children']);
			}
		}
		echo "</ul>";
	}	
 }

/*
	* Object create from databaseTaskTwo with data connection parameter
*/
$obj= new databaseTaskTwo($connection->conn /*Connection data*/ );
/*
	* database data query method call
*/
$categories= $obj->category_tree();


?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Brain Station Assignment Task 2</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="sidebar">
					<?php $obj->recursivePrint($categories); ?>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
