<?php 

/*
	*Task 2 File
	*
*/
	
/* 
	*Include database connection file
*/

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
		 while($r = $q->fetchAll(PDO::FETCH_OBJ)){
		 	$data[]=$r;
		 }
		 return $this->buildTree($data[0]);
	}
	/*
 		* Database data to tree making function
 	*/

	public function buildTree($items) {
	    $children = [];
	    foreach($items as &$item) {
	      $children[$item->parent][] = $item; 
	      unset($item);
	    }
	    foreach($items as $item) {
	      if(isset($children[$item->id])) {
	      	$item->count= array_sum(array_column($children[$item->id],'count'));
	        $item->children += $children[$item->id];
	      }
	    }
	    foreach ($children[0] as $value) {
	    	$value->count += array_sum(array_column($value->children,'count'));
	    }
	    return $children[0];
	}

	/*
 		* Recursive loading data
 	*/

	public function recursivePrint($data){
		foreach ($data as $value) {
			echo "<ul>";
				echo "<li>" . $value->name . "(" .$value->count .')'. "</li>";
				if (isset($value->children)) {
					$this->recursivePrint($value->children);
				}
			echo "</ul>";	
		}
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

	<style>
		.root{}
		.root li{}
		.child_one{}
		.child_one li{}
		.child_two{}
		.child_two li{}
		.child_three li{}
		.child_three{}
	</style>
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="sidebar">
					<?php foreach($categories as $category ): ?>
						<ul class="navbar root">
							<li> <?=$category->name .' ('.$category->count.')';?>
								<?php $obj->recursivePrint($category->children); ?>
							</li>
						</ul>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
