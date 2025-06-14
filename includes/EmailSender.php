<?php
/**
 * Traballa - Email Sender
 * 
 * @copyright 2025 Marcos Núñez Fernández
 * @license   MIT License
 * @link      https://github.com/markostech/traballa-tfc
 * 
 * This class provides email sending functionality for the application
 * with proper GDPR compliance measures.
 */

class EmailSender {
    private $fromEmail;
    private $fromName;
    private $replyTo;
    private $templatePath;
    
    /**
     * Constructor
     * 
     * @param string $fromEmail Sender email address
     * @param string $fromName Sender name (optional)
     * @param string $replyTo Reply-to email address (optional)
     * @param string $templatePath Path to email templates directory (optional)
     */
    public function __construct($fromEmail = null, $fromName = null, $replyTo = null, $templatePath = null) {
        // Set default sender email if not provided
        $this->fromEmail = $fromEmail ?: 'noreply@traballa-tfc.com';
        $this->fromName = $fromName ?: 'Traballa';
        $this->replyTo = $replyTo ?: $this->fromEmail;
        $this->templatePath = $templatePath ?: __DIR__ . '/../templates/emails';
    }
    
    /**
     * Send an email
     * 
     * @param string $to Recipient email address
     * @param string $subject Email subject
     * @param string $message Email message (plain text)
     * @param string $htmlMessage HTML version of the message (optional)
     * @param array $attachments Array of file paths to attach (optional)
     * @return boolean Success or failure
     */
    public function send($to, $subject, $message, $htmlMessage = null, $attachments = []) {
        // Generate a unique boundary for the email parts
        $boundary = md5(time());
        
        // Set up headers
        $headers = [
            'From' => $this->fromName ? "{$this->fromName} <{$this->fromEmail}>" : $this->fromEmail,
            'Reply-To' => $this->replyTo,
            'X-Mailer' => 'Traballa-TFC/1.0',
            'MIME-Version' => '1.0'
        ];
        
        // Set content type based on whether HTML content is provided
        if ($htmlMessage) {
            $headers['Content-Type'] = "multipart/alternative; boundary=\"$boundary\"";
        } else {
            $headers['Content-Type'] = 'text/plain; charset=UTF-8';
        }
        
        // Format headers for the mail() function
        $headerString = '';
        foreach ($headers as $name => $value) {
            $headerString .= "$name: $value\r\n";
        }
        
        // Build the email body
        $body = '';
        if ($htmlMessage) {
            // If we have HTML content, create a multipart email
            $body .= "--$boundary\r\n";
            $body .= "Content-Type: text/plain; charset=UTF-8\r\n";
            $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
            $body .= chunk_split(base64_encode($message)) . "\r\n";
            
            $body .= "--$boundary\r\n";
            $body .= "Content-Type: text/html; charset=UTF-8\r\n";
            $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
            $body .= chunk_split(base64_encode($htmlMessage)) . "\r\n";
            $body .= "--$boundary--";
        } else {
            // Plain text email
            $body = $message;
        }
        
        // Send the email using PHP's mail() function
        return mail($to, $subject, $body, $headerString);
    }
    
    /**
     * Send an email using a template
     * 
     * @param string $to Recipient email address
     * @param string $subject Email subject
     * @param string $templateName Name of the template file (without extension)
     * @param array $variables Variables to replace in the template
     * @param array $attachments Array of file paths to attach (optional)
     * @return boolean Success or failure
     */
    public function sendTemplate($to, $subject, $templateName, $variables = [], $attachments = []) {
        // Check if template exists
        $templateFile = "{$this->templatePath}/{$templateName}.php";
        $htmlTemplateFile = "{$this->templatePath}/{$templateName}_html.php";
        
        if (!file_exists($templateFile)) {
            return false;
        }
        
        // Prepare variables for template
        extract($variables);
        
        // Capture output from template
        ob_start();
        include $templateFile;
        $message = ob_get_clean();
        
        // Check if HTML version exists
        $htmlMessage = null;
        if (file_exists($htmlTemplateFile)) {
            ob_start();
            include $htmlTemplateFile;
            $htmlMessage = ob_get_clean();
        }
        
        // Send the email
        return $this->send($to, $subject, $message, $htmlMessage, $attachments);
    }
    
    /**
     * Generate a GDPR compliant email footer
     * 
     * @return string Email footer with unsubscribe and privacy links
     */
    public function getGdprFooter() {
        $footer = "\n\n--\n";
        $footer .= "Este email ha sido enviado por Traballa TFC.\n";
        $footer .= "Si no desea recibir más comunicaciones, puede actualizar sus preferencias en su cuenta de Traballa.\n";
        $footer .= "Para más información sobre cómo gestionamos sus datos personales, consulte nuestra política de privacidad: https://traballa-tfc.com/privacy-policy\n";
        
        return $footer;
    }
}
?>
