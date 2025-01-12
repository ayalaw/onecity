<?php

enum ProductRequestMessage: string {
    case ERROR = 'Request failed';
    case NOT_FOUND = 'Product not found.';
    case AVAILABLE = 'The product is available';
    case UNAVAILABLE = 'The product is not available';
    case COMING_SOON = 'The product will be available soon.';
    case MISSING_PRODUCT_ID = 'Missing or invalid product ID.';
    case SECURITY_FAILED = 'Security check failed.';

    static function GetEnumValue(string $caseName): ?string {
        $enumArray = array_column(ProductRequestMessage::cases(), 'value', 'name');
        return $enumArray[strtoupper($caseName)] ?? null;
    }

    static function ValidResponse(): array {
        return [
            self::AVAILABLE->name,
            self::UNAVAILABLE->name,
            self::COMING_SOON->name
        ];
    }
}