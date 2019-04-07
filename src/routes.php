<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

$app->get('/', function (Request $request, Response $response, array $args) {
    $endpoints = [
        'all courses' => $this->api['api_url'] . '/courses',
        'single course' => $this->api['api_url'] . '/courses/{course_id}',
        'reviews by course' => $this->api['api_url'] . '/courses/{course_id}/reviews',
        'single review' => $this->api['api_url'] . '/courses/{course_id}/reviews/{review_id}',
        'help' => $this->api['base_url'] . '/',
    ];
    $result = [
        'endpoints' => $endpoints,
        'version' => $this->api['version'],
        'timestamp' => time(),
    ];
    return $response->withJson($result, 200, JSON_PRETTY_PRINT);
});

// courses api endpoint group
$app->group('/api/v1/courses', function () use ($app) {
    $app->get('', function (Request $request, Response $response, array $args) {
        $result = $this->course->getCourses();
        return $response->withJson($result, 200, JSON_PRETTY_PRINT);
    });
    $app->get('/{course_id}', function (Request $request, Response $response, array $args) {
        $result = $this->course->getCourse($args['course_id']);
        return $response->withJson($result, 200, JSON_PRETTY_PRINT);
    });
    $app->post('', function (Request $request, Response $response, array $args) {
        $result = $this->course->createCourse($request->getParsedBody());
        return $response->withJson($result, 201, JSON_PRETTY_PRINT);
    });
    $app->put('/{course_id}', function (Request $request, Response $response, array $args) {
        $data = $request->getParsedBody();
        $data['course_id'] = $args['course_id'];
        $result = $this->course->updateCourse($data);
        return $response->withJson($result, 201, JSON_PRETTY_PRINT);
    });
    $app->delete('/{course_id}', function (Request $request, Response $response, array $args) {
        $result = $this->course->deleteCourse($args['course_id']);
        return $response->withJson($result, 200, JSON_PRETTY_PRINT);
    });

    // reviews api endpoint group
    $app->group('/{course_id}/reviews', function () use ($app) {
        $app->get('', function ($request, $response, $args) {
            $result = $this->review->getReviewsByCourseId($args['course_id']);
            return $response->withJson($result, 200, JSON_PRETTY_PRINT);
        });
        $app->get('/{review_id}', function ($request, $response, $args) {
            $result = $this->review->getReview($args['review_id']);
            return $response->withJson($result, 200, JSON_PRETTY_PRINT);
        });
        $app->post('', function ($request, $response, $args) {
            $data = $request->getParsedBody();
            $data['course_id'] = $args['course_id'];
            $result = $this->review->createReview($data);
            return $response->withJson($result, 201, JSON_PRETTY_PRINT);
        });
        $app->put('/{review_id}', function ($request, $response, $args) {
            $data = $request->getParsedBody();
            $data['course_id'] = $args['course_id'];
            $data['review_id'] = $args['review_id'];
            $result = $this->review->updateReview($data);
            return $response->withJson($result, 201, JSON_PRETTY_PRINT);
        });
        $app->delete('/{review_id}', function ($request, $response, $args) {
            $result = $this->review->deleteReview($args['review_id']);
            return $response->withJson($result, 200, JSON_PRETTY_PRINT);
        });
    });
});
