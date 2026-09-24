<?php

namespace Transxact\Types;

use Transxact\Core\Json\JsonSerializableType;
use Transxact\Core\Json\JsonProperty;

class ErrorError extends JsonSerializableType
{
    /**
     * @var string $code Stable machine-readable error code.
     */
    #[JsonProperty('code')]
    public string $code;

    /**
     * @var string $message Human-readable detail, not contractual.
     */
    #[JsonProperty('message')]
    public string $message;

    /**
     * @param array{
     *   code: string,
     *   message: string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->code = $values['code'];
        $this->message = $values['message'];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
