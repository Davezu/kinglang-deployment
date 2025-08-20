<?php
/**
 * PayMongo Integration Routes
 * 
 * Add these routes to your main routing system
 */

require_once __DIR__ . '/app/controllers/client/PayMongoController.php';

// Initialize PayMongo controller
$payMongoController = new PayMongoController();

// Handle routing based on request URI
$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Remove query parameters for clean routing
$path = parse_url($requestUri, PHP_URL_PATH);

switch ($path) {
    case '/paymongo/webhook':
        if ($requestMethod === 'POST') {
            $payMongoController->handleWebhook();
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
        }
        break;
        
    case '/paymongo/success':
        if ($requestMethod === 'GET') {
            $payMongoController->handleSuccess();
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
        }
        break;
        
    case '/paymongo/cancel':
        if ($requestMethod === 'GET') {
            $payMongoController->handleCancel();
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
        }
        break;
        
    case '/paymongo/success-page':
        if ($requestMethod === 'GET') {
            $payMongoController->showSuccessPage();
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
        }
        break;
        
    case '/paymongo/status':
        if ($requestMethod === 'GET') {
            $payMongoController->getPaymentStatus();
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
        }
        break;
        
    default:
        // Route not found
        http_response_code(404);
        echo json_encode(['error' => 'Route not found']);
        break;
}
?>
