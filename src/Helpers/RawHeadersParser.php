<?php

namespace GenAPI\Helpers;

class RawHeadersParser
{
    /**
     * Parse Raw Headers.
     *
     * @param string $rawHeaders
     * @return array
     */
    public static function parse(string $rawHeaders): array
    {
        if (self::isEmptyHeaders($rawHeaders)) {
            return [];
        }

        $lines = self::splitIntoLines($rawHeaders);

        return self::processHeaderLines($lines);
    }

    /**
     * Check if headers string is empty
     *
     * @param string $rawHeaders
     * @return bool
     */
    private static function isEmptyHeaders(string $rawHeaders): bool
    {
        return empty(trim($rawHeaders));
    }

    /**
     * Split raw headers into lines
     *
     * @param string $rawHeaders
     * @return array
     */
    private static function splitIntoLines(string $rawHeaders): array
    {
        return array_filter(
            explode("\n", $rawHeaders),
            fn ($line) => !empty(trim($line))
        );
    }

    /**
     * Process header lines and construct headers array
     *
     * @param array $lines
     * @return array
     */
    private static function processHeaderLines(array $lines): array
    {
        $headers = [];
        $currentKey = '';

        foreach ($lines as $line) {
            $line = trim($line);

            if (self::isContinuationLine($line)) {
                self::handleContinuationLine($headers, $currentKey, $line);
                continue;
            }

            $headerParts = self::splitHeaderLine($line);
            if (empty($headerParts)) {
                continue;
            }

            [$name, $value] = $headerParts;
            $currentKey = $name;

            self::addHeaderValue($headers, $name, $value);
        }

        return $headers;
    }

    /**
     * Check if line is a continuation of previous header
     *
     * @param string $line
     * @return bool
     */
    private static function isContinuationLine(string $line): bool
    {
        return str_starts_with($line, "\t") || str_starts_with($line, ' ');
    }

    /**
     * Handle continuation line for multiline headers
     *
     * @param array $headers
     * @param string $currentKey
     * @param string $line
     */
    private static function handleContinuationLine(array &$headers, string $currentKey, string $line): void
    {
        if ($currentKey && isset($headers[$currentKey])) {
            if (is_array($headers[$currentKey])) {
                $lastIdx = count($headers[$currentKey]) - 1;
                $headers[$currentKey][$lastIdx] .= "\r\n\t" . $line;
            } else {
                $headers[$currentKey] .= "\r\n\t" . $line;
            }
        }
    }

    /**
     * Split header line into name and value
     *
     * @param string $line
     * @return array|null
     */
    private static function splitHeaderLine(string $line): ?array
    {
        $headerParts = explode(':', $line, 2);

        if (count($headerParts) < 2) {
            return null;
        }

        return [
            trim($headerParts[0]),
            trim($headerParts[1])
        ];
    }

    /**
     * Add header value to headers array
     *
     * @param array $headers
     * @param string $name
     * @param string $value
     */
    private static function addHeaderValue(array &$headers, string $name, string $value): void
    {
        if (! isset($headers[$name])) {
            $headers[$name] = $value;
            return;
        }

        if (!is_array($headers[$name])) {
            $headers[$name] = [$headers[$name]];
        }

        $headers[$name][] = $value;
    }
}
