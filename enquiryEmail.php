<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);

$name    = isset($data['name']) ? htmlspecialchars($data['name']) : '';
$email   = isset($data['email']) ? htmlspecialchars($data['email']) : '';
$inquiry = isset($data['inquiry']) ? htmlspecialchars($data['inquiry']) : '';
$message = isset($data['message']) ? htmlspecialchars($data['message']) : '';
$time    = date('r'); 

if (!$name || !$email || !$inquiry || !$message) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
    exit;
}

$subject = "Indust Food Craft Website query sent by $name (Inquiry: $inquiry) at time $time";

$body = '
<div style="font-family: Arial, sans-serif; background: #f9f9f9; padding: 24px;">
  <div style="max-width: 600px; margin: auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px #eee; padding: 32px;">
    <h2 style="color: #4CAF50; margin-bottom: 16px;">New Website Query</h2>
    <div style="margin-bottom: 16px;">
      <strong style="color: #333;">Name:</strong>
      <span style="color: #555;">' . $name . '</span>
    </div>
    <div style="margin-bottom: 16px;">
      <strong style="color: #333;">Email:</strong>
      <span style="color: #555;">' . $email . '</span>
    </div>
    <div style="margin-bottom: 16px;">
      <strong style="color: #333;">Nature of Inquiry:</strong>
      <span style="color: #555;">' . $inquiry . '</span>
    </div>
    <div style="margin-bottom: 16px;">
      <strong style="color: #333;">Message:</strong>
      <div style="color: #555; margin-top: 8px; background: #f5f5f5; padding: 12px 16px; border-radius: 4px;">' . nl2br($message) . '</div>
    </div>
    <div style="margin-top: 32px; font-size: 13px; color: #888;">
      <em>Submitted on: ' . $time . '</em>
    </div>
  </div>
</div>
';

$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=UTF-8\r\n";
$headers .= "From: Indust Food Craft Website <no-reply@industfoodcraft.com>\r\n";
$headers .= "Reply-To: $email\r\n";

$to = "jimmylois1234@gmail.com";
$mailSent = @mail($to, $subject, $body, $headers);

if ($mailSent) {
    echo json_encode(['success' => true, 'message' => 'Query sent successfully']);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to send email', 'debug' => error_get_last()]);
}
?>