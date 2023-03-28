<?php

// Generated by the protocol buffer compiler.  DO NOT EDIT!
// source: jobs.proto

namespace Spiral\RoadRunner\Jobs\DTO\V1;

use Google\Protobuf\Internal\GPBUtil;

/**
 * HeaderValue represents []string value for the header (map[string][]string).
 *
 * Generated from protobuf message <code>jobs.v1.HeaderValue</code>
 */
class HeaderValue extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>repeated string value = 1;</code>.
     */
    private $value;

    /**
     * Constructor.
     *
     * @param array $data {
     *                    Optional. Data for populating the Message object.
     *
     *     @var string[]|\Google\Protobuf\Internal\RepeatedField $value
     * }
     */
    public function __construct($data = null)
    {
        \GPBMetadata\Jobs::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>repeated string value = 1;</code>.
     *
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Generated from protobuf field <code>repeated string value = 1;</code>.
     *
     * @param string[]|\Google\Protobuf\Internal\RepeatedField $var
     *
     * @return $this
     */
    public function setValue($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::STRING);
        $this->value = $arr;

        return $this;
    }
}
