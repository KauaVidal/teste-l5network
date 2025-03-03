<?php

namespace App\Filters;

use App\Libraries\ResponseFormat;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class AuthFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return RequestInterface|ResponseInterface|string|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $key = getenv('JWT_SECRET');
        $token = $request->getHeaderLine("Authentication");    
   
        if(is_null($token) || empty($token)) {
            $response = service('response');

            return $response->setStatusCode(401)
            ->setJSON(ResponseFormat::formatResponse(401, 'Erro ao acessar', "Acesso negado: Token não informado"));
        }
   
        try {
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            $perfil = $decoded->perfil;
            
            if (!is_array($arguments)){
                $arguments = [];
            }

            if (in_array('admin', $arguments) && $perfil !== 'admin') {
                $response = service('response');
                return $response->setStatusCode(403)
                    ->setJSON(ResponseFormat::formatResponse(403, 'Erro ao acessar', "Permissão negada"));
            }

        } catch (\Exception $ex) {
            $response = service('response');

            return $response->setStatusCode(401)
                ->setJSON(ResponseFormat::formatResponse(401, 'Erro ao acessar', "Acesso negado: " . $ex->getMessage()));
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
