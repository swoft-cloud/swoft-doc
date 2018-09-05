# 捕捉组件抛出的异常

```php
use Swoft\Bean\Annotation\ExceptionHandler;
use Swoft\Bean\Annotation\Handler;
use Swoft\Http\Message\Server\Response;
use Swoft\Auth\Exception\AuthException;

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
     */
    public function handleAuthException(Response $response, \Throwable $t) : Response
    {
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
        return $response->withStatus($statusCode)->json($error);
    }

}
```
