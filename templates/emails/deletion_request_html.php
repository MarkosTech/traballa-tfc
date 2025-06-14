<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Account Deletion Request</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #0066cc;
            color: white;
            padding: 10px 20px;
            border-radius: 5px 5px 0 0;
        }
        .footer {
            background-color: #f5f5f5;
            padding: 10px 20px;
            border-radius: 0 0 5px 5px;
            font-size: 12px;
            color: #666;
        }
        .content {
            padding: 20px;
            border: 1px solid #ddd;
            border-top: none;
            border-bottom: none;
        }
        .important {
            color: #cc0000;
            font-weight: bold;
        }
        .button {
            display: inline-block;
            background-color: #0066cc;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Traballa - Account Deletion Request</h2>
    </div>
    
    <div class="content">
        <p>Dear <?php echo htmlspecialchars($userName); ?>,</p>
        
        <p>We have received your request to delete your Traballa account. Your account is scheduled for deletion on <strong><?php echo date('Y-m-d', strtotime('+30 days')); ?></strong>.</p>
        
        <p>During this time, your data will be marked for deletion but kept inactive to allow you to change your mind if needed. After this date, your account and associated data will be permanently removed from our systems.</p>
        
        <p class="important">If this was not requested by you, please log in to cancel this request or contact support immediately at support@traballa-tfc.com.</p>
        
        <p>Reason provided for deletion: <em><?php echo htmlspecialchars($reason); ?></em></p>
        
        <a href="https://traballa-tfc.com/login" class="button">Log In to Cancel Request</a>
        
        <p>Thank you for using Traballa.</p>
        
        <p>Best regards,<br>
        The Traballa Team</p>
    </div>
    
    <div class="footer">
        <p>Este email ha sido enviado por Traballa TFC.</p>
        <p>Si no desea recibir más comunicaciones, puede actualizar sus preferencias en su cuenta de Traballa.</p>
        <p>Para más información sobre cómo gestionamos sus datos personales, consulte nuestra 
        <a href="https://traballa-tfc.com/privacy-policy">política de privacidad</a>.</p>
    </div>
</body>
</html>
