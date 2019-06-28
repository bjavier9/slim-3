<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\Task;

return function (App $app) {
    $container = $app->getContainer();

    $app->get('/[{name}]', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message
        $container->get('logger')->info("Slim-Skeleton '/' route");

        // Render index view
        return $container->get('renderer')->render($response, 'index.phtml', $args);
    });
    $app->group('/api/v1', function(App $app) {
 
    // get all todos
    $app->get('/todos', function ($request, $response, $args) {
        $todos = Task::all();
        return $this->response->withJson($todos);
    });
    
    // Retrieve todo with id 
    $app->get('/todo/[{id}]', function ($request, $response, $args) {
        $todo = Task::find($args['id']);
        return $this->response->withJson($todo);
    });
       // Search for todo with given search teram in their name
    $app->get('/todos/search/[{query}]', function ($request, $response, $args) {
        $todos = Task::where('task', 'like', "%".$args['query']."%");
    return $this->response->withJson($todos);
    });
        // Add a new todo
    $app->post('/todo', function ($request, $response) {
            $input = $request->getParsedBody();
            $task = Task::craete(['task' => $input['task']]);
       return $this->response->withJson($task);
   });
   
    // DELETE a todo with given id
    $app->delete('/todo/[{id}]', function ($request, $response, $args) {     
        $task = Task::destroy($args['id']);
    return $this->response->withJson($task);
});

// Update todo with given id
$app->put('/todo/[{id}]', function ($request, $response, $args) {
    $input = $request->getParsedBody();

    $task = Task::find($args['id']);
    $task->task = $input['task'];
    $task->save();
   
    return $this->response->withJson($task);
});
    });
};
