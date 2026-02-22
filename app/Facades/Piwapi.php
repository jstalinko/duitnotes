<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array sendText(string $recipient, string $message, int $priority = 2)
 * @method static array sendMediaUrl(string $recipient, string $mediaUrl, string $mediaType, string $caption = '', int $priority = 2)
 * @method static array sendMediaFile(string $recipient, string $filePath, string $caption = '', int $priority = 2)
 * @method static array sendDocumentUrl(string $recipient, string $documentUrl, string $documentType, string $documentName = '', string $caption = '', int $priority = 2)
 * @method static array sendDocumentFile(string $recipient, string $filePath, string $caption = '', int $priority = 2)
 * @method static array sendBulkText(string $campaign, string $message)
 *
 * @see \App\Http\Services\PiwapiService
 */
class Piwapi extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \App\Http\Services\PiwapiService::class;
    }
}
