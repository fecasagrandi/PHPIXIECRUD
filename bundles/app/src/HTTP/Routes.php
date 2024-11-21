<?php
namespace App\HTTP;

class Routes extends \PHPixie\DefaultBundle\Processor\Routes
{
    protected function buildRoutes()
    {
        // Definir rota de API para produtos
        $this->route('api.product.create', '/produtos', 'products@create')
            ->method('POST');
        
        $this->route('api.product.list', '/produtos', 'products@index')
            ->method('GET');
        
        $this->route('api.product.show', '/produtos/{id}', 'products@show')
            ->method('GET');
        
        $this->route('api.product.update', '/produtos/{id}', 'products@update')
            ->method('PUT');
        
        $this->route('api.product.delete', '/produtos/{id}', 'products@delete')
            ->method('DELETE');
    }
}
