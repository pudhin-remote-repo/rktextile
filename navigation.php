<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Rathi kannan textile</title>
  <style>
    /* CSS for navigation menu */
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
    }

    nav {
      background-color: #333;
      padding: 10px 20px;
      display: flex;
      justify-content: space-between; /* Distribute items along the main axis */
      align-items: center; /* Center items along the cross axis */
    }

    nav ul {
      list-style: none;
      padding: 0;
      margin: 0;
      display: flex; /* Display menu items in a row */
    }

    nav ul li {
      margin-right: 20px;
    }

    nav ul li:last-child {
      margin-right: 0;
    }

    nav ul li a {
      color: white;
      text-decoration: none;
      padding: 10px 20px;
      display: block;
      border-radius: 5px;
      transition: background-color 0.3s ease;
    }

    nav ul li a:hover {
      background-color: #555;
    }
    .company-name {
      color: yellow;
      font-size: 20px;
      text-align: center;
      flex: 1; /* Occupy remaining space */
      font-weight: bold; /* Set font weight to bold */
      text-transform: uppercase; /* Convert text to uppercase */
      text-shadow: 1px 1px 1px #000; /* Add a shadow effect */
    }
  </style>
  <script>
    function OpenProductForm() {
            const productForm = document.getElementById("content-form-product");
            const userForm = document.getElementById("content-form-user");


            if (productForm.style.display === "none") {
              userForm.style.display = "none";
                productForm.style.display = "block";

            } 
            else {
                productForm.style.display = "none";
            }
        }
        function OpenUserForm() {
            const userForm = document.getElementById("content-form-user");
            const productForm = document.getElementById("content-form-product");


            if (userForm.style.display === "none") {
              productForm.style.display = "none";
                userForm.style.display = "block";

            } 
            else {
                userForm.style.display = "none";
            }
        }
  </script>

</head>
<body>

<nav>

  <ul>
    <li><a href="index.php">Home</a></li>
    <li><a href="bill_index">Bills</a></li>
    <li><a href="product_index.php">Products</a></li>
    <li><a href="client_index.php">Clients</a></li>
  </ul>
  <div class="company-name">Rathi Kannan Textile</div>

  <ul>
    <li><a href="javascript:void(0);" onclick="OpenProductForm()">New Product</a></li>
    <li><a href="javascript:void(0);" onclick="OpenUserForm()">New Client</a></li>
    <li><a href="logout.php">Logout</a></li>
  </ul>
</nav>
<main>
  
<div class="product-add-form container" id="content-form-product" style="display: none;">
    <form id="productForm" method="post" action="submit_product.php">
    <div class="form-group">
    <input type="text" name="productName" placeholder="Product Name" class="form-control"  required>

    </div>
    <div class="form-group">
    <input type="text" name="productPrice" placeholder="Product HSN Code"  class="form-control"  >

    </div>
    <div class="form-group">
    <button type="submit" class="btn btn-primary" >Add Product</button>

    </div>

    </form>
</div>


<div class="content-add-form container" id="content-form-user" style="display: none;">
    <form id="userForm" method="post" action="submit_user.php">
    <div class="form-group">
        <input type="text" name="userName" placeholder="User Name" class="form-control" required>
    </div>
    <div class="form-group">
        <input type="text" name="userAddress" placeholder="User Address" class="form-control" required>
    </div>
    <div class="form-group">
        <input type="text" name="userMobile" placeholder="User Mobile" class="form-control" >
    </div>
    <div class="form-group">
        <input type="text" name="userGST" placeholder="Company GST NO" class="form-control" >
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary" >Add User</button>
    </div>
    </form>
</div>
</main>



</body>
</html>
