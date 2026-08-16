<?php
/*
 * Sumopod QRIS Webhook Callback Handler
 * Handles payment notification from Sumopod
 */

// Log callback for debugging
$log_dir = __DIR__ . '/../../logs';
if (!is_dir($log_dir)) {
    mkdir($log_dir, 0755, true);
}
$log_file = $log_dir . '/sumopod_callback_' . date('Y-m-d') . '.log';
$log_data = date('Y-m-d H:i:s') . " - Notification received\n";
$log_data .= "Headers: " . json_encode(getallheaders()) . "\n";
$log_data .= "Body: " . file_get_contents('php://input') . "\n";
$log_data .= "---\n";
@file_put_contents($log_file, $log_data, FILE_APPEND);

include_once('../../include/db_config.php');
include_once('../../lib/PublicPayment.class.php');
include_once('../../lib/PaymentGateway.class.php');

// Get notification data
$json = file_get_contents('php://input');
$payload = json_decode($json, true);

if (!$payload) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid JSON']);
    exit;
}

// Get webhook token from header
$headers = getallheaders();
$webhookToken = $headers['X-Webhook-Token'] ?? $headers['x-webhook-token'] ?? $_SERVER['HTTP_X_WEBHOOK_TOKEN'] ?? '';

try {
    // 1. Initialize public payment for validation
    $payment = new PublicPayment('sumopod');
    
    // 2. Verify callback token
    if (!$payment->verifyCallback($payload, $webhookToken)) {
        throw new Exception('Invalid webhook token');
    }
    
    // 3. Extract order details
    $event = $payload['event_type'] ?? '';
    $data = $payload['data'] ?? $payload;
    $order_id = $data['order_id'] ?? '';
    $status = $data['status'] ?? '';
    
    if (empty($order_id)) {
        throw new Exception('Order ID not found in payload');
    }
    
    error_log("Sumopod Callback - Order ID: $order_id, Event: $event, Status: $status");
    
    // 4. Dispatch based on Order ID prefix
    if (strpos($order_id, 'TOPUP-') === 0) {
        // Handle Agent Topup
        $gateway = new PaymentGateway('sumopod');
        $success = $gateway->handleCallback($payload);
        if ($success) {
            http_response_code(200);
            echo json_encode(['success' => true, 'message' => 'Topup processed']);
        } else {
            throw new Exception('Failed to process agent topup');
        }
        exit;
    } elseif (strpos($order_id, 'SAAS-') === 0) {
        // Handle SaaS Owner Registration Payment
        $ownerId = (int)str_replace('SAAS-', '', $order_id);
        
        $conn = getDBConnection();
        $stmt = $conn->prepare("SELECT id, username, phone FROM owners WHERE id = ?");
        $stmt->execute([$ownerId]);
        $ownerUser = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$ownerUser) {
            throw new Exception("Owner with ID $ownerId not found");
        }
        
        // Update Owner status to active
        $stmt = $conn->prepare("UPDATE owners SET status = 'active' WHERE id = ?");
        $stmt->execute([$ownerId]);
        
        http_response_code(200);
        echo json_encode(['success' => true, 'message' => 'SaaS registration payment processed, owner activated']);
        exit;
    } else {
        // Handle Public Voucher / Billing Sales
        $status = $payment->getPaymentStatus($payload);
        
        $conn = getDBConnection();
        $stmt = $conn->prepare("SELECT * FROM public_sales WHERE transaction_id = :trx_id OR payment_reference = :ref");
        $stmt->execute([
            ':trx_id' => $order_id,
            ':ref' => $data['payment_id'] ?? ''
        ]);
        $transaction = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$transaction) {
            throw new Exception('Transaction not found');
        }
        
        // Update transaction status
        $update_data = [
            'status' => $status,
            'callback_data' => $json
        ];
        
        if ($status == 'paid') {
            $completed_at = $data['completed_at'] ?? date('Y-m-d H:i:s');
            $update_data['paid_at'] = date('Y-m-d H:i:s', strtotime($completed_at));
        }
        
        $set_clause = [];
        foreach ($update_data as $key => $value) {
            $set_clause[] = "$key = :$key";
        }
        
        $sql = "UPDATE public_sales SET " . implode(', ', $set_clause) . " WHERE id = :id";
        $stmt = $conn->prepare($sql);
        
        $update_data['id'] = $transaction['id'];
        $stmt->execute($update_data);
        
        // Generate voucher if payment succeeded
        if ($status == 'paid' && empty($transaction['voucher_code'])) {
            include_once('../../lib/VoucherGenerator.class.php');
            $generator = new VoucherGenerator();
            $result = $generator->generateAndSend($transaction['id']);
            if (!$result['success']) {
                error_log("Voucher generation failed: " . $result['message']);
            }
        }
        
        http_response_code(200);
        echo json_encode(['success' => true, 'message' => 'Payment processed']);
        exit;
    }
    
} catch (Exception $e) {
    error_log("Sumopod callback error: " . $e->getMessage());
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
