<?php

require 'DB.php';
require 'ORM.php';
require 'parser.php';

$db = new DB('mysql:host=127.0.0.1;dbname=nmc', 'root', '');
$orm = new ORM($db);

function insertData($orm, $xmlFile)
{

    $parser = new XMLParser($xmlFile);
    $data = $parser->parse();

    // we check if product is already on our db
    $existingProduct = $orm->query("SELECT * FROM product WHERE product_id = :product_id", [
        ':product_id' => $data['product']['product_id']
    ])->fetch();

    if ($existingProduct)
    {
       echo "Error: Product with ID '{$data['product']['product_id']}' already exists.";exit;
    }
    // Insert product
    $orm->insert('product', $data['product']);

    // Insert header data
    $orm->insert('header_data', $data['header']);

    // Insert details data
    foreach ($data['details'] as $detail)
    {
        $orm->insert('details_data', $detail);
    }

    echo "Data inserted successfully!\n";
}



function deleteData($orm, $xmlFile)
{


    $parser = new XMLParser($xmlFile);
    $data = $parser->parse();

    // we check if product is already on our db
    $existingProduct = $orm->query("SELECT * FROM product WHERE product_id = :product_id", [
        ':product_id' => $data['product']['product_id']
    ])->fetch();

    if (!$existingProduct)
    {
        echo "Error: Product with ID '{$data['product']['product_id']}' doesn't exists.";exit;
    }
    $productId = $data['product']['product_id'];

    // Delete header data and details data first
    $orm->delete('header_data', ['id_product' => $productId]);
    $orm->delete('details_data', ['id_product' => $productId]);

    // Then delete product
    $orm->delete('product', ['product_id' => $productId]);

    echo "Product with ID '$productId' and related data deleted successfully!\n";
}

function updateData($orm, $xmlFile)
{
    $parser = new XMLParser($xmlFile);
    $data = $parser->parse();

    $existingProduct = $orm->query("SELECT * FROM product WHERE product_id = :product_id", [
        ':product_id' => $data['product']['product_id']
    ])->fetch();

    if (!$existingProduct)
    {
        echo "Error: Product with ID '{$data['product']['product_id']}' already exists.";exit;
    }

    $productId = $data['product']['product_id'];

    if (!empty($data['product'])) {
        $orm->update('product', $data['product'], ['product_id' => $productId]);
    }
    if (!empty($data['header'])) {
        $orm->update('header_data', $data['header'], ['id_product' => $productId]);
    }
    if (!empty($data['details'])) {
        foreach ($data['details'] as $detail) {
            if (!empty($detail['sku_id'])) {
                $orm->update('details_data', $detail, ['sku_id' => $detail['sku_id']]);
            }
        }
    }

    echo "Product with ID '$productId' and related data updated successfully!\n";
}

$action = $argv[1] ?? null;
$file = $argv[2] ?? null;

if ($action === 'insert' && $file) {
    insertData($orm, $file);
} elseif ($action === 'delete' && $file) {
    deleteData($orm, $file);
} elseif ($action === 'update' && $file) {
    updateData($orm, $file);
} else {
    echo "Usage:\n";
    echo "  php cli_script.php insert <xml_file>\n";
    echo "  php cli_script.php delete <xml_file>\n";
    echo "  php cli_script.php update <xml_file>\n";
}
?>
