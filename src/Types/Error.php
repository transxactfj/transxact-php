<?php

namespace Transxact\Types;

use Transxact\Core\Json\JsonSerializableType;
use Transxact\Core\Json\JsonProperty;

class Error extends JsonSerializableType
{
    /**
     * @var ErrorError $error
     */
    #[JsonProperty('error')]
    public ErrorError $error;

    /**
     * @param array{
     *   error: ErrorError,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->error = $values['error'];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
