<?php
// Database Connection Class
class Database
{
    private $host = "localhost";
    private $user = "root";
    private $pass = "";
    private $db = "pos_shop";
    protected $connection;

    public function __construct()
    {
        $this->connection = mysqli_connect($this->host, $this->user, $this->pass, $this->db);
        if (!$this->connection) {
            die("Tidak bisa terkoneksi ke database");
        }
    }

    public function closeConnection()
    {
        mysqli_close($this->connection);
    }
}

// Product Class for CRUD Operations
class Product extends Database
{
    public function createProduct($product_name, $category_id, $product_code, $unit, $description, $price, $stock, $image_paths)
    {
        $product_name = mysqli_real_escape_string($this->connection, $product_name);
        $category_id = mysqli_real_escape_string($this->connection, $category_id);
        $product_code = mysqli_real_escape_string($this->connection, $product_code);
        $unit = mysqli_real_escape_string($this->connection, $unit);
        $description = mysqli_real_escape_string($this->connection, $description);
        $price = mysqli_real_escape_string($this->connection, $price);
        $stock = mysqli_real_escape_string($this->connection, $stock);

       $gambar_json = serialize($image_paths);
       $gambar_json = mysqli_real_escape_string($product->connection, $gambar_json);

       $sql = "INSERT INTO products (product_name, category_id, product_code, unit, description, price, stock, image) VALUES ('$product_name', '$category_id', '$product_code', '$unit', '$description', '$price', '$stock', '$gambar_json')";


        $result = mysqli_query($this->connection, $sql);
        return $result;
    }

    public function updateProduct($product_code, $product_name, $category_id, $unit, $description, $price, $stock, $image_paths)
    {
        $product_code = mysqli_real_escape_string($this->connection, $product_code);
        $product_name = mysqli_real_escape_string($this->connection, $product_name);
        $category_id = mysqli_real_escape_string($this->connection, $category_id);
        $unit = mysqli_real_escape_string($this->connection, $unit);
        $description = mysqli_real_escape_string($this->connection, $description);
        $price = mysqli_real_escape_string($this->connection, $price);
        $stock = mysqli_real_escape_string($this->connection, $stock);

       // Instantiate the Product class to access the database connection
$product = new Product();

// Then use $product to access the connection
$gambar_json = mysqli_real_escape_string($product->connection, $gambar_json);


        $sql = "UPDATE products SET product_name = '$product_name', category_id = '$category_id', unit = '$unit', description = '$description', price = '$price', stock = '$stock', image = '$gambar_json' WHERE product_code = '$product_code'";

        $result = mysqli_query($this->connection, $sql);
        return $result;
    }

    public function deleteProduct($product_code)
    {
        $product_code = mysqli_real_escape_string($this->connection, $product_code);
        $sql = "DELETE FROM products WHERE product_code = '$product_code'";
        $result = mysqli_query($this->connection, $sql);
        return $result;
    }

    public function getProductByCode($product_code)
    {
        $product_code = mysqli_real_escape_string($this->connection, $product_code);
        $sql = "SELECT * FROM products WHERE product_code = '$product_code'";
        $result = mysqli_query($this->connection, $sql);
        return mysqli_fetch_assoc($result);
    }

    public function getProducts()
    {
        $sql = "SELECT * FROM products ORDER BY product_code DESC";
        $result = mysqli_query($this->connection, $sql);
        $products = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }
        return $products;
    }
}

// Create an instance of the Product class
$product = new Product();

$product_name = $category_id = $product_code = $unit = $description = $price = $stock = $image_paths = [];

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

if ($op == 'delete') {
    $product_code = $_GET['product_code'];
    $result = $product->deleteProduct($product_code);
    if ($result) {
        $sukses = "Berhasil hapus data";
    } else {
        $error = "Gagal melakukan delete data";
    }
}

if ($op == 'edit') {
    $product_code = $_GET['product_code'];
    $productData = $product->getProductByCode($product_code);

if ($productData) {
    $product_name = $productData['product_name'];
    $category_id = $productData['category_id'];
    $product_code = $productData['product_code'];
    $unit = $productData['unit'];
    $description = $productData['description'];
    $price = $productData['price'];
    $stock = $productData['stock'];
    $image_paths = unserialize($productData['image']);
} else {
    $error = "Data tidak ditemukan";
}
}
// ...

