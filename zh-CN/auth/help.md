# 捕捉组件抛出的异常

```php
    /**
     * @ExceptionHandler()
     */
    class SwoftExceptionHandler
    {
    
    /**
         * @Inject()
         * @var ErrorCodeHelper
         */
        protected $authHelper;
    
    
        /**
         * @Handler(AuthException::class)
         * @param Response $response
         * @param \Throwable $t
         * @return Response
         */
        public function handleAuthException(Response $response, \Throwable $t){
            $errorCode = $t->getCode();
            $statusCode = 500;
            $message = $t->getMessage();
    
            if ($this->authHelper->has($errorCode)) {
                $defaultMessage = $this->authHelper->get($errorCode);
                $statusCode = $defaultMessage['statusCode'];
                if (!$message) {
                    $message = $defaultMessage['message'];
                }
            }
            $error = [
                'code' => $errorCode,
                'message' => $message ?: 'Unspecified error',
            ];
            $response = $response->withStatus($statusCode)->json($error);
            return $response;
        }
    
    }
```