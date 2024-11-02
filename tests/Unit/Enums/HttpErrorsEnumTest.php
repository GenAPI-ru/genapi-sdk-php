<?php

namespace Tests\GenAPI\Unit\Enums;

use GenAPI\Enums\Http\HttpErrorsEnum;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class HttpErrorsEnumTest extends TestCase
{
    /**
     * Test getMessage method.
     *
     * @return void
     */
    #[Test]
    public function testGetMessageReturnsCorrectErrorMessages(): void
    {
        $this->assertSame(HttpErrorsEnum::HTTP_BAD_REQUEST, HttpErrorsEnum::getMessage(400));
        $this->assertSame(HttpErrorsEnum::HTTP_UNAUTHORIZED, HttpErrorsEnum::getMessage(401));
        $this->assertSame(HttpErrorsEnum::HTTP_NOT_FOUND, HttpErrorsEnum::getMessage(404));
        $this->assertSame(HttpErrorsEnum::HTTP_METHOD_NOT_ALLOWED, HttpErrorsEnum::getMessage(405));
        $this->assertSame(HttpErrorsEnum::HTTP_UNPROCESSABLE_ENTITY, HttpErrorsEnum::getMessage(422));
        $this->assertSame(HttpErrorsEnum::HTTP_TOO_MANY_REQUESTS, HttpErrorsEnum::getMessage(429));
        $this->assertSame(HttpErrorsEnum::HTTP_INTERNAL_SERVER_ERROR, HttpErrorsEnum::getMessage(500));
        $this->assertSame(HttpErrorsEnum::UNKNOWN_ERROR, HttpErrorsEnum::getMessage(999));
    }
}
