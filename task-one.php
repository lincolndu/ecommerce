<?php 

/*
	*Task 1 File
	*
*/
	
/* 
	*Include database connection file
*/
include('database.php');

 class databaseTaskOne{

 	private $conn=false;
 	public function __construct($connection){
 		$this->conn=$connection; // Connection check from database file and store $conn property
 	}
 	/*
 		* Data query
 	*/
    public function showData(){
		 $sql='select category.Name as name, count(item.Id) as total from category left join item_category_relations on item_category_relations.categoryId=category.Id left join item on item.Number=item_category_relations.ItemNumber group by category.Id order by total desc';
		 $q = $this->conn->query($sql) or die("failed!");
		 $data=[];
		 while($r = $q->fetchAll(PDO::FETCH_OBJ)){
		 	$data[]=$r;
		 }
		 return $data[0];
	 }
 }

/*
	* Object create from databaseTaskOne with data connection parameter
*/

$obj= new databaseTaskOne($connection->conn /*Connection data*/);
/*
	* Database data query Calling method
*/
$dataitems= $obj->showData();

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Brain Station Assignment Task One</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>

	<div class="container my-5">
		<div class="row">
			<div class="col-md-8 offset-md-2">
				<table class="table table-bordered">
				  <thead>
				    <tr>
				      <th scope="col"> Category Name </th>
				      <th scope="col"> Total Items </th>
				    </tr>
				  </thead>
				  <tbody>
					
					<?php foreach($dataitems as $item): ?>
					    <tr>
					      <td><?=$item->name;?></td>
					      <td><?=$item->total;?></td>
					    </tr>
				    <?php endforeach; ?>


				  </tbody>
				</table>


			</div>
		</div>
	</div>
	
</body>
</html>
