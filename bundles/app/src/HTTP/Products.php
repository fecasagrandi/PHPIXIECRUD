<?php
namespace App\HTTP\Processors;

use App\ORM\Model\Product;
use PHPixie\HTTP\Request;

class Products extends \PHPixie\DefaultBundle\Processor
{
    // Listar todos os produtos
    public function index($request)
    {
        $products = Product::findAll();
        return $this->jsonResponse($products);
    }
    
    // Mostrar produto específico
    public function show($request)
    {
        $id = $request->param('id');
        $product = Product::find($id);
        
        if (!$product) {
            return $this->jsonResponse(['error' => 'Product not found'], 404);
        }

        return $this->jsonResponse($product);
    }
    
    // Criar um novo produto
    public function create($request)
    {
        $data = $request->data()->get();
        $product = Product::create($data);
        
        return $this->jsonResponse($product, 201);
    }
    
    // Atualizar um produto
    public function update($request)
    {
        $id = $request->param('id');
        $data = $request->data()->get();
        $product = Product::find($id);
        
        if (!$product) {
            return $this->jsonResponse(['error' => 'Product not found'], 404);
        }

        $product->update($data);
        return $this->jsonResponse($product);
    }
    
    // Excluir um produto
    public function delete($request)
    {
        $id = $request->param('id');
        $product = Product::find($id);
        
        if (!$product) {
            return $this->jsonResponse(['error' => 'Product not found'], 404);
        }

        $product->delete();
        return $this->jsonResponse(['message' => 'Product deleted']);
    }

    protected function jsonResponse($data, $status = 200)
    {
        return new \PHPixie\HTTP\Responses\JSON($data, $status);
    }
}
