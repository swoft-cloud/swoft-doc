

# 应用结构

src目录结构如下:

```
|-- App.php
|-- Base
|   |-- Application.php
|   |-- ApplicationContext.php
|   |-- Config.php
|   |-- Controller.php
|   |-- Coroutine.php
|   |-- DispatcherInterface.php
|   |-- InitApplicationContext.php
|   |-- Inotify.php
|   |-- Request.php
|   |-- RequestContext.php
|   |-- RequestHandler.php
|   |-- Response.php
|   `-- Timer.php
|-- Bean
|   |-- Annotation
|   |   |-- Bean.php
|   |   |-- Column.php
|   |   |-- Controller.php
|   |   |-- Entity.php
|   |   |-- Enum.php
|   |   |-- Floats.php
|   |   |-- Id.php
|   |   |-- Inject.php
|   |   |-- Integer.php
|   |   |-- Listener.php
|   |   |-- Log.php
|   |   |-- Mapping.php
|   |   |-- Middleware.php
|   |   |-- Middlewares.php
|   |   |-- Number.php
|   |   |-- RequestMapping.php
|   |   |-- RequestMethod.php
|   |   |-- Required.php
|   |   |-- RestController.php
|   |   |-- Scheduled.php
|   |   |-- Scope.php
|   |   |-- Service.php
|   |   |-- Strings.php
|   |   |-- Table.php
|   |   |-- Task.php
|   |   |-- ValidatorFrom.php
|   |   |-- Value.php
|   |   `-- View.php
|   |-- BeanFactory.php
|   |-- BeanFactoryInterface.php
|   |-- Collector.php
|   |-- Container.php
|   |-- ObjectDefinition
|   |   |-- ArgsInjection.php
|   |   |-- MethodInjection.php
|   |   `-- PropertyInjection.php
|   |-- ObjectDefinition.php
|   |-- Parser
|   |   |-- AbstractParser.php
|   |   |-- BeanParser.php
|   |   |-- ColumnParser.php
|   |   |-- ControllerParser.php
|   |   |-- EntityParser.php
|   |   |-- EnumParser.php
|   |   |-- FloatsParser.php
|   |   |-- IParser.php
|   |   |-- IdParser.php
|   |   |-- InjectParser.php
|   |   |-- IntegerParser.php
|   |   |-- ListenerParser.php
|   |   |-- LogParser.php
|   |   |-- MappingParser.php
|   |   |-- MethodWithoutAnnotationParser.php
|   |   |-- MiddlewareParser.php
|   |   |-- MiddlewaresParser.php
|   |   |-- NumberParser.php
|   |   |-- RequestMappingParser.php
|   |   |-- RequiredParser.php
|   |   |-- ScheduledParser.php
|   |   |-- ServiceParser.php
|   |   |-- StringsParser.php
|   |   |-- TableParser.php
|   |   |-- TaskParser.php
|   |   |-- ValueParser.php
|   |   `-- ViewParser.php
|   |-- Resource
|   |   |-- AbstractResource.php
|   |   |-- AnnotationResource.php
|   |   |-- DefinitionResource.php
|   |   `-- IResource.php
|   `-- Wrapper
|       |-- AbstractWrapper.php
|       |-- BeanWrapper.php
|       |-- ControllerWrapper.php
|       |-- EntityWrapper.php
|       |-- IWrapper.php
|       |-- ListenerWrapper.php
|       |-- ServiceWrapper.php
|       `-- TaskWrapper.php
|-- Cache
|   |-- CacheResult.php
|   `-- Redis
|       |-- RedisClient.php
|       |-- RedisConnect.php
|       `-- SyncRedisConnect.php
|-- Circuit
|   |-- CircuitBreaker.php
|   |-- CircuitBreakerState.php
|   |-- CloseState.php
|   |-- HalfOpenState.php
|   `-- OpenState.php
|-- Console
|   |-- Command
|   |   |-- EntityController.php
|   |   |-- RpcController.php
|   |   `-- ServerController.php
|   |-- CommandParser.php
|   |-- Console.php
|   |-- ConsoleCommand.php
|   |-- ConsoleController.php
|   |-- DocumentParser.php
|   |-- IConsole.php
|   |-- Input
|   |   |-- IInput.php
|   |   `-- Input.php
|   |-- Output
|   |   |-- IOutput.php
|   |   `-- Output.php
|   `-- Style
|       |-- Color.php
|       `-- Style.php
|-- Contract
|   `-- Arrayable.php
|-- Crontab
|   |-- Crontab.php
|   |-- ParseCrontab.php
|   `-- TableCrontab.php
|-- Db
|   |-- AbstractDbConnect.php
|   |-- DataResult.php
|   |-- Entity
|   |   |-- AbstractGenerator.php
|   |   |-- Generator.php
|   |   |-- IGenerator.php
|   |   |-- Mysql
|   |   |   `-- Schema.php
|   |   |-- Schema.php
|   |   |-- SetGetGenerator.php
|   |   `-- Stub
|   |       |-- Getter.stub
|   |       |-- Model.stub
|   |       |-- Property.stub
|   |       `-- Setter.stub
|   |-- EntityManager.php
|   |-- Executor.php
|   |-- IConnect.php
|   |-- IDbConnect.php
|   |-- IEntityManager.php
|   |-- IQueryBuilder.php
|   |-- Model.php
|   |-- Mysql
|   |   |-- MysqlConnect.php
|   |   |-- QueryBuilder.php
|   |   `-- SyncMysqlConnect.php
|   |-- QueryBuilder.php
|   |-- Statement.php
|   |-- Types.php
|   `-- Validator
|       |-- IValidator.php
|       |-- ValidatorBoolean.php
|       |-- ValidatorDatetime.php
|       |-- ValidatorEnum.php
|       |-- ValidatorFloat.php
|       |-- ValidatorInt.php
|       |-- ValidatorNumber.php
|       `-- ValidatorString.php
|-- Event
|   |-- ApplicationEvent.php
|   |-- Event.php
|   |-- Events
|   |   `-- BeforeTaskEvent.php
|   |-- IApplicationListener.php
|   `-- Listeners
|       |-- AfterProcessListener.php
|       |-- AfterRequestListener.php
|       |-- AfterTaskListener.php
|       |-- ApplicationLoaderListener.php
|       |-- BeforeProcessListener.php
|       |-- BeforeReceiveListener.php
|       |-- BeforeRequestListener.php
|       `-- BeforeTaskListener.php
|-- Exception
|   |-- BadMethodCallException.php
|   |-- DbException.php
|   |-- Exception.php
|   |-- Handler
|   |   |-- AbstractHandler.php
|   |   |-- ExceptionHandlerManager.php
|   |   |-- HttpExceptionHandler.php
|   |   |-- RuntimeExceptionHandler.php
|   |   |-- SystemErrorHandler.php
|   |   `-- ValidatorExceptionHandler.php
|   |-- Http
|   |   |-- BadRequestException.php
|   |   |-- ForbiddenException.php
|   |   |-- HttpException.php
|   |   |-- MethodNotAllowedException.php
|   |   |-- NotAcceptableException.php
|   |   |-- NotFoundException.php
|   |   |-- RouteNotFoundException.php
|   |   `-- UnauthorizedException.php
|   |-- InvalidArgumentException.php
|   |-- LoginException.php
|   |-- MysqlException.php
|   |-- RedisException.php
|   |-- RuntimeException.php
|   |-- ServiceException.php
|   `-- ValidatorException.php
|-- Helper
|   |-- ArrayHelper.php
|   |-- DirHelper.php
|   |-- FileHelper.php
|   |-- Functions.php
|   |-- JsonHelper.php
|   |-- PhpHelper.php
|   |-- ProcessHelper.php
|   |-- ResponseHelper.php
|   |-- StringHelper.php
|   `-- ValidatorHelper.php
|-- Http
|   |-- AbstractHttpClient.php
|   |-- CurlClient.php
|   |-- HttpClient.php
|   |-- HttpResult.php
|   `-- IHttpClient.php
|-- I18n
|   `-- I18n.php
|-- Log
|   |-- FileHandler.php
|   |-- Log.php
|   `-- Logger.php
|-- Memory
|   |-- ITable.php
|   `-- Table.php
|-- Middleware
|   |-- Http
|   |   |-- FaviconIcoMiddleware.php
|   |   |-- HandlerAdapterMiddleware.php
|   |   |-- ParserMiddleware.php
|   |   |-- PoweredByMiddleware.php
|   |   |-- RouterMiddleware.php
|   |   |-- UserMiddleware.php
|   |   `-- ValidatorMiddleware.php
|   |-- MiddlewareInterface.php
|   `-- Service
|       |-- HandlerAdapterMiddleware.php
|       |-- PackerMiddleware.php
|       `-- RouterMiddleware.php
|-- Pipeline
|   |-- AbstractProcessor.php
|   |-- FingersCrossedProcessor.php
|   |-- Pipeline.php
|   |-- PipelineBuilder.php
|   |-- PipelineInterface.php
|   `-- ProcessorInterface.php
|-- Pool
|   |-- AbstractConnect.php
|   |-- Balancer
|   |   |-- IBalancer.php
|   |   |-- RandomBalancer.php
|   |   `-- RoundRobinBalancer.php
|   |-- ConnectPool.php
|   |-- DbPool.php
|   |-- IConnect.php
|   |-- IPool.php
|   |-- RedisPool.php
|   `-- ServicePool.php
|-- Process
|   |-- AbstractProcess.php
|   |-- CronExecProcess.php
|   |-- CronTimerProcess.php
|   |-- IProcess.php
|   |-- Process.php
|   `-- ReloadProcess.php
|-- Router
|   |-- HandlerAdapterInterface.php
|   |-- HandlerMappingInterface.php
|   |-- Http
|   |   |-- AbstractRouter.php
|   |   |-- HandlerAdapter.php
|   |   `-- HandlerMapping.php
|   `-- Service
|       |-- HandlerAdapter.php
|       `-- HandlerMapping.php
|-- Server
|   |-- AbstractServer.php
|   |-- Booting
|   |   |-- Bootable.php
|   |   |-- InitMbFunsEncoding.php
|   |   |-- InitSwoftConfig.php
|   |   |-- InitWorkerLock.php
|   |   |-- LoadEnv.php
|   |   `-- LoadInitConfiguration.php
|   |-- HttpServer.php
|   |-- IServer.php
|   |-- PipeMessage.php
|   `-- RpcServer.php
|-- Service
|   |-- AbstractServiceConnect.php
|   |-- ConsulProvider.php
|   |-- DispatcherService.php
|   |-- IPack.php
|   |-- IServiceConnect.php
|   |-- JsonPacker.php
|   |-- Service.php
|   |-- ServiceConnect.php
|   |-- ServiceProvider.php
|   |-- ServiceResult.php
|   `-- SyncServiceConnect.php
|-- Task
|   `-- Task.php
|-- Testing
|   |-- Application.php
|   |-- SwooleRequest.php
|   |-- SwooleResponse.php
|   `-- Web
|       |-- Request.php
|       `-- Response.php
|-- Validator
|   |-- AbstractValidator.php
|   |-- EnumValidator.php
|   |-- FloatsValidator.php
|   |-- HttpValidator.php
|   |-- IntegerValidator.php
|   |-- NumberValidator.php
|   |-- StringsValidator.php
|   `-- ValidatorInterface.php
`-- Web
    |-- AbstractResult.php
    |-- Application.php
    |-- Concerns
    |   `-- InteractsWithInput.php
    |-- Controller.php
    |-- Dispatcher.php
    |-- DispatcherServer.php
    |-- IResult.php
    |-- MessageTrait.php
    |-- Psr7Request.php
    |-- Request.php
    |-- RequestJsonParser.php
    |-- RequestParser.php
    |-- RequestParserInterface.php
    |-- Response.php
    |-- SwooleStream.php
    |-- UploadedFile.php
    |-- Uri.php
    |-- ViewRenderer.php
    `-- ViewRendererTrait.php
```