if (isset($_POST['simpan'])) {
    $product_name = $_POST['product_name'];
    $category_id = $_POST['category_id'];
    $product_code = $_POST['product_code'];
    $unit = $_POST['unit'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    // Instantiate the Product class to access the database connection
    $product = new Product();

    // Serialize the array before storing it
    $gambar_json = serialize($image_paths);
    $gambar_json = mysqli_real_escape_string($product->connection, $gambar_json);

    // Handle image upload and processing
    if (isset($_FILES['gambar']) && !empty($_FILES['gambar']['name'][0])) {
        $gambar_produk_dir = "htdocs/haloo/pos_shop/";

        foreach ($_FILES['gambar']['tmp_name'] as $key => $tmp_name) {
            $gambar_name = $_FILES['gambar']['name'][$key];
            $gambar_tmp = $_FILES['gambar']['tmp_name'][$key];
            $gambar_path = $gambar_produk_dir . $gambar_name;

            if (move_uploaded_file($gambar_tmp, $gambar_path)) {
                $image_paths[] = $gambar_path;
            }
        }
    }

    if ($product_name && $category_id && $product_code && $unit && $description && $price && $stock) {
        if ($op == 'edit') {
            $result = $product->updateProduct($product_code, $product_name, $category_id, $unit, $description, $price, $stock, $image_paths);
            if ($result) {
                $sukses = "Data berhasil diupdate";
            } else {
                $error = "Data gagal diupdate";
            }
        } else {
            $result = $product->createProduct($product_name, $category_id, $product_code, $unit, $description, $price, $stock, $image_paths);
            if ($result) {
                $sukses = "Berhasil memasukkan data baru";
            } else {
                $error = "Gagal memasukkan data";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
    .mx-auto {
      width: 800px
      max-width: 400px;
      margin: 0 auto;
      padding: 30px;
      border: 1px solid #e1e1e1;
      border-radius: 5px;
      background-color: pink;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

    .card {
      margin-top: 10px;
      width: 800px
      max-width: 400px;
      margin: 0 auto;
      padding: 30px;
      border: 2px solid black;;
      border-radius: 5px;
      background-color: pink;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <div class="mx-auto">
        <div class="card">
            <div class="card-header">
                Create / Edit data
            </div>
            <div class="card-body">
                <form action="" method="POST" enctype="multipart/form-data">
                    <!-- Include the form fields for product data, similar to your original code -->
                    <div class="mb-3 row">
                        <label for="description" class="col-sm-2 col-form-label">product_name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="product_name" name="product_name"
                                value="<?php echo $product_name ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="stock" class="col-sm-2 col-form-label">category_id</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="category_id" name="category_id"
                                value="<?php echo $category_id ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="stock" class="col-sm-2 col-form-label">product_code</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="product_code" name="product_code"
                                value="<?php echo $product_code ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="stock" class="col-sm-2 col-form-label">unit</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="unit" name="unit" value="<?php echo $unit ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="stock" class="col-sm-2 col-form-label">description</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="description" name="description"
                                value="<?php echo $description ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="stock" class="col-sm-2 col-form-label">price</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="price" name="price"
                                value="<?php echo $price ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="stock" class="col-sm-2 col-form-label">stock</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="stock" name="stock"
                                value="<?php echo $stock ?>">
                        </div>
                    </div>
                    <label for="gambar">Gambar Produk:</label>
                    <input type="file" id="gambar" name="gambar[]" multiple required>
                    <div class="col-12">
                        <input type="submit" name="simpan" value="Simpan Data" class="btn btn-primary" />
                    </div>
                </form>
            </div>
        </div>

        <!-- untuk menampilkan data -->

        <div class="card">
            <div class="card-header text-white bg-secondary">
                Data Product
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Product_name</th>
                            <th scope="col">category_id</th>
                            <th scope="col">product_code</th>
                            <th scope="col">unit</th>
                            <th scope="col">description</th>
                            <th scope="col">price</th>
                            <th scope="col">stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $products = $product->getProducts();
                        $urut = 1;
                        foreach ($products as $product) {
                        ?>
                            <tr>
                                <th scope="row"><?php echo $urut++ ?></th>
                                <td scope="row"><?php echo $product['product_name'] ?></td>
                                <td scope="row"><?php echo $product['category_id'] ?></td>
                                <td scope="row"><?php echo $product['product_code'] ?></td>
                                <td scope="row"><?php echo $product['unit'] ?></td>
                                <td scope="row"><?php echo $product['description'] ?></td>
                                <td scope="row"><?php echo $product['price'] ?></td>
                                <td scope="row"><?php echo $product['stock'] ?></td>
                                <td scope="row">
                                    <a href="index.php?op=edit&product_code=<?php echo $product['product_code'] ?>">
                                        <button type="button" class="btn btn-warning">Edit</button>
                                    </a>
                                    <a href="index.php?op=delete&product_code=<?php echo $product['product_code'] ?>"
                                        onclick="return confirm('Yakin mau delete data?')">
                                        <button type="button" class="btn btn-danger">Delete</button>
                                    </a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                            <?php
                                // Assuming $image_paths is an array
                                foreach ($image_paths as $image_path) {
                                    echo '<input type="file" id="gambar" name="gambar[]" multiple required>';
                                    echo '<input type="hidden" name="uploaded_images[]" value="' . $image_path . '">';
                                }
                                ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
