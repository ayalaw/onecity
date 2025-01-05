<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/ads/googleads/v18/errors/automatically_created_asset_removal_error.proto

namespace Google\Ads\GoogleAds\V18\Errors\AutomaticallyCreatedAssetRemovalErrorEnum;

use UnexpectedValueException;

/**
 * Enum describing possible automatically created asset removal errors.
 *
 * Protobuf type <code>google.ads.googleads.v18.errors.AutomaticallyCreatedAssetRemovalErrorEnum.AutomaticallyCreatedAssetRemovalError</code>
 */
class AutomaticallyCreatedAssetRemovalError
{
    /**
     * Enum unspecified.
     *
     * Generated from protobuf enum <code>UNSPECIFIED = 0;</code>
     */
    const UNSPECIFIED = 0;
    /**
     * The received error code is not known in this version.
     *
     * Generated from protobuf enum <code>UNKNOWN = 1;</code>
     */
    const UNKNOWN = 1;
    /**
     * The ad does not exist.
     *
     * Generated from protobuf enum <code>AD_DOES_NOT_EXIST = 2;</code>
     */
    const AD_DOES_NOT_EXIST = 2;
    /**
     * Ad type is not supported. Only Responsive Search Ad type is supported.
     *
     * Generated from protobuf enum <code>INVALID_AD_TYPE = 3;</code>
     */
    const INVALID_AD_TYPE = 3;
    /**
     * The asset does not exist.
     *
     * Generated from protobuf enum <code>ASSET_DOES_NOT_EXIST = 4;</code>
     */
    const ASSET_DOES_NOT_EXIST = 4;
    /**
     * The asset field type does not match.
     *
     * Generated from protobuf enum <code>ASSET_FIELD_TYPE_DOES_NOT_MATCH = 5;</code>
     */
    const ASSET_FIELD_TYPE_DOES_NOT_MATCH = 5;
    /**
     * Not an automatically created asset.
     *
     * Generated from protobuf enum <code>NOT_AN_AUTOMATICALLY_CREATED_ASSET = 6;</code>
     */
    const NOT_AN_AUTOMATICALLY_CREATED_ASSET = 6;

    private static $valueToName = [
        self::UNSPECIFIED => 'UNSPECIFIED',
        self::UNKNOWN => 'UNKNOWN',
        self::AD_DOES_NOT_EXIST => 'AD_DOES_NOT_EXIST',
        self::INVALID_AD_TYPE => 'INVALID_AD_TYPE',
        self::ASSET_DOES_NOT_EXIST => 'ASSET_DOES_NOT_EXIST',
        self::ASSET_FIELD_TYPE_DOES_NOT_MATCH => 'ASSET_FIELD_TYPE_DOES_NOT_MATCH',
        self::NOT_AN_AUTOMATICALLY_CREATED_ASSET => 'NOT_AN_AUTOMATICALLY_CREATED_ASSET',
    ];

    public static function name($value)
    {
        if (!isset(self::$valueToName[$value])) {
            throw new UnexpectedValueException(sprintf(
                    'Enum %s has no name defined for value %s', __CLASS__, $value));
        }
        return self::$valueToName[$value];
    }


    public static function value($name)
    {
        $const = __CLASS__ . '::' . strtoupper($name);
        if (!defined($const)) {
            throw new UnexpectedValueException(sprintf(
                    'Enum %s has no value defined for name %s', __CLASS__, $name));
        }
        return constant($const);
    }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(AutomaticallyCreatedAssetRemovalError::class, \Google\Ads\GoogleAds\V18\Errors\AutomaticallyCreatedAssetRemovalErrorEnum_AutomaticallyCreatedAssetRemovalError::class);

