<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/ads/googleads/v18/common/asset_types.proto

namespace Google\Ads\GoogleAds\V18\Common;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Business Profile location data synced from the linked Business Profile
 * account.
 *
 * Generated from protobuf message <code>google.ads.googleads.v18.common.BusinessProfileLocation</code>
 */
class BusinessProfileLocation extends \Google\Protobuf\Internal\Message
{
    /**
     * Advertiser specified label for the location on the Business Profile
     * account. This is synced from the Business Profile account.
     *
     * Generated from protobuf field <code>repeated string labels = 1;</code>
     */
    private $labels;
    /**
     * Business Profile store code of this location. This is synced from the
     * Business Profile account.
     *
     * Generated from protobuf field <code>string store_code = 2;</code>
     */
    protected $store_code = '';
    /**
     * Listing ID of this Business Profile location. This is synced from the
     * linked Business Profile account.
     *
     * Generated from protobuf field <code>int64 listing_id = 3;</code>
     */
    protected $listing_id = 0;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type array<string>|\Google\Protobuf\Internal\RepeatedField $labels
     *           Advertiser specified label for the location on the Business Profile
     *           account. This is synced from the Business Profile account.
     *     @type string $store_code
     *           Business Profile store code of this location. This is synced from the
     *           Business Profile account.
     *     @type int|string $listing_id
     *           Listing ID of this Business Profile location. This is synced from the
     *           linked Business Profile account.
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Google\Ads\GoogleAds\V18\Common\AssetTypes::initOnce();
        parent::__construct($data);
    }

    /**
     * Advertiser specified label for the location on the Business Profile
     * account. This is synced from the Business Profile account.
     *
     * Generated from protobuf field <code>repeated string labels = 1;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getLabels()
    {
        return $this->labels;
    }

    /**
     * Advertiser specified label for the location on the Business Profile
     * account. This is synced from the Business Profile account.
     *
     * Generated from protobuf field <code>repeated string labels = 1;</code>
     * @param array<string>|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setLabels($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::STRING);
        $this->labels = $arr;

        return $this;
    }

    /**
     * Business Profile store code of this location. This is synced from the
     * Business Profile account.
     *
     * Generated from protobuf field <code>string store_code = 2;</code>
     * @return string
     */
    public function getStoreCode()
    {
        return $this->store_code;
    }

    /**
     * Business Profile store code of this location. This is synced from the
     * Business Profile account.
     *
     * Generated from protobuf field <code>string store_code = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setStoreCode($var)
    {
        GPBUtil::checkString($var, True);
        $this->store_code = $var;

        return $this;
    }

    /**
     * Listing ID of this Business Profile location. This is synced from the
     * linked Business Profile account.
     *
     * Generated from protobuf field <code>int64 listing_id = 3;</code>
     * @return int|string
     */
    public function getListingId()
    {
        return $this->listing_id;
    }

    /**
     * Listing ID of this Business Profile location. This is synced from the
     * linked Business Profile account.
     *
     * Generated from protobuf field <code>int64 listing_id = 3;</code>
     * @param int|string $var
     * @return $this
     */
    public function setListingId($var)
    {
        GPBUtil::checkInt64($var);
        $this->listing_id = $var;

        return $this;
    }

}
