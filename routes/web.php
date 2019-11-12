<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
    return view('welcome');
});

//Session::push()

//Session::has()
//Session::forget()
//Session(['key'=>'value']);
//Session::get('key');

Route::get('/test',function(){
		
	session(['login'=>'yes','key2'=>'value2']);
	  	
});

Route::get('/view',function(){
	
	echo session('login');
	
});

Route::get('/status',function(){
	
	echo session()->has('login');
	
});

Route::get('/forget',function(){
	
	echo session()->forget('login');
	
});


Route::get('user/create',function(){
 
 DB::insert("insert into user(username,full_name,password,role_id)values('towhid','Mohammad Towhidul Islam','111111',1)");	
 
 echo "success";
 
});

Route::get('/login',function(){
	
		
	$result=DB::select("select  id,username,full_name,password,role_id from user where id=1");

   if(count($result)){
	   session(['id'=>$result[0]->id,
	            'full_name'=>$result[0]->full_name, 'role_id'=>$result[0]->role_id
				]);
				
		return redirect("/");			
	   
	}else{
	  
	  //return redirect("/");	
	
	}
	
	
});


Route::get('/cart/add',function(){
		
	$items=array("Apple","Apricot","Asparagus","Aubergine","Avocado","Banana","Beetroot","Black-eye bean","Broad bean","Broccoli","Brussels sprout","Butternut Squash","Carrot","Cherry","Clementine","Courgette","Date","Elderberry");
		
			
	   $newid= is_array(session()->get('items'))?count(session()->get('items')):1;	
			
		
	session()->push('items',[
	  'id'=>$newid,
	  'name'=>$items[rand(0,count($items))],
	  'qty'=>rand(1,10),
	  'price'=>rand(1,200)	
	]);
	
	
});

Route::get('/cart/delete/{id}',function($id){
			
	
	  $items = session()->get('items');

    foreach ($items as $key => $val) {
        if($id == $val["id"]){
	      session()->forget('items.'.$key);
		}
	}
	
});

Route::get('/cart/view',function(){
	
	$items=session()->get('items');
	
	 if(isset($items)){
		 
		 echo "<table>";
		 echo "<tr><th>Id</th><th>Name</th><th>Qty</th><th>Price</th><th>Total</th></tr>";
		 $total=0;
		foreach($items as $key=>$value){
			
			$line_total=$value["qty"]*$value["price"];
			$total+=$line_total;
			echo "<tr><td>".$value["id"]."</td><td>".$value["name"]."</td><td>".$value["qty"]."</td><td>".$value["price"]."</td><td>".$line_total."</td><tr/>";
		}
		echo "<tr><td colspan='4'>Total</td><td>$total</td></tr>";
		
		echo "</table>";
	 }
	
});

Route::get('/cart/flash',function(){
  session()->flash("items");
  	
	
});

Route::get('/cart/save',function(){
	
	$items=session()->get('items');
		
	$order_id=DB::table("order_master")->insertGetId(
	 ['customer'=>'POS Customer']
	);
	
	foreach($items as $key=>$value){
			 
			 $qty=$value["qty"];
			 $price=$value["price"];
			 $item=$value["name"];
		
		 DB::insert("insert into order_details(order_id,item,qty,price)values('$order_id','$item','$qty','$price')");	
		 
	}
	 session()->flash("items");
	
});


