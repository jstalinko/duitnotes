<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;
use Exception;

class PiwapiService
{
    /**
     * @var string
     */
    protected $baseUrl = 'https://piwapi.com/api';

    /**
     * @var string|null
     */
    protected $secret;

    /**
     * @var string|null
     */
    protected $account;

    public function __construct()
    {
        $this->secret = env('PIWAPI_API_KEY', null);
        $this->account = env('PIWAPI_ACCOUNT_ID', null);
    }

    /**
     * Get configured HTTP Client
     * 
     * @return PendingRequest
     */
    protected function client(): PendingRequest
    {
        return Http::baseUrl($this->baseUrl)->timeout(60);
    }

    /**
     * Get default payload options
     * 
     * @return array
     */
    protected function getDefaultData(): array
    {
        return [
            'secret' => $this->secret,
            'account' => $this->account,
        ];
    }

    /**
     * Send a single text message
     *
     * @param string $recipient
     * @param string $message
     * @param int $priority
     * @return array
     */
    public function sendText(string $recipient, string $message, int $priority = 2)
    {
        return $this->client()->asForm()->post('/send/whatsapp', array_merge($this->getDefaultData(), [
            'type' => 'text',
            'recipient' => $recipient,
            'message' => $message,
            'priority' => $priority,
        ]))->json();
    }

    /**
     * Send a media message via URL
     *
     * @param string $recipient
     * @param string $mediaUrl
     * @param string $mediaType (image, audio, video)
     * @param string $caption
     * @param int $priority
     * @return array
     */
    public function sendMediaUrl(string $recipient, string $mediaUrl, string $mediaType, string $caption = '', int $priority = 2)
    {
        return $this->client()->asForm()->post('/send/whatsapp', array_merge($this->getDefaultData(), [
            'type' => 'media',
            'recipient' => $recipient,
            'message' => $caption,
            'media_url' => $mediaUrl,
            'media_type' => $mediaType,
            'priority' => $priority,
        ]))->json();
    }

    /**
     * Send a media message by uploading a local file
     *
     * @param string $recipient
     * @param string $filePath
     * @param string $caption
     * @param int $priority
     * @return array
     * @throws Exception
     */
    public function sendMediaFile(string $recipient, string $filePath, string $caption = '', int $priority = 2)
    {
        if (!file_exists($filePath)) {
            throw new Exception("Media file not found at: {$filePath}");
        }

        return $this->client()
            ->attach('media_file', file_get_contents($filePath), basename($filePath))
            ->post('/send/whatsapp', array_merge($this->getDefaultData(), [
                'type' => 'media',
                'recipient' => $recipient,
                'message' => $caption,
                'priority' => $priority,
            ]))->json();
    }

    /**
     * Send a document message via URL
     *
     * @param string $recipient
     * @param string $documentUrl
     * @param string $documentType (pdf, xml, xls, xlsx, doc, docx)
     * @param string $documentName
     * @param string $caption
     * @param int $priority
     * @return array
     */
    public function sendDocumentUrl(string $recipient, string $documentUrl, string $documentType, string $documentName = '', string $caption = '', int $priority = 2)
    {
        return $this->client()->asForm()->post('/send/whatsapp', array_merge($this->getDefaultData(), [
            'type' => 'document',
            'recipient' => $recipient,
            'message' => $caption,
            'document_url' => $documentUrl,
            'document_type' => $documentType,
            'document_name' => $documentName,
            'priority' => $priority,
        ]))->json();
    }

    /**
     * Send a document message by uploading a local file
     *
     * @param string $recipient
     * @param string $filePath
     * @param string $caption
     * @param int $priority
     * @return array
     * @throws Exception
     */
    public function sendDocumentFile(string $recipient, string $filePath, string $caption = '', int $priority = 2)
    {
        if (!file_exists($filePath)) {
            throw new Exception("Document file not found at: {$filePath}");
        }

        return $this->client()
            ->attach('document_file', file_get_contents($filePath), basename($filePath))
            ->post('/send/whatsapp', array_merge($this->getDefaultData(), [
                'type' => 'document',
                'recipient' => $recipient,
                'message' => $caption,
                'priority' => $priority,
            ]))->json();
    }

    /**
     * Send a bulk text message
     *
     * @param string $campaign
     * @param string $message
     * @return array
     */
    public function sendBulkText(string $campaign, string $message)
    {
        return $this->client()->asForm()->post('/send/whatsapp.bulk', array_merge($this->getDefaultData(), [
            'type' => 'text',
            'campaign' => $campaign,
            'message' => $message,
        ]))->json();
    }
}
