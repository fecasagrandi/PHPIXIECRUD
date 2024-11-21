<?php
namespace App\ORM\Model;

class Product
{
    private static $products = [];
    private static $nextId = 1;
    
    public $id;
    public $name;
    public $price;

    public static function findAll()
    {
        return self::$products;
    }
    
    public static function find($id)
    {
        foreach (self::$products as $product) {
            if ($product->id == $id) {
                return $product;
            }
        }
        return null;
    }
    
    public static function create($data)
    {
        $product = new self();
        $product->id = self::$nextId++;
        $product->name = $data['name'];
        $product->price = $data['price'];
        
        self::$products[] = $product;
        return $product;
    }
    
    public function update($data)
    {
        $this->name = $data['name'] ?? $this->name;
        $this->price = $data['price'] ?? $this->price;
    }
    
    public function delete()
    {
        foreach (self::$products as $key => $product) {
            if ($product->id == $this->id) {
                unset(self::$products[$key]);
                break;
            }
        }
    }
}
