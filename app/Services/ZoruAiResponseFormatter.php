<?php

namespace App\Services;

class ZoruAiResponseFormatter
{
    /**
     * @param list<array{from: string, to: string}> $corrections
     */
    public function format(string $answer, array $corrections = []): string
    {
        $polished = $this->polish($answer);

        if ($corrections !== []) {
            $polished = "Saya memahami maksud pertanyaan Anda. Tenang saja, ketikan Anda telah saya bantu luruskan dengan penuh kesabaran agar data yang disajikan tetap presisi dan hati Anda tetap tenteram.\n\n" . $polished;
        }

        return $polished;
    }

    public function repairMojibake(string $text): string
    {
        return strtr($text, [
            'â€”' => '-',
            'â€“' => '-',
            'â€¢' => '-',
            'â†’' => '->',
            'â‰¤' => '<=',
            'â‰¥' => '>=',
            'â€˜' => "'",
            'â€™' => "'",
            'â€œ' => '"',
            'â€ ' => '"',
            'â€¦' => '...',
            'âš¡' => 'ZeroPay',
            'ðŸ¤–' => 'ZoruAi',
        ]);
    }

    private function polish(string $text): string
    {
        $text = trim($this->repairMojibake($text));
        if ($text === '') {
            return $text;
        }

        // Convert asterisk bullet points (* ) to clean dashes (- ) BEFORE stripping remaining asterisks!
        $text = preg_replace('/^\s*\*\s+/mu', '- ', $text) ?? $text;

        // Hapus simbol emoji / ikon aneh agar natural dan bersih (sisakan huruf, angka, tanda baca standar, simbol matematika/mata uang, spasi, newline)
        $text = preg_replace('/[^\p{L}\p{N}\p{P}\p{S}\p{Z}\n]/u', '', $text) ?? $text;

        // Hapus simbol markdown header (seperti #, ##, ### di awal baris)
        $text = preg_replace('/^#+\s+/mu', '', $text) ?? $text;

        // Hapus simbol markdown tebal/miring/kode (seperti **, *, _, `)
        $text = str_replace(['**', '*', '`', '_'], '', $text);

        $text = $this->normalizeWhitespace($text);
        $text = $this->normalizeInstructionalSequence($text);

        $blocks = preg_split("/\n\n+/", $text) ?: [$text];
        $polished = [];

        foreach ($blocks as $block) {
            $block = trim($block);
            if ($block === '') {
                continue;
            }
            if (str_starts_with($block, '-')) {
                $polished[] = $this->normalizeListBlock($block);
                continue;
            }
            $lines = explode("\n", $block);
            $linePolished = [];
            foreach ($lines as $line) {
                $line = trim($line);
                if ($line === '') {
                    continue;
                }
                if (preg_match('/^(-|\d+\.)\s*/u', $line)) {
                    $line = preg_replace('/^(-|\d+\.)\s*/u', '$1 ', $line, 1) ?? $line;
                    $linePolished[] = $this->capitalizeSentence($line);
                } else {
                    $linePolished[] = $this->capitalizeSentence($line);
                }
            }
            $polished[] = implode("\n", $linePolished);
        }

        $result = implode("\n\n", $polished);

        if (! preg_match('/[.!?]$/u', $result) && ! str_contains($result, "\n-")) {
            $result .= '.';
        }

        // Final sanity spacing check for Indonesian spelling:
        // 1. Ensure no double spaces
        $result = preg_replace('/ +/', ' ', $result) ?? $result;
        // 2. Ensure spaces after punctuation like , . : ? !
        $result = preg_replace('/([,.:?!])(?=[^\s\d.,:?!])/u', '$1 ', $result) ?? $result;
        // 3. Ensure no spaces before punctuation
        $result = preg_replace('/ +([,.:?!])/u', '$1', $result) ?? $result;

        return trim($result);
    }

    private function normalizeInstructionalSequence(string $text): string
    {
        $markers = [
            'Pertama,' => "\n1.",
            'Kedua,' => "\n2.",
            'Ketiga,' => "\n3.",
            'Keempat,' => "\n4.",
            'Kelima,' => "\n5.",
            'Keenam,' => "\n6.",
            'Ketujuh,' => "\n7.",
        ];

        $matched = 0;
        foreach (array_keys($markers) as $marker) {
            if (str_contains($text, $marker)) {
                $matched++;
            }
        }

        if ($matched < 2) {
            return $text;
        }

        foreach ($markers as $marker => $replacement) {
            $text = str_replace($marker, $replacement, $text);
        }

        return trim($text);
    }

    private function normalizeWhitespace(string $text): string
    {
        $text = preg_replace('/[ \t]+/u', ' ', $text) ?? $text;
        $text = preg_replace('/[ \t]*\n[ \t]*/u', "\n", $text) ?? $text;
        $text = preg_replace('/\n{3,}/u', "\n\n", $text) ?? $text;
        $text = preg_replace('/\s*([,;:])(?=\S)/u', '$1 ', $text) ?? $text;
        $text = preg_replace('/\s+([.!?])/u', '$1', $text) ?? $text;
        $text = preg_replace('/\s*;\s*/u', '; ', $text) ?? $text;
        $text = preg_replace('/\s*,\s*/u', ', ', $text) ?? $text;
        $text = preg_replace('/ ;/u', ';', $text) ?? $text;
        $text = preg_replace('/ ,/u', ',', $text) ?? $text;

        return trim($text);
    }

    private function normalizeListBlock(string $block): string
    {
        $lines = explode("\n", $block);
        $normalized = [];
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }
            $line = preg_replace('/^(-|\*)\s*/u', '$1 ', $line) ?? $line;
            $line = $this->capitalizeSentence($line);
            $normalized[] = $line;
        }

        return implode("\n", $normalized);
    }

    private function capitalizeSentencesInText(string $text): string
    {
        if ($text === '') {
            return $text;
        }

        // Capitalize first letter after any sentence-ending punctuation (. or ? or !) followed by one or more whitespace characters
        $text = preg_replace_callback('/([.!?]\s+)([a-z])/u', function ($matches) {
            return $matches[1] . mb_strtoupper($matches[2], 'UTF-8');
        }, $text) ?? $text;

        return $text;
    }

    private function capitalizeSentence(string $line): string
    {
        if ($line === '') {
            return $line;
        }

        if (str_contains($line, '<')) {
            return $line;
        }

        // Handle list item capitalization
        if (preg_match('/^(\s*-\s+)([a-z])/u', $line, $m)) {
            $prefix = $m[1];
            $char = $m[2];
            $rest = mb_substr($line, mb_strlen($prefix) + 1, null, 'UTF-8');
            $line = $prefix . mb_strtoupper($char, 'UTF-8') . $rest;
        } elseif (preg_match('/^(\s*\d+\.\s+)([a-z])/u', $line, $m)) {
            $prefix = $m[1];
            $char = $m[2];
            $rest = mb_substr($line, mb_strlen($prefix) + 1, null, 'UTF-8');
            $line = $prefix . mb_strtoupper($char, 'UTF-8') . $rest;
        } else {
            // Standard single sentence capitalization for the line
            $first = mb_substr($line, 0, 1, 'UTF-8');
            $rest = mb_substr($line, 1, null, 'UTF-8');
            $line = mb_strtoupper($first, 'UTF-8') . $rest;
        }

        return $this->capitalizeSentencesInText($line);
    }
}
